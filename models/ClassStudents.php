<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "class_students".
 *
 * @property int $id ID
 * @property int $course_id Курс
 * @property int|null $class_id Клас
 * @property int $student_id Студент
 * @property string $status Статус
 * @property int $created_at Створено
 * @property int $updated_at Відредаговано
 *
 * @property Classes $class
 * @property Courses $course
 * @property User $student
 */
class ClassStudents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'student_id'], 'required'],
            [['course_id', 'class_id', 'student_id', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'string'],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['student_id' => 'id']],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::class, 'targetAttribute' => ['class_id' => 'id']],
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
            'class_id' => 'Клас',
            'student_id' => 'Студент',
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
    public function contact($email, $subject, $body)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' mailer'])
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();

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
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Courses::class, ['id' => 'course_id']);
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
