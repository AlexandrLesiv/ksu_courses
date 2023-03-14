<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ForgotPasswordForm is the model behind the forgot-password form.
 *
 * @property-read User|null $user
 *
 */
class ForgotPasswordForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'E-mail' => 'E-mail',
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
