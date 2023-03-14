<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

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
<section class="index">

    <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

    <div class="index__content">
        <?= $content->text ?>
    </div>

</section>