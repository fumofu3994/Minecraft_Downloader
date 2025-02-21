<?php
session_start();

// 加载翻译文件
if (isset($_GET['language'])) {
    $_SESSION['language'] = $_GET['language'];
}
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'zh-cn';
$lang_file = __DIR__ . "/lang/{$language}.php";
if (file_exists($lang_file)) {
    $lang = include($lang_file);
} else {
    $lang = include(__DIR__ . "/lang/zh-cn.php");
}
?>
<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $lang['title']; ?></title>
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
    <h1><?php echo $lang['title']; ?></h1>
</div>
<br><br>
<div class="container">
<form method="get" action="">
    <div class="form-group">
    <label><?php echo $lang['language']; ?></label>
    <br>
    <select class="form-control" name="language" onchange="this.form.submit()">
        <option value="zh-cn" <?php if ($language == 'zh-cn') echo 'selected'; ?>>中文</option>
        <option value="zh-tw" <?php if ($language == 'zh-tw') echo 'selected'; ?>>繁體中文</option>
        <option value="en" <?php if ($language == 'en') echo 'selected'; ?>>English</option>
    </select>
    </div>
</form>
<form method="post" action="">
    <div class="form-group">
    <label><?php echo $lang['version']; ?></label>
    <br>
    <input type="text" class="form-control" name="version" placeholder="Enter version">
    <br>
    <label><?php echo $lang['type']; ?></label>
    <select class="form-control" name="select">
        <option value="forge"><?php echo $lang['forge']; ?></option>
        <option value="optifine"><?php echo $lang['optifine']; ?></option>
        <option value="minecraft_client"><?php echo $lang['minecraft_client']; ?></option>
        <option value="minecraft_server"><?php echo $lang['minecraft_server']; ?></option>
    </select>
    <div class="cf-turnstile" data-sitekey="站点密钥"></div>
</div>
<br>
<button type="submit" class="btn btn-primary"><?php echo $lang['submit']; ?></button>
</form>
</div>
<br>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cf-turnstile-response'])) {
    $turnstile_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $turnstile_secret = '密钥';
    $turnstile_response = $_POST['cf-turnstile-response'];
    $data = [
        'secret' => $turnstile_secret,
        'response' => $turnstile_response
    ];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $result = @file_get_contents($turnstile_url, false, $context);
    if ($result === FALSE) {
        echo "<div class=\"alert alert-danger\"><strong>Failed to verify Turnstile response.</strong></div>";
    } else {
        $turnstile = json_decode($result);
        if ($turnstile->success) {
            if (!empty($_POST["version"]) && $_POST["select"] == 'forge') {
                $verlist = "https://bmclapi2.bangbang93.com/forge/minecraft/" . $_POST["version"];
                $verjson = @file_get_contents($verlist);
                if ($verjson === FALSE) {
                    echo "<div class=\"alert alert-danger\"><strong>" . $lang['query_failed'] . "</strong></div>";
                } else {
                    $dejson = json_decode($verjson, true);
                    if (empty($dejson)) {
                        echo "<div class=\"alert alert-danger\"><strong>" . $lang['query_failed'] . "</strong></div>";
                    } else {
                        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' . $lang['attention'] . '</strong></div>';
                        echo "<table class=\"table table-bordered center\">";
                        echo "<tr><th>" . $lang['version'] . "</th><th>时间</th><th>build</th><th>" . $lang['download'] . "</th></tr>";
                        foreach ($dejson as $key => $value) {
                            echo "<tr><td width=\"20%\">" . $value['version'] . "</td><td width=\"40%\">" . $value['modified'] . "</td><td width=\"20%\">" . $value['build'] . "</td><td width=\"20%\"><a href=\"https://bmclapi2.bangbang93.com/forge/download?mcversion=" . $_POST["version"] . "&version=" . $value['version'] . "&category=universal&format=jar\">" . $lang['download'] . "</a></td></tr>";
                        }
                        echo "</table>";
                    }
                }
            }
            if (!empty($_POST["version"]) && $_POST["select"] == 'optifine') {
                $verlist = "https://bmclapi2.bangbang93.com/optifine/" . $_POST["version"];
                $verjson = @file_get_contents($verlist);
                if ($verjson === FALSE) {
                    echo "<div class=\"alert alert-danger\"><strong>" . $lang['query_failed'] . "</strong></div>";
                } else {
                    $dejson = json_decode($verjson, true);
                    if (empty($dejson)) {
                        echo "<div class=\"alert alert-danger\"><strong>" . $lang['query_failed'] . "</strong></div>";
                    } else {
                        echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' . $lang['attention'] . '</strong></div>';
                        echo "<table class=\"table table-bordered center\">";
                        echo "<tr><th>" . $lang['type'] . "</th><th>" . $lang['version'] . "</th><th>" . $lang['download'] . "</th></tr>";
                        foreach ($dejson as $key => $value) {
                            echo "<tr><td width=\"40%\">" . $value['type'] . "</td><td width=\"20%\">" . $value['patch'] . "</td><td width=\"20%\"><a href=\"https://bmclapi2.bangbang93.com/optifine/" . $_POST["version"] . "/" . $value['type'] . "/" . $value['patch'] . "\">" . $lang['download'] . "</a></td></tr>";
                        }
                        echo "</table>";
                    }
                }
            }
            if (!empty($_POST["version"]) && $_POST["select"] == 'minecraft_client') {
                $download_link = "https://bmclapi2.bangbang93.com/version/" . $_POST["version"] . "/client";
                echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' . $lang['auto_download'] . '</strong></div>';
                echo '<script>window.location.href="' . $download_link . '"</script>';
            }
            if (!empty($_POST["version"]) && $_POST["select"] == 'minecraft_server') {
                $download_link = "https://bmclapi2.bangbang93.com/version/" . $_POST["version"] . "/server";
                echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>' . $lang['auto_download'] . '</strong></div>';
                echo '<script>window.location.href="' . $download_link . '"</script>';
            }
        } else {
            echo "<div class=\"alert alert-danger alert-dismissible fade show\"><strong>" . $lang['robot_warning'] . "</strong></div>";
        }
    }
}
?>
</div>
<br><br>
<div class="container center">
<p><?php echo $lang['all_rights_reserved']; ?></p>
<p><?php echo $lang['resource_provided_by']; ?><a href="https://bmclapidoc.bangbang93.com/">BMCLAPI</a></p>
</div>
<div class="container">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><?php echo $lang['sponsor_author']; ?></button>
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo $lang['sponsor_author']; ?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <img src="https://bmclapidoc.bangbang93.com/alipay.jpg" width="200px">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $lang['close']; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<br><br>
</body>
</html>
