<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Авторизація';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="login">
    <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

    <div class="login__wrapper">
        <?= Html::img('@web/img/login-img.png', ['class' => 'login__img', 'alt' => 'Сторінка авторизації']) ?>

        <?php $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'login__form',
                    'id' => 'login-form',
                ]
            ]
        ); ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="bx bx-log-in-circle"></i> Увійти', ['class' => 'btn btn-action', 'name' => 'login-button']) ?>
        </div>

        <p class="login__info">Якщо ви забули пароль - ви можете відновити його, перейшовши за <a href="<?= Yii::$app->urlManager->createUrl(['/site/forgot-password']) ?>"">посиланням</a>.</p>

        <?php ActiveForm::end(); ?>
    </div>
</section>