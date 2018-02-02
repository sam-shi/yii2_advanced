<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '创建广告';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>创建广告:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($model, 'title')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'file')->fileInput() ?>
            <button>Submit</button>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<script>
    //图片上传
    $('#photo-file').on('change', function () {
        var option = {
            url: '/account/photos/uploaduser',
            type: 'POST',
            //dataType: 'JSON',
            success: function (response) {
                loadingHiden();
                if (typeof response != 'object') {
                    response = JSON.parse(response);
                }
                if (response.success == true) {
                    var res = response.data;
                    $("#photo-thumb").attr("src", res.path);
                    $("#photo-top").attr("src", res.path);
                } else {
                    alert('头像', response.msg);
                }
            },
            error: function (response) {
                alert('头像', '上传图片异常');
            }
        };
        $("#userAvatar").ajaxSubmit(option);
        //$(this).parent().submit();
    });
</script>
