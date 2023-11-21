<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_name']) && $_POST['form_name'] == 'loginform')
{
   $success_page = './home.html';
   $error_page = basename(__FILE__);
   $mysql_server = 'localhost';
   $mysql_username = 'root';
   $mysql_password = '';
   $mysql_database = 'qaz';
   $mysql_table = 'qaz';
   $crypt_pass = md5($_POST['password']);
   $found = false;
   $db_email = '';
   $db_fullname = '';
   $db_username = '';
   $db_role = '';
   $db_avatar = '';
   $session_timeout = 600;
   $db = mysqli_connect($mysql_server, $mysql_username, $mysql_password);
   if (!$db)
   {
      die('Failed to connect to database server!<br>'.mysqli_error($db));
   }
   mysqli_select_db($db, $mysql_database) or die('Failed to select database<br>'.mysqli_error($db));
   mysqli_set_charset($db, 'utf8');
   $username = mysqli_real_escape_string($db, $_POST['username']);
   $sql = "SELECT * FROM ".$mysql_table." WHERE username = '".$username."' OR email = '".$username."'";
   $result = mysqli_query($db, $sql);
   if ($data = mysqli_fetch_array($result))
   {
      if ($crypt_pass == $data['password'] && $data['active'] != 0)
      {
         $found = true;
         $db_email = $data['email'];
         $db_fullname = $data['fullname'];
         $db_username = $data['username'];
         $db_role = $data['role'];
         $folder = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1);
         $db_avatar = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$folder" . "avatars/" . $data['avatar'];
      }
   }
   mysqli_close($db);
   if ($found == false)
   {
      header('Location: '.$error_page);
      exit;
   }
   else
   {
      $_SESSION['email'] = $db_email;
      $_SESSION['fullname'] = $db_fullname;
      $_SESSION['username'] = $db_username;
      $_SESSION['role'] = $db_role;
      $_SESSION['avatar'] = $db_avatar;
      $_SESSION['expires_by'] = time() + $session_timeout;
      $_SESSION['expires_timeout'] = $session_timeout;
      $rememberme = isset($_POST['rememberme']) ? true : false;
      if ($rememberme)
      {
         setcookie('username', $db_username, time() + 3600*24*30);
         setcookie('password', $_POST['password'], time() + 3600*24*30);
      }
      header('Location: '.$success_page);
      exit;
   }
}
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Page</title>
<link href="Jaymo1.css" rel="stylesheet">
<link href="login.css" rel="stylesheet">
</head>
<body>
<div id="container">
<div id="Layer1" style="position:absolute;text-align:center;left:236px;top:201px;width:428px;height:540px;z-index:6;">
<div id="Layer1_Container" style="width:428px;height:540px;position:relative;margin-left:auto;margin-right:auto;margin-top:auto;margin-bottom:auto;text-align:left;">
<div id="wb_Login1" style="position:absolute;left:33px;top:99px;width:363px;height:274px;z-index:0;">
<form name="loginform" method="post" accept-charset="UTF-8" action="<?php echo basename(__FILE__); ?>" id="loginform">
<input type="hidden" name="form_name" value="loginform">
<table id="Login1">
<tr>
   <td class="label"><label for="username">User Name</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="username" type="text" id="username" value="<?php echo $username; ?>"></td>
</tr>
<tr>
   <td class="label"><label for="password">Password</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="password" type="password" id="password" value="<?php echo $password; ?>"></td>
</tr>
<tr>
   <td class="row"><input id="rememberme" type="checkbox" name="rememberme"><label for="rememberme">Remember me</label></td>
</tr>
<tr>
   <td style="text-align:center;vertical-align:bottom"><input class="button" type="submit" name="login" value="Log In" id="login"></td>
</tr>
</table>
</form>
</div>
<div id="wb_Text1" style="position:absolute;left:149px;top:50px;width:131px;height:22px;text-align:center;z-index:1;" class="h1">
<span style="color:#FFFFFF;font-family:'Lato Black';font-size:19px;"><strong>Log In</strong></span></div>
<a id="Button1" href="./signup.php" style="position:absolute;left:239px;top:470px;width:88px;height:35px;z-index:2;">Signup</a>
<label for="" id="Label1" style="position:absolute;left:102px;top:475px;width:114px;height:16px;line-height:16px;z-index:3;">Create an account </label>
</div>
</div>
</div>
<header id="Layer2" style="position:fixed;text-align:center;left:0;top:0;right:0;height:99px;z-index:7;">
<div id="Layer2_Container" style="width:900px;position:relative;margin-left:auto;margin-right:auto;text-align:left;">
<div id="wb_Text2" style="position:absolute;left:344px;top:30px;width:280px;height:38px;z-index:4;">
<span style="color:#FFFFFF;font-family:Lato;font-size:32px;"><strong>The Spice Bazaar</strong></span></div>
<div id="wb_Image1" style="position:absolute;left:249px;top:5px;width:88px;height:88px;z-index:5;">
<img src="images/7s025o3imffsulb14e9120v4oo.png" id="Image1" alt="" width="88" height="88"></div>
</div>
</header>
</body>
</html>