<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Dynamic;

/**
 * Login form
 */
class DynamicApp extends Model
{
    public $customer_id;
    public $type;
    public $content;
    public $media_url;
    public $location;
    public $view_count;
    public $like_count;
    public $comment_count;
    public $collect_count;
    public $status;
    const SCENARIO_REGISTER = 'add';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_UPDATE = 'edit';
    const SCENARIO_DELETE = 'del';
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            // 公共规则：所有场景下，用户名和密码都必须是字符串
            [['content','media_url','location','status'], 'string'], 
            [['customer_id','type'], 'integer'],
        ];
    }


    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function add()
    {
        
        // 1. 先通过 rules() 校验表单数据
        if (!$this->validate()) {
            return json_encode([
                'status' => 'error',
                'data' => 11,
            ]);
 
        }
        // 2. 创建 User 模型实例并赋值
        $member = new Dynamic();
        $member->type = $this->type;
        $member->content = $this->content;
        $member->media_url = $this->media_url;
        $member->customer_id = 1;
        $member->status = $member::STATUS_ACTIVE;
       
        // 5. 将数据保存到数据库
        if ($member->save()) {
            return json_encode([
                'status' => 'success',
                'data' => $member,
            ]);
        }else{
            return json_encode([
                'status' => 'error',
                'data' => $this,
            ]);
        }

       
    }
    public function del($id)
    {
        // 2. 创建 User 模型实例并赋值
        $member = $this->getById($id);
        $member->status = Dynamic::STATUS_DELETED;
        // 5. 将数据保存到数据库
        if ($member->save()) {
            return json_encode([
                'status' => 'success',
                'data' => $member,
            ]);
        }else{
            return json_encode([
                'status' => 'error',
                'data' => 'id重复',
            ]);
        }
    }
    public function apply($id)
    {
        // 2. 创建 User 模型实例并赋值
        $member = $this->getById($id);
        $member->status = Dynamic::STATUS_ACTIVE;
        // 5. 将数据保存到数据库
        if ($member->save()) {
            return json_encode([
                'status' => 'success',
                'data' => $member,
            ]);
        }else{
            return json_encode([
                'status' => 'error',
                'data' => 'id重复',
            ]);
        }
    }
    public function edit($id)
    {
        
        // 1. 先通过 rules() 校验表单数据
        if (!$this->validate()) {
            return json_encode([
                'status' => 'error',
                'data' => 11,
            ]);
 
        }
        // 2. 创建 User 模型实例并赋值
        $member = $this->getById($id);
       
        $member->customer_id = $this->customer_id;
        
        $member->level_code = $this->level_code;
        // $customer->attributes = $this;
        $member->points = $this->points;
        $member->join_date = $this->join_date;
        $member->expire_date = $this->expire_date;
        
        $member->status = $this->status;
       
        // 5. 将数据保存到数据库
        if ($member->save()) {
            return json_encode([
                'status' => 'success',
                'data' => $member,
            ]);
        }else{
            return json_encode([
                'status' => 'error',
                'data' => 'id重复',
            ]);
        }

       
    }
    /**
     * 【关键修复】这就是报错提示缺失的方法
     * 它的作用是：先执行注册(save)，如果成功，返回这个 User 对象供 Controller 登录使用
     */
    public function getUser()
    {
        // 1. 尝试调用 register() 方法保存数据
        // 注意：这里需要根据你的业务逻辑调整。
        // 如果你的 Controller 里已经调用了 $model->register()，那么这里只需要查询即可。
        // 但为了配合你截图中的控制器写法，我们在这里做完整的“保存+获取”动作。
        if ($this->username) {
            return Dynamic::findOne(['username' => $this->username]);
        }

        return null;
    }

    /**
     * 【关键修复】这就是报错提示缺失的方法
     * 它的作用是：先执行注册(save)，如果成功，返回这个 User 对象供 Controller 登录使用
     */
    public function getById($id)
    {
        // 1. 尝试调用 register() 方法保存数据
        // 注意：这里需要根据你的业务逻辑调整。
        // 如果你的 Controller 里已经调用了 $model->register()，那么这里只需要查询即可。
        // 但为了配合你截图中的控制器写法，我们在这里做完整的“保存+获取”动作。
        if ($id) {
            return Dynamic::findOne($id);
        }

        return null;
    }
}
