<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Homework;

/**
 * HomeworkSearch represents the model behind the search form of `app\models\Homework`.
 */
class HomeworkSearch extends Homework
{
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
            [['id', 'student_id', 'lesson_id', 'created_at', 'updated_at'], 'integer'],
            [['description', 'comment', 'file', 'first_name', 'middle_name', 'last_name', 'student'], 'safe'],
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
    public function search($params, $lesson_id)
    {
        $query = Homework::find()->where(['lesson_id' => $lesson_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_ASC,
                ]
            ]
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

        $query->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'user.first_name', $this->first_name])
            ->andFilterWhere(['like', 'user.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'user.last_name', $this->last_name]);

        return $dataProvider;
    }
}
