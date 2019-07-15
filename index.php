<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
<script src="https://www.recaptcha.net/recaptcha/api.js?render=input----your----recaptcha----public----here"></script>
<script>
        grecaptcha.ready(function () {
            grecaptcha.execute('input----your----recaptcha----public----here', { action: 'verify' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
</script>
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
    </select>
    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
</div>
<br>
<button type="submit" class="btn btn-primary">提交</button>
</form>
</div>
<br>
<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
      $recaptcha_url = 'https://www.recaptcha.net/recaptcha/api/siteverify';
      $recaptcha_secret = 'input--------your--------recaptcha--------secret--------here';
      $recaptcha_response = $_POST['recaptcha_response'];
      $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
      $recaptcha = json_decode($recaptcha);
  } ?>
<div class="container">
<?php
if(!empty($_POST["version"]) && $_POST["select"] == 'forge'){
    if ($recaptcha->score >= 0.6){
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
        echo "<div class=\"alert alert-danger alert-dismissible fade show\"><strong>等等！</strong>reCAPTCHA认为您是机器人。如果您不是的话，<a href=\"rev2.php\">请点击这里</a></div>";
    }
}
if(!empty($_POST["version"]) && $_POST["select"] == 'optifine'){
    if ($recaptcha->score >= 0.6){
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
        echo "<div class=\"alert alert-danger alert-dismissible fade show\"><strong>等等！</strong>reCAPTCHA认为您是机器人。如果您不是的话，<a href=\"rev2.php\">请点击这里</a></div>";
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