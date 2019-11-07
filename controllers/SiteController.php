<?php

namespace app\controllers;

use app\services\VarDumper;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        exit();

        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionPrizm777()
    {
        $client = new Client(['baseUrl' => 'http://localhost:7742']);

        $response = $client->get('prizm', Yii::$app->request->get())->send();

        try {
            $data = Json::decode($response->content);
            VarDumper::dump($data);
        } catch (\Exception $e) {
            VarDumper::dump($response);
        }


    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionPrizm778()
    {
        $client = new Client(['baseUrl' => 'http://localhost:7742']);

        $response = $client->get('prizm', Yii::$app->request->get())->send();

        try {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Json::decode($response->content);
            return $data;
        } catch (\Exception $e) {
            VarDumper::dump($response);
        }


    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionPrizm998()
    {
        $client = new Client(['baseUrl' => 'http://localhost:7742']);

        $response = $client->get('prizm', [
            'requestType' => 'getBlockchainTransactions',
            'account' => 'PRIZM-GPN2-8CZ7-PNYP-8CEHG',
            'lastIndex' => 10,
        ])->send();

        try {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = Json::decode($response->content);
            return $data;
        } catch (\Exception $e) {
            VarDumper::dump($response);
        }


    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionPrizm999()
    {
        $client = new Client(['baseUrl' => 'http://localhost:7742']);

        $response = $client->get('prizm', [
            'requestType' => 'getBlockchainTransactions',
            'account' => 'PRIZM-GPN2-8CZ7-PNYP-8CEHG',
            'lastIndex' => 10,
        ])->send();

        try {
            $data = Json::decode($response->content);
            VarDumper::dump($data);
        } catch (\Exception $e) {
            VarDumper::dump($response);
        }


    }

}
