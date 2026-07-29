<?php
// 假设你的项目使用了命名空间，如果没有请去掉 namespace
namespace common\models;

use yii\db\ActiveRecord;
use common\models\ApiParameter;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
class ApiInterface extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const METHOD_TEXT = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';
    public $error; // 添加这一行

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%api_interface}}'; // 对应之前设计的表名
        
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
    //                'value' => function ($event) {
    //                    // 如果前端传入了特定格式（如 d/m/Y），需要先替换符号再转换
    //                    return date('Y-m-d H:i:s', strtotime(str_replace("/", "-", new Expression('NOW()'))));
    //                },
              
            ],
           
        ];
    }
  
    /**
     * 定义验证规则
     */
    public function rules()
    {
        return [
            [['path'], 'required'],
            [['module_name', 'method', 'name', 'description','response_example'], 'safe'],
            [['request_content_type'], 'string', 'max' => 150],
        ];
    }

    /**
     * 定义属性标签（用于表单显示）
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_name' => '所属模块/分组 (例如: User, Order)',
            'path' => '接口路径 (例如: /api/v1/user/login)',
            'method' => '请求方式',
            'name' => '接口名称/标题 (例如: 用户登录)',
            'description' => '接口详细描述文档',
            'request_content_type' => '请求头Content-Type',
            'response_example' => '成功响应示例 (JSON格式)',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 【关键】关联 Customer 表
     * 假设你的 Customer 模型类名为 Customer，表主键为 id
     */
    public function getParameter()
    {
        // hasOne 表示一对一关系
        return $this->hasOne(ApiParameter::className(), ['id' => 'customer_id']);
    }

    /**
     * 获取会员列表（支持搜索和分页）
     * @param array $params 查询参数 (如 search, page, per-page)
     * @return array ['data' => [], 'total' => int]
     */
    public static function getList($params = [])
    {
        $query = self::find()->with('apiParameter'); // 预加载 customer 关联数据，防止 N+1 问题

        

        // 2. 处理排序 (Bootstrap Table 默认传 sort 和 order)
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['sort_order']) ? $params['sort_order'] : 'DESC';
        $query->orderBy([$sort => $order]);

        // 3. 获取总数
        $total = $query->count();

        // 4. 处理分页 (Bootstrap Table 默认传 offset 和 limit)
        $limit = isset($params['limit']) ? (int)$params['limit'] : 10;
        $offset = isset($params['offset']) ? (int)$params['offset'] : 0;
        $query->limit($limit)->offset($offset);

        $data = $query->asArray()->all();

        return [
            'total' => $total,
            'rows'  => $data // Bootstrap Table 默认接收 rows 作为数据列表
        ];
    }
}