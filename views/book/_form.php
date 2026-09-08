<?php

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\bootstrap5\ActiveForm $form */

use app\models\Author;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

$authors = ArrayHelper::map(
    Author::find()->orderBy('full_name')->all(),
    'id',
    'full_name'
);

?>
<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'authorIds')->checkboxList($authors) ?>

    <?php if (!$model->isNewRecord && $model->photo): ?>
        <div class="mb-3">
            <label class="form-label">Текущее фото</label>
            <div><?= Html::img($model->photo, ['alt' => $model->title, 'style' => 'max-width:200px;']) ?></div>
        </div>
    <?php endif ?>

    <?= $form->field($model, 'photoFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
