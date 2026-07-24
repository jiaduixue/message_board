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
                        <!-- Example Events -->
                        <div class="example-wrap">
                            <h4 class="example-title">事件</h4>
                            <div class="example">
                                <div class="alert alert-success"  role="alert">
                                    事件结果
                                </div>
                                
                                <table 
                                    id="customerInfo" 
                                    data-url="<?= Url::to(['customer/get-info-list', 'id' => $id], true) ?>" 

                                    data-toggle="customerInfo" 
                                   
                                    data-query-params="queryParams" 
                                    data-mobile-responsive="true" 
                                    data-height="600" 
                                    data-pagination="true" 
                                    data-page-size="10"
                                    data-page-list="[10, 20]"
                                    data-search="true"
                                    data-sort-name="id"
                                    data-sort-order="asc"
                                    >
                                    <thead>
                                        <tr>
                                            <th data-field="state" data-checkbox="true"></th>
                                            <th data-field="id">ID</th>
                                            <th data-field="name">名称</th>
                                            <th data-field="price">价格</th>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </div>
                        </div>
                        <!-- End Example Events -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Panel Other -->
    </div>
  
