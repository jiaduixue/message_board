<?php
// 假设你的项目使用了命名空间，如果没有请去掉 namespace
namespace common\models;

use yii\db\ActiveRecord;
use common\models\Customer;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
class Member extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 2;
    const STATUS_ACTIVE = 1;

    const LEVEL_NORMAL = 1;
    const LEVEL_SILVER = 2;
    const LEVEL_GOLD = 3;
    const LEVEL_PLATINUM = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_member}}'; // 对应之前设计的表名
        
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','join_date', 'updated_at'],
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
      // 2. 将 level_name 的逻辑放在 beforeSave 中更合适
    public function beforeSave($insert)
    {
          if (parent::beforeSave($insert)) {
              // 定义映射关系
              $levelMap = [
                  self::LEVEL_NORMAL   => '普通',
                  self::LEVEL_SILVER   => '银卡',
                  self::LEVEL_GOLD     => '金卡',
                  self::LEVEL_PLATINUM => '白金',
              ];
            // 获取当前 level 并设置 name
            if (isset($this->level_code)) {
                $this->level_name = $levelMap[$this->level_code] ?? '未知等级';
             }

        return true;
        }
    }
    /**
     * 定义验证规则
     */
    public function rules()
    {
        return [
            [['customer_id', 'level_code'], 'required'],
            [['points', 'total_points', 'status'], 'integer'],
            [['join_date', 'expire_date', 'created_at', 'updated_at'], 'safe'],
            [['level_name'], 'string', 'max' => 50],
            [['level_code'], 'string', 'max' => 32],
            // 确保 customer_id 唯一（假设一个用户只有一个会员记录）
            [['customer_id'], 'unique'],
        ];
    }

    /**
     * 定义属性标签（用于表单显示）
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => '用户ID',
            'level_code' => '等级编码',
            'level_name' => '等级名称',
            'points' => '当前积分',
            'status' => '状态',
            'join_date' => '入会时间',
            'expire_date' => '到期时间',
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