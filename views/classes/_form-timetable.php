<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Timetable $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerCssFile("https://use.fontawesome.com/releases/v5.3.1/css/all.css");
$this->registerJsFile("@web/js/maskedinput.min.js", [
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]
]);
$this->registerJs(
    '$("#timetable-start").mask("9999.99.99 99:99")'
);
?>

<div class="classes-form-timetable">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'start')->widget(DateTimePicker::className(), [
                'options' => [
                    'placeholder' => 'Формат: РРРР.ММ.ДД ГГ.ХХ',
                    'autocomplete' => 'off',
                ],
                'type' => DateTimePicker::TYPE_INPUT,
                'convertFormat' => true,
                'language' => 'uk',
                'pluginOptions' => [
                    'format' => 'yyyy-MM-dd hh:i',
                    'autoclose' => true,
                    'weekStart' => 1,
                    'startDate' => date('Y.m.d H:i', time()),
                ],
            ]); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'end')->widget(DateTimePicker::className(), [
                'options' => [
                    'placeholder' => 'Формат: РРРР.ММ.ДД ГГ.ХХ',
                    'autocomplete' => 'off',
                ],
                'type' => DateTimePicker::TYPE_INPUT,
                'convertFormat' => true,
                'language' => 'uk',
                'pluginOptions' => [
                    'format' => 'yyyy-MM-dd hh:i',
                    'autoclose' => true,
                    'weekStart' => 1,
                    'startDate' => date('Y.m.d H:i', time()),
                ],
            ]); ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="bx bx-save"></i> Зберегти', ['class' => 'btn btn-action']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>