<?php

use app\models\ClassStudents;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Courses $model */

$this->title = $model->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);

$status_info = '<div class="alert alert-info" role="alert">Перш ніж змінювати статус курсу на "Йде набір" - переконайтесь, що всю інформацію по навчальному курсу заповнено, а саме: додано групи, уроки та наповнено уроки навчальними матеріалами.</div>';
?>
<div class="courses-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-edit-alt"></i>Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?php if ($model->status === 'Йде набір') : ?>
            <?= Html::a('<i class="bx bx-mail-send"></i>Заявки<span class="badge bg-warning req-count" title="Нові заявки">' . $req_count . '</span>', ['requests', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?php endif; ?>
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
            'description',
            'keywords',
            [
                'attribute' => 'students_count',
                'label' => 'К-сть. студентів',
                'format' => 'raw',
                'value' => function ($data) {
                    $min = 0;
                    $max = $data['students_count'];
                    $now = ClassStudents::find()->where(['course_id' => $data['id'], 'status' => 'Підтверджено'])->count();
                    $width = number_format($now / $max * 100, 2, '.', '');
                    return '<div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: ' . $width . '%" aria-valuenow="' . $now . '" aria-valuemin="' . $min . '" aria-valuemax="' . $max . '">' . $now . '/' . $max . '</div></div>';
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data['status'] === 'Новий') {
                        $style = 'info';
                    } elseif ($data['status'] === 'В процесі') {
                        $style = 'success';
                    } elseif ($data['status'] === 'Йде набір') {
                        $style = 'primary';
                    } elseif ($data['status'] === 'Завершено') {
                        $style = 'secondary';
                    } elseif ($data['status'] === 'Заблоковано') {
                        $style = 'danger';
                    }
                    return '<span class="badge bg-' . $style . '">' . $data['status'] . '</span>';
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['created_at']);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['updated_at']);
                },
            ],
        ],
    ]) ?>

    <p class="control-btn control-btn--inverse">
        <?= Html::a('<i class="bx bx-group"></i>Групи<span class="badge bg-success req-count" title="Кількість груп">' . $class_count . '</span>', ['classes', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-notepad"></i>Уроки<span class="badge bg-success req-count" title="Кількість уроків">' . $lesson_count . '</span>', ['/lessons/index', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-chat"></i>Форум', ['/forum/index', 'id' => $model->id], ['class' => 'btn btn-action']) ?>

        <?php
        Modal::begin([
            'title' => 'Редагування статусу',
            'toggleButton' => [
                'label' => '<i class="bx bx-badge-check"></i>Статус',
                'tag' => 'a',
                'class' => 'btn btn-action',
            ],
            'size' => 'modal-md'
        ]);
        ?>

        <?= $status_info ?>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'status')->dropDownList(
            [
                'Новий' => 'Новий',
                'Йде набір' => 'Йде набір',
                'В процесі' => 'В процесі',
                'Завершено' => 'Завершено',
            ],
            [
                'prompt' => ''
            ]
        ) ?>

        <?= Html::submitButton('<i class="bx bx-save"></i> Зберегти', ['class' => 'btn btn-action mt-3']) ?>

        <?php ActiveForm::end() ?>

        <?php Modal::end(); ?>
    </p>

</div>