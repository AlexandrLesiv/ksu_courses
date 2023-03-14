<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Media $model */

$this->title = 'Додання НМ (' . $model->type . ')';

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
    'label' => 'НМ',
    'url' => ['index', 'id' => $lesson->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];

$tpl = str_replace('create-', '', Yii::$app->controller->action->id);
?>
<div class="media-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form-' . $tpl, [
        'model' => $model,
    ]) ?>

</div>