<?php

use app\models\Courses;
use app\models\User;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Форум';

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($course->title, 0, 10) . '...',
    'url' => ['view', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="forum-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?php
        Modal::begin([
            'title' => 'Редагування статусу',
            'toggleButton' => [
                'label' => '<i class="bx bx-send"></i>Написати',
                'tag' => 'a',
                'class' => 'btn btn-action',
            ],
            'size' => 'modal-md'
        ]);
        ?>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 4]) ?>

        <?= Html::submitButton('<i class="bx bxs-send"></i> Надіслати', ['class' => 'btn btn-action mt-3']) ?>

        <?php ActiveForm::end() ?>

        <?php Modal::end(); ?>
    </p>

    <?php Pjax::begin(); ?>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'showHeader' => false,
            'tableOptions' => ['class' => 'table table-striped table-borderless'],
            'columns' => [

                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width:180px;text-align:left;'],
                    'value' => function ($data) {
                        $course = Courses::findOne(['id' =>$data['course_id']]);
                        $user = User::findOne(['id' => $data['user_id']]);
                        $user_name = 'Користувач І.П.';
                        $user_name_title = 'Користувача видалено';
                        $delete = '';
                        $teacher = '';
                        if ($course->teacher_id === Yii::$app->user->id) {
                            $delete = '<br>' . Html::a('<i class="bx bxs-trash"></i>', ['delete', 'id' => $data['id']], [
                                    'data' => [
                                        'confirm' => 'Ви впевнені, що хочете видалити це повідомлення?',
                                        'method' => 'post',
                                    ],
                                ]) . ' ';
                        }
                        if(($user) !== null) {
                            if ($course->teacher_id === $user->id) {
                                $teacher = '<i class="bx bxs-star"></i> ';
                            }
                            $first_name = mb_substr($user->first_name, 0, 1) . '.';
                            $middle_name = mb_substr($user->middle_name, 0, 1) . '.';
                            $last_name = $user->last_name;
                            $user_name = $last_name . ' ' . $first_name . ' ' . $middle_name;
                            $user_name_title = $last_name . ' ' . $user->first_name . ' ' . $user->middle_name;
                        }
                        return '<small><b title="' . $user_name_title . '">' . $teacher . $user_name . '</b><br>' . date('d.m.Y H:i:s', $data['created_at']) . '</small>' . $delete;
                    },
                ],
                [
                    'attribute' => 'text',
                    'format' => 'ntext',
                    'contentOptions' => ['style' => 'width:calc(100% - 200px);text-align:justify;'],
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>
