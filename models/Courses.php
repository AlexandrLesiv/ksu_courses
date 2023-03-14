<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courses".
 *
 * @property int $id ID
 * @property int $teacher_id Викладач
 * @property string $title Назва курсу
 * @property string $description Опис
 * @property string $keywords Ключові слова
 * @property int $students_count Кількість студентів
 * @property string $status Статус
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property Classes[] $classes
 * @property Forum[] $forums
 * @property Lessons[] $lessons
 * @property User $teacher
 */
class Courses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'title', 'description', 'keywords', 'students_count'], 'required'],
            [['teacher_id', 'students_count', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'string'],
            [['title', 'keywords'], 'string', 'max' => 300],
            [['description'], 'string', 'max' => 500],
            ['status', 'default', 'value' => 'Новий'],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => 'Викладач',
            'title' => 'Назва курсу',
            'description' => 'Опис',
            'keywords' => 'Ключові слова',
            'students_count' => 'Кількість студентів',
            'status' => 'Статус',
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
     * Gets query for [[Classes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClasses()
    {
        return $this->hasMany(Classes::class, ['course_id' => 'id']);
    }

    /**
     * Gets query for [[Forums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(Forum::class, ['course_id' => 'id']);
    }

    /**
     * Gets query for [[Lessons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLessons()
    {
        return $this->hasMany(Lessons::class, ['course_id' => 'id']);
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(User::class, ['id' => 'teacher_id']);
    }
}
