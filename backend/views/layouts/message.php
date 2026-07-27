<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\MessageAsset;
use yii\helpers\Url;
use yii\bootstrap4\Html;

MessageAsset::register($this);
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
        $('#messageIndex').bootstrapTable({
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
                  field: 'username',
                  class: 'mail-ontact',
                  title: '用户ID'
              }, {
                  field: 'status',
                  class: 'mail-ontact',
                  title: '状态',
                  formatter: function(value, row, index) {
                    
                      // 返回映射后的文字，若数字不在映射表中则显示原始值 <i class="fa fa-paperclip"></i>
                      if(value == 1){
                        return '<span class="label label-warning pull-right">待审核</span>'
                      }else if(value == 2){
                        return '<span class="label label-danger pull-right">正常</span>'

                      }else if(value == 3){
                        return '<span class="label label-primary pull-right">未读</span>'

                      }else{
                        return '<span class="label label-success pull-right">已读</span>'

                      }
                  }
              },{
                  field: 'customer_id',
                  class:'mail-subject',
                  title: '发布位置'
              }, {
                  field: 'ip_address',
                  title: '状态',
                  formatter: function(value, row, index) {
                    return '<i class="fa fa-paperclip"></i> '+value;
                      // 返回映射后的文字，若数字不在映射表中则显示原始值 
                     
                  }
              }, {
                  field: 'created_at',
                  class:'text-right mail-date',
                  title: '创建时间'
              }], 
               // 定义行样式
            rowStyle: function (row, index) {
                // 假设你想根据 status 字段的值来改变行的背景色
                if (row.status != 4) { 
                    return { classes: 'unread' }; // 返回 Bootstrap 自带的警告色类
                }else{
                  return { classes: 'read' }; // 返回 Bootstrap 自带的警告色类
                }
              }
                
          });
          function customerDetail(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 假设你的表格 ID 是 #myTable
              // 或者如果没设 uniqueId，可以用 getData()[index]，但上面的方法更稳
              if(id) {
                $.ajax({
                    url: "<?= Url::to(['member/get-member-by-id', 'id' => $id], true) ?>" , // 你的后端接口地址
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
                        $('#detail_customer_id').val(res.customer_id);
                        $('#detail_level_name').val(res.level_name);
                        $('#detail_level_code').val(res.level_code);

                        $('#detail_points').val(res.points);
                        $('#detail_expire_date').val(res.expire_date);
                        $('#detail_join_date').val(res.join_date);
                       
                        $('#detail_status').val(res.status);
                             
                          // 使用 jQuery 显示模态框
                        $('#detailCustomer').modal('show');
                      }
                      toastr.info("详情页面","信息加载成功!")
                        
                    },
                    error: function() {
                      toastr.error("详情页面","信息加载失败!")
                    }
                });
                
              }else{
                toastr.error("客户详情加载页面","id失败!")
              }

          }
          function deleteCustomer(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              swal({
                title:"您确定要删除这条信息吗",
                text:"删除后将无法恢复，请谨慎操作！",
                type:"warning",
                showCancelButton:true,
                confirmButtonColor:"#DD6B55",
                confirmButtonText:"是的，我要删除！",
                cancelButtonText:"让我再考虑一下…",
                closeOnConfirm:false,
                closeOnCancel:false
              },function(isConfirm){
                if(isConfirm){
                  if(id) {
                      $.ajax({
                          url: "<?= Url::to(['member/delete', 'id' => $id], true) ?>" , // 你的后端接口地址
                          type: 'POST',
                          data:  {id:  id},
                          headers: {
                              'X-CSRF-Token': csrfToken
                          },
                          success: function(res) {
                            if(res.status == 200){
                              var res = res.data;
                                  
                            }
                            swal("删除成功！","您已经永久删除了这条信息。","success")
                            $('#memberIndex').bootstrapTable('refresh');
                          },
                          error: function() {
                            swal("已取消","服务器异常","error")
                          }
                      });
                      
                    }else{
                      swal("已取消","id缺失！","error")
                    }
                  
                }else{
                  swal("已取消","您取消了删除操作！","error")
                }
              })


          }


</script>

</body>
</html>
<?php $this->endPage();
