<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Каталог книг</h1>
        <p class="lead">Каталог книг с авторами, подписками и отчётами.</p>
        <p>
            <?= Html::a('Перейти к каталогу', ['/book/index'], ['class' => 'btn btn-lg btn-success']) ?>
        </p>
    </div>
</div>
