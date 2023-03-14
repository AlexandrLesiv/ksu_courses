<?php

use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->last_name;

$this->title = $model->last_name . ' ' . mb_substr($model->first_name, 0, 1) . '.';

$this->params['breadcrumbs'][] = [
    'label' => $role == 'teacher' ? 'Викладачі' : 'Здобувачі освіти',
    'url' => $role == 'teacher' ? ['teachers'] : ['students'],
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-edit-alt"></i>Редагувати', ['update-profile'], ['class' => 'btn btn-action']) ?>
        <?php if (Yii::$app->user->can('teacher')) : ?>
            <?= Html::a('<i class="bx bxs-user-detail"></i>Редагувати CV', ['update-info'], ['class' => 'btn btn-action']) ?>
        <?php endif ?>

        <?php
        Modal::begin([
            'title' => 'Редагування паролю',
            'toggleButton' => [
                'label' => '<i class="bx bx-shield-quarter"></i>Безпека',
                'tag' => 'a',
                'class' => 'btn btn-action',
            ],
            'size' => 'modal-md'
        ]);
        ?>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($password, 'password')->passwordInput() ?>

        <?= $form->field($password, 'repeat')->passwordInput() ?>

        <?= Html::submitButton('<i class="bx bx-save"></i> Зберегти', ['class' => 'btn btn-action mt-3']) ?>

        <?php ActiveForm::end() ?>

        <?php Modal::end(); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'last_name',
            'first_name',
            'middle_name',
            'email:email',
        ],
    ]) ?>

    <?php if ($info !== null) : ?>
        <p class="control-btn control-btn--inverse">
            <?php
            Modal::begin([
                'title' => 'Резюме викладача',
                'toggleButton' => [
                    'label' => '<i class="bx bx-detail"></i>Моє резюме',
                    'tag' => 'a',
                    'class' => 'btn btn-action',
                ],
                'size' => 'modal-xl'
            ]);
            ?>

            <?= $info->info ?>

            <?php Modal::end(); ?>
        </p>
    <?php endif; ?>

</div>