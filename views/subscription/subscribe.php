<?php

/** @var yii\web\View $this */
/** @var app\models\SubscriptionForm $model */
/** @var app\models\Author $author */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Подписка на новые книги';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['/author/index']];
$this->params['breadcrumbs'][] = ['label' => $author->full_name, 'url' => ['/author/view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-subscribe">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Подпишитесь на новые книги автора
        <strong><?= Html::encode($author->full_name) ?></strong>.
        Уведомление придёт по SMS.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'author_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'phone')->textInput(['placeholder' => '+7 900 123-45-67', 'autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Подписаться', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
