<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionRegistration()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new \app\models\forms\Registration();
        if (!$model->load(Yii::$app->request->post(), '')) {
            return self::jsonErrorId(101, 'Не загружены даннеы');
        }
        if (!$model->validate()) {
            return self::jsonErrorId(102, $model->errors);
        }
        $user = $model->action();
        if (is_null($user)) {
            return self::jsonErrorId(103, $model->errors);
        }

        return self::jsonSuccess([
            'id' => $user->id,
        ]);
    }

    public function jsonErrorId($id, $data)
    {
        return [
            'success' => false,
            'data' => $data,
        ];
    }
    public function jsonSuccess($data = null)
    {
        return [
            'success' => true,
            'data' => $data,
        ];
    }
}
