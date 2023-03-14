<?php

use app\models\AuthAssignment;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

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
        <?= Html::a('<i class="bx bx-edit-alt"></i>Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a($model->status === 10 ? '<i class="bx bx-lock-alt"></i>Заблокувати' : '<i class="bx bx-lock-open-alt"></i>Розблокувати', ['ban', 'id' => $model->id], [
            'class' => 'btn btn-action',
            'data' => [
                'confirm' => $model->status === 10 ? 'Ви впевнені, що хочете заблокувати користувача?' : 'Ви впевнені, що хочете розблокувати користувача?',
                'method' => 'post',
            ],
        ]) ?>
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
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                    $status_class = $data['status'] == 10 ? 'success' : 'danger';
                    $status_text = $data['status'] == 10 ? 'Активний' : 'Заблоковано';

                    return '<span class="badge bg-' . $status_class . '">' . $status_text . '</span>';
                },
            ],
            [
                'attribute' => 'id',
                'attribute' => 'Роль',
                'format' => 'raw',
                'value' => function ($data) {
                    $role = AuthAssignment::findOne(['user_id' => $data['id']])->item_name;
                    $role = $role == 'teacher' ? 'Викладач' : 'Здобувач освіти';
                    return '<span class="badge bg-dark">' . $role . '</span>';
                },
            ],
            'last_name',
            'first_name',
            'middle_name',
            'email:email',
        ],
    ]) ?>

</div>