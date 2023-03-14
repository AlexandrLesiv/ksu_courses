<?php

use app\models\Content;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Контент';

$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="content-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php Pjax::begin(); ?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'label' => 'Сторінка',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                        $host = $_SERVER['HTTP_HOST'];

                        return '<b>Сторінка: </b> ' . $data['title'] . '<br>
                                <b>URL: </b> ' . Html::a($protocol . $host . $data['url'], [$protocol . $host . $data['url']]) . '<br>
                                <b>Ключові слова: </b> ' . $data['keywords'] . '<br>
                                <b>Опис: </b> ' . $data['description'];
                    },
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => function ($data) {
                        return date('d.m.Y H:i:s', $data['updated_at']);
                    },
                ],

                [
                    'class' => ActionColumn::className(),
                    'template' => '{view} {update} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>