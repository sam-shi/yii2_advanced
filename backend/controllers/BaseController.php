<?php
/**
 * Created by PhpStorm.
 * User: hacklog
 * Date: 12/13/16
 * Time: 10:12 AM
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    const STATUS_CODE_SUCC = 1;
    const STATUS_CODE_FAIL = 0;

    protected function formatResponse($status, $message = '', $url = '', $data = [])
    {
        return ['status' => $status, 'message' => $message, 'url' => $url, 'data' => $data];
    }

    protected function setAjaxResponse()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    protected function ajaxSuccess($message = '', $url = '', $data = [])
    {
        $this->setAjaxResponse();
        return $this->formatResponse(self::STATUS_CODE_SUCC, $message, $url, $data);
    }

    protected function ajaxFail($message = '', $url = '', $data = [])
    {
        $this->setAjaxResponse();
        return $this->formatResponse(self::STATUS_CODE_FAIL, $message, $url, $data);
    }

    public function isAjax()
    {
        return Yii::$app->request->getIsAjax();
    }

    //统一获取post参数的方法
    public function post($key, $default = "") {
        return Yii::$app->request->post($key, $default);
    }

    //统一获取get参数的方法
    public function get($key, $default = "") {
        return Yii::$app->request->get($key, $default);
    }
}