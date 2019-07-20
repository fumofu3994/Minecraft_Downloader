<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
<script src='https://www.recaptcha.net/recaptcha/api.js'></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Minecraft 资源下载工具</title>
<meta name="Description" content="Minecraft Resources Download Tools">
<meta charset="utf-8" />
<style>
.center{
    text-align:center;
}
* {
    font-family: "微软雅黑"
}
</style>
</head>
<body>
<div class="container center">
    <br>
    <h1>Minecraft 下载工具</h1>
<div>
<br><br>
<div class="container">
<form method="post">
    <div class="form-group">
    <label>版本号</label>
    <br>
    <input type="text" class="form-control" name="version" placeholder="Enter version">
    <br>
    <label>类型</label>
    <select class="form-control" name="select">
        <option value="forge">Forge</option>
        <option value="optifine">Optifine</option>
        <option value="minecraft_client">Minecraft 客户端核心</option>
        <option value="minecraft_server">Minecraft 服务端核心</option>
    </select>
    <br>
    <span style="font-size:14px;"><div class="g-recaptcha" data-sitekey="input----your----recaptcha----public----here"></div></span>
</div>
<button type="submit" class="btn btn-primary">提交</button>
</form>
</div>
<br>
<?php  
function send_post($url, $post_data)  
{  
    $postdata = http_build_query($post_data);  
    $options = array(  
        'http' => array(  
            'method' => 'POST',  
            'header' => 'Content-type:application/x-www-form-urlencoded',  
            'content' => $postdata,  
            'timeout' => 15 * 60  
        )  
    );  
    $context = stream_context_create($options);  
    $result = file_get_contents($url, false, $context);  
    return $result;  
}  
              
$post_data = array(          
'secret' => 'input--------your--------recaptcha--------secret--------here',          
'response' => $_POST["g-recaptcha-response"]    );  
  $recaptcha_json_result = send_post('https://www.recaptcha.net/recaptcha/api/siteverify', $post_data);     
 $recaptcha_result = json_decode($recaptcha_json_result,true);     
?> 
<div class="container">
<?php
if(!empty($_POST["version"]) && $_POST["select"] == 'forge'){
    if(!empty(json_decode($recaptcha_result['success']))){
        $verlist="http://bmclapi2.bangbang93.com/forge/minecraft/".$_POST["version"];
        $verjson= file_get_contents($verlist);
        $dejson=json_decode($verjson,true);
        if(empty($dejson)){
            echo "<div class=\"alert alert-danger\"><strong>查询失败！</strong>可能是 Forge 尚未适配该版本，或版本号输入错误</div>";
        }
        else {
            echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>请注意！</strong>部分列表排序可能有问题，请查看所有版本以找到最新版本。</div>';
            echo "<table class=\"table table-bordered center\">";
            echo "<tr><th>版本</th><th>时间</th><th>build</th><th>下载</th></tr>";
        }
        foreach($dejson as $key=>$value){
            echo "<tr><td width=\"20%\">".$value['version']."</td><td width=\"40%\">".$value['modified']."</td><td width=\"20%\">".$value['build']."</td><td width=\"20%\"><a href=\"https://bmclapi2.bangbang93.com/forge/download?mcversion=".$_POST["version"]."&version=".$value['version']."&category=universal&format=jar\">下载</a></td></tr>";
        }
    }
    else {
        echo "<div class=\"alert alert-danger alert-dismissible fade show\"><strong>等等！</strong>reCAPTCHA认为您是机器人,请刷新页面重试。如果仍然无效，<a href=\"https://t.me/piezi\">请点击这里联系开发者</a></div>";
    }
}
if(!empty($_POST["version"]) && $_POST["select"] == 'optifine'){
    if(!empty(json_decode($recaptcha_result['success']))){
        $verlist="http://bmclapi2.bangbang93.com/optifine/".$_POST["version"];
        $verjson= file_get_contents($verlist);
        $dejson=json_decode($verjson,true);
        if(empty($dejson)){
            echo "<div class=\"alert alert-danger\"><strong>查询失败！</strong>可能是 Optifine 尚未适配该版本，或版本号输入错误</div>";
        }
        else {
            echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>请注意！</strong>部分列表排序可能有问题，请查看所有版本以找到最新版本。</div>';
            echo "<table class=\"table table-bordered center\">";
            echo "<tr><th>版本</th><th>类型</th><th>下载</th></tr>";
        }
        foreach($dejson as $key=>$value){
            echo "<tr><td width=\"20%\">".$value['patch']."</td><td width=\"40%\">".$value['type']."</td><td width=\"20%\"><a href=\"https://bmclapi2.bangbang93.com/optifine/".$_POST["version"]."/".$value['type']."/".$value['patch']."\">下载</a></td></tr>";
        }
    }
    else {
        echo "<div class=\"alert alert-danger alert-dismissible fade show\"><strong>等等！</strong>reCAPTCHA认为您是机器人,请刷新页面重试。如果仍然无效，<a href=\"https://t.me/piezi\">请点击这里联系开发者</a></div>";
    }
}
if(!empty($_POST["version"]) && $_POST["select"] == 'minecraft_client'){
    if(!empty(json_decode($recaptcha_result['success']))){
        $download_link = "https://bmclapi2.bangbang93.com/version/".$_POST["version"]."/client";
        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>请注意！</strong>如果没有开始自动下载，请<a href="https://bmclapi2.bangbang93.com/version/'.$_POST["version"].'/client">点击这里</a>。</div>';
        echo '<script>window.location.href="'.$download_link.'"</script>';
    }
    else {
        echo "<div class=\"alert alert-danger alert-dismissible fade show\"><strong>等等！</strong>reCAPTCHA认为您是机器人,请刷新页面重试。如果仍然无效，<a href=\"https://t.me/piezi\">请点击这里联系开发者</a></div>";
    }
}
if(!empty($_POST["version"]) && $_POST["select"] == 'minecraft_server'){
    if(!empty(json_decode($recaptcha_result['success']))){
        $download_link = "https://bmclapi2.bangbang93.com/version/".$_POST["version"]."/server";
        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>请注意！</strong>如果没有开始自动下载，请<a href="https://bmclapi2.bangbang93.com/version/'.$_POST["version"].'/server">点击这里</a>。</div>';
        echo '<script>window.location.href="'.$download_link.'"</script>';
    }
    else {
        echo "<div class=\"alert alert-danger alert-dismissible fade show\"><strong>等等！</strong>reCAPTCHA认为您是机器人,请刷新页面重试。如果仍然无效，<a href=\"https://t.me/piezi\">请点击这里联系开发者</a></div>";
    }
}
?>
</table>
</div>
<br><br>
<div class="container center">
<p>2017-2019 blingwang.cn All rights reserved.</p>
<p>资源提供：<a href="https://bmclapidoc.bangbang93.com/">BMCLAPI</a></p>
</div>
<div class="container">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">赞助BMCLAPI作者</button>
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">赞助BMCLAPI作者</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <img src="https://bmclapidoc.bangbang93.com/alipay.jpg" width="200px">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
        </div>
      </div>
    </div>
  </div>
</div>
<br><br>
</body>
</html>