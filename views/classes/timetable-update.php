<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Classes $model */

$this->title = 'Редагування розкладу';

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['/courses/index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($course->title, 0, 10) . '...',
    'url' => ['/courses/view', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Групи',
    'url' => ['/courses/classes', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $class->title,
    'url' => ['view', 'id' => $class->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редагування розкладу',
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="classes-timetable-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form-timetable', [
        'model' => $model,
    ]) ?>

</div>