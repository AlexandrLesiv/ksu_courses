<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "timetable".
 *
 * @property int $id ID
 * @property int $lesson_id Заняття
 * @property int $class_id Клас
 * @property string $start Початок заняття
 * @property string $end Кінець заняття
 * @property string $status Статус
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property Classes $class
 * @property Lessons $lesson
 */
class Timetable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_id', 'class_id', 'start', 'end'], 'required'],
            [['lesson_id', 'class_id', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'string'],
            [['start', 'end'], 'string', 'max' => 20],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::class, 'targetAttribute' => ['class_id' => 'id']],
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
            'lesson_id' => 'Заняття',
            'class_id' => 'Клас',
            'start' => 'Початок заняття',
            'end' => 'Кінець заняття',
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
     * {@inheritdoc}
     */
    public function compareDate()
    {
        $date_start = strtotime($this->start);
        $date_end = strtotime($this->end);
        if ($date_start < $date_end) {
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[Class]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::class, ['id' => 'class_id']);
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
}
