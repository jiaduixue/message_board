<?php

/** @var yii\web\View $this */
use yii\helpers\Url;
$this->title = '留言系统';
?>


<div class="wrapper wrapper-content animated fadeInRight">
        

        <!-- Panel Other -->
        <div class="ibox float-e-margins">
            
            <div class="ibox-content">
                <div class="row row-lg">
                

                    <div class="col-sm-12">
                        <!-- Example Toolbar -->
                        <div class="example-wrap">
                            <h4 class="example-title">工具条</h4>
                            <div class="example">
                                <div class="alert alert-success" id="examplebtTableEventsResult" role="alert">
                                    事件结果
                                </div>
                                <div class="btn-group hidden-xs" id="exampleTableEventsToolbar" role="group">
                                    <button type="button" class="btn btn-outline btn-default">
                                        <i class="glyphicon glyphicon-plus" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline btn-default">
                                        <i class="glyphicon glyphicon-heart" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline btn-default">
                                        <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <table 
                                    id="customerMember" 
                                    data-toggle="customerMember" 
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
                                            <th data-field="name">名称</th>
                                            <th data-field="star">Star</th>
                                            <th data-field="license">许可</th>
                                            <th data-field="description">描述</th>
                                            <th data-field="url">地址</th>
                                            <th data-field="price">价格</th>
                                        </tr>
                                    </thead>
                                    
                                </table>

                            </div>
                        </div>
                        <!-- End Example Toolbar -->
                    </div>

                

                    
                </div>
            </div>
        </div>
        <!-- End Panel Other -->
    </div>
  
