<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CClassesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="classes-search mb-5">

    <?php $form = ActiveForm::begin([
        'action' => $action,
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'title') ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-search"></i> Шукати', ['class' => 'btn btn-action']) ?>
        <?= Html::resetButton('<i class="bx bx-reset"></i> Скинути', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>