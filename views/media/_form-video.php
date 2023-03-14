<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Media $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="media-form-video">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'description', ['options' => ['class' => 'required']])->textInput(['maxlength' => true])->label('Посилання на відео YouTube') ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-save"></i> Зберегти', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>