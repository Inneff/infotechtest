<?php

/** @var yii\web\View $this */
/** @var app\models\BookSearch $model */
/** @var yii\bootstrap5\ActiveForm $form */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>
<div class="book-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'row g-2 align-items-end mb-3'],
    ]); ?>

    <div class="col-auto">
        <?= $form->field($model, 'title')->textInput(['placeholder' => 'Название'])->label(false) ?>
    </div>

    <div class="col-auto">
        <?= $form->field($model, 'authorName')->textInput(['placeholder' => 'Автор'])->label(false) ?>
    </div>

    <div class="col-auto">
        <?= $form->field($model, 'year')->textInput(['placeholder' => 'Год'])->label(false) ?>
    </div>

    <div class="col-auto">
        <?= $form->field($model, 'isbn')->textInput(['placeholder' => 'ISBN'])->label(false) ?>
    </div>

    <div class="col-auto">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
