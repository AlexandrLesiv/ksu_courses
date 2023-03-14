<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Content $model */

$this->title = mb_substr($model->title, 0, 10) . '...';

$this->params['breadcrumbs'][] = [
    'label' => 'Контент',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<div class="content-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-edit-alt"></i>Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-trash"></i>Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-action',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цей елемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function ($data) {
                    $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                    $host = $_SERVER['HTTP_HOST'];

                    return Html::a($protocol . $host . $data['url'], [$protocol . $host . $data['url']]);
                },
            ],
            'keywords',
            'description',
            'text:raw',
            [
                'attribute' => 'updated_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['updated_at']);
                },
            ],
        ],
    ]) ?>

</div>