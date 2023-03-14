<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Content $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'text')->widget(CKEditor::className(), [
                'editorOptions' => [
                    'language' => 'uk',
                    'preset' => 'content',
                    'inline' => false,
                ],
            ]); ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-save"></i> Зберегти', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>