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
                        <h5>所有项目</h5>
                        <div class="ibox-tools">
                            <a href="javascript:void(0)" 
                            data-toggle="modal" data-target="#addDynamic"
                            tppabs="http://www.zi-han.net/theme/hplus/projects.html" class="btn btn-primary btn-xs">发新动态</a>
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
                                    data-url="<?= Url::to(['dynamic/get-list', 'id' => $id], true) ?>" 
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
            </div>


    
        </div>



        <div class="modal inmodal fade" id="addDynamic" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">发布动态</h4>
                                           
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="addCustomerForm" class="form-horizontal">

                                                <div class="form-group">
                                                    <label>动态类型：</label>
                                                    <select class="form-control m-b"  name="type" id="form_type">
                                                        <option value="1" checked>文字</option>
                                                        <option value="2" >图片</option>
                                                        <option value="3" >视频</option>
                                                        <option value="4" >消息</option>
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label>文字/消息内容：</label>
                                                    <input type="text"  name="content" id="form_content" placeholder="文字/消息内容" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>视频/图片链接：</label>
                                                    <input type="text"  name="media_url" id="form_password" placeholder="视频/图片链接" class="form-control">
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


                <div class="modal inmodal fade" id="detailDynamic" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                
                                    <div class="modal-content">
                                        <div class="wrapper wrapper-content animated fadeInUp">
                                            <div class="ibox">
                                                <div class="ibox-content">

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button type="button" class="close" data-dismiss="modal"> 关闭 <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="m-b-md" id="detail_customer_id">
                                                                <h2>阿里巴巴集团</h2>
                                                            </div>
                                                            <dl class="dl-horizontal">
                                                                <dt>状态：</dt>
                                                                <dd><span class="label label-primary" id="detail_status">-</span>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <dl class="dl-horizontal">

                                                                <dt>type：</dt>
                                                                <dd id="detail_type">-</dd>
                                                            
                                                                <dt>位置坐标：</dt>
                                                                <dd id="detail_location">
                                                                </dd>
                                                                <dt>浏览数量：</dt>
                                                                <dd id="detail_view_count">-</dd>
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
                                                                <dt>当前进度</dt>
                                                                <dd>
                                                                    <div class="progress progress-striped active m-b-sm" id="detail_like_d">
                                                                        
                                                                    </div>
                                                                    <small>当前动态达到一百个赞完成度： <strong id="detail_like_d2">0%</strong></small>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <dl class="dl-horizontal">
                                                                <dt>内容</dt>
                                                                <dd id="detail_content">-
                                                                    
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
                                                                            <li><a href="project_detail.html#tab-1" tppabs="http://www.zi-han.net/theme/hplus/project_detail.html#tab-1" data-toggle="tab" aria-expanded="true"><i class="fa fa-commenting"></i> 评论 </a>
                                                                            </li>
                                                                            <li class=""><a href="project_detail.html#tab-2" tppabs="http://www.zi-han.net/theme/hplus/project_detail.html#tab-2" data-toggle="tab"><i class="fa fa-thumbs-up"></i> 赞 </a>
                                                                            </li>
                                                                            <li class=""><a href="project_detail.html#tab-3" tppabs="http://www.zi-han.net/theme/hplus/project_detail.html#tab-2" data-toggle="tab"><i class="fa fa-heart"></i> 收藏</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                                <div class="panel-body">

                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="tab-1">
                                                                            <div class="feed-activity-list">
                                                                                <div class="feed-element">
                                                                                    <a href="profile.html#" tppabs="http://www.zi-han.net/theme/hplus/profile.html#" class="pull-left">
                                                                                        <img alt="image" class="img-circle" src="img/a1.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/a1.jpg">
                                                                                    </a>
                                                                                    <div class="media-body ">
                                                                                        <small class="pull-right text-navy">1天前</small>
                                                                                        <strong>奔波儿灞</strong> 关注了 <strong>灞波儿奔</strong>.
                                                                                        <br>
                                                                                        <small class="text-muted">54分钟前 来自 皮皮时光机</small>
                                                                                        <div class="actions">
                                                                                            <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> 赞 </a>
                                                                                            <a class="btn btn-xs btn-danger"><i class="fa fa-heart"></i> 收藏</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="feed-element">
                                                                                    <a href="profile.html#" tppabs="http://www.zi-han.net/theme/hplus/profile.html#" class="pull-left">
                                                                                        <img alt="image" class="img-circle" src="img/profile.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/profile.jpg">
                                                                                    </a>
                                                                                    <div class="media-body ">
                                                                                        <small class="pull-right">5分钟前</small>
                                                                                        <strong>作家崔成浩</strong> 发布了一篇文章
                                                                                        <br>
                                                                                        <small class="text-muted">今天 10:20 来自 iPhone 6 Plus</small>

                                                                                    </div>
                                                                                </div>

                                                                                <div class="feed-element">
                                                                                    <a href="profile.html#" tppabs="http://www.zi-han.net/theme/hplus/profile.html#" class="pull-left">
                                                                                        <img alt="image" class="img-circle" src="img/a2.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/a2.jpg">
                                                                                    </a>
                                                                                    <div class="media-body ">
                                                                                        <small class="pull-right">2小时前</small>
                                                                                        <strong>作家崔成浩</strong> 抽奖中了20万
                                                                                        <br>
                                                                                        <small class="text-muted">今天 09:27 来自 Koryolink iPhone</small>
                                                                                        <div class="well">
                                                                                            抽奖，人民币2000元，从转发这个微博的粉丝中抽取一人。11月16日平台开奖。随手一转，万一中了呢？
                                                                                        </div>
                                                                                        <div class="pull-right">
                                                                                            <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> 赞 </a>
                                                                                            <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> 收藏</a>
                                                                                            <a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> 评论</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="feed-element">
                                                                                    <a href="profile.html#" tppabs="http://www.zi-han.net/theme/hplus/profile.html#" class="pull-left">
                                                                                        <img alt="image" class="img-circle" src="img/a3.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/a3.jpg">
                                                                                    </a>
                                                                                    <div class="media-body ">
                                                                                        <small class="pull-right">2天前</small>
                                                                                        <strong>天猫</strong> 上传了2张图片
                                                                                        <br>
                                                                                        <small class="text-muted">11月7日 11:56 来自 微博 weibo.com</small>
                                                                                        <div class="photos">
                                                                                            <a target="_blank" href="javascript:if(confirm(%27http://24.media.tumblr.com/20a9c501846f50c1271210639987000f/tumblr_n4vje69pJm1st5lhmo1_1280.jpg  \n\nThis file was not retrieved by Teleport Pro, because it is addressed on a domain or path outside the boundaries set for its Starting Address.  \n\nDo you want to open it from the server?%27))window.location=%27http://24.media.tumblr.com/20a9c501846f50c1271210639987000f/tumblr_n4vje69pJm1st5lhmo1_1280.jpg%27" tppabs="http://24.media.tumblr.com/20a9c501846f50c1271210639987000f/tumblr_n4vje69pJm1st5lhmo1_1280.jpg">
                                                                                                <img alt="image" class="feed-photo" src="img/p1.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/p1.jpg">
                                                                                            </a>
                                                                                            <a target="_blank" href="javascript:if(confirm(%27http://37.media.tumblr.com/9afe602b3e624aff6681b0b51f5a062b/tumblr_n4ef69szs71st5lhmo1_1280.jpg  \n\nThis file was not retrieved by Teleport Pro, because it is addressed on a domain or path outside the boundaries set for its Starting Address.  \n\nDo you want to open it from the server?%27))window.location=%27http://37.media.tumblr.com/9afe602b3e624aff6681b0b51f5a062b/tumblr_n4ef69szs71st5lhmo1_1280.jpg%27" tppabs="http://37.media.tumblr.com/9afe602b3e624aff6681b0b51f5a062b/tumblr_n4ef69szs71st5lhmo1_1280.jpg">
                                                                                                <img alt="image" class="feed-photo" src="img/p3.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/p3.jpg">
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </div>

                                                                        </div>
                                                                        <div class="tab-pane" id="tab-2">

                                                                            <table class="table table-striped"
                                                                                id="dynamicLikeIndex" 
                                                                                data-toggle="dynamicLikeIndex" 
                                                                                data-url="<?= Url::to(['dynamic/get-like-list', 'id' => $id], true) ?>" 
                                                                                data-query-params="queryParams" 
                                                                                data-mobile-responsive="true" 
                                                                                data-height="600" 
                                                                                data-pagination="true" 
                                                                                data-page-size="10"
                                                                                data-page-list="[10, 20]"
                                                                                data-search="false"
                                                                                data-sort-name="id"
                                                                                data-sort-order="asc"
                                                                            
                                                                            >
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>状态</th>
                                                                                        <th>标题</th>
                                                                                        <th>开始时间</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    


                                                                                </tbody>
                                                                            </table>

                                                                        </div>


                                                                        <div class="tab-pane" id="tab-3">

                                                                            <table class="table table-striped"
                                                                                id="dynamicCollectIndex" 
                                                                                data-toggle="dynamicCollectIndex" 
                                                                                data-url="<?= Url::to(['dynamic/get-collect-list', 'id' => $id], true) ?>" 
                                                                                data-query-params="queryParams" 
                                                                                data-mobile-responsive="true" 
                                                                                data-height="600" 
                                                                                data-pagination="true" 
                                                                                data-page-size="10"
                                                                                data-page-list="[10, 20]"
                                                                                data-search="false"
                                                                                data-sort-name="id"
                                                                                data-sort-order="asc"
                                                                            >
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>状态</th>
                                                                                        <th>标题</th>
                                                                                        <th>开始时间</th>
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
   
    
