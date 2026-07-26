<?php
use yii\helpers\Url;
/** @var yii\web\View $this */
use yii\helpers\Html; // 添加这一行
$this->title = 'My Yii Application';
?>

<div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <span><img alt="image" class="img-circle" src="img/profile_small.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/profile_small.jpg" /></span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold">Beaut-zihan</strong></span>
                                <span class="text-muted text-xs block">超级管理员<b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a class="J_menuItem" href="form_avatar.html" tppabs="http://www.zi-han.net/theme/hplus/form_avatar.html">修改头像</a>
                                </li>
                                <li><a class="J_menuItem" href="profile.html" tppabs="http://www.zi-han.net/theme/hplus/profile.html">个人资料</a>
                                </li>
                                <li><a class="J_menuItem" href="contacts.html" tppabs="http://www.zi-han.net/theme/hplus/contacts.html">联系我们</a>
                                </li>
                                <li><a class="J_menuItem" href="mailbox.html" tppabs="http://www.zi-han.net/theme/hplus/mailbox.html">信箱</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#"  onclick="document.getElementById('logout-form').submit(); return false;" tppabs="<?= Url::to(['site/logout', 'id' => $id], true) ?>">安全退出</a>
                                </li>
                            </ul>
                        </div>
                        <div class="logo-element">H+
                        </div>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-home"></i>
                            <span class="nav-label">主页</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="<?= Url::to(['home/index', 'id' => $id], true) ?>" tppabs="<?= Url::to(['home/index', 'id' => $id], true) ?>" data-index="0">首页</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="<?= Url::to(['home/bug', 'id' => $id], true) ?>" tppabs="<?= Url::to(['home/bug', 'id' => $id], true) ?>">bug问题</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="<?= Url::to(['home/info', 'id' => $id], true) ?>" tppabs="<?= Url::to(['home/info', 'id' => $id], true) ?>">信息</a>
                            </li>
                        
                        
                        </ul>

                    </li>
                

                    <li>
                        <a href="mailbox.html" tppabs="http://www.zi-han.net/theme/hplus/mailbox.html"><i class="fa fa-envelope"></i> <span class="nav-label">信箱 </span><span class="label label-warning pull-right">16</span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="<?= Url::to(['message/index', 'id' => $id], true) ?>" tppabs="<?= Url::to(['message/index', 'id' => $id], true) ?>">收件箱</a>
                            </li>
                            <li><a class="J_menuItem" href="<?= Url::to(['message/detail', 'id' => $id], true) ?>" tppabs="<?= Url::to(['message/detail', 'id' => $id], true) ?>">查看邮件</a>
                            </li>
                            <li><a class="J_menuItem" href="<?= Url::to(['message/add', 'id' => $id], true) ?>" tppabs="<?= Url::to(['message/add', 'id' => $id], true) ?>">写信</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">系统管理</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
    
                            <li><a class="J_menuItem" href="<?= Url::to(['system/project', 'id' => $id], true) ?>" tppabs="<?= Url::to(['system/project', 'id' => $id], true) ?>">项目</a>
                            </li>
    
                           
                            <li><a class="J_menuItem" href="<?= Url::to(['system/customer', 'id' => $id], true) ?>" tppabs="<?= Url::to(['system/customer', 'id' => $id], true) ?>">客户管理</a>
                            </li>
                            <li><a class="J_menuItem" href="<?= Url::to(['system/file', 'id' => $id], true) ?>" tppabs="<?= Url::to(['system/file', 'id' => $id], true) ?>">文件管理器</a>
                            </li>
    
                          
                        </ul>
                    </li>
                    
                    <li>
                        <a href="#"><i class="fa fa-table"></i> <span class="nav-label">用户管理</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="<?= Url::to(['customer/info', 'id' => $id], true) ?>" tppabs="<?= Url::to(['customer/info', 'id' => $id], true) ?>">管理员</a>
                            </li>
                          
                            <li><a class="J_menuItem" href="<?= Url::to(['customer/index', 'id' => $id], true) ?>" tppabs="<?= Url::to(['customer/index', 'id' => $id], true) ?>">用户列表</a>
                            </li>
                            <li><a class="J_menuItem" href="<?= Url::to(['customer/member', 'id' => $id], true) ?>" tppabs="<?= Url::to(['customer/member', 'id' => $id], true) ?>">会员管理
                                <span class="label label-danger pull-right">推荐</span></a>
                            </li>
                        </ul>
                    </li>
                

                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
          
            <div class="row content-tabs">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs J_menuTabs">
                    <div class="page-tabs-content">
                        <a href="javascript:;" class="active J_menuTab" data-id="<?= Url::to(['home/index', 'id' => $id], true) ?>">首页</a>
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
                </button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
                <a href="#"  onclick="document.getElementById('logout-form').submit(); return false;" tppabs="<?= Url::to(['site/logout', 'id' => $id], true) ?>" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" id="content-main" >
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?= Url::to(['home/index', 'id' => $id], true) ?>" tppabs="<?= Url::to(['home/index', 'id' => $id], true) ?>" frameborder="0" data-id="<?= Url::to(['home/index', 'id' => $id], true) ?>" seamless></iframe>
            </div>
            <div class="footer">
                <div class="pull-right">&copy; 2014-2015 <a href="javascript:if(confirm(%27http://www.zi-han.net/  \n\nThis file was not retrieved by Teleport Pro, because it is addressed on a domain or path outside the boundaries set for its Starting Address.  \n\nDo you want to open it from the server?%27))window.location=%27http://www.zi-han.net/%27" tppabs="http://www.zi-han.net/" target="_blank">zihan's blog</a>
                </div>
            </div>
           
            <form id="logout-form" method="post" action="<?= Yii::$app->urlManager->createUrl(['site/logout']) ?>" style="display:none;">
                <?= Html::hiddenInput('_csrf', Yii::$app->request->csrfToken) ?>
            </form>
        </div>
        <!--右侧部分结束-->
    
       
    </div>