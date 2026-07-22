<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

use yii\data\ActiveDataProvider;
class Message extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%messages}}';
    }

    // 定义验证规则
    public function rules()
    {
        return [
            [['username', 'content'], 'required'],
            [['email'], 'email'],
            [['parent_id'], 'integer'],
            [['content', 'reply'], 'string'],
            [['username', 'email'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    // 定义字段标签，方便在后台表格中显示中文
    public function attributeLabels()
    {
        return [
            'id' => '留言ID',
            'username' => '留言人昵称',
            'email' => '邮箱',
            'content' => '留言内容',
            'parent_id' => '父留言ID',
            'reply' => '管理员回复',
            'ip_address' => 'IP地址',
            'created_at' => '创建时间',
        ];
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

