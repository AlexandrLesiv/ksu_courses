<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MediaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="media-search mb-5">

    <?php $form = ActiveForm::begin([
        'action' => $action,
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-12 col-md-8">
            <?= $form->field($model, 'title') ?>
        </div>

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'type')->dropDownList(
                [
                    'Відео' => 'Відео',
                    'Домашня робота' => 'Домашня робота',
                    'Зображення' => 'Зображення',
                    'Текст' => 'Текст',
                    'Файл' => 'Файл',
                ],
                [
                    'prompt' => ''
                ]
            ) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-search"></i> Шукати', ['class' => 'btn btn-action']) ?>
        <?= Html::resetButton('<i class="bx bx-reset"></i> Скинути', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>