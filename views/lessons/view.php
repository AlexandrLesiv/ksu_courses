<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Lessons $model */

$this->title = $model->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['/courses/index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($course->title, 0, 10) . '...',
    'url' => ['/courses/view', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Уроки',
    'url' => ['index', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($this->title, 0, 10) . '...',
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<div class="lessons-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-edit-alt"></i>Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-trash"></i>Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-action',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цей елемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description',
            'keywords',
            [
                'attribute' => 'keywords',
                'format' => 'raw',
                'value' => function ($data) {
                    $words = explode(',', $data['keywords']);
                    $keywords = '';
                    foreach ($words as $item) {
                        $keywords .= Html::a('<span class="badge bg-dark">' . trim($item) . '</span>', ['index', 'id' => $data['course_id'], 'LessonsSearch[keywords]' => trim($item)]) . ' ';
                    }
                    return trim($keywords);
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['created_at']);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['updated_at']);
                },
            ],
        ],
    ]) ?>


    <p class="control-btn control-btn--inverse">
        <?= Html::a('<i class="bx bx-book-reader"></i>Матеріали<span class="badge bg-success req-count" title="Навчальні матеріали">' . $media_count . '</span>', ['/media/index', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-task"></i>Дом. роботи<span class="badge bg-warning req-count" title="Домашні роботи">' . $homework_count . '</span>', ['/homework/index', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
    </p>

</div>