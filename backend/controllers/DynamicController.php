<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Dynamic;
use common\models\DynamicLike;
use common\models\DynamicCollect;
use common\models\DynamicComment;

use Yii;
use yii\filters\VerbFilter;
use yii\behaviors\TimestampBehavior;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use backend\models\DynamicApp;
/**
 * Site controller
 */
class DynamicController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','add', 'delete','get-dynamic-by-id',
                         'index','get-list','get-like-list','get-collect-list','get-comment-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
 /**
     * Displays homepage.
     *
     * @return string 
     */
    public function actionGetLikeList()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $query = DynamicLike::find();
        $allParams = Yii::$app->request->get(); 
            // 假设 DynamicLike 表里有一个 dynamic_id 字段用于筛选
        if (isset($allParams['dynamic_id']) && $allParams['dynamic_id'] != '') {
            $query->andWhere(['dynamic_id' => $allParams['dynamic_id']]);
        }

        // 2. 创建数据提供者
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $allParams['limit'], // 每页显示10条，也可从请求参数动态获取
                'page' => $allParams['offset'],
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC], // 默认按ID降序

            ],
            ]);
    
            // 3. 返回数据（适用于 API 或 AJAX 请求）
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'total' => $dataProvider->getTotalCount(),
                'rows' => $dataProvider->getModels(),
                'message' => '数据获取成功',
            ];
    }
     /**
     * Displays homepage.
     *
     * @return string 
     */
    public function actionGetCollectList()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $query = DynamicCollect::find();
        $allParams = Yii::$app->request->get(); 
        // 假设 DynamicLike 表里有一个 dynamic_id 字段用于筛选
        if (isset($allParams['dynamic_id']) && $allParams['dynamic_id'] != '') {
            $query->andWhere(['dynamic_id' => $allParams['dynamic_id']]);
        }
        // 2. 创建数据提供者
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $allParams['limit'], // 每页显示10条，也可从请求参数动态获取
                'page' => $allParams['offset'],
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC], // 默认按ID降序

            ],
            ]);
    
            // 3. 返回数据（适用于 API 或 AJAX 请求）
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'total' => $dataProvider->getTotalCount(),
                'rows' => $dataProvider->getModels(),
                'message' => '数据获取成功',
            ];
    }
     /**
     * Displays homepage.
     *
     * @return string 
     */
    public function actionGetCommentList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // 1. 构建查询（不要执行 all() 或 count()）
        $dataList = array();
        $allParams = Yii::$app->request->post(); 
           // 假设 DynamicLike 表里有一个 dynamic_id 字段用于筛选
        $dataList['dynamic_id'] = $allParams['dynamic_id'];
        $dataList['limit'] = 10;
        $dataList['offset'] = $allParams['page'];
        $return = DynamicComment::getList($dataList);
        
        
        return $return;
        
    }
    /**
     * add user
     *
     * @return string
     */
    public function actionAdd()
    {
        
        $model = new DynamicApp();

        $post = Yii::$app->request->post();
       
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // 如果表单被提交且注册成功
        if ($model->load($post,'Dynamic')) {
            $return = $model->add();
            $return = json_decode($return);
            if($return->status != 'error'){
                return [
                
                    'status' => 200,
                    'message' => '数据添加成功',
                    'error' => $return->data
                ];
            }else{
                return [
                    'status' => 500,
                    'message' => '数据添加失败',
                    'error' => $return->data
                ];
            }
           
        
        }else{
            $errors = $model->errors;
           
            return [
                'status' => 500,
                'message' => '数据添加失败',
                'error' => $model,
                's'=>$post
            ];
        }


    }
    /**
     * Displays homepage.
     *
     * @return string 
     */
    public function actionGetDynamicById()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $customer = DynamicApp::getById(Yii::$app->request->post('id'));
      
            // 3. 返回数据（适用于 API 或 AJAX 请求）
        Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => 200,
                'data' => $customer,
                'message' => '数据获取成功',
            ];
    }
    /**
     * add user
     *
     * @return string
     */
    public function actionDelete()
    {
        $model = new DynamicApp();
        $post = Yii::$app->request->post();
        $id = $post['id'];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($id !== null){
            // 如果表单被提交且注册成功
            $return = $model->del($id);
            $return = json_decode($return);
                if($return->status != 'error'){
                    return [
                    
                        'status' => 200,
                        'message' => '数据修改成功',
                        'error' => $return
                    ];
                }else{
                    return [
                        'status' => 500,
                        'message' => '数据修改失败',
                        'error' => $return
                    ];
                }
        }else {
            return [
                'status' => 500,
                'message' => '确少用户id',
                's'=>$post
            ];
        }
        


    }

    

      /**
     * Displays homepage.
     *
     * @return string 
     */
    public function actionGetList()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $query = Dynamic::find();
        $allParams = Yii::$app->request->get(); 
        // 2. 创建数据提供者
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $allParams['limit'], // 每页显示10条，也可从请求参数动态获取
                'page' => $allParams['offset'],
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC], // 默认按ID降序

            ],
            ]);
    
            // 3. 返回数据（适用于 API 或 AJAX 请求）
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'total' => $dataProvider->getTotalCount(),
                'rows' => $dataProvider->getModels(),
                'message' => '数据获取成功',
            ];
    }
   
   
   
}
