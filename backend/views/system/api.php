<?php

/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = '留言系统';
?>


<div class="wrapper wrapper-content animated fadeInUp">
        <div class="row">
            <div class="col-sm-12">
            <input type="hidden" name="id" id="detail_id">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>所有api接口</h5>
                        <div class="ibox-tools">
                            <a href="javascript:void(0)" 
                            data-toggle="modal" data-target="#addApiModal"
                            tppabs="http://www.zi-han.net/theme/hplus/projects.html" class="btn btn-primary btn-xs">添加新api</a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row m-b-sm m-t-sm">
                            <div class="col-md-1">
                                <button type="button" id="loading-example-btn" onclick="flesh()" class="btn btn-white btn-sm"><i class="fa fa-refresh"></i> 刷新</button>
                            </div>
                            <div class="col-md-11">
                                <div class="input-group">
                                    <input type="text" placeholder="请输入ID" class="input-sm form-control"> <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-primary"> 搜索</button> </span>
                                </div>
                            </div>
                        </div>

                        <div class="project-list">

                            <table class="table table-hover"
                                id="customerIndex" 
                                    data-toggle="customerIndex" 
                                    data-url="<?= Url::to(['api/get-list', 'id' => $id], true) ?>" 
                                    data-query-params="queryParams" 
                                    data-mobile-responsive="true" 
                                    data-height="600" 
                                    data-pagination="true" 
                                    data-page-size="10"
                                    data-page-list="[20, 40]"
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



        <div class="modal inmodal fade" id="addApiModal" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">添加api接口</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="addApiForm" class="form-horizontal">

                                                <div class="form-group">
                                                    <label>所属模块/分组 (例如: User, Order)：</label>
                                                    <input type="text"  name="module_name" id="form_module_name" placeholder="所属模块/分组 (例如: User, Order)" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>接口路径 (例如: /api/v1/user/login)：</label>
                                                    <input type="text"  name="path" id="form_path" placeholder="接口路径 (例如: /api/v1/user/login)" class="form-control">
                                                </div>
                                               

                                                <div class="form-group">
                                                    <label>请求方式（'GET','POST','PUT','DELETE','PATCH'）：</label>
                                                    <select class="form-control m-b"  name="method" id="form_method">
                                                        <option value="GET" checked>GET</option>
                                                        <option value="POST" >POST</option>
                                                        <option value="PUT" >PUT</option>
                                                        <option value="DELETE" >DELETE</option>
                                                        <option value="PATCH" >PATCH</option>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label>接口名称/标题 (例如: 用户登录)：</label>
                                                    <input type="text"  name="name" id="form_name" placeholder="接口名称/标题 (例如: 用户登录)" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>接口详细描述文档：</label>
                                                    <input type="text"  name="description" id="form_description" placeholder="接口详细描述文档" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>请求头Content-Type：默认（application/json）</label>
                                                    <input type="text"  name="request_content_type" id="form_request_content_type" placeholder="请求头Content-Type" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>成功响应示例 (JSON格式)：</label>
                                                    <input type="text"  name="response_example" id="form_response_example" placeholder="成功响应示例 (JSON格式)" class="form-control">
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                                            <button type="button" class="btn btn-primary" onClick="submitAddForm()">保存</button>
                                        </div>
                                    </div>
                                </div>
                        </div>



                        <div class="modal inmodal fade" id="editApiModal" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">修改api接口</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="editApiForm" class="form-horizontal">
                                                <input type="hidden" name="id" id="edit_id">

                                                <div class="form-group">
                                                    <label>所属模块/分组 (例如: User, Order)：</label>
                                                    <input type="text"  name="module_name" id="form_module_name_e" placeholder="所属模块/分组 (例如: User, Order)" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>接口路径 (例如: /api/v1/user/login)：</label>
                                                    <input type="text"  name="path" id="form_path_e" placeholder="接口路径 (例如: /api/v1/user/login)" class="form-control">
                                                </div>
                                               

                                                <div class="form-group">
                                                    <label>请求方式（'GET','POST','PUT','DELETE','PATCH'）：</label>
                                                    <select class="form-control m-b"  name="method" id="form_method_e">
                                                        <option value="GET" checked>GET</option>
                                                        <option value="POST" >POST</option>
                                                        <option value="PUT" >PUT</option>
                                                        <option value="DELETE" >DELETE</option>
                                                        <option value="PATCH" >PATCH</option>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label>接口名称/标题 (例如: 用户登录)：</label>
                                                    <input type="text"  name="name" id="form_name_e" placeholder="接口名称/标题 (例如: 用户登录)" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>接口详细描述文档：</label>
                                                    <input type="text"  name="description" id="form_description_e" placeholder="接口详细描述文档" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>请求头Content-Type：默认（application/json）</label>
                                                    <input type="text"  name="request_content_type" id="form_request_content_type_e" placeholder="请求头Content-Type" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>成功响应示例 (JSON格式)：</label>
                                                    <input type="text"  name="response_example" id="form_response_example_e" placeholder="成功响应示例 (JSON格式)" class="form-control">
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                                            <button type="button" class="btn btn-primary" onClick="submitEditForm()">保存</button>
                                        </div>
                                    </div>
                                </div>
                        </div>


                        <div class="modal inmodal fade" id="addApiParameterModal" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">添加api参数属性</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="addApiParameterForm" class="form-horizontal">
                                                <input type="hidden" name="id" id="detail_api_id">
                                      
                                                <div class="form-group">
                                                    <label>参数类型: request-请求参数, response-响应参数：</label>
                                                    <select class="form-control m-b"  name="param_type" id="form_param_type">
                                                        <option value="request" checked>request</option>
                                                        <option value="response" >response</option>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label>参数位置 (仅针对request)：</label>
                                                    <select class="form-control m-b"  name="location" id="form_location">
                                                        <option value="query" checked>query</option>
                                                        <option value="body" >body</option>
                                                        <option value="header" checked>header</option>
                                                        <option value="path" >path</option>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label>参数名称 (例如: username)：</label>
                                                    <input type="text"  name="name" id="form_name" placeholder="参数名称 (例如: username)" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>数据类型 (String, Integer, Boolean, Object, Array)：</label>
                                                    <select class="form-control m-b"  name="data_type" id="form_data_type">
                                                        <option value="String" checked>String</option>
                                                        <option value="Integer" >Integer</option>
                                                        <option value="Boolean" checked>Boolean</option>
                                                        <option value="Object" >Object</option>
                                                        <option value="Array" >Array</option>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label>是否必填: 1-是, 0-否：</label>
                                                    <select class="form-control m-b"  name="is_required" id="form_is_required">
                                                        <option value="0" checked>否</option>
                                                        <option value="1" >是</option>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label>默认值：</label>
                                                    <input type="text"  name="default_value" id="form_default_value" placeholder="默认值" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>参数说明/备注：</label>
                                                    <input type="text"  name="description" id="form_description" placeholder="参数说明/备注" class="form-control">
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                                            <button type="button" class="btn btn-primary" onClick="submitAddParameterForm()">保存</button>
                                        </div>
                                    </div>
                                </div>
                        </div>

                <div class="modal inmodal fade" id="detailDynamic" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                
                                    <div class="modal-content">
                                        <div class="wrapper wrapper-content animated fadeInUp">
                                            <div class="ibox">
                                                <div class="ibox-content">

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="close" onclick="closeModel()"> 关闭 </button>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="m-b-md" id="detail_module_name">
                                                                <h2>-</h2>
                                                            </div>
                                                            <dl class="dl-horizontal">
                                                                <dt>接口路径：</dt>
                                                                <dd><span class="label label-primary" id="detail_path">-</span>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <dl class="dl-horizontal">

                                                                <dt>请求方式：</dt>
                                                                <dd  id="detail_method">-</dd>
                                                            
                                                               
                                                                <dt>请求头Content-Type：</dt>
                                                                <dd id="detail_request_content_type">-</dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-sm-7" id="cluster_info">
                                                            <dl class="dl-horizontal">

                                                                <dt>最后更新：</dt>
                                                                <dd id="detail_updated_at">-</dd>
                                                                <dt>创建于：</dt>
                                                                <dd id="detail_created_at">-</dd>
                                                                
                                                            </dl>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <dl class="dl-horizontal">
                                                                <dt>接口名称/标题：</dt>
                                                                <dd>
                                                                    <div class="label label-warning" id="detail_name">
                                                                        
                                                                    </div>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <dl class="dl-horizontal">
                                                                <dt>成功响应示例 (JSON格式)</dt>
                                                                <dd>
                                                                    <div class="progress progress-striped active m-b-sm" id="detail_response_example">
                                                                        
                                                                    </div>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <dl class="dl-horizontal">
                                                                <dt>接口详细描述文档</dt>
                                                                <dd id="detail_description">-
                                                                    
                                                                </dd>
                                                            </dl>
                                                        </div>

                                                    </div>
                                                    <div class="row m-t-sm">
                                                        <div class="col-sm-12">
                                                            <div class="panel blank-panel">
                                                                <div class="panel-heading">
                                                                    <div class="panel-options">
                                                                        <ul class="nav nav-tabs">
                                                                          
                                                                            <li class=""><a href="project_detail.html#tab-2" tppabs="http://www.zi-han.net/theme/hplus/project_detail.html#tab-2" data-toggle="tab"><i class="fa fa-thumbs-up"></i> 属性列表 </a>
                                                                            </li>
                                                                         
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                                <div class="panel-body">

                                                                    <div class="tab-content">
                                                                       
                                                                        <div class="tab-pane" id="tab-2">

                                                                            <table class="table table-striped"
                                                                                id="dynamicLikeIndex" 
                                                                                data-toggle="dynamicLikeIndex" 
                                                                                data-url="<?= Url::to(['api/get-parameter-list', 'id' => $id], true) ?>" 
                                                                                data-query-params="queryParams" 
                                                                                data-mobile-responsive="true" 
                                                                                data-height="600" 
                                                                                data-pagination="true" 
                                                                                data-page-size="10"
                                                                                data-page-list="[10, 20]"
                                                                                data-search="false"
                                                                                data-sort-name="id"
                                                                                data-sort-order="asc"
                                                                                data-header="false"
                                                                            
                                                                            >
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>状态</th>
                                                                                        <th>标题</th>
                                                                                        <th>开始时间</th>
                                                                                        <th>状态</th>
                                                                                        <th>标题</th>
                                                                                        <th>开始时间</th>
                                                                                        <th>状态</th>
                                                                                        <th>标题</th>
                                                                                    </tr>
                                                                                </thead>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
   
    
