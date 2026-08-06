<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\SystemAsset;
use yii\helpers\Url;
use yii\bootstrap4\Html;

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
        $(document).ready(function(){
          $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",});
          $(".summernote").summernote({
            height: 200, // 设置高度
            lang:"zh-CN"
          })
        });
        var edit=function(){
          $(".click2edit").summernote({
            focus:true
          })
        };
        var save=function(){
          var aHTML=$(".click2edit").code();
          $(".click2edit").destroy()
        };
 
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
                  field: 'module_name',
                  class:'project-people',
                  title: '用户ID',
                  formatter: function(value, row, index) {
                   
                    return '所属模块/分组： '+value+'<br><small>接口路径：'+row.path+'</small>'
                     
                  }
                 
              }, {
                  field: 'method',
                  class:'project-title',
                  title: '用户名称',
                  formatter: function(value, row, index) {
                   
                    return ' <a  href="javascript:void(0)"  >请求方式:'+value+'</a><br><small>接口名称/标题：'+row.name+'</small>'
                     
                  }
              }, {
                  field: 'request_content_type',
                  class:'project-completion',
                  title: '密码',
                  formatter: function(value, row, index) {

                    return ' <small>请求头Content-Type： '+value+'%</small>  </div>'
                     
                  }
              }, {
                  field: 'status',
                  class:'project-status',
                  title: '状态',
                  formatter: function(value, row, index) {
                
                      if(value == 1){
                        return '状态: <span class="label label-primary">进行中</span>'
                      }else{
                        return '状态: <span class="label label-danger">已删除 </span>'

                      }
                     
                  }
              },  {
                  field: 'action',
                  class:'project-actions',
                  title: '操作',
                  formatter: function (value, row, index) {
                      // 这里直接返回 HTML 字符串
                      return [
                        '<a class=" btn btn-white btn-sm" style="margin-right:5px" href="javascript:void(0)" onclick="customerEdit(' + row.id + ')"><i class="fa fa-pencil text-warning"></i> 修改 </a>',
                          '<a class=" btn btn-white btn-sm" style="margin-right:5px" href="javascript:void(0)" onclick="deleteCustomer(' + row.id + ')"><i class="fa fa-times text-danger"></i> 删除 </a>',
                          '<a class=" btn btn-white btn-sm" style="margin-right:5px" href="javascript:void(0)" onclick="addApiParameter(' + row.id + ')"><i class="fa fa-plus text-warning"></i> 属性 </a>',
                          '<a class=" btn btn-white btn-sm" href="javascript:void(0)" onclick="customerDetail(' + row.id + ')"><i class="fa fa-folder text-success"></i> 详情 </a>'
                      ].join(''); // join('') 用于把数组变成字符串
                  }
              }]
          });
          function flesh(){
            $('#customerIndex').bootstrapTable('refresh');

          }
          function addApiParameter(id){

            $('#addApiParameterModal').modal('show');
            $('#detail_api_id').val(id);

          }
          function closeModel(){
            $('#dynamicLikeIndex').bootstrapTable('destroy');
            $('#detailDynamic').modal('hide');
          }
          function customerEdit(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 假设你的表格 ID 是 #myTable
              // 或者如果没设 uniqueId，可以用 getData()[index]，但上面的方法更稳
              if(id) {
                $.ajax({
                    url: "<?= Url::to(['api/get-api-by-id', 'id' => $id], true) ?>" , // 你的后端接口地址
                    type: 'POST',
                    data:  {id:  id},
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    success: function(res) {
                      if(res.status == 200){
                        var res = res.data;
                        var status_text = res.status == 1 ? '进行中' : '已删除';
                   
                        var  like_count = res.like_count <= 100 ? res.like_count : 100;
                       // 将数据填入 input
                        $('#edit_id').val(res.id);
                        $('#form_module_name_e').val(res.module_name);
                        $('#form_path_e').val(res.path);
                        $('#form_method_e').val(res.method);
                        $('#form_name_e').val(res.name);
                        $('#form_request_content_type_e').val(res.request_content_type);

                        $('#form_description_e').val(res.description);
                        $('#form_response_example_e').val(res.response_example);
                      
                      
                        
                        // 【核心】修改发送给后端的参数名，以匹配你的 PHP 逻辑
                        

                          // 使用 jQuery 显示模态框
                        $('#editApiModal').modal('show');
                      }
                      toastr.info("修改页面","信息加载成功!")
                        
                    },
                    error: function() {
                      toastr.error("页面","信息加载失败!")
                    }
                });
                
              }else{
                toastr.error("加载页面","id失败!")
              }
          }


          function customerDetail(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 假设你的表格 ID 是 #myTable
              // 或者如果没设 uniqueId，可以用 getData()[index]，但上面的方法更稳
              if(id) {
                $.ajax({
                    url: "<?= Url::to(['api/get-api-by-id', 'id' => $id], true) ?>" , // 你的后端接口地址
                    type: 'POST',
                    data:  {id:  id},
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    success: function(res) {
                      if(res.status == 200){
                        var res = res.data;
                        var status_text = res.status == 1 ? '进行中' : '已删除';
                        var type_text = '';
                        switch(res.type){
                          case 2:
                            type_text = '图片';
                            break;
                          case 3:
                            type_text = '视频';
                          break;
                          case 4:
                            type_text = '消息';
                            break;
                          default:
                          type_text = '文字';
                            break;
                        }
                        var  like_count = res.like_count <= 100 ? res.like_count : 100;
                       // 将数据填入 input
                        $('#detail_id').val(res.id);
                        $('#detail_module_name').html("<h2> 所属模块/分组："+res.module_name+"</h2>");
                        $('#detail_path').html('<span class="label label-primary">'+res.path+'</span>');
                        $('#detail_method').text(res.method);
                        $('#detail_name').text(res.name);
                        $('#detail_request_content_type').text(res.request_content_type);

                        $('#detail_updated_at').text(res.updated_at);
                        $('#detail_created_at').text(res.created_at);
                        $('#detail_description').text(res.description);
                        $('#detail_response_example').html(res.response_example);
                       
                        
                        $('#detail_status').text(status_text);
                        


                        $('#dynamicLikeIndex').bootstrapTable({
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
                          interface_id:res.id
                          // 方案 B：如果你的 PHP 里习惯用 page (页码)
                          // page: Math.floor(params.offset / params.limit) + 1,
                          // pageSize: params.limit, 
                          
                              };
                          },
                          // 定义哪一列对应哪个字段
                            columns: [{
                                field: 'name',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '参数名称：<br><small>'+value+'</small>' 
                            
                                }
                              
                            }, {
                                field: 'param_type',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '参数类型：<br><small>'+value+'</small>' 
                            
                                }
                              
                            },{
                                field: 'location',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '参数位置：<br><small>'+value+'</small>' 
                            
                                }
                              
                            },{
                                field: 'data_type',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '数据类型：<br><small>'+value+'</small>' 
                            
                                }
                              
                            }, {
                                field: 'is_required',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  var v_r = value == 1?'是' :'否';
                                  return '是否必填：<br><small>'+v_r+'</small>' 
                            
                                }
                              
                            },  {
                                field: 'default_value',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '默认值：<br><small>'+value+'</small>' 
                            
                                }
                              
                            },  {
                                field: 'description',
                                class:'project-status',
                                title: '状态',
                                formatter: function(value, row, index) {
                                     return '参数说明/备注：<br><small>'+value+'</small>' 
                               
                                  
                                }
                            } , {
                                field: 'action',
                                class:'project-actions',
                                title: '操作',
                                formatter: function (value, row, index) {
                                    // 这里直接返回 HTML 字符串
                                    return [
                                        '<a class=" btn btn-white btn-sm" style="margin-right:5px" href="javascript:void(0)" onclick="deleteParameter(' + row.id + ')"><i class="fa fa-times text-danger"></i> 删除 </a>',
                                    ].join(''); // join('') 用于把数组变成字符串
                                }
                            }]
                        });

                          // 使用 jQuery 显示模态框
                        $('#detailDynamic').modal('show');
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
          function deleteParameter(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              swal({
                title:"您确定要删除这条动态吗",
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
                          url: "<?= Url::to(['api/delete-parameter', 'id' => $id], true) ?>" , // 你的后端接口地址
                          type: 'POST',
                          data:  {id:  id},
                          headers: {
                              'X-CSRF-Token': csrfToken
                          },
                          success: function(res) {
                            if(res.status == 200){
                              var res = res.data;
                                  
                            }
                            swal("删除成功！","您已经永久删除了这条动态。","success")
                            $('#dynamicLikeIndex').bootstrapTable('refresh');
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

          function deleteCustomer(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              swal({
                title:"您确定要删除这条动态吗",
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
                          url: "<?= Url::to(['api/delete', 'id' => $id], true) ?>" , // 你的后端接口地址
                          type: 'POST',
                          data:  {id:  id},
                          headers: {
                              'X-CSRF-Token': csrfToken
                          },
                          success: function(res) {
                            if(res.status == 200){
                              var res = res.data;
                                  
                            }
                            swal("删除成功！","您已经永久删除了这条动态。","success")
                            $('#customerIndex').bootstrapTable('refresh');
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
          
          // 2. 提交表单
          function submitAddForm() {
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 获取表单数据
              var formData = $('#addApiForm').serialize(); // 自动序列化成 username=xxx&password=xxx
              // 使用 URLSearchParams 解析
              var params = new URLSearchParams(formData);
              var module_nameVal = params.get('module_name');
              var pathVal = params.get('path');
              var methodVal = params.get('method');
              var nameVal = params.get('name');
              var descriptionVal = params.get('description');
              var request_content_typeVal = params.get('request_content_type');
              var response_exampleVal = params.get('response_example');
              $.ajax({
              url: "<?= Url::to(['api/add', 'id' => $id], true) ?>" , // 你的后端接口地址
              type: 'POST',
              data:  {Api:  {  // 注意这里加上模型类名
                  module_name: module_nameVal,
                  path: pathVal,
                  method: methodVal,
                  name: nameVal,
                  description: descriptionVal,
                  request_content_type: request_content_typeVal,
                  response_example: response_exampleVal,
                }},
              headers: {
                  'X-CSRF-Token': csrfToken
              },
              success: function(res) {
                 if(res.status == 200){
                    // 关闭模态框
                    $('#addApiModal').modal('hide');
                    // 刷新表格
                    $('#customerIndex').bootstrapTable('refresh');
                     
                 }
                 toastr.success("添加页面","动态添加成功!")
                  
              },
              error: function() {
                toastr.error("添加页面","动态添加失败!")
              }
              });
          }
    
          // 2. 提交表单
          function submitEditForm() {
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 获取表单数据
              var formData = $('#editApiForm').serialize(); // 自动序列化成 username=xxx&password=xxx
              // 使用 URLSearchParams 解析
              var params = new URLSearchParams(formData);
              var module_nameVal = params.get('module_name');
              var pathVal = params.get('path');
              var methodVal = params.get('method');
              var nameVal = params.get('name');
              var descriptionVal = params.get('description');
              var request_content_typeVal = params.get('request_content_type');
              var response_exampleVal = params.get('response_example');
              $.ajax({
              url: "<?= Url::to(['api/edit', 'id' => $id], true) ?>" , // 你的后端接口地址
              type: 'POST',
              data:  {Api:  {  // 注意这里加上模型类名
                  module_name: module_nameVal,
                  path: pathVal,
                  method: methodVal,
                  name: nameVal,
                  description: descriptionVal,
                  request_content_type: request_content_typeVal,
                  response_example: response_exampleVal,
                },id:$("#edit_id").val()},
              headers: {
                  'X-CSRF-Token': csrfToken
              },
              success: function(res) {
                 if(res.status == 200){
                    // 关闭模态框
                    $('#editApiModal').modal('hide');
                    // 刷新表格
                    $('#customerIndex').bootstrapTable('refresh');
                     
                 }
                 toastr.success("修改页面","api修改成功!")
                  
              },
              error: function() {
                toastr.error("修改页面","api修改失败!")
              }
              });
          }

          // 2. 提交表单
          function submitAddParameterForm() {
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 获取表单数据
              var formData = $('#addApiParameterForm').serialize(); // 自动序列化成 username=xxx&password=xxx
              // 使用 URLSearchParams 解析
              var params = new URLSearchParams(formData);
              var param_typeVal = params.get('param_type');
              var locationVal = params.get('location');
              var data_typeVal = params.get('data_type');
              var nameVal = params.get('name');
              var is_requiredVal = params.get('is_required');
              var default_valueVal = params.get('default_value');
              var descriptionVal = params.get('description');
              $.ajax({
              url: "<?= Url::to(['api/add-parameter', 'id' => $id], true) ?>" , // 你的后端接口地址
              type: 'POST',
              data:  {Api:  {  // 注意这里加上模型类名
                param_type: param_typeVal,
                location: locationVal,
                data_type: data_typeVal,
                name: nameVal,
                is_required: is_requiredVal,
                default_value: default_valueVal,
                description: descriptionVal,
                },id:$('#detail_api_id').val()},
              headers: {
                  'X-CSRF-Token': csrfToken
              },
              success: function(res) {
                 if(res.status == 200){
                    // 关闭模态框
                    $('#addApiParameterModal').modal('hide');
                    // 刷新表格
                    $('#customerIndex').bootstrapTable('refresh');
                     
                 }
                 toastr.success("添加页面","动态添加成功!")
                  
              },
              error: function() {
                toastr.error("添加页面","动态添加失败!")
              }
              });
          }
    </script>

</body>
</html>
<?php $this->endPage();
