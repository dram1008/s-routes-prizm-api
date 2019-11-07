<?php

namespace app\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class VarDumper
{
    public static function dump($value, $depth = 10, $isHighlight = true)
    {
        if (isset(\Yii::$app)) {
            if (\Yii::$app->response->className() != 'yii\console\Response') {
                \Yii::$app->response->headers->set('Content-Encoding', 'utf-8');
                \Yii::$app->response->charset = 'utf-8';
                \Yii::$app->response->send();
                if (Yii::$app->response->format == Response::FORMAT_JSON) {
                    $isHighlight = false;
                }
                if (Yii::$app->request->isAjax) {
                    $isHighlight = false;
                }
            } else {
                $isHighlight = false;
            }
        }  else {
            $isHighlight = true;
        }

        echo \yii\helpers\VarDumper::dumpAsString($value, $depth, $isHighlight);

        $c = 1;

        if (\Yii::$app->response->className() != 'yii\console\Response') {
            echo "\r\n";
            if ($isHighlight) echo '<pre>';
            foreach (debug_backtrace(2) as $item) {
                echo '#' . $c . ' ' . ArrayHelper::getValue($item, 'file', '') . ':' . ArrayHelper::getValue($item, 'line', '') . ' ' . ArrayHelper::getValue($item, 'class', '') . ArrayHelper::getValue($item, 'type', '') . ArrayHelper::getValue($item, 'function', '') . "\n";
                $c++;
            }
            if ($isHighlight) echo '</pre>';
        }

        exit;
    }

    /**
     * @return array
     */
    public static function getStack()
    {
        $rows = [];
        $c = 1;
        foreach (debug_backtrace(2) as $item) {
            $rows[] = '#' . $c . ' ' . ArrayHelper::getValue($item, 'file', '') . ':' . ArrayHelper::getValue($item, 'line', '') . ' ' . ArrayHelper::getValue($item, 'class', '') . ArrayHelper::getValue($item, 'type', '') . ArrayHelper::getValue($item, 'function', '');
            $c++;
        }
        
        return $rows;
    }
} 