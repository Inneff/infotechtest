<?php

namespace app\models;

use yii\base\Model;

/**
 * Форма подписки гостя на новые книги автора.
 */
class SubscriptionForm extends Model
{
    public $author_id;
    public $phone;

    /** @var Author */
    private $_author;

    public function rules(): array
    {
        return [
            [['author_id', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['author_id'], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            [['phone'], 'trim'],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'validatePhone'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'author_id' => 'Автор',
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * Проверяет формат номера телефона.
     */
    public function validatePhone(string $attribute): void
    {
        $normalized = $this->normalizePhone();
        if ($normalized === null) {
            $this->addError($attribute, 'Введите корректный номер телефона, например +7 900 123-45-67.');
        }
    }

    /**
     * Приводит номер к международному формату 7XXXXXXXXXX.
     *
     * @return string|null нормализованный номер или null, если формат неверный
     */
    public function normalizePhone(): ?string
    {
        $digits = preg_replace('/\D+/', '', $this->phone);

        if (strlen($digits) === 11 && $digits[0] === '8') {
            return '7' . substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return '7' . $digits;
        }

        if (strlen($digits) === 11 && $digits[0] === '7') {
            return $digits;
        }

        return null;
    }

    /**
     * Создаёт подписку.
     *
     * @return Subscription|null
     */
    public function subscribe(): ?Subscription
    {
        if (!$this->validate()) {
            return null;
        }

        $subscription = new Subscription([
            'author_id' => $this->author_id,
            'phone' => $this->normalizePhone(),
        ]);

        return $subscription->save() ? $subscription : null;
    }
}
