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
    
    
    // 2. 提交表单
    function submitAddForm() {
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              // 获取表单数据
              var formData = $('#addCustomerForm').serialize(); // 自动序列化成 username=xxx&password=xxx
              // 使用 URLSearchParams 解析
              var params = new URLSearchParams(formData);
              var parent_idVal = params.get('parent_id');
              var emailVal = params.get('email');
              var titleVal = params.get('title');
              var contentV = $('.summernote');
              if (contentV.length > 0) {
                  // 尝试获取内容，如果未初始化，这行可能会报错，所以最好加个 try-catch 或者确保初始化逻辑在页面加载时已执行
                  var content = contentV.code();
              } else {
                  console.error("找不到 ID 为 summernote 的元素！");
                  var content = ""; // 给个默认值防止程序崩溃
              }
              $.ajax({
              url: "<?= Url::to(['message/add-message', 'id' => $id], true) ?>" , // 你的后端接口地址
              type: 'POST',
              data:  {Message:  {  // 注意这里加上模型类名
                    parent_id: parent_idVal,
                    customer_id: 1,
                    username: 'yoga',
                    email: emailVal,
                    title: titleVal,
                    content:content
                }},
              headers: {
                  'X-CSRF-Token': csrfToken
              },
              success: function(res) {
             
                 toastr.success("信息发送","发送成功!")
                  // 跳转到目标页面（可带参数）
                  $('#messageListA').click();
                
                  
              },
              error: function() {
                toastr.error("信息发送","发送失败!")
              }
              });
          }
    </script>

<script type="text/javascript" src="../../../tajs.qq.com/stats-sId=9051096.js" tppabs="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>
</body>
</html>
<?php $this->endPage();
