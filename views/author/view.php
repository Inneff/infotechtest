<?php

/** @var yii\web\View $this */
/** @var app\models\Author $model */

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Подписаться на новые книги', ['/subscription/subscribe', 'author_id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Удалить этого автора?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
        ],
    ]) ?>

    <h3>Книги автора</h3>
    <?php if (empty($model->books)): ?>
        <p class="text-muted">Книг пока нет.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($model->books as $book): ?>
                <li><?= Html::a(Html::encode($book->title), ['/book/view', 'id' => $book->id]) ?> (<?= $book->year ?>)</li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>
