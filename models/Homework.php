<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "homework".
 *
 * @property int $id ID
 * @property int $student_id Студент
 * @property int $lesson_id Заняття
 * @property string|null $description Примітка
 * @property string|null $comment Коментар
 * @property string $file Файл
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property Lessons $lesson
 * @property User $student
 */
class Homework extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'homework';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'lesson_id', 'file'], 'required'],
            [['student_id', 'lesson_id', 'created_at', 'updated_at'], 'integer'],
            [['description', 'comment'], 'string'],
            [['file'], 'string', 'max' => 50],
            [['imageFile'], 'file', 'maxSize' => 1024 * 1024 * 100],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['student_id' => 'id']],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lessons::class, 'targetAttribute' => ['lesson_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Студент',
            'lesson_id' => 'Заняття',
            'description' => 'Примітка',
            'comment' => 'Коментар',
            'file' => 'Файл',
            'imageFile' => 'Файл',
            'created_at' => 'Додано',
            'updated_at' => 'Переглянуто',
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
     * @inheritdoc
     */
    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('files/homeworks/'. $this->file);

            return true;
        } else {
            return false;
        }
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
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(User::class, ['id' => 'student_id']);
    }
}
