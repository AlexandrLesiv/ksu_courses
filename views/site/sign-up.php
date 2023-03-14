<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Реєстрація';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="sign-up">
    <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

    <div class="sign-up__wrapper">
        <?= Html::img('@web/img/login-img.png', ['class' => 'sign-up__img', 'alt' => 'Сторінка реєстрації']) ?>

        <?php $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'sign-up__form',
                    'id' => 'sign-up-form',
                ]
            ]
        ); ?>

        <?= $form->field($model, 'role')->dropDownList(
            [
                'teacher' => 'Викладач',
                'student' => 'Здобувач освіти',
            ],
            [
                'prompt' => 'Не вказано'
            ]
        );
        ?>

        <?= $form->field($model, 'first_name')->textInput() ?>

        <?= $form->field($model, 'middle_name')->textInput() ?>

        <?= $form->field($model, 'last_name')->textInput() ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'repeat')->passwordInput() ?>

        <?= $form->field($model, 'term')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="bx bx-user-plus"></i> Зареєструватись', ['class' => 'btn btn-action', 'name' => 'sign-up-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</section>