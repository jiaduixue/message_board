<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\CustomerAsset;
use yii\helpers\Url;
use yii\bootstrap4\Html;

CustomerAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <?= Html::csrfMetaTags() ?>
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
            offset: params.offset, 
            limit: params.limit,
             // 方案 B：如果你的 PHP 里习惯用 page (页码)
            // page: Math.floor(params.offset / params.limit) + 1,
            // pageSize: params.limit, 
            
            search: params.search   // 搜索关键词
                };
            },
            // 定义哪一列对应哪个字段
              columns: [{
                  field: 'id',
                  title: '用户ID'
              }, {
                  field: 'username',
                  title: '用户名称'
              }, {
                  field: 'password',
                  title: '密码'
              }, {
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
                      // 返回映射后的文字，若数字不在映射表中则显示原始值
                      return statusMap[value] || value;
                  }
              }, {
                  field: 'created_at',
                  title: '创建时间'
              }, {
                  field: 'updated_at',
                  title: '更新时间'
              }, {
                  field: 'action',
                  title: '操作',
                  formatter: function (value, row, index) {
                      // 这里直接返回 HTML 字符串
                      return [
                          '<a class=" " href="javascript:void(0)" onclick="editCustomerModal(' + row.id + ')"><i class="fa fa-wrench "></i>编辑</a>',
                          '<a class=" " href="javascript:void(0)" onclick="deleteCustomer(' + row.id + ')"><i class="fa fa-times text-danger"></i>删除</a>',
                          '<a class=" " href="javascript:void(0)" onclick="customerDetail(' + row.id + ')"><i class="fa fa-link  text-navy"></i>详情</a>'
                      ].join(''); // join('') 用于把数组变成字符串
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
                        $('#detail_username').val(res.username);
                        $('#detail_password').val(res.password);
                        $('#detail_real_name').val(res.real_name);
                        $('#detail_nickname').val(res.nickname);
                        $('#detail_birthday').val(res.birthday);
                        $('#detail_phone').val(res.phone);
                        $('#detail_email').val(res.email);
                        $('#detail_github_link').val(res.github_link);
                        $('#detail_blog_link').val(res.blog_link);
                        $('#detail_bio').val(res.bio);
                        $('#detail_avatar_url').val(res.avatar_url);
                        $('#detail_gender').val(res.gender);
                        $('#detail_skills').val(res.skills);
                        $('#detail_status').val(res.status);
                             
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
                          url: "<?= Url::to(['customer/delete', 'id' => $id], true) ?>" , // 你的后端接口地址
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
          // 1. 打开模态框并填充数据
          function editCustomerModal(id) {
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
                        $('#edit_id').val(res.id);
                        $('#edit_username').val(res.username);
                        $('#edit_password').val(res.password);
                        $('#edit_real_name').val(res.real_name);
                        $('#edit_nickname').val(res.nickname);
                        $('#edit_birthday').val(res.birthday);
                        $('#edit_phone').val(res.phone);
                        $('#edit_email').val(res.email);
                        $('#edit_github_link').val(res.github_link);
                        $('#edit_blog_link').val(res.blog_link);
                        $('#edit_bio').val(res.bio);
                        $('#edit_avatar_url').val(res.avatar_url);
                        $('#edit_gender').val(res.gender);
                        $('#edit_skills').val(res.skills);
                        $('#edit_status').val(res.status);
                             
                          // 使用 jQuery 显示模态框
                        $('#editCustomer').modal('show');
                      }
                      toastr.info("客户编辑页面","信息加载成功!")
                        
                    },
                    error: function() {
                      toastr.error("客户编辑页面","信息加载失败!")
                    }
                });
                
              }else{
                toastr.error("客户详情加载页面","id失败!")
              }
           
          }
            // 2. 提交edit表单
          function submitEditForm() {
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 获取表单数据
              var formData = $('#editCustomerForm').serialize(); // 自动序列化成 username=xxx&password=xxx
              // 使用 URLSearchParams 解析
              var params = new URLSearchParams(formData);
              var usernameVal = params.get('username');
              var passwordVal = params.get('password');
              var real_nameVal = params.get('real_name');
              var nicknameVal = params.get('nickname');
              var genderVal = params.get('gender');
              var birthdayVal = params.get('birthday');
              var phoneVal = params.get('phone');
              var emailVal = params.get('email');
              var avatar_urlVal = params.get('avatar_url');
              var bioVal = params.get('bio');
              var github_linkVal = params.get('github_link');
              var blog_linkVal = params.get('blog_link');
              var skillsVal = params.get('skills');
              var statusVal = params.get('status');
              $.ajax({
              url: "<?= Url::to(['customer/edit', 'id' => $id], true) ?>" , // 你的后端接口地址
              type: 'POST',
              data:  {Customer:  {  // 注意这里加上模型类名
                    username: usernameVal,
                    password: passwordVal,
                    real_name: real_nameVal,
                    nickname: nicknameVal,
                    gender: genderVal,
                    birthday: birthdayVal,
                    phone: phoneVal,
                    email: emailVal,
                    avatar_url: avatar_urlVal,
                    bio: bioVal,
                    github_link: github_linkVal,
                    blog_link: blog_linkVal,
                    skills: skillsVal,
                    status: statusVal ? statusVal : 10,
                },id:$('#edit_id').val()},
              headers: {
                  'X-CSRF-Token': csrfToken
              },
              success: function(res) {
                 if(res.status == 200){
                    // 关闭模态框
                    $('#editCustomer').modal('hide');
                    // 刷新表格
                    $('#customerIndex').bootstrapTable('refresh');
                     
                 }
                 toastr.success("客户修改页面","客户修改成功!")
                  
              },
              error: function() {
                toastr.error("客户修改页面","客户修改失败!")
              }
              });
          }
          // 2. 提交表单
          function submitAddForm() {
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 获取表单数据
              var formData = $('#addCustomerForm').serialize(); // 自动序列化成 username=xxx&password=xxx
              // 使用 URLSearchParams 解析
              var params = new URLSearchParams(formData);
              var usernameVal = params.get('username');
              var passwordVal = params.get('password');
              $.ajax({
              url: "<?= Url::to(['customer/add', 'id' => $id], true) ?>" , // 你的后端接口地址
              type: 'POST',
              data:  {Customer:  {  // 注意这里加上模型类名
                    username: usernameVal,
                    password: passwordVal,
                }},
              headers: {
                  'X-CSRF-Token': csrfToken
              },
              success: function(res) {
                 if(res.status == 200){
                    // 关闭模态框
                    $('#addCustomer').modal('hide');
                    // 刷新表格
                    $('#customerIndex').bootstrapTable('refresh');
                     
                 }
                 toastr.success("客户添加页面","客户添加成功!")
                  
              },
              error: function() {
                toastr.error("客户添加页面","客户添加失败!")
              }
              });
          }
    
    </script>
    <script type="text/javascript" src="../../../tajs.qq.com/stats-sId=9051096.js" tppabs="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

</body>
</html>
<?php $this->endPage();
