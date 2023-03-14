<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Редагування: ' . $model->last_name . ' ' . mb_substr($model->first_name, 0, 1) . '.';

$this->params['breadcrumbs'][] = [
    'label' => $role == 'teacher' ? 'Викладачі' : 'Здобувачі освіти',
    'url' => $role == 'teacher' ? ['teachers'] : ['students'],
];
$this->params['breadcrumbs'][] = [
    'label' => $model->last_name . ' ' . mb_substr($model->first_name, 0, 1) . '.',
    'url' => ['view', 'id' => $model->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редагування',
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="user-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>