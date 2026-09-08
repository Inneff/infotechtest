<?php

/** @var yii\web\View $this */
/** @var app\models\Book $model */

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Каталог книг', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Удалить эту книгу?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif ?>
    </p>

    <?php if ($model->photo): ?>
        <div class="mb-3">
            <?= Html::img($model->photo, ['alt' => $model->title, 'style' => 'max-width:300px;']) ?>
        </div>
    <?php endif ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'isbn',
            'description:ntext',
            [
                'label' => 'Авторы',
                'value' => function ($model) {
                    return implode(', ', array_map(fn ($author) => $author->full_name, $model->authors));
                },
            ],
        ],
    ]) ?>
</div>
