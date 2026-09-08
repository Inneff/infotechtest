<?php

namespace app\models;

use app\services\BookNotificationService;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Книга каталога.
 *
 * @property int $id
 * @property string $title Название
 * @property int $year Год выпуска
 * @property string|null $description Описание
 * @property string $isbn ISBN
 * @property string|null $photo Путь к фото главной страницы
 * @property int|null $created_by Пользователь, добавивший книгу
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    /** @var int[] идентификаторы выбранных авторов (для формы) */
    public $authorIds = [];

    /** @var UploadedFile|null загруженный файл фото (для формы) */
    public $photoFile;

    private const PHOTO_DIR = 'uploads';

    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['title', 'year', 'isbn'], 'required'],
            [['title'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['year'], 'integer', 'min' => 1000, 'max' => (int) date('Y') + 1],
            [['description'], 'string'],
            [['isbn'], 'trim'],
            [['isbn'], 'string', 'max' => 20],
            [['isbn'], 'unique'],
            [['photo'], 'string', 'max' => 255],
            [['authorIds'], 'required', 'message' => 'Укажите хотя бы одного автора.'],
            [['authorIds'], 'each', 'rule' => ['integer']],
            [['authorIds'], 'each', 'rule' => ['exist', 'targetClass' => Author::class, 'targetAttribute' => 'id']],
            [['photoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, webp', 'maxSize' => 2 * 1024 * 1024],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'photo' => 'Фото главной страницы',
            'photoFile' => 'Фото главной страницы',
            'authorIds' => 'Авторы',
            'created_by' => 'Добавил',
            'created_at' => 'Создана',
            'updated_at' => 'Обновлена',
        ];
    }

    /**
     * Авторы книги (связь многие-ко-многим).
     */
    public function getAuthors(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }

    /**
     * Пользователь, добавивший книгу.
     */
    public function getCreator(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Абсолютный путь к файлу фото на диске.
     */
    public function getPhotoPath(): string
    {
        return Yii::getAlias('@webroot') . $this->photo;
    }

    /**
     * Полное сохранение книги: запись, синхронизация авторов и загрузка фото.
     *
     * @return bool
     * @throws \Throwable
     */
    public function saveBook(): bool
    {
        $isNewRecord = $this->isNewRecord;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            $this->syncAuthors();

            if (!$this->uploadPhoto()) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        // Уведомления рассылаются уже после коммита, вне транзакции.
        if ($isNewRecord) {
            BookNotificationService::notifyNewBook($this);
        }

        return true;
    }

    /**
     * Загружает id авторов книги (используется при отображении формы).
     */
    public function loadAuthorIds(): void
    {
        $this->authorIds = $this->getAuthors()->select('id')->column();
    }

    /**
     * Синхронизирует связь книги с авторами.
     */
    private function syncAuthors(): void
    {
        if ($this->authorIds === null) {
            return;
        }

        $this->unlinkAll('authors', true);

        $authors = Author::findAll($this->authorIds);
        foreach ($authors as $author) {
            $this->link('authors', $author);
        }
    }

    /**
     * Сохраняет загруженный файл фото и обновляет путь к нему.
     */
    private function uploadPhoto(): bool
    {
        if ($this->photoFile === null) {
            return true;
        }

        $fileName = Yii::$app->security->generateRandomString(20) . '.' . $this->photoFile->extension;
        $dir = Yii::getAlias('@webroot') . '/' . self::PHOTO_DIR;

        FileHelper::createDirectory($dir);

        if (!$this->photoFile->saveAs($dir . '/' . $fileName)) {
            $this->addError('photoFile', 'Не удалось сохранить файл фото.');
            return false;
        }

        $this->deletePhotoFile();
        $this->photo = '/' . self::PHOTO_DIR . '/' . $fileName;

        return static::updateAll(['photo' => $this->photo], ['id' => $this->id]) > 0;
    }

    /**
     * Удаляет предыдущий файл фото (если был).
     */
    public function deletePhotoFile(): void
    {
        if ($this->photo && is_file($this->getPhotoPath())) {
            unlink($this->getPhotoPath());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert && $this->created_by === null && !Yii::$app->user->isGuest) {
            $this->created_by = Yii::$app->user->id;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete(): bool
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $this->deletePhotoFile();

        return true;
    }
}
