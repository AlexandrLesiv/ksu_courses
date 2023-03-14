<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Homework $model */

$this->title = 'Відповідь на домашню роботу';

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
    'url' => ['index', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($lesson->title, 0, 10) . '...',
    'url' => ['/lessons/view', 'id' => $lesson->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Домашні роботи',
    'url' => ['index', 'id' => $lesson->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Відповідь',
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="homework-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
