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
                <div class="mail-box">

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
    </div>
     <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
    <script type="text/javascript" src="../../../tajs.qq.com/stats-sId=9051096.js" tppabs="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
