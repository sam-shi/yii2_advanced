<?php
namespace common\helpers;

use Yii;

class EmailHelper{

    /*
     * @desc  发送邮件
     * @param $target  接收者邮箱
     * @param $title  发送邮件名称
     * @param $content 内容
     * @return bool;
     * */
    public static function send($target,$title,$content)
    {
        $mail = Yii::$app->mailere->compose();
        $mail->setTo($target);
        $mail->setSubject($title);
        //$mail->setTextBody($content);   //发布纯文字文本
        $mail->setHtmlBody($content);   //发布可以带html标签的文本
        if($mail->send())
        {
            return true;
        }else{
            return false;
        }
    }


}