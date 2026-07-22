<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\behaviors\TimestampBehavior;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class CustomerController extends Controller
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
                        'actions' => ['logout', 'index', 'member' ,'info' ,'count'],
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionMember()
    {
      
        $this->layout = 'customer';
        return $this->render('member');
    }
      /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionInfo()
    {
      
        $this->layout = 'customerInfo';
        return $this->render('info');
    }
      /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCount()
    {
      
        $this->layout = 'system';
        return $this->render('count');
    }
   
   
}
