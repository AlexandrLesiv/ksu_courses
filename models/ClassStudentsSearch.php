<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClassStudents;

/**
 * ClassStudentsSearch represents the model behind the search form of `app\models\ClassStudents`.
 */
class ClassStudentsSearch extends ClassStudents
{
    public $email;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $student;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course_id', 'class_id', 'student_id', 'created_at', 'updated_at'], 'integer'],
            [['status', 'email', 'first_name', 'middle_name', 'last_name', 'student'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $course_id, $class_id, $status)
    {
        $query = ClassStudents::find()->where(['course_id' => $course_id]);
        $sort = [
            'defaultOrder' => [
                'status' => SORT_ASC,
                'created_at' => SORT_DESC,
            ]
        ];

        if ($class_id !== null) {
            $query->andWhere(['class_id' => $class_id]);
        }

        if ($status !== 'all') {
            $query->andWhere(['class_students.status' => $status]);

            $sort = [
                'defaultOrder' => [
                    'student' => SORT_ASC,
                ]
            ];
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('student');

        $dataProvider->sort->attributes['student'] = [
            'asc' => [
                'user.last_name' => SORT_ASC,
                'user.first_name' => SORT_ASC,
                'user.middle_name' => SORT_ASC,
            ],
            'desc' => [
                'user.last_name' => SORT_DESC,
                'user.first_name' => SORT_DESC,
                'user.middle_name' => SORT_DESC,
            ],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'course_id' => $this->course_id,
            'class_id' => $this->class_id,
            'student_id' => $this->student_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'user.first_name', $this->first_name])
            ->andFilterWhere(['like', 'user.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'user.last_name', $this->last_name]);

        return $dataProvider;
    }
}
