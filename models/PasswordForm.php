<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * PasswordForm is the model behind the password form.
 *
 * @property-read User|null $user
 *
 */
class PasswordForm extends Model
{
    public $password;
    public $repeat;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'repeat'], 'required'],
            [['password', 'repeat'], 'string', 'min' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'repeat' => 'Повторіть пароль',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function checkPassword()
    {
        if ($this->password == $this->repeat) {
            return true;
        }
        return false;
    }
}
