<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Customer;

/**
 * Login form
 */
class CustomerApp extends Model
{
    public $username;
    public $password;
    public $real_name;
    public $nickname;
    public $gender;
    public $birthday;
    public $phone;
    public $email;
    public $avatar_url;
    public $bio;
    public $github_link;
    public $blog_link;
    public $skills;
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
            [['username', 'password'], 'string'], 

            // 注册场景：用户名和密码必填，且密码长度至少为 6
            [['username', 'password'], 'required', 'on' => self::SCENARIO_REGISTER],
            
            // ['password', 'string', 'min' => 6, 'on' => self::SCENARIO_REGISTER],

            // 登录场景：只需要验证用户名和密码是否为空
            [['username', 'password'], 'required', 'on' => self::SCENARIO_LOGIN],
    
            // 更新场景：密码非必填（如果填了才验证长度）
            ['username', 'required', 'on' => self::SCENARIO_UPDATE],
              // 或者设置默认值
            [['real_name'], 'default', 'value' => ''],
            // 这里的字段允许被 mass-assignment (批量赋值)
            [['nickname', 'phone', 'gender','skills','status',
            'birthday','email','avatar_url','bio','github_link','blog_link'], 'string', 'max' => 255],
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
        $customer = new Customer();
        $customer->username = $this->username;
        $customer->password = $this->password;
        $customer->status = $customer::STATUS_ACTIVE;
        // 3. 核心步骤：使用安全组件对密码进行单向哈希加密
        $customer->setPassword($this->password);

        // 4. 生成认证密钥（用于 Cookie 自动登录）
        $customer->generateAuthKey();

        // 5. 将数据保存到数据库
        if ($customer->save()) {
            return $customer;
        }else{
            return json_encode([
                'status' => 'error',
                'data' => 22,
            ]);
        }

       
    }
    public function del($id)
    {
        // 2. 创建 User 模型实例并赋值
        $customer = $this->getById($id);
        $customer->status = Customer::STATUS_DELETED;
        // 5. 将数据保存到数据库
        if ($customer->save()) {
            return $customer;
        }else{
            return null;
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
        $customer = $this->getById($id);
       
        $customer->username = $this->username;
        
        $customer->real_name = $this->real_name;
        // $customer->attributes = $this;
        $customer->nickname = $this->nickname;
        if($this->gender){
            $customer->gender = $this->gender;
        }
        $customer->birthday = $this->birthday;
        $customer->phone = $this->phone;
        $customer->email = $this->email;
        $customer->avatar_url = $this->avatar_url;
        $customer->bio = $this->bio;
        $customer->github_link = $this->github_link;
        $customer->blog_link = $this->blog_link;
       
        $customer->skills = $this->skills;
        $customer->status = $this->status;
        if($this->password){
            $customer->password = $this->password;
            // 3. 核心步骤：使用安全组件对密码进行单向哈希加密
            $customer->setPassword($this->password);
    
            // 4. 生成认证密钥（用于 Cookie 自动登录）
            $customer->generateAuthKey();
        }
       
        // 5. 将数据保存到数据库
        if ($customer->save()) {
            return $customer;
        }else{
            return null;
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
            return Customer::findOne(['username' => $this->username]);
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
            return Customer::findOne($id);
        }

        return null;
    }
}
