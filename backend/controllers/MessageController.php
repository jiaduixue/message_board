<?php
namespace backend\controllers;

use Yii;
use backend\models\Message;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                        'actions' => ['index','detail','add'],
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
        $searchModel = new \backend\models\Message(); // 搜索模型（Gii可自动生成）
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->layout = 'message';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
