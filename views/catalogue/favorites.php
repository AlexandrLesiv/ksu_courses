<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CatalogueSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Обрані курси';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="courses-favorites">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-filter-alt"></i> Фільтри', [''], ['class' => 'btn btn-action btn-filter']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <div class="form-filter d-none">
        <?= $this->render(
            '_search',
            [
                'action' => ['favorites'],
                'category' => 'favorites',
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
                        $teacher = User::findOne(['id' => $data['teacher_id']]);
                        $last_name = $teacher->last_name;
                        $first_name = ' ' . $teacher->first_name;
                        $middle_name = ' ' . $teacher->middle_name;
                        $teacher_link = Html::a($last_name . $first_name . $middle_name, ['/user/info', 'id' => $teacher->id]);
                        $status = $teacher->status === 99 ? '<i class="bx bx-lock"></i>' : '';
                        return '<b>' . $data['title'] . '</b>
                            <br><small><b>Викладач:</b> ' . $status . ' ' . $teacher_link . '</small>
                            <br>' . $data['description'];
                    },
                ],
                [
                    'attribute' => 'status',
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

                        return '<span class="badge bg-' . $style . '">' . $data['status'] . '</span>';
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
                    'template' => '{view} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>