<?php
namespace app\components;
class ConsoleHelper
{
    public static function log($data, $label = '')
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        echo "<script>console.log('{$label}:', {$json});</script>";
    }
}