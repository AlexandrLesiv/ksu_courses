<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "classes".
 *
 * @property int $id ID
 * @property int $course_id Курс
 * @property string $title Назва групи
 * @property int $students_count Кількість студентів
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property ClassStudents[] $classStudents
 * @property Courses $course
 * @property Timetable[] $timetables
 */
class Classes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'classes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'title', 'students_count'], 'required'],
            [['course_id', 'students_count', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 10],
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
            'title' => 'Назва групи',
            'students_count' => 'Кількість студентів',
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
     * @inheritdoc
     */
    public function checkCount()
    {
        return $this->students_count >= 1;
    }

    /**
     * Gets query for [[ClassStudents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClassStudents()
    {
        return $this->hasMany(ClassStudents::class, ['class_id' => 'id']);
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
     * Gets query for [[Timetables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTimetables()
    {
        return $this->hasMany(Timetable::class, ['class_id' => 'id']);
    }
}
