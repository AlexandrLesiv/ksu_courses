<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Редагування CV';

$this->params['breadcrumbs'][] = [
    'label' => 'Профіль',
    'url' => ['profile'],
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="user-update-info">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form-info', [
        'model' => $model,
    ]) ?>

</div>