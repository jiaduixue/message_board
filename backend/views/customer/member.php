<?php

/** @var yii\web\View $this */
use yii\helpers\Url;
$this->title = '留言系统';
?>


<div class="wrapper wrapper-content animated fadeInRight">
        
        
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>用户会员列表</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="table_basic.html#" tppabs="http://www.zi-han.net/theme/hplus/table_basic.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="table_basic.html#" tppabs="http://www.zi-han.net/theme/hplus/table_basic.html#">选项1</a>
                                </li>
                                <li><a href="table_basic.html#" tppabs="http://www.zi-han.net/theme/hplus/table_basic.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success" id="examplebtTableEventsResult" role="alert">
                                    搜索成功
                                </div>
                        <div class="table-responsive">
                            
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCustomerMember">
                                <i class="fa fa-paste"></i>添加用户会员
                            </button>
                            <table 
                                class="table table-striped"
                                id="memberIndex" 
                                    data-toggle="memberIndex" 
                                    data-url="<?= Url::to(['member/get-list', 'id' => $id], true) ?>" 
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

                                        <th></th>
                                        
                                            <th data-field="id">ID</th>
                                            <th data-field="username">名称</th>
                                            <th data-field="password">价格</th>
                                            <th data-field="gender">ID</th>
                                            <th data-field="created_at">名称</th>
                                            <th data-field="action">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="modal inmodal fade" id="addCustomerMember" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">添加会员</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="addCustomerForm" class="form-horizontal">
                                             
                                                <div class="form-group">
                                                        <label >会员等级编码：</label>

                                                        <select class="form-control m-b" name="level_code" id="form_level_code">
                                                                <option value="1">LEVEL_NORMAL</option>
                                                                <option value="2">LEVEL_SILVER</option>
                                                                <option value="3">LEVEL_GOLD</option>
                                                                <option value="4">LEVEL_PLATINUM</option>
                                                            </select>
                                                    </div>
                                                <!-- <div class="form-group">
                                                    <label>会员等级名称：</label>
                                                    <input type="text"  name="level_name" id="form_level_name"  class="form-control">
                                                </div> -->
                                                <div class="form-group">
                                                    <label>用户id：</label>
                                                    <input type="text"  name="customer_id" id="form_customer_id"  class="form-control">
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
                    </div>

                    <div class="modal inmodal fade" id="detailCustomer" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">会员详情页面</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form method="get" id="detailCustomerForm" class="form-horizontal">
                                                    <!-- 隐藏域，用来存 ID -->
                                                    <input type="hidden" name="id" id="detail_id">
            

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">用户ID</label>

                                                        <div class="col-sm-10">
                                                        <input type="text" disabled name="customer_id" id="detail_customer_id"  class="form-control"> 
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">等级编码</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="level_name" id="detail_level_name"  class="form-control"> 
                                                           
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">等级名称</label>

                                                        <div class="col-sm-10">
                                                            <select class="form-control m-b"  name="level_code" id="detail_level_code">
                                                                <option value="1" disabled>LEVEL_NORMAL</option>
                                                                <option value="2" disabled>LEVEL_SILVER</option>
                                                                <option value="3" disabled>LEVEL_GOLD</option>
                                                                <option value="4" disabled>LEVEL_PLATINUM</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">当前积分</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled class="form-control" name="points" id="detail_points"   >
                                                        </div>
                                                    </div>
                                 
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">入会时间</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="join_date" id="detail_join_date" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">到期时间</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="expire_date" id="detail_expire_date" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    
     
              
       
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">用户状态</label>

                                                        <div class="col-sm-10">
                                                            <select class="form-control m-b"  name="status" id="detail_status">
                                                                <option value="0" disabled>已作废</option>
                                                                <option value="1" disabled>活跃</option>
                                                                <option value="2" disabled>不活跃</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    
                                                  
                                                </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="modal inmodal fade" id="editCustomer" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">编辑会员</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form method="get" id="editCustomerForm" class="form-horizontal">
                                                    <!-- 隐藏域，用来存 ID -->
                                                    <input type="hidden" name="id" id="edit_id">
            

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">用户ID</label>

                                                        <div class="col-sm-10">
                                                            <input type="text"  name="customer_id" id="edit_customer_id"  class="form-control">
                                                            <span class="help-block m-b-none">用户名Id必须</span>
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">等级编码</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control m-b" name="level_code" id="edit_level_code">
                                                                <option value="1">LEVEL_NORMAL</option>
                                                                <option value="2">LEVEL_SILVER</option>
                                                                <option value="3">LEVEL_GOLD</option>
                                                                <option value="3">LEVEL_PLATINUM</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">当前积分</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="points" id="edit_points"   >
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div> 
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">入会时间</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="join_date"  placeholder="已被禁用" id="edit_join_date" class="form-control">
                                                            <span class="help-block m-b-none">格式是（2016-1-2 12:12:12）</span>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">到期时间</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="expire_date" id="edit_expire_date" class="form-control">
                                                            <span class="help-block m-b-none">格式是（2016-1-2 12:12:12）</span>
                                                        </div>
                                                        
                                                    </div>
                                                    

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">状态</label>

                                                        <div class="col-sm-10">
                                                            <select class="form-control m-b" name="status" id="edit_status">
                                                                <option value="0">已作废</option>
                                                                <option value="1">活跃</option>
                                                                <option value="2">不活跃</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    
                                                  
                                                </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                                            <button type="button" class="btn btn-primary" onclick="submitEditForm()">保存</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        
    </div>
    
