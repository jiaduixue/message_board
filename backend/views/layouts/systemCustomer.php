<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\SystemAsset;
use yii\helpers\Html;
use yii\helpers\Url;

SystemAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="gray-bg">
<?php $this->beginBody() ?>
  <?= $content ?>

<?php $this->endBody() ?>
<script>
    // 建议放在公共 JS 文件的开头
    toastr.options = {
            closeButton: true,       // 显示关闭按钮
            debug: false,            // 开启调试模式
            progressBar: true,       // 显示进度条
            positionClass: "toast-top-center", // 设置显示位置
            onclick: null,           // 点击回调
            showDuration: "300",     // 显示动画时长
            hideDuration: "1000",    // 隐藏动画时长
            timeOut: "5000",
            extendedTimeOut: "1000", // 鼠标悬停后的额外停留时间
            showEasing: "swing",     // 显示时的动画缓冲方式
            hideEasing: "linear",    // 隐藏时的动画缓冲方式
            showMethod: "fadeIn",    // 显示的方式
            hideMethod: "fadeOut"    // 隐藏的方式
        };
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
        $('#customerIndex').bootstrapTable({
          showHeader: false,

          dataType:"json",    //服务器返回的数据类型
          method: 'get',
          contentType: "application/x-www-form-urlencoded", // 必须设置，否则 Yii 接收不到参数
          sidePagination: "server",       // 开启服务端分页
          
          // 【核心】修改发送给后端的参数名，以匹配你的 PHP 逻辑
          queryParams: function(params) { 
            return {
            // params.offset: 当前页起始行号 (例如第2页就是10)
            // params.limit: 每页显示条数 (例如10)
            
            // 方案 A：如果你的 PHP 里是用 offset 计算
            offset: params.offset / params.limit , 
            limit: params.limit,
             // 方案 B：如果你的 PHP 里习惯用 page (页码)
            // page: Math.floor(params.offset / params.limit) + 1,
            // pageSize: params.limit, 
            
            search: params.search   // 搜索关键词
                };
            },
            // 定义哪一列对应哪个字段
              columns: [{
                  field: 'avatar_url',
                  class: 'client-avatar',
                  formatter: function (value, row, index) {
                    if(value){
                      return '<a href="javascript:void(0)" onclick="customerDetail(' + row.id + ')"><img alt="image" src="'+value+'" tppabs="http://www.zi-han.net/theme/hplus/img/a5.jpg" /> </a>'
                    }else{
                      return '<a href="javascript:void(0)" onclick="customerDetail(' + row.id + ')"><img alt="image" src="img/a5.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/a5.jpg" /> </a>'
                    }
                    
              
                  }
              }, {
                  field: 'username',
              },  {
                  field: 'phone',
                  formatter: function (value, row, index) {
                    return '<i class="fa fa-phone"> </i>'
                  }
              },{
                  field: 'phone',
              },{
                  field: 'phone',
                  formatter: function (value, row, index) {
                    return '<i class="fa fa-envelope"> </i>'
              
                  }
              },  {
                  field: 'email',
              }, {
                  field: 'status',
                  formatter: function (value, row, index) {
                    if(value == 9 || value == '-'){
                        return '<span class="label label-primary">待审核 </span>'
                      }else if(value == 10){
                        return '<span class="label label-default">正常 </span>'

                      }else{
                        return '<span class="label label-danger">已删除 </span>'

                      }
              
                  }
              }]
          });
          function customerDetail(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 假设你的表格 ID 是 #myTable
              // 或者如果没设 uniqueId，可以用 getData()[index]，但上面的方法更稳
              if(id) {
                $.ajax({
                    url: "<?= Url::to(['customer/get-customer-by-id', 'id' => $id], true) ?>" , // 你的后端接口地址
                    type: 'POST',
                    data:  {id:  id},
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    success: function(res) {
                      if(res.status == 200){
                        var res = res.data;
                       // 将数据填入 input
                        $('#detail_id').val(res.id);
                        $('#detail_username').text(res.username);
                        $('#detail_real_name').text(res.real_name);
                        $('#detail_nickname').text(res.nickname);
                        $('#detail_birthday').text(res.birthday);
                        $('#detail_phone').text(res.phone);
                        $('#detail_email').text(res.email);
                        $('#detail_github_link').text(res.github_link);
                        $('#detail_blog_link').text(res.blog_link);
                        $('#detail_bio').text(res.bio);
                        if(res.avatar_url){
                          $('#detail_avatar_url').attr('src',res.avatar_url);
                        }
                      
                        $('#detail_gender').text(res.gender);
                        $('#detail_skills').text(res.skills);
                        $('#detail_status').text(res.status);
                             
                          // 使用 jQuery 显示模态框
                        $('#detailCustomer').modal('show');
                      }
                      toastr.info("客户详情页面","信息加载成功!")
                        
                    },
                    error: function() {
                      toastr.error("客户详情页面","信息加载失败!")
                    }
                });
                
              }else{
                toastr.error("客户详情加载页面","id失败!")
              }

          }
          

          $('#userIndex').bootstrapTable({
          showHeader: false,

          dataType:"json",    //服务器返回的数据类型
          method: 'get',
          contentType: "application/x-www-form-urlencoded", // 必须设置，否则 Yii 接收不到参数
          sidePagination: "server",       // 开启服务端分页
          
          // 【核心】修改发送给后端的参数名，以匹配你的 PHP 逻辑
          queryParams: function(params) { 
            return {
            // params.offset: 当前页起始行号 (例如第2页就是10)
            // params.limit: 每页显示条数 (例如10)
            
            // 方案 A：如果你的 PHP 里是用 offset 计算
            offset: params.offset / params.limit , 
            limit: params.limit,
             // 方案 B：如果你的 PHP 里习惯用 page (页码)
            // page: Math.floor(params.offset / params.limit) + 1,
            // pageSize: params.limit, 
            
            search: params.search   // 搜索关键词
                };
            },
            // 定义哪一列对应哪个字段
              columns: [{
                  field: 'avatar_url',
                  class: 'client-avatar',
                  formatter: function (value, row, index) {
                    if(value){
                      return '<a href="javascript:void(0)" onclick="userDetail(' + row.id + ')"><img alt="image" src="'+value+'" tppabs="http://www.zi-han.net/theme/hplus/img/a5.jpg" /> </a>'
                    }else{
                      return '<a href="javascript:void(0)" onclick="userDetail(' + row.id + ')"><img alt="image" src="img/a5.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/a5.jpg" /> </a>'
                    }
                    
              
                  }
              },  {
                  field: 'username',
              },  {
                  field: 'phone',
                  formatter: function (value, row, index) {
                    return '<i class="fa fa-phone"> </i>'
                  }
              },{
                  field: 'phone',
              },{
                  field: 'phone',
                  formatter: function (value, row, index) {
                    return '<i class="fa fa-envelope"> </i>'
              
                  }
              },  {
                  field: 'email',
              },{
                  field: 'status',
                  title: '状态',
                  formatter: function(value, row, index) {
                      // 定义「数字 → 文字」的映射关系（根据业务需求调整）
                      const statusMap = {
                          0: '已删除',
                          10: '正常',
                          '-':'待审核',
                          9: '待审核'
                          // 若有更多状态，继续补充...
                      };
                      if(value == 9 || value == '-'){
                        return '<span class="label label-primary">待审核 </span>'
                      }else if(value == 10){
                        return '<span class="label label-default">正常 </span>'

                      }else{
                        return '<span class="label label-danger">已删除 </span>'

                      }
                  }
              }]
          });
          function userDetail(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 假设你的表格 ID 是 #myTable
              // 或者如果没设 uniqueId，可以用 getData()[index]，但上面的方法更稳
              if(id) {
                $.ajax({
                    url: "<?= Url::to(['user/get-customer-by-id', 'id' => $id], true) ?>" , // 你的后端接口地址
                    type: 'POST',
                    data:  {id:  id},
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    success: function(res) {
                      if(res.status == 200){
                        var res = res.data;
                       // 将数据填入 input
                        $('#detail_id').val(res.id);
                        $('#detail_username').text(res.username);
                        $('#detail_real_name').text(res.real_name);
                        $('#detail_nickname').text(res.nickname);
                        $('#detail_birthday').text(res.birthday);
                        $('#detail_phone').text(res.phone);
                        $('#detail_email').text(res.email);
                        $('#detail_github_link').text(res.github_link);
                        $('#detail_blog_link').text(res.blog_link);
                        $('#detail_bio').text(res.bio);
                        if(res.avatar_url){
                          $('#detail_avatar_url').text('<img alt="image" class="img-circle" src="'+res.avatar_url+'" tppabs="http://www.zi-han.net/theme/hplus/img/a2.jpg" style="width: 62px">');
                        }else{
                          $('#detail_avatar_url').text('<img alt="image" class="img-circle" src="img/a2.jpg" tppabs="http://www.zi-han.net/theme/hplus/img/a2.jpg" style="width: 62px">');
                        }
                      
                        $('#detail_gender').text(res.gender);
                        $('#detail_skills').text(res.skills);
                        $('#detail_status').text(res.status);
                             
                          // 使用 jQuery 显示模态框
                        $('#detailCustomer').modal('show');
                      }
                      toastr.info("客户详情页面","信息加载成功!")
                        
                    },
                    error: function() {
                      toastr.error("客户详情页面","信息加载失败!")
                    }
                });
                
              }else{
                toastr.error("客户详情加载页面","id失败!")
              }

          }
        
    </script>
    <script type="text/javascript" src="../../../tajs.qq.com/stats-sId=9051096.js" tppabs="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
</body>
</html>
<?php $this->endPage();
