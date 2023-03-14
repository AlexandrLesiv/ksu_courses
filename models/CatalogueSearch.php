<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Courses;
use Yii;

/**
 * CatalogueSearch represents the model behind the search form of `app\models\Courses`.
 */
class CatalogueSearch extends Courses
{
    public $email;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $teacher;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'students_count', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'keywords', 'status', 'email', 'first_name', 'middle_name', 'last_name', 'teacher'], 'safe'],
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
    public function search($params, $category)
    {
        $teacher_arr = [];
        $banned = User::find()->where(['user.status' => 10])->all();
        foreach ($banned as $item) {
            array_push($teacher_arr, $item->id);
        }
        $query = Courses::find()->where(['teacher_id' => $teacher_arr])->andWhere(['<>', 'courses.status', 'Заблоковано']);

        if ($category === 'favorites') {
            $user_id = Yii::$app->user->id;
            $courses_id = [];
            $favorites = Favorites::find()->where(['student_id' => $user_id])->all();
            foreach ($favorites as $item) {
                array_push($courses_id, $item->course_id);
            }

            $query->andWhere(['courses.id' => $courses_id]);
        }

        if ($category === 'my-courses') {
            $user_id = Yii::$app->user->id;
            $courses_id = [];
            $favorites = ClassStudents::find()->where(['student_id' => $user_id, 'status' => 'Підтверджено'])->all();
            foreach ($favorites as $item) {
                array_push($courses_id, $item->course_id);
            }

            $query->andWhere(['courses.id' => $courses_id]);
        }

        if ($category === 'requests') {
            $user_id = Yii::$app->user->id;
            $courses_id = [];
            $favorites = ClassStudents::find()->where(['student_id' => $user_id])->all();
            foreach ($favorites as $item) {
                array_push($courses_id, $item->course_id);
            }

            $query->andWhere(['courses.id' => $courses_id]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('teacher');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            'students_count' => $this->students_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'user.first_name', $this->first_name])
            ->andFilterWhere(['like', 'user.middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'user.last_name', $this->last_name])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'courses.status', $this->status]);

        return $dataProvider;
    }
}
