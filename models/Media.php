<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $id ID
 * @property int $lesson_id Заняття
 * @property string $title Назва/заголовок
 * @property string $description Текст/примітка
 * @property string $type Тип
 * @property string $file Файл
 * @property string $imageFile Файл
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property Lessons $lesson
 */
class Media extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_id', 'title', 'type'], 'required'],
            [['lesson_id', 'created_at', 'updated_at'], 'integer'],
            [['type'], 'string'],
            [['title'], 'string', 'max' => 300],
            [['description'], 'string'],
            [['file'], 'string', 'max' => 50],
            [['imageFile'], 'file', 'maxSize' => 1024 * 1024 * 100],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lessons::class, 'targetAttribute' => ['lesson_id' => 'id']],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lesson_id' => 'Заняття',
            'title' => 'Назва/заголовок',
            'description' => 'Текст/примітка',
            'type' => 'Тип',
            'file' => 'Файл',
            'imageFile' => 'Прикріплення',
            'created_at' => 'Створено',
            'updated_at' => 'Відредаговано',
        ];
    }

    /**
     * Gets query for [[Lesson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLesson()
    {
        return $this->hasOne(Lessons::class, ['id' => 'lesson_id']);
    }

    /**
     * @inheritdoc
     */
    public function validateDesc()
    {
        return !(($this->description === null) || ($this->description === ''));
    }

    /**
     * @inheritdoc
     */
    public function validateVideo()
    {
        return preg_match('/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/', $this->description);
    }

    /**
     * @inheritdoc
     */
    public function upload()
    {
        if ($this->validate()) {
            $dir = $this->type == 'Зображення' ? 'img/media/' : 'files/';

            $this->imageFile->saveAs($dir . $this->file);
            return true;
        } else {
            return false;
        }
    }
}
