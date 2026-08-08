<?php
// 假设你的项目使用了命名空间，如果没有请去掉 namespace
namespace common\models;

use yii\db\ActiveRecord;
use common\models\Customer;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
class Dynamic extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 2;
    const STATUS_WAIT = 1;

    const TYPE_TEXT = 1;
    const TYPE_IMG = 2;
    const TYPE_VIDEO = 3;
    const TYPE_MESSAGE = 4;
    public $error; // 添加这一行

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_dynamics}}'; // 对应之前设计的表名
        
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
            [['customer_id'], 'required'],
            [['created_at', 'content', 'type', 'updated_at'], 'safe'],
            [['media_url'], 'string', 'max' => 150],
            [['location'], 'string', 'max' => 45],
        ];
    }

    /**
     * 定义属性标签（用于表单显示）
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '动态类型',
            'media_url' => '媒体地址',
            'location' => '坐标',
            'content' => '内容',
            'view_count' => '浏览量',
            'like_count' => '点赞量',
            'comment_count' => '评论量',
            'collect_count' => '收藏量',
            'customer_id' => '用户id',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 【关键】关联 Customer 表
     * 假设你的 Customer 模型类名为 Customer，表主键为 id
     */
    public function getCustomer()
    {
        // hasOne 表示一对一关系
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * 获取会员列表（支持搜索和分页）
     * @param array $params 查询参数 (如 search, page, per-page)
     * @return array ['data' => [], 'total' => int]
     */
    public static function getList($params = [])
    {
        $query = self::find()->with('customer'); // 预加载 customer 关联数据，防止 N+1 问题

        // 1. 处理搜索逻辑
        if (!empty($params['search'])) {
            $search = $params['search'];
            // 搜索会员等级 或 关联用户的用户名
            $query->andWhere([
                'or',
                ['like', 'level_name', $search],
                ['like', 'points', $search],
                // 这里需要 join 或者通过关联表搜索，简单起见可以用子查询或直接 join
                // 更推荐的方式是显式 Join:
                // ->joinWith('customer') 
                // ->andWhere(['like', '{{customer}}.username', $search])
            ]);
            
            // 如果想搜关联的用户名，建议开启 joinWith
             $query->joinWith('customer')->andFilterWhere([
                'or',
                ['like', 'customer_member.level_name', $search],
                ['like', 'customer.username', $search], // 假设 customer 表有 username 字段
                ['like', 'customer_member.points', $search]
            ]);
        }

        // 2. 处理排序 (Bootstrap Table 默认传 sort 和 order)
        $sort = isset($params['sort']) ? $params['sort'] : 'id';
        $order = isset($params['order']) ? $params['order'] : 'DESC';
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