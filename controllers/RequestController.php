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
     *
     * @return string
     * @throws
     */
    public function actionIndex()
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ],
        ];


        $params = Yii::$app->request->post();
        unset($params['key']);
        $rows = [];
        foreach ($params)

        $response = file_get_contents("https://prizm-api.neiro-n.com:9976/prizm?requestType=getBlockchainStatus", false, stream_context_create($arrContextOptions));


        $client = new Client(['baseUrl' => 'http://localhost:9976']);

        $response = $client->get('prizm', $params)->send();

        return  Json::decode($response->content);
    }
}
