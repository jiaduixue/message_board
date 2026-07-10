<?php
namespace app\components;

use yii\web\ErrorHandler;
use yii\web\NotFoundHttpException;

class CustomErrorHandler extends ErrorHandler
{
    /**
     * 重写渲染异常的方法
     */
    protected function renderException($exception)
    {
        // 1. 根据不同异常类型，走不同的处理逻辑（例如 404 和 500 分开）
        if ($exception instanceof NotFoundHttpException) {
            // 可以自定义 404 的渲染逻辑
            // ...
        }

        // 2. 核心避坑：必须显式调用父类逻辑，否则不会走默认视图流程
        return parent::renderException($exception);
    }

    /**
     * 可选：重写日志记录逻辑
     */
    public function logException($exception)
    {
        // 自定义日志记录策略，例如将特定异常发送到第三方监控平台
        parent::logException($exception);
    }
}