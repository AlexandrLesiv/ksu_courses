<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Редагування особистих даних';

$this->params['breadcrumbs'][] = [
    'label' => 'Профіль',
    'url' => ['profile'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редагування',
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="user-update-profile">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>