<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TeacherInfo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form-info">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'info')->widget(CKEditor::className(), [
                'editorOptions' => [
                    'language' => 'uk',
                    'preset' => 'cv',
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