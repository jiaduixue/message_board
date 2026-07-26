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
                        <h5>用户信息列表</h5>
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
                            
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCustomer">
                                <i class="fa fa-paste"></i>添加用户
                            </button>
                            <table 
                                class="table table-striped"
                                id="customerIndex" 
                                    data-toggle="customerIndex" 
                                    data-url="<?= Url::to(['customer/get-list', 'id' => $id], true) ?>" 
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
                        <div class="modal inmodal fade" id="addCustomer" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">添加用户</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="addCustomerForm" class="form-horizontal">
                                                <div class="form-group">
                                                    <label>用户名：</label>
                                                    <input type="email"  name="username" id="form_username" placeholder="请输入用户名" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>密码：</label>
                                                    <input type="password"  name="password" id="form_password" placeholder="请输入密码" class="form-control">
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
                                            <h4 class="modal-title">用户详情页面</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form method="get" id="detailCustomerForm" class="form-horizontal">
                                                    <!-- 隐藏域，用来存 ID -->
                                                    <input type="hidden" name="id" id="detail_id">
            

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">用户名</label>

                                                        <div class="col-sm-10">
                                                        <input type="text" disabled name="username" id="detail_username"  class="form-control"> 
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">密码</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="password" id="detail_password"  class="form-control"> 
                                                           
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">真实名称</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="real_name" id="detail_real_name"  class="form-control"> 
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">昵称/花名</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled class="form-control" name="nickname" id="detail_nickname"   >
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div> 
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">出生日期</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="birthday" disabled placeholder="已被禁用" id="detail_birthday" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">联系电话</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="phone" id="detail_phone" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">电子邮箱</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="email" id="detail_email" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    
                                                   
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">GitHub 主页链接</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="github_link" id="detail_github_link" class="form-control">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">个人博客链接</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="blog_link" id="detail_blog_link" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">个人简介/一句话签名</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="bio" id="detail_bio" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">图像照片链接</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="avatar_url" id="detail_avatar_url" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">性别
                                                            <br><small class="text-navy">自定义样式</small>
                                                        </label>

                                                        <div class="col-sm-10"  id="detail_gender">
                                                            
                                                            <div class="radio i-checks">
                                                                <label>
                                                                    <div class="iradio_square-green" style="position: relative;">
                                                                    <input type="radio" value="1" name="gender" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> <i></i> 男</label>
                                                            </div>
                                                            <div class="radio i-checks">
                                                                <label class="">
                                                                    <div class="iradio_square-green checked" style="position: relative;">
                                                                    <input type="radio" checked="" value="2" name="gender" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> <i></i> 女</label>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                               
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">技能标签</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" disabled name="skills" id="detail_skills" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">用户状态</label>

                                                        <div class="col-sm-10">
                                                            <select class="form-control m-b"  name="status" id="detail_status">
                                                                <option value="0" disabled>已作废</option>
                                                                <option value="10" disabled>活跃</option>
                                                                <option value="9" disabled>不活跃</option>
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
                                            <h4 class="modal-title">编辑用户</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form method="get" id="editCustomerForm" class="form-horizontal">
                                                    <!-- 隐藏域，用来存 ID -->
                                                    <input type="hidden" name="id" id="edit_id">
            

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">用户名</label>

                                                        <div class="col-sm-10">
                                                            <input type="text"  name="username" id="edit_username"  class="form-control">
                                                            <span class="help-block m-b-none">用户名必须</span>
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">密码</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"  name="password" id="edit_password"  class="form-control"> 
                                                           
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">真实名称</label>
                                                        <div class="col-sm-10">
                                                            <input type="text"  name="real_name" id="edit_real_name"  class="form-control"> 
                                                            <span class="help-block m-b-none">这是用户的真名</span>
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">昵称/花名</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="nickname" id="edit_nickname"   >
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div> 
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">出生日期</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="birthday"  placeholder="已被禁用" id="edit_birthday" class="form-control">
                                                            <span class="help-block m-b-none">格式是（2016-1-2）</span>
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">联系电话</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="phone" id="edit_phone" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">电子邮箱</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="email" id="edit_email" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    
                                                   
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">GitHub 主页链接</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="github_link" id="edit_github_link" class="form-control">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">个人博客链接</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="blog_link" id="edit_blog_link" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">个人简介/一句话签名</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="bio" id="edit_bio" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">图像照片链接</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="avatar_url" id="edit_avatar_url" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">性别
                                                            <br><small class="text-navy">自定义样式</small>
                                                        </label>

                                                        <div class="col-sm-10"  id="edit_gender">
                                                            <div class="radio i-checks">
                                                                <label>
                                                                    <input style="width:22px; margin-top:2px" type="radio"  name="gender"  value="1" name="a"> 
                                                                    <i class="fa fa-venus"></i>  男</label>
                                                                </label>
                                                            </div>
                                                            <div class="radio i-checks">
                                                                <label>
                                                                    <input style="width:22px;    margin-top:2px" type="radio"  name="gender"  value="2" name="a">
                                                                    
                                                                    <i class="fa fa-mercury"></i> 女</label>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                               
                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">技能标签</label>

                                                        <div class="col-sm-10">
                                                            <input type="text" name="skills" id="edit_skills" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="hr-line-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">用户状态</label>

                                                        <div class="col-sm-10">
                                                            <select class="form-control m-b" name="status" id="edit_status">
                                                                <option value="0">已作废</option>
                                                                <option value="10">活跃</option>
                                                                <option value="9">不活跃</option>
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
    
