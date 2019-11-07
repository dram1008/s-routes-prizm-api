<?php

namespace app\models\forms;

use common\models\avatar\UserBill;
use common\models\SendLetter;
use common\models\UserAvatar;
use cs\Application;
use cs\services\Str;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\base\Security;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 */
class Registration extends Model
{

    public $email;

    public $password;

    /**
     */
    public function rules()
    {
        return [
            [['password', 'email'], 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'existEmail', 'message' => 'Такой пользователь уже существует'],
            ['password', 'string', 'min' => 3],
        ];
    }

    public function existEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (\app\models\User::find()->where(['email' => $this->email])->exists()) {
                $this->addError($attribute, 'Такой пользователь уже существует');
                return;
            }
        }
    }

    /**
     */
    public function attributeLabels()
    {
        return [
            'email'    => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * @return \app\models\User
     */
    public function action()
    {
        $email = strtolower($this->email);
        $fields = [
            'email'         => $email,
            'password'      => $this->password,
            'registerDate'  => time(),
            'confirmed'     => 1,
            'time_bonus'    => 5,
            'lastLogin'     => 0,
            'ip'            => 1,
            'rememberToken' => '1',
            'role'          => 0,
        ];

        $user = new \app\models\User($fields);
        $ret = $user->save();
        $user->id = \app\models\User::getDb()->lastInsertID;

        return $user;
    }
}
