<?php
namespace backend\controllers;

use backend\models\UploadForm;
use common\helpers\EmailHelper;
use common\models\Ad;
use common\models\Picture;
use Yii;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
 * Site controller
 */
class AdController extends BaseController
{

    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'ad-list' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCreate()
    {
        $model = new UploadForm();
        /*if(!Yii::$app->request->isPost){
            return $this->render('create');
        }*/
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('UploadForm');
            $model->file = UploadedFile::getInstance($model, 'file');
            $type = 'ad';
            if ($model->file && $model->validate()) {
                $base_path = \Yii::getAlias('@uploads/');
                if(!file_exists($base_path)){
                    mkdir ($base_path,0777,true);
                }
                $file = $type.'/'.$model->file->baseName . '.' . $model->file->extension;
                $file_path = $base_path . $file;
                if($model->file->saveAs($file_path)){
                    //上传到七牛
                    $img_info = $this->savePic($file_path);
                    if(!$img_info){
                        return false;
                    }
                    //保存至数据库
                    $ad = new Ad();
                    $ad->title = $post['title'];
                    $ad->pic_id = $img_info['id'];
                    $ad->save();
                }
            }
        }

        return $this->render('create', ['model' => $model]);
    }


    /**
     * 广告列表
     */
    public function actionAdList()
    {
        $title = Yii::$app->request->post('title','');
        $query = Ad::find()->select('id,title');
        if($title){
            $query->andWhere(['like','title',$title]);
        }
        $ad_list = $query->asArray()->all();
        return $this->ajaxSuccess('修改成功','',$ad_list);
    }

    /**
     * 发送邮箱
     */
    public function actionSendEmail()
    {
        $emailContent = 'aaa<br>'
            .'bbb<br>'
            .'ccc<br>'
            .'ddd<br>'
            .'eee<br>';
        try{
            $emailHelper = EmailHelper::send('894272817@qq.com','测试邮件发送',$emailContent);
            if(!$emailHelper){
                return $this->ajaxFail('发送失败');
            }
        }catch (\Exception $e){
            return $this->ajaxFail($e->getMessage());
        }
        return $this->ajaxSuccess('发送成功');
    }

    /**
     * 上传图片到数据库
     */
    public function savePic($url)
    {
        $file_path =  $url;
        $file_md5 = md5_file($file_path);
        /*$image = Picture::find()->where(['md5'=>$file_md5])->asArray()->one();
        if ($image) {
            unlink($file_path); // 图片已存在，删除该图片
            return $image;
        }*/
        $model = new Picture();
        //$data['path'] = $url;  //本地图片路径
        $data['path'] = $this->upload($url);  //七牛图片路径
        $data['md5'] = $file_md5;
        $data['create_time'] = time();
        $data['status'] = 1;
        $model->setAttributes($data);
        if ($model->save()) {
            return $model->getAttributes();
        }
        return false;
    }

    /**
     * 上传图片到七牛服务器
     */
    public function upload($url, $type = 1)
    {
        //上传文件的本地路径
        if ($type == 1) {
            $token = $this->getToken();
            $filePath =  $url;
        } else {
            $token = $this->getTokenUeditor();
            $filePath = Yii::$app->params['ueditorConfig']['imageRoot'] . $url;
        }
        $uploadMgr = new UploadManager();
        // 上传到七牛后保存的文件名
        //$key = uniqid();
        $key = explode('/', $url);
        $key = array_pop($key);

        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return false;
        } else {
            return $ret['key'];
        }

    }

    /**
     * 生成上传凭证
     * @return string
     */
    private function getToken()
    {
        $accessKey = Yii::$app->params['qiniu']['ak'];
        $secretKey = Yii::$app->params['qiniu']['sk'];
        $auth = new Auth($accessKey, $secretKey);
        $bucket = Yii::$app->params['qiniu']['bucket'];//上传空间名称
        //设置put policy的其他参数
        //$opts=['callbackUrl'=>'http://www.callback.com/','callbackBody'=>'name=$(fname)&hash=$(etag)','returnUrl'=>"http://www.baidu.com"];
        return $auth->uploadToken($bucket);//生成token
    }

    /**
     * 生成上传凭证->百度编辑器
     * @return string
     */
    private function getTokenUeditor()
    {
        $accessKey = Yii::$app->params['qiniu_ueditor']['ak'];
        $secretKey = Yii::$app->params['qiniu_ueditor']['sk'];
        $auth = new Auth($accessKey, $secretKey);
        $bucket = Yii::$app->params['qiniu_ueditor']['bucket'];//上传空间名称
        //设置put policy的其他参数
        //$opts=['callbackUrl'=>'http://www.callback.com/','callbackBody'=>'name=$(fname)&hash=$(etag)','returnUrl'=>"http://www.baidu.com"];
        return $auth->uploadToken($bucket);//生成token
    }
}
