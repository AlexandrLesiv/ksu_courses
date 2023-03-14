<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "content".
 *
 * @property int $id ID
 * @property string $title Назва сторінки
 * @property string $description Опис
 * @property string $keywords Ключові слова
 * @property string $text Вміст
 * @property string $url URL
 * @property int $updated_at Відредаговано
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'keywords', 'text', 'url'], 'required'],
            [['text'], 'string'],
            [['updated_at'], 'integer'],
            [['title'], 'string', 'max' => 150],
            [['description', 'keywords'], 'string', 'max' => 300],
            [['url'], 'string', 'max' => 100],
            [['url'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Назва сторінки',
            'description' => 'Опис',
            'keywords' => 'Ключові слова',
            'text' => 'Вміст',
            'url' => 'URL',
            'updated_at' => 'Відредаговано',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
