<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\MainAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;

MainAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title><?= Html::encode($this->title) ?></title>

      <!-- CSRF Token (非常重要，用于表单安全) -->
      <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<?php $this->beginBody() ?>

   
      
        <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
