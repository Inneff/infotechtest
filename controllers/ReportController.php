<?php

namespace app\controllers;

use app\models\ReportForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Отчёт "ТОП-10 авторов, выпустивших больше книг за год".
 * Доступен всем (и гостям, и пользователям).
 */
class ReportController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $model = new ReportForm();
        $model->year = (int) Yii::$app->request->get('year', date('Y'));

        $rows = $model->validate() ? $model->getTopAuthors() : [];

        return $this->render('index', [
            'model' => $model,
            'rows' => $rows,
        ]);
    }
}
