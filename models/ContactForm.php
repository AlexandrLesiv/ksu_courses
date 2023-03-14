<?php

namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $body;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'body'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваші ПІБ',
            'email' => 'Ваш e-mail',
            'body' => 'Повідомлення',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function contact($subject, $body)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo(User::findOne(['id' => 1])->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' mailer'])
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();

            return true;
        }
        return false;
    }
}
