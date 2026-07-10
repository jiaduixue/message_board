<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = '注册页面';
?>

<div class=" signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1>[ <?= Html::encode($this->title) ?> ]</h1>
                </div>
                <div class="m-b"></div>
                <h4>欢迎使用 <strong>后台漂流瓶管理系统</strong></h4>
                <ul class="m-b">
                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势一</li>
                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势二</li>
                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势三</li>
                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势四</li>
                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势五</li>
                </ul>
                <strong><?= \yii\helpers\Html::a('返回登录', ['site/login']) ?></strong>
            </div>
        </div>
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <h4 class="no-margins">注册窗口：</h4>
            <p class="m-t-md">注册漂流瓶系统</p>
            <?= $form->field($model, 'username')->textInput( ['autofocus' => true , 'class' => 'form-control uname','placeholder' => '用户名' ])->label(false) ?>

            <?= $form->field($model, 'password')->passwordInput([
                    'class' => 'form-control pword m-b',
                    'placeholder' => '密码'
            ])->label(false) ?>

            <?= $form->field($model, 'rememberMe')->checkbox()->label('记住密码') ?>
            <a href="">忘记密码了？</a>
            <?= Html::submitButton('注册', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; 2015 All Rights Reserved. H+
        </div>
    </div>
</div>

