<?php

use app\models\Favorites;
use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = trim($model->last_name . ' ' . $model->first_name . ' ' . $model->last_name);

$this->params['breadcrumbs'][] = [
    'label' => 'Всі курси',
    'url' => ['/catalogue/index']
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<div class="user-info">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if ($info !== null) : ?>
        <p class="control-btn">
            <?php
            Modal::begin([
                'title' => 'Інформація про викладача',
                'toggleButton' => [
                    'label' => '<i class="bx bxs-user-detail"></i>Про викладача',
                    'tag' => 'a',
                    'class' => 'btn btn-action',
                ],
                'size' => 'modal-xl'
            ]);
            ?>

            <?= $info->info ?>

            <?php Modal::end(); ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'last_name',
            'first_name',
            'middle_name',
            'email:email',
        ],
    ]) ?>

    <h3 class="mt-5">Список курсів викладача</h3>
    <div class="table-responsive mt-3">
        <?= GridView::widget([
            'dataProvider' => $courses,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'label' => 'Навчальний курс',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $favorites = Favorites::findOne(['course_id' => $data['id'], 'student_id' => Yii::$app->user->id]);
                        $star = $favorites !== null ? '<i class="bx bxs-star"></i> ' : '';

                        return $star . '<b>' . $data['title'] . '</b>
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
                        return Url::toRoute(['/catalogue/' . $action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
</div>