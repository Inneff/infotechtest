<?php

namespace app\controllers;

use app\models\Author;
use app\models\SubscriptionForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Подписка на новые книги автора. Доступна гостям и пользователям.
 */
class SubscriptionController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['subscribe'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Оформление подписки на конкретного автора.
     */
    public function actionSubscribe(int $author_id): Response|string
    {
        $author = $this->findAuthor($author_id);

        $model = new SubscriptionForm(['author_id' => $author->id]);

        if ($model->load(Yii::$app->request->post()) && $model->subscribe()) {
            Yii::$app->session->setFlash('success', 'Вы подписались на новые книги автора.');
            return $this->redirect(['author/view', 'id' => $author->id]);
        }

        return $this->render('subscribe', [
            'model' => $model,
            'author' => $author,
        ]);
    }

    protected function findAuthor(int $id): Author
    {
        if (($author = Author::findOne($id)) !== null) {
            return $author;
        }

        throw new NotFoundHttpException('Автор не найден.');
    }
}
