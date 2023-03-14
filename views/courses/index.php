<?php

use app\models\ClassStudents;
use app\models\Courses;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CoursesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мої курси';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="courses-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-plus"></i> Додати', ['create'], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-filter-alt"></i> Фільтри', [''], ['class' => 'btn btn-action btn-filter']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <div class="form-filter d-none">
        <?= $this->render(
            '_search',
            [
                'action' => ['index'],
                'model' => $searchModel,
            ]
        )
        ?>
    </div>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'label' => 'Навчальний курс',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data['status'] === 'Новий') {
                            $style = 'info';
                        } elseif ($data['status'] === 'В процесі') {
                            $style = 'success';
                        } elseif ($data['status'] === 'Йде набір') {
                            $style = 'primary';
                        } elseif ($data['status'] === 'Завершено') {
                            $style = 'secondary';
                        } elseif ($data['status'] === 'Заблоковано') {
                            $style = 'danger';
                        }

                        $req_count = ClassStudents::find()->where(['course_id' => $data['id'], 'status' => 'Новий'])->count();
                        $new_req = '';
                        if ($data['status'] === 'Йде набір') {
                            $new_req = '<span class="badge bg-warning" title="Нові заявки">' . $req_count . '</span>';
                        }

                        return '<span class="badge bg-' . $style . '">' . $data['status'] . '</span> ' . $new_req . '
                                <br><b>' . $data['title'] . '</b>
                                <br>' . $data['description'];
                    },
                ],
                [
                    'attribute' => 'students_count',
                    'label' => 'К-сть. студентів',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $min = 0;
                        $max = $data['students_count'];
                        $now = ClassStudents::find()->where(['course_id' => $data['id'], 'status' => 'Підтверджено'])->count();
                        $width = number_format($now / $max * 100, 2, '.', '');
                        return $now . '/' . $max . '<br><div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: ' . $width . '%" aria-valuenow="' . $now . '" aria-valuemin="' . $min . '" aria-valuemax="' . $max . '"></div></div>';
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($data) {
                        return date('d.m.Y H:i:s', $data['created_at']);
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                    'template' => '{view} {update} {delete} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>