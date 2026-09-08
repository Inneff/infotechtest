<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Автор книги.
 *
 * @property int $id
 * @property string $full_name ФИО
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Book[] $books
 */
class Author extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%author}}';
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
            [['full_name'], 'required'],
            [['full_name'], 'trim'],
            [['full_name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'full_name' => 'ФИО',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
        ];
    }

    /**
     * Книги автора (связь многие-ко-многим).
     */
    public function getBooks(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('{{%book_author}}', ['author_id' => 'id']);
    }

    /**
     * Возвращает список авторов, выпустивших больше всего книг за указанный год.
     *
     * @return array массив строк вида ['id', 'full_name', 'books_count']
     */
    public static function topByYear(int $year, int $limit = 10): array
    {
        return self::find()
            ->alias('a')
            ->select(['a.id', 'a.full_name', 'COUNT(b.id) AS books_count'])
            ->innerJoin('{{%book_author}} ba', 'ba.author_id = a.id')
            ->innerJoin('{{%book}} b', 'b.id = ba.book_id')
            ->andWhere(['b.year' => $year])
            ->groupBy(['a.id', 'a.full_name'])
            ->orderBy(['books_count' => SORT_DESC, 'a.full_name' => SORT_ASC])
            ->limit($limit)
            ->asArray()
            ->all();
    }
}
