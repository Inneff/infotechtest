<?php

namespace app\controllers;

use app\models\Book;
use app\models\BookSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Каталог книг (с поиском/фильтрацией).
     */
    public function actionIndex(): string
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр книги.
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Добавление книги.
     */
    public function actionCreate(): Response|string
    {
        $model = new Book();

        if ($this->saveModel($model)) {
            Yii::$app->session->setFlash('success', 'Книга добавлена.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Редактирование книги.
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $model->loadAuthorIds();

        if ($this->saveModel($model)) {
            Yii::$app->session->setFlash('success', 'Книга обновлена.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Удаление книги.
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Загружает данные формы и сохраняет книгу.
     */
    private function saveModel(Book $model): bool
    {
        if (!$model->load(Yii::$app->request->post())) {
            return false;
        }

        $model->photoFile = UploadedFile::getInstance($model, 'photoFile');

        return $model->saveBook();
    }

    protected function findModel(int $id): Book
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Книга не найдена.');
    }
}
