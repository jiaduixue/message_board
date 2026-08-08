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
                  field: 'id',
                  class:'project-people',
                  title: '用户ID',
                  formatter: function(value, row, index) {
                   
                    return '动态ID： '+value+'<br><small>发布的用户ID：'+row.customer_id+'</small>'
                     
                  }
                 
              }, {
                  field: 'type',
                  class:'project-title',
                  title: '用户名称',
                  formatter: function(value, row, index) {
                    var text = '';
                    switch(value){
                      case 2:
                        text = '图片';
                        break;
                      case 3:
                        text = '视频';
                      break;
                      case 4:
                        text = '消息';
                        break;
                      default:
                      text = '文字';
                        break;
                    }
                    return ' <a  href="javascript:void(0)" onclick="customerDetail(' + row.id + ')" >'+text+'</a><br><small>发布的动态类型 创建时间：'+row.created_at+'</small>'
                     
                  }
              }, {
                  field: 'like_count',
                  class:'project-completion',
                  title: '密码',
                  formatter: function(value, row, index) {
                    var v = value <= 100 ? value : 100;
                    return ' <small>一百个赞达成数： '+v+'%</small> <div class="progress progress-mini">  <div style="width: '+v+'%;" class="progress-bar"></div>  </div>'
                     
                  }
              }, {
                  field: 'status',
                  class:'project-status',
                  title: '状态',
                  formatter: function(value, row, index) {
                
                      if(value == 1){
                        return '动态状态：<span class="label label-primary">进行中</span>'
                      }else if(value == 2){
                        return '动态状态：<span class="label label-warning">已发布 </span>'

                      }else{
                        return '动态状态：<span class="label label-danger">已删除 </span>'
                      }
                     
                  }
              },  {
                  field: 'action',
                  class:'project-actions',
                  title: '操作',
                  formatter: function (value, row, index) {
                      // 这里直接返回 HTML 字符串
                      return [
                          '<a class=" btn btn-white btn-sm" style="margin-right:5px" href="javascript:void(0)" onclick="deleteCustomer(' + row.id + ')"><i class="fa fa-times text-danger"></i> 删除 </a>',
                          '<a class=" btn btn-white btn-sm" style="margin-right:5px" href="javascript:void(0)" onclick="customerDetail(' + row.id + ')"><i class="fa fa-folder"></i> 详情 </a>',
                          '<a class=" btn btn-white btn-sm" href="javascript:void(0)" onclick="customerApply(' + row.id + ')"><i class="fa fa-folder text-warning"></i> 审核 </a>'
                      ].join(''); // join('') 用于把数组变成字符串
                  }
              }]
          });
          function flesh(){
            $('#customerIndex').bootstrapTable('refresh');

          }
          function closeModel(){
            $('#dynamicLikeIndex').bootstrapTable('destroy');
            $('#dynamicCollectIndex').bootstrapTable('destroy');
            $('#detailDynamic').modal('hide');
          }
          function customerDetail(id){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 假设你的表格 ID 是 #myTable
              // 或者如果没设 uniqueId，可以用 getData()[index]，但上面的方法更稳
              if(id) {
                $.ajax({
                    url: "<?= Url::to(['dynamic/get-dynamic-by-id', 'id' => $id], true) ?>" , // 你的后端接口地址
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
                        $('#detail_customer_id').html("<h2> 用户ID："+res.customer_id+"</h2>");
                        $('#detail_type').text(type_text);
                        $('#detail_created_at').text(res.created_at);
                        $('#detail_updated_at').text(res.updated_at);
                        $('#detail_view_count').text(res.view_count);
                        $('#detail_location').text(res.location);
                        $('#detail_content').html(res.content);
                        $('#detail_like_d2').text(like_count+'%');
                        $('#detail_like_d').html('<div style="width: '+like_count+'%;" class="progress-bar"></div>');
                        
                        $('#detail_github_link').val(res.github_link);
                        $('#detail_blog_link').val(res.blog_link);
                        $('#detail_bio').val(res.bio);
                        
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
                          dynamic_id:res.id
                          // 方案 B：如果你的 PHP 里习惯用 page (页码)
                          // page: Math.floor(params.offset / params.limit) + 1,
                          // pageSize: params.limit, 
                          
                              };
                          },
                          // 定义哪一列对应哪个字段
                            columns: [{
                                field: 'id',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '点赞用户ID：' +value
                            
                                }
                              
                            }, {
                                field: 'created_at',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '创建时间：' +value
                            
                                }
                              
                            }, {
                                field: 'status',
                                class:'project-status',
                                title: '状态',
                                formatter: function(value, row, index) {
                              
                                    if(value == 1){
                                      return '状态：<span class="label label-primary">进行中</span>'
                                    }else{
                                      return '状态：<span class="label label-danger">已删除 </span>'

                                    }
                                  
                                }
                            }]
                        });

                        getCommentList(res.id);

                        $('#dynamicCollectIndex').bootstrapTable({
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
                          dynamic_id:res.id
                          // 方案 B：如果你的 PHP 里习惯用 page 
                              };
                          },
                          // 定义哪一列对应哪个字段
                            columns: [{
                                field: 'id',
                                class:'project-people',
                                title: '用户ID',
                                formatter: function(value, row, index) {
                                  
                                  return '收藏用户ID：' +value
                            
                                }
                              
                            }, {
                                field: 'created_at',
                                class:'project-completion',
                                title: '密码',
                                formatter: function(value, row, index) {
                                  
                                  return '创建时间：' +value
                            
                                }
                            }, {
                                field: 'status',
                                class:'project-status',
                                title: '状态',
                                formatter: function(value, row, index) {
                              
                                    if(value == 1){
                                      return '状态：<span class="label label-primary">进行中</span>'
                                    }else{
                                      return '状态：<span class="label label-danger">已删除 </span>'

                                    }
                                  
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
          function getCommentList(id,page){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            page = page?page:0;
            var id = id ? id :$('#detail_id').val();
            if(id) {
              $.ajax({
                          url: "<?= Url::to(['dynamic/get-comment-list', 'id' => $id], true) ?>" , // 你的后端接口地址
                          type: 'POST',
                          data:  {dynamic_id:  id,page:page},
                          headers: {
                              'X-CSRF-Token': csrfToken
                          },
                          success: function(res) {
                            var all_page = 0;
                            if(res.total >= 1){
                              all_page = Math.ceil(res.total / 10);

                              let all_page_tab = '';
                              for (let i = 0; i < all_page; i++) {
                                all_page_tab += '<button onclick="getCommentList('+id+','+i+')" class="btn btn-primary" type="button">'+(i+1)+'</button> ';
                              }
                              $('#all_page_tab').html(all_page_tab);
                            }
                            if(res.rows.length >= 1){
                              
                              // 假设通过 AJAX 从后端获取评论数据
                              let commentHtml = '';
                                  res.rows.forEach(comment => { // 假设 res.data 是评论数组
                                      commentHtml += `
                                          <div class="feed-element">
                                              <a href="profile.html?uid=${comment.customer_id}">
                                                  <img src="${comment.customer.avatar_url}" class="img-circle" alt="头像">
                                              </a>
                                              <div class="media-body">
                                                  <small class="pull-right text-navy">${comment.created_at}</small>
                                                  <strong>${comment.customer.username}</strong> 发布了一个评论：
                                                  <br>
                                                  <small class="text-muted">性别 ${comment.customer.gender}</small>
                                                  <div class="well">
                                                    ${comment.content}
                                                  </div>
                                              </div>
                                          </div>
                                      `;
                                  });
                                  // 插入到 #getCommentData 容器中
                                  $('#getCommentData').html(commentHtml);

                                  
                            }else{
                              $('#getCommentData').html('');
                            }
                          },
                          error: function() {
                            //swal("已取消","服务器异常","error")
                          }
                      });
                  
             }

          }
          function customerApply(id){
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
                swal({
                  title:"您确定要审核这条动态吗",
                  text:"审核后将无法恢复，请谨慎操作！",
                  type:"warning",
                  showCancelButton:true,
                  confirmButtonColor:"#DD6B55",
                  confirmButtonText:"是的，我要审核！",
                  cancelButtonText:"让我再考虑一下…",
                  closeOnConfirm:false,
                  closeOnCancel:false
                },function(isConfirm){
                  if(isConfirm){
                    if(id) {
                        $.ajax({
                            url: "<?= Url::to(['dynamic/apply', 'id' => $id], true) ?>" , // 你的后端接口地址
                            type: 'POST',
                            data:  {id:  id},
                            headers: {
                                'X-CSRF-Token': csrfToken
                            },
                            success: function(res) {
                              if(res.status == 200){
                                var res = res.data;
                                    
                              }
                              swal("审核成功！","您已经永久审核了这条动态。","success")
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
                          url: "<?= Url::to(['dynamic/delete', 'id' => $id], true) ?>" , // 你的后端接口地址
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
              var formData = $('#addCustomerForm').serialize(); // 自动序列化成 username=xxx&password=xxx
              // 使用 URLSearchParams 解析
              var params = new URLSearchParams(formData);
              var typeVal = params.get('type');
              var contentVal = params.get('content');
              var media_urlVal = params.get('media_url');
              $.ajax({
              url: "<?= Url::to(['dynamic/add', 'id' => $id], true) ?>" , // 你的后端接口地址
              type: 'POST',
              data:  {Dynamic:  {  // 注意这里加上模型类名
                  type: typeVal,
                  content: contentVal,
                  media_url: media_urlVal,
                }},
              headers: {
                  'X-CSRF-Token': csrfToken
              },
              success: function(res) {
                 if(res.status == 200){
                    // 关闭模态框
                    $('#addDynamic').modal('hide');
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
