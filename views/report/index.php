<?php

/** @var yii\web\View $this */
/** @var app\models\ReportForm $model */
/** @var array $rows */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'ТОП-10 авторов по количеству книг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['index'], 'options' => ['class' => 'row g-2 align-items-end mb-4']]); ?>

    <div class="col-auto">
        <?= $form->field($model, 'year')->textInput(['type' => 'number', 'placeholder' => date('Y')]) ?>
    </div>

    <div class="col-auto">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if ($model->hasErrors()): ?>
        <div class="alert alert-danger">
            <?php foreach ($model->getFirstErrors() as $error): ?>
                <div><?= Html::encode($error) ?></div>
            <?php endforeach ?>
        </div>
    <?php elseif (empty($rows)): ?>
        <p class="text-muted">За <?= Html::encode($model->year) ?> год данных нет.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Автор</th>
                <th>Количество книг</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= Html::a(Html::encode($row['full_name']), ['/author/view', 'id' => $row['id']]) ?></td>
                    <td><?= (int) $row['books_count'] ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</div>
