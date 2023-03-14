<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Classes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="classes-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'students_count')->textInput(['type' => 'number', 'min' => 1, 'max' => (int)$max_count === 0 ? 1 : (int)$max_count, 'placeholder' => 'Максимальна к-ть. ' . $max_count]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-save"></i> Зберегти', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>