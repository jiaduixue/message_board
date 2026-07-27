<?php

/** @var yii\web\View $this */
use yii\helpers\Url;
$this->title = '留言系统';
?>


<div class="wrapper wrapper-content">
        <div class="row">
           
            <div class="col-sm-12 animated fadeInRight">
                <div class="mail-box-header">

                    <form method="get" action="http://www.zi-han.net/theme/hplus/index.html" class="pull-right mail-search">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" name="search" placeholder="搜索邮件标题，正文等">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    搜索
                                </button>
                            </div>
                        </div>
                    </form>
                    <h2>
                    收件箱 
                </h2>
                   
                </div>
                <div class="mail-box" style="padding:20px">

                    <table class="table table-hover table-mail"
                    id="messageIndex" 
                                    data-toggle="messageIndex" 
                                    data-url="<?= Url::to(['message/get-list', 'id' => $id], true) ?>" 
                                    data-query-params="queryParams" 
                                    data-mobile-responsive="true" 
                                    data-height="600" 
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

        <div class="modal inmodal fade" id="detailCustomer" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class=" modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">消息详情页面</h4>
            
                                        </div>
                                        <div class="modal-body">
                                             <div class="row">
           
                                                <div class="col-sm-12 animated fadeInRight">
                                                    <div class="mail-box-header">
                                                        <div class="pull-right tooltip-demo">
                                                            <a href="mail_compose.html"  data-dismiss="modal" tppabs="http://www.zi-han.net/theme/hplus/mail_compose.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="关闭"><i class="fa fa-reply"></i> 关闭</a>
                                                            
                                                        </div>
                                                        <h2>
                                                        查看消息/邮件
                                                    </h2>
                                                        <div class="mail-tools tooltip-demo m-t-md">
                                                        <h3>
                                                            <span class="font-noraml">状态： </span><div id="detail_status">-</div>
                                                        </h3>
                                                        <h3>
                                                            <span class="font-noraml">邮箱： </span><div id="detail_email">-</div>
                                                        </h3>
                                                            <h3>
                                                            <span class="font-noraml">主题： </span><div id="detail_title">-</div>
                                                        </h3>
                                                            <h5>
                                                            <span class="pull-right font-noraml" id="detail_created_at">-</span>
                                                            <span class="font-noraml">发件人： </span><div id="detail_username">-</div>
                                                        </h5>
                                                      
                                                        </div>
                                                    </div>
                                                    <div class="mail-box">
                                                    <input type="hidden" name="id" id="edit_id">


                                                        <div class="mail-body" id="detail_content">
                                                            -
                                                        </div>
                                                        <div class="mail-attachment">
                                                            <p>
                                                                <span><i class="fa fa-paperclip"></i> 2 个附件 - </span>
                                                                <a href="mail_detail.html#" tppabs="http://www.zi-han.net/theme/hplus/mail_detail.html#">下载全部</a> |
                                                                <a href="mail_detail.html#" tppabs="http://www.zi-han.net/theme/hplus/mail_detail.html#">预览全部图片</a>
                                                            </p>

                                                            <div class="attachment">
                                                                <div class="file-box">
                                                                    <div class="file">
                                                                        <a href="mail_detail.html#" tppabs="http://www.zi-han.net/theme/hplus/mail_detail.html#">
                                                                            <span class="corner"></span>

                                                                            <div class="icon">
                                                                                <i class="fa fa-file"></i>
                                                                            </div>
                                                                            <div class="file-name">
                                                                                Document_2014.doc
                                                                            </div>
                                                                        </a>
                                                                    </div>

                                                                </div>
                                                                <div class="file-box">
                                                                    <div class="file">
                                                                        <a href="mail_detail.html#" tppabs="http://www.zi-han.net/theme/hplus/mail_detail.html#">
                                                                            <span class="corner"></span>

                                                                            <div class="image">
                                                                                <img alt="image" class="img-responsive" src="img/p1.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/p1.jpg">
                                                                            </div>
                                                                            <div class="file-name">
                                                                                Italy street.jpg
                                                                            </div>
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                                <div class="file-box">
                                                                    <div class="file">
                                                                        <a href="mail_detail.html#" tppabs="http://www.zi-han.net/theme/hplus/mail_detail.html#">
                                                                            <span class="corner"></span>

                                                                            <div class="image">
                                                                                <img alt="image" class="img-responsive" src="img/p2.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/p2.jpg">
                                                                            </div>
                                                                            <div class="file-name">
                                                                                My feel.png
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                        <div class="mail-body text-center tooltip-demo">
                                                            <a class="btn btn-sm btn-white" href="javascript:void(0)" onclick="deleteCustomer(2)" tppabs="http://www.zi-han.net/theme/hplus/mail_compose.html"><i class="fa fa-reply"></i> 审核</a>
                                                         
                                                            
                                                            <button title="" onclick="deleteCustomer(1)" data-placement="top" data-toggle="tooltip" data-original-title="删除邮件" class="btn btn-sm btn-white"><i class="fa fa-trash-o"></i> 删除</button>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
    </div>
     
