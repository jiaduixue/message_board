<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\User;


use Yii;
use yii\filters\VerbFilter;
use yii\behaviors\TimestampBehavior;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use backend\models\UserApp;
/**
 * Site controller
 */
class UserController extends Controller
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
                        'actions' => ['logout','add','edit', 'delete','get-customer-by-id',
                         'index', 'member' ,'info','get-list','get-member-list' ,'get-info-list','count'],
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
    public function actionIndex()
    {
      
        $this->layout = 'customerIndex';
        return $this->render('index');
    }
    /**
     * add user
     *
     * @return string
     */
    public function actionAdd()
    {
        
        $model = new UserApp();

        $post = Yii::$app->request->post();
       
        Yii::$app->response->format = Response::FORMAT_JSON;
        // 如果表单被提交且注册成功
        if ($model->load($post,'Customer')) {
            $return = $model->add();
            if($return->status != 'error'){
                return [
                
                    'status' => 200,
                    'message' => '数据添加成功',
                    'error' => $model
                ];
            }else{
                return [
                    'status' => 500,
                    'message' => '数据添加失败',
                    'error' => $return
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
    public function actionGetCustomerById()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $customer = UserApp::getById(Yii::$app->request->post('id'));
      
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
      
        $model = new UserApp();
        $post = Yii::$app->request->post();
     
        $id = $post['id'];
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if($id !== null){
            // 如果表单被提交且注册成功
            $customer = $model->del($id);
                
                if($customer){
                    return [
                    
                        'status' => 200,
                        'message' => '数据修改成功',
                        'error' => $customer
                    ];
                }else{
                    return [
                        'status' => 500,
                        'message' => '数据修改失败',
                        'error' => $customer
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
    public function actionEdit()
    {
        $model = new UserApp();
        $post = Yii::$app->request->post();
        $id = $post['id'];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($id !== null){
            // 如果表单被提交且注册成功
            if ( $model->load($post,'Customer')) {
                $customer = $model->edit($id);
                
                if($customer){
                    return [
                    
                        'status' => 200,
                        'message' => '数据修改成功',
                        'error' => $customer
                    ];
                }else{
                    return [
                        'status' => 500,
                        'message' => '数据修改失败',
                        'error' => $customer
                    ];
                }
            
            
            }else{
                
            
                return [
                    'status' => 500,
                    'message' => '数据修改失败',
                   
                    's'=>$post
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
        $query = User::find();
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
