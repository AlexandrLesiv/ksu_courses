<?php

use vision\ytbwidget\YouTube;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Lessons $model */

$this->title = $model->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['/courses/index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($course->title, 0, 10) . '...',
    'url' => ['/catalogue/view', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($this->title, 0, 10) . '...',
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<div class="lessons-info">

    <h2><?= Html::encode($this->title) ?></h2>


    <?php if (count($hometask)) : ?>
        <p class="control-btn">
            <?= Html::a('<i class="bx bx-task"></i> Завдання', ['hometask', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        </p>
    <?php endif ?>

    <div class="lessons-info__content">
        <?php foreach ($media as $item) : ?>
            <?php if ($item->type === 'Текст') : ?>
                <?= $item->description ?>
            <?php elseif ($item->type === 'Зображення') : ?>
                <?= $item->description ?>
                <?= Html::img('@web/img/media/' . $item->file, ['class' => 'img-fluid', 'alt' => $item->title]) ?>
            <?php elseif ($item->type === 'Файл') : ?>
                <?= $item->description ?>
                <p><?= $item->title ?>: <a href="/files/<?= $item->file ?>" download>Завантажити файл</a></p>
            <?php elseif ($item->type === 'Відео') : ?>
                <?php
                $elements = explode('/', $item->description);

                if (mb_substr($elements[2], -9) == 'vimeo.com') {
                    $type = 'vimeo';
                } else {
                    $type = 'youtube';
                }

                for ($i = 0; $i < count($elements); $i++) {
                    if ($i == count($elements) - 1) {
                        if (mb_substr($elements[$i], 0, 8) == 'watch?v=') {
                            $videoId = mb_substr($elements[$i], 8);
                        } else {
                            $videoId = $elements[$i];
                        }

                        $el = explode('?', $videoId);
                        $videoId = $el[0];
                    }
                }

                echo YouTube::widget([
                    'videoId' => $videoId,
                    'playerVars' => [
                        'controls' => 0,
                        'fs' => 0,
                        'iv_load_policy' => 3,
                        'modestbranding' => 1,
                        'rel' => 0,
                        'showinfo' => 0,
                    ]
                ]) ?>
            <?php endif ?>
        <?php endforeach; ?>
    </div>
</div>