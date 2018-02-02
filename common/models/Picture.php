<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "picture".
 *
 * @property string $id
 * @property string $path
 * @property string $md5
 * @property string $create_time
 * @property integer $status
 */
class Picture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'picture';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'status'], 'integer'],
            [['path'], 'string', 'max' => 255],
            [['md5'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id自增',
            'path' => '路径',
            'md5' => '文件md5',
            'create_time' => '创建时间',
            'status' => '状态（0删除，1正常）',
        ];
    }
}
