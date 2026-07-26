<?php

/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = '留言系统';
?>


<div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-sm-8">
                <div class="ibox">
                    <div class="ibox-content">
                        <span class="text-muted small pull-right">最后更新：<i class="fa fa-clock-o"></i> 2015-09-01 12:00</span>
                        <h2>客户/管理员查看</h2>
          
                        <div class="input-group">
                            <input type="text" placeholder="查找客户" class="input form-control">
                            <span class="input-group-btn">
                                        <button type="button" class="btn btn btn-primary"> <i class="fa fa-search"></i> 搜索</button>
                                </span>
                        </div>
                        <div class="clients-list">
                            <ul class="nav nav-tabs">
                                <span class="pull-right small text-muted">1406 个客户</span>
                                <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i> 用户</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-briefcase"></i> 管理员</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                <div class="table-responsive">
                                            <table class="table table-striped table-hover"
                                                
                                                id="customerIndex" 
                                                    data-toggle="customerIndex" 
                                                    data-url="<?= Url::to(['customer/get-list', 'id' => $id], true) ?>" 
                                                    data-query-params="queryParams" 
                                                    data-mobile-responsive="true" 
                                                    data-height="600" 
                                                    data-pagination="true" 
                                                    data-page-size="10"
                                                    data-page-list="[10, 20]"
                                                    data-sort-name="id"
                                                    data-sort-order="asc"
                                            >
                                            <thead>
                                                <tr>

                                                    <th></th>
                                                    
                                                        <th data-field="avatar_url">ID</th>
                                                        <th data-field="username">名称</th>
                                                        <th data-field="phone">电话</th>
                                                        <th data-field="email">邮箱</th>
                                                        <th data-field="status">状态</th>
                                                </tr>
                                            </thead>
                                                <tbody>
                                                   
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                                <div id="tab-2" class="tab-pane">
                                <div class="table-responsive">
                                            <table class="table table-striped table-hover"
                                                id="userIndex" 
                                                    data-toggle="userIndex" 
                                                    data-url="<?= Url::to(['user/get-list', 'id' => $id], true) ?>" 
                                                    data-query-params="queryParams" 
                                                    data-mobile-responsive="true" 
                                                    data-height="200" 
                                                    data-pagination="true" 
                                                    data-page-size="10"
                                                    data-page-list="[10, 20]"
                                                    data-sort-name="id"
                                                    data-sort-order="asc"
                                            >
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox ">

                    <div class="ibox-content">
                        <div class="tab-content">
                            <div id="contact-1" class="tab-pane active">
                                <div class="row m-b-lg">
                                    <div class="col-lg-4 text-center">
                                        <h2 id="detail_username">-</h2>

                                        <div class="m-b-sm" id="detail_avatar_url">
                                        <img alt="image" class="img-circle" src="img/a2.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/a2.jpg" style="width: 62px">
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <h3>
                                                个人简介
                                            </h3>

                                        <p id="detail_real_name">
                                            -
                                        </p>
                                        <p id="detail_bio">

                                            -
                                        </p>
                                        <br>
                                        <button type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-envelope"></i> 发送消息
                                        </button>
                                    </div>
                                </div>
                                <div class="client-detail">
                                    <div class="full-height-scroll">

                                        <strong>当前动态</strong>

                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right" id="detail_nickname"> -</span> 昵称
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right" id="detail_phone"> - </span> 电话
                                            </li>
                                            <li class="list-group-item" >
                                                <span class="pull-right" id="detail_email"> - </span> 邮件
                                            </li>
                                            <li class="list-group-item" >
                                                <span class="pull-right" id="detail_github_link"> - </span> github 链接
                                            </li>
                                            <li class="list-group-item" >
                                                <span class="pull-right" id="detail_blog_link"> - </span> 微博 链接
                                            </li>
                                            <li class="list-group-item" >
                                                <span class="pull-right" id="detail_gender"> - </span> 性别
                                            </li>
                                        </ul>
                                        <strong>技能</strong>
                                        <p id="detail_skills">
                                            -
                                        </p>
                                  

                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
