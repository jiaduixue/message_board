<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;     // ← 你之前漏的就是这一行
use yii\web\Response; 
/**
 * Site controller
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
   

    

   
    public function actionError()
    {
        // 你的错误处理逻辑
        return $this->render('error');
    }
    
}
