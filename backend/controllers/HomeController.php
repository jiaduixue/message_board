<?php

namespace backend\controllers;

use common\models\LoginForm;
use backend\models\RegisterForm;
use Yii;
use yii\filters\VerbFilter;
use yii\behaviors\TimestampBehavior;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class HomeController extends Controller
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
                        'actions' => ['logout', 'index', 'bug' ,'info' ,'count'],
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
      
        $this->layout = 'content';
        return $this->render('index');
    }
    
      /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionInfo()
    {
      
        $this->layout = 'content';
        return $this->render('info');
    }
      /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCount()
    {
      
        $this->layout = 'content';
        return $this->render('count');
    }
   
   
}
