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
        $params = Yii::$app->request->post();
        unset($params['key']);

        return $this->send($params);
    }

    /**
     * Выводит монеты на нужный кошелек
     *
     * @return string
     * @throws
     */
    public function actionWithdraw()
    {
        $params = Yii::$app->request->post();
        unset($params['key']);

        return $this->payPZM($params['amount'], $params['address'], $params['public_key'], $params['comment']);
    }

    /**
     * @param string $summa         сумма для перевода
     * @param string $pzm           Кошелек для вывода
     * @param string $public_key    Публичный ключ
     * @param string $text          Коментарий
     */
    private function payPZM($summa, $pzm, $public_key, $text)
    {
        $p2 = \Yii::$app->params['prizm_wallet_secret'];   //  это пароль вы который указывали при настройке сервлета
        $params = [
            'sendkey'     => $p2,
            'amount'      => $summa,
            'comment'     => urlencode($text),
            'destination' => $pzm,
            'publickey'   => $public_key,
            'requestType' => 'sendMoney',
        ];
//        $url = 'http://localhost:8888/send?sendkey=' . $p2 . '&amount=' . $summa . '&comment=' . urlencode($text) . '&destination=' . $pzm . '&publickey=' . $public_key;
//        $page = '';
//        $result = get_web_page($url);
//
//        if (($result['errno'] != 0) || ($result['http_code'] != 200)) {
//            $error = $result['errmsg'];
//        } else {
//            $page = $result['content'];
//        }
//
//        if (preg_match('/^\+?\d+$/', $page)) {
//            $return = true;
//        } else {
//            $return = false;
//        }

        return $this->send($params);
    }

    private function send($params)
    {
        $arrContextOptions = [
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ],
        ];

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

        return $data;
    }
}
