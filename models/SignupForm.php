<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Форма регистрации пользователя.
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules(): array
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username'], 'trim'],
            [['username'], 'string', 'min' => 2, 'max' => 255],
            [['username'], 'match', 'pattern' => '/^[a-zA-Z0-9_\-]+$/', 'message' => 'Имя может содержать только латинские буквы, цифры, дефис и нижнее подчёркивание.'],
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username', 'message' => 'Это имя пользователя уже занято.'],
            [['email'], 'trim'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message' => 'Этот e-mail уже занят.'],
            [['password'], 'string', 'min' => 6],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'Логин',
            'email' => 'E-mail',
            'password' => 'Пароль',
        ];
    }

    /**
     * Регистрирует пользователя.
     *
     * @return User|null созданный пользователь либо null при ошибке валидации
     */
    public function signup(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save(false) ? $user : null;
    }
}
