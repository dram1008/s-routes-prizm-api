<?php

namespace app\controllers;

use app\services\VarDumper;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class RequestController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $key = Yii::$app->request->post('key');
        if ($key != Yii::$app->params['key']) {
            throw new ForbiddenHttpException('Запрещено, код не верный');
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws
     */
    public function actionIndex()
    {
        $client = new Client(['baseUrl' => 'http://webserver:7742']);
        $params = Yii::$app->request->post();
        $response = $client->get('prizm', $params)->send();

        return  Json::decode($response->content);
    }
}
