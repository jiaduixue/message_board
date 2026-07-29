<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\ApiInterface;
use common\models\ApiParameter;

use Yii;
use yii\filters\VerbFilter;
use yii\behaviors\TimestampBehavior;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use backend\models\ApiApp;
/**
 * Site controller
 */
class ApiController extends Controller
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
                        'actions' => ['logout','add','add-parameter', 'delete', 'delete-parameter','get-api-by-id',
                         'index','get-list','get-parameter-list','get-collect-list','get-comment-list'],
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
    public function actionGetParameterList()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $query = ApiParameter::find();
        $allParams = Yii::$app->request->get(); 
            // 假设 DynamicLike 表里有一个 dynamic_id 字段用于筛选
        if (isset($allParams['interface_id']) && $allParams['interface_id'] != '') {
            $query->andWhere(['interface_id' => $allParams['interface_id']]);
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
     * add user
     *
     * @return string
     */
    public function actionAdd()
    {
        
        $model = new ApiApp();

        $post = Yii::$app->request->post();
       
        Yii::$app->response->format = Response::FORMAT_JSON;
       
        // 如果表单被提交且注册成功
        if ($model->load($post,'Api')) {
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
     * add user
     *
     * @return string
     */
    public function actionAddParameter()
    {
        
        $model = new ApiApp();

        $post = Yii::$app->request->post();
       
        Yii::$app->response->format = Response::FORMAT_JSON;
       
        // 如果表单被提交且注册成功
        if ($model->load($post,'Api')) {
            $return = $model->addParameter($post['id']);
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
    public function actionGetApiById()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $customer = ApiApp::getById(Yii::$app->request->post('id'));
      
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
        $model = new ApiApp();
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
     * add user
     *
     * @return string
     */
    public function actionDeleteParameter()
    {
        $model = new ApiApp();
        $post = Yii::$app->request->post();
        $id = $post['id'];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($id !== null){
            // 如果表单被提交且注册成功
            $return = $model->delParameter($id);
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
        $query = ApiInterface::find();
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
