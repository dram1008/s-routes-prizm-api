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
        foreach ($params as $k => $v) {
            $rows[] = $k . '=' . urlencode($v);
        }

        $response = file_get_contents("https://prizm-api.neiro-n.com:9976/prizm?" . join('&', $rows), false, stream_context_create($arrContextOptions));

        try {
            $data = Json::decode($response);
        } catch (\Exception $e) {
            throw $e;
        }

        return  $response;
    }
}
