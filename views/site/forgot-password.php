<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Відновлення пароля';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<section class="forgot-password">
    <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

    <div class="forgot-password__wrapper">
        <?= Html::img('@web/img/login-img.png', ['class' => 'forgot-password__img', 'alt' => 'СВідновлення пароля']) ?>

        <?php $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'forgot-password__form',
                    'id' => 'forgot-password-form',
                ]
            ]
        ); ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="bx bxs-key"></i> Відновити', ['class' => 'btn btn-action', 'name' => 'forgot-password-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</section>