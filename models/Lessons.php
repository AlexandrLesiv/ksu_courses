<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lessons".
 *
 * @property int $id ID
 * @property int $course_id Курс
 * @property string $title Назва заняття
 * @property string $description Опис
 * @property string $keywords Ключові слова
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property Courses $course
 * @property Homework[] $homeworks
 * @property Marks[] $marks
 * @property Media[] $media
 * @property Timetable[] $timetables
 */
class Lessons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lessons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'title', 'description', 'keywords'], 'required'],
            [['course_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'keywords'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 500],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Courses::class, 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Курс',
            'title' => 'Назва заняття',
            'description' => 'Опис',
            'keywords' => 'Ключові слова',
            'created_at' => 'Створено',
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Courses::class, ['id' => 'course_id']);
    }

    /**
     * Gets query for [[Homeworks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHomeworks()
    {
        return $this->hasMany(Homework::class, ['lesson_id' => 'id']);
    }

    /**
     * Gets query for [[Marks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarks()
    {
        return $this->hasMany(Marks::class, ['lesson_id' => 'id']);
    }

    /**
     * Gets query for [[Media]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::class, ['lesson_id' => 'id']);
    }

    /**
     * Gets query for [[Timetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTimetables()
    {
        return $this->hasMany(Timetable::class, ['lesson_id' => 'id']);
    }
}
