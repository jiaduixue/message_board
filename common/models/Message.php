<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $email
 * @property string|null $content
 * @property int|null $parent_id
 * @property string|null $ip_address
 * @property int $customer_id
 * @property integer $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Message extends ActiveRecord
{
    const STATUS_PENDING_REVIEW = 1;
    const STATUS_NO_READ = 2;
    const STATUS_READ = 3;
    const STATUS_DELETED = 0;

    public static function tableName()
    {
        return '{{%messages}}';
    }

    // 定义验证规则
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['created_at', 'content', 'updated_at'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'], // 增加邮箱格式验证
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    // 定义字段标签，方便在后台表格中显示中文
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'email' => '邮箱',
            'title' => '标题',
            'content' => '内容',
            'parent_id' => '父级ID',
            'ip_address' => 'IP地址',
            'customer_id' => '用户id',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
        /**
     * 在保存前自动处理时间戳（如果数据库没有设置自动更新）
     * 注意：如果你的数据库字段类型是 timestamp 且设置了 DEFAULT CURRENT_TIMESTAMP，
     * Yii 的 TimestampBehavior 可能不需要，或者需要配置 format 为 'php:Y-m-d H:i:s'
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
     * 定义关联关系（可选）
     * 假设 parent_id 关联的是同表的 id，即这是一条回复
     */
    public function getParent()
    {
        return $this->hasOne(Messages::class, ['id' => 'parent_id']);
    }

    public function search($params)
    {
        $query = Message::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        // 添加搜索条件
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        // ...其他字段

        return $dataProvider;
    }
}

