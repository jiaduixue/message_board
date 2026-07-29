<?php
// 假设你的项目使用了命名空间，如果没有请去掉 namespace
namespace common\models;

use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
class ApiParameter extends ActiveRecord
{
    const REQUIRE_YES = 1;
    const REQUIRE_NO = 0;

    const TYPE_DATA_STRING = 'String';
    const TYPE_DATA_INT = 'Integer';
    const TYPE_DATA_BOOL = 'Boolean';
    const TYPE_DATA_OBJ = 'Object';
    const TYPE_DATA_ARRAY = 'Array';

    const TYPE_PARAM_REQUEST = 'request';
    const TYPE_PARAM_RESPONSE = 'response';

    const TYPE_LOCATION_QUERY = 'query';
    const TYPE_LOCATION_BODY = 'body';
    const TYPE_LOCATION_HEADER = 'header';
    const TYPE_LOCATION_PATH = 'path';

    public $error; // 添加这一行

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%api_parameter}}'; // 对应之前设计的表名
        
    }
    
  
    /**
     * 定义验证规则
     */
    public function rules()
    {
        return [
            [['interface_id'], 'required'],
            [['param_type', 'location', 'name', 'default_value', 'data_type', 'is_required'],  'safe'],
            [['default_value'], 'string', 'max' => 150],
        ];
    }

    /**
     * 定义属性标签（用于表单显示）
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'interface_id' => '关联 api_interface.id',
            'param_type' => '参数类型: request-请求参数, response-响应参数',
            'location' => '参数位置 (仅针对request)',
            'name' => '参数名称 (例如: username)',
            'default_value' => '',
            'data_type' => '数据类型 (String, Integer, Boolean, Object, Array)',
            'is_required' => '是否必填: 1-是, 0-否',
            'default_value' => '默认值',
            'description' => '参数说明/备注',
            'sort_order' => '排序权重',
        ];
    }

  
    
}