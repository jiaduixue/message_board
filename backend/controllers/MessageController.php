<?php
namespace backend\controllers;

use Yii;
use common\models\Message;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\MessageApp;
use yii\web\Response;
use yii\data\ActiveDataProvider;

class MessageController extends Controller
{
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
                        'actions' => ['index','detail','add','add-message','get-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'], // 删除操作只允许 POST 请求，防止误删
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    // 1. 留言列表展示
    public function actionIndex()
    {
        $searchModel = new \common\models\Message(); // 搜索模型（Gii可自动生成）
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->layout = 'message';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

      /**
     * Displays homepage.
     *
     * @return string 
     */
    public function actionGetList()
    {

        // 1. 构建查询（不要执行 all() 或 count()）
        $query = Message::find();
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
            
            // 获取分页对象，用于返回 total 等信息
            $pagination = $dataProvider->getPagination();
            // 3. 返回数据（适用于 API 或 AJAX 请求）
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'total' => $dataProvider->getTotalCount(),
                'rows' => $dataProvider->getModels(),
                'message' => '数据获取成功',  
            ];
    }

    // 1. 留言列表展示
    public function actionDetail()
    {
        $this->layout = 'message';
        return $this->render('detail');
    }
     // 1. 留言列表展示
     public function actionAdd()
     {
         $this->layout = 'messageAdd';
         return $this->render('add');
     }
    // 1. 留言列表展示
    public function actionAddMessage()
    {
        $model = new MessageApp();

        $post = Yii::$app->request->post();
       
        Yii::$app->response->format = Response::FORMAT_JSON;
      
        // 如果表单被提交且注册成功
        if ($model->load($post,'Message')) {
          
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

    // 2. 管理员回复功能
    public function actionReply($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '回复成功！');
            return $this->redirect(['index']);
        }

        return $this->render('reply', [
            'model' => $model,
        ]);
    }

    // 3. 一键删除功能
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', '留言删除成功！');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('您请求的留言不存在。');
    }
}
