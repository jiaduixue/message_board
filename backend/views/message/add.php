<?php

/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = '留言系统';
?>

<div class="wrapper wrapper-content">
        <div class="row">
            
            <div class="col-sm-12 animated fadeInRight">
                <div class="mail-box-header">
                    
                    <h2>
                    写信
                </h2>
                </div>
                <div class="mail-box" >


                    <div class="mail-body">

                        <form class="form-horizontal" method="post" id="addCustomerForm">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">发送到：</label>

                                <div class="col-sm-10">
                                    <input type="text" name="parent_id" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">email：</label>

                                <div class="col-sm-10">
                                    <input type="text" name="email" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标题：</label>

                                <div class="col-sm-10">
                                    <input type="text" name="title" class="form-control" value="">
                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="mail-text h-200">

                        <div class="summernote" >
                            <h2>H+ 后台主题</h2>
                          
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="mail-body text-center tooltip-demo">
                        <a href="javascript:void(0)" onclick="submitAddForm()" tppabs="" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Send"><i class="fa fa-reply"></i> 发送</a>
                        <a class="J_menuItem"  data-index="0" id="messageListA" href="<?= Url::to(['message/index', 'id' => $id], true) ?>" tppabs="<?= Url::to(['message/index', 'id' => $id], true) ?>">收件箱</a>
                            
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
   

