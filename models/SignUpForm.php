<?php

namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * SignUpForm is the model behind the sign-up form.
 *
 * @property-read User|null $user
 *
 */
class SignUpForm extends Model
{
    public $role;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $password;
    public $repeat;
    public $term = false;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'password', 'repeat', 'role'], 'required'],
            [['first_name', 'middle_name', 'last_name'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['password'], 'string', 'min' => 5],
            ['term', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role' => 'Роль',
            'first_name' => 'Імʼя',
            'middle_name' => 'По батькові',
            'last_name' => 'Прізвище',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'repeat' => 'Повторіть пароль',
            'term' => 'Користувацьку угоду прочитано',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function checkEmail()
    {
        $query = User::find()->where(['email' => $this->email])->andWhere(
            [
                'or',
                ['status' => 10],
                ['status' => 99]
            ]
        );

        if ($query->count() === 0) {
            return true;
        }
        return false;
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

    /**
     * {@inheritdoc}
     */
    public function contact($subject, $body)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' mailer'])
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();

            return true;
        }
        return false;
    }
}
