<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ad".
 *
 * @property string $id
 * @property string $title
 * @property integer $pic_id
 */
class Ad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'pic_id'], 'required'],
            [['pic_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '广告ID',
            'title' => '广告标题',
            'pic_id' => '图片ID',
        ];
    }
}
