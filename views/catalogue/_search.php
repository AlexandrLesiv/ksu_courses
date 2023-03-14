<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CatalogueSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="courses-search mb-5">

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

        <div class="col-md-8">
            <?= $form->field($model, 'keywords') ?>
        </div>

        <?php if ($category === 'all') : ?>
            <div class="col-12 col-md-4">
                <?= $form->field($model, 'status')->dropDownList(
                    [
                        'Новий' => 'Новий',
                        'В процесі' => 'В процесі',
                        'Йде набір' => 'Йде набір',
                        'Завершено' => 'Завершено',
                        'Заблоковано' => 'Заблоковано'
                    ],
                    [
                        'prompt' => ''
                    ]
                ) ?>
            </div>
        <?php elseif ($category === 'favorites') : ?>
            <div class="col-12 col-md-4">
                <?= $form->field($model, 'status')->dropDownList(
                    [
                        'Новий' => 'Новий',
                        'Йде набір' => 'Йде набір',
                    ],
                    [
                        'prompt' => ''
                    ]
                ) ?>
            </div>
        <?php endif ?>

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'last_name')->label('Прізвище') ?>
        </div>

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'first_name')->label('Імʼя') ?>
        </div>

        <div class="col-12 col-md-4">
            <?= $form->field($model, 'middle_name')->label('По батькові') ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-search"></i> Шукати', ['class' => 'btn btn-action']) ?>
        <?= Html::resetButton('<i class="bx bx-reset"></i> Скинути', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>