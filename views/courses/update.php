<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Courses $model */

$this->title = 'Редагування курсу: ' . mb_substr($model->title, 0, 10) . '...';

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($model->title, 0, 10) . '...',
    'url' => ['view', 'id' => $model->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редагування',
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="courses-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>