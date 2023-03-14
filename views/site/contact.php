<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$title = $content->title;
$keywords = $content->description;
$description = $content->keywords;

$this->title = $title;

$this->registerMetaTag([
    'name' => 'description',
    'content' => $description,
]);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $keywords,
]);

Yii::$app->seo->putOpenGraphTags(
    [
        'og:site_name' => Yii::$app->name,
        'og:title' => $title,
        'og:description' => $description,
        'og:image' => Url::to('@web/img/banner.jpg', true),
        'og:url' => Url::canonical(),
    ]
);

Yii::$app->seo->putGooglePlusMetaTags(
    [
        'name' => $title,
        'description' => $description,
        'image'  => Url::to('@web/img/banner.jpg', true),
    ]
);
?>
<div class="contact">

    <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

    <div class="contact__wrapper">
        <div class="contact__info">
            <?= $content->text ?>
        </div>

        <?php $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'contact__form',
                    'id' => 'contact-form',
                ]
            ]
        ); ?>

        <h3 class="mb-3">Зворотній звʼязок</h3>

        <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>

        <?= $form->field($model, 'email')->textInput(['type' => 'email', 'autocomplete' => 'off']) ?>

        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="bx bx-send"></i> Надіслати', ['class' => 'btn btn-action', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>