<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CClassesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="class-students-search">

    <?php $form = ActiveForm::begin([
        'action' => $action,
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'last_name')->label('Прізвище') ?>
        </div>

        <div class="col-12 col-md-6">
            <?= $form->field($model, 'first_name')->label('Імʼя') ?>
        </div>

        <div class="col-12 col-md-6">
            <?= $form->field($model, 'middle_name')->label('По батькові') ?>
        </div>

        <div class="col-12 col-md-6">
            <?= $form->field($model, 'email')->label('E-mail') ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-search"></i> Шукати', ['class' => 'btn btn-action']) ?>
        <?= Html::resetButton('<i class="bx bx-reset"></i> Скинути', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>