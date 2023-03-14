<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacher_info".
 *
 * @property int $id ID
 * @property int $teacher_id Викладач
 * @property string $info Інформація
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property User $teacher
 */
class TeacherInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'info'], 'required'],
            [['teacher_id', 'created_at', 'updated_at'], 'integer'],
            [['info'], 'string'],
            [['teacher_id'], 'unique'],
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
            'info' => 'Інформація',
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
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(User::class, ['id' => 'teacher_id']);
    }
}
