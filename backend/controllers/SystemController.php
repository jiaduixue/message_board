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
class SystemController extends Controller
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
                        'actions' => ['receipt','print','blog','blogDetail','logout', 'file','customer','employee', 'employeeDetail' ,'project' ,'projectDetail','team'],
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
    public function actionReceipt()
    {
      
        $this->layout = 'system';
        return $this->render('receipt');
    }
       /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionPrint()
    {
      
        $this->layout = 'system';
        return $this->render('print');
    }
    
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionFile()
    {
      
        $this->layout = 'system';
        return $this->render('file');
    }
    
      /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionProject()
    {
      
        $this->layout = 'system';
        return $this->render('project');
    }
      /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionProjectDetail()
    {
      
        $this->layout = 'system';
        return $this->render('projectDetail');
    }
     
   /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionCustomer()
    {
      
        $this->layout = 'systemCustomer';
        return $this->render('customer');
    }
}
