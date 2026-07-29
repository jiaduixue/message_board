<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\ApiInterface;
use common\models\ApiParameter;
/**
 * Login form
 */
class ApiApp extends Model
{
    public $module_name;
    public $path;
    public $method;
    public $name;
    public $description;
    public $request_content_type;
    public $response_example;
    public $status;


    public $interface_id;
    public $param_type;
    public $location;
    public $data_type;
    public $is_required;
    public $default_value;
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
            [['module_name','path','method','name','description','response_example','request_content_type','status'], 'string'], 

            [['interface_id','param_type','location','name',
            'data_type','is_required','default_value','status'], 'string'], 
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
        $member = new ApiInterface();
        $member->module_name = $this->module_name;
        $member->path = $this->path;
        $member->method = $this->method;
        $member->name = $this->name;
        $member->description = $this->description;
        $member->request_content_type = $this->request_content_type;
        $member->response_example = $this->response_example;
       
       
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
    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function addParameter($param)
    {
        
        // 1. 先通过 rules() 校验表单数据
        if (!$this->validate()) {
            return json_encode([
                'status' => 'error',
                'data' => 11,
            ]);
 
        }
        // 2. 创建 User 模型实例并赋值
        $member = new ApiParameter();
        $member->interface_id = $param;
        $member->param_type = $this->param_type;
        $member->location = $this->location;
        $member->data_type = $this->data_type;
        $member->name = $this->name;
        $member->is_required = $this->is_required;
        $member->default_value = $this->default_value;
        $member->description = $this->description;
       
       
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
        $member->status = ApiInterface::STATUS_DELETED;
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
    public function delParameter($id)
    {
        // 2. 创建 User 模型实例并赋值
        $member = $this->getByParameterId($id);       
   
            
        if (!$member) {
            return json_encode(['success' => false, 'status' => 'error','message' => '记录不存在']);
        }
        
        if ($member->delete()) {
            return json_encode(['success' => true]);
        } else {
            return json_encode(['success' => false, 'status' => 'error','message' => '删除失败']);
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
            return ApiInterface::findOne(['username' => $this->username]);
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
            return ApiInterface::findOne($id);
        }

        return null;
    }
    public function getByParameterId($id)
    {
        // 1. 尝试调用 register() 方法保存数据
        // 注意：这里需要根据你的业务逻辑调整。
        // 如果你的 Controller 里已经调用了 $model->register()，那么这里只需要查询即可。
        // 但为了配合你截图中的控制器写法，我们在这里做完整的“保存+获取”动作。
        if ($id) {
            return ApiParameter::findOne($id);
        }

        return null;
    }
}
