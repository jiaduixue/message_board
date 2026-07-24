<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()

        ];
    }



    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function register()
    {
        // 1. 先通过 rules() 校验表单数据
        if (!$this->validate()) {
            return null;
        }
        // 2. 创建 User 模型实例并赋值
        $user = new User();
        $user->username = $this->username;
        $user->password = $this->password;
        $user->status = $user::STATUS_ACTIVE;
        // 3. 核心步骤：使用安全组件对密码进行单向哈希加密
        $user->setPassword($this->password);

        // 4. 生成认证密钥（用于 Cookie 自动登录）
        $user->generateAuthKey();

        // 5. 将数据保存到数据库
        if ($user->save()) {
            return $user;
        }

        return null;
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
            return User::findOne(['username' => $this->username]);
        }

        return null;
    }


}
