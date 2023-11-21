<?php
$mysql_server = 'localhost';
$mysql_username = 'root';
$mysql_password = '';
$mysql_database = 'qaz';
$mysql_table = 'qaz';
$success_page = './login.php';
$error_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_name']) && $_POST['form_name'] == 'signupform')
{
   $newusername = $_POST['username'];
   $newemail = $_POST['email'];
   $newpassword = $_POST['password'];
   $confirmpassword = $_POST['confirmpassword'];
   $newfullname = $_POST['fullname'];
   $code = 'NA';
   if ($newpassword != $confirmpassword)
   {
      $error_message = 'Password and Confirm Password are not the same!';
   }
   else
   if (!preg_match("/^[A-Za-z0-9-_!@$]{1,50}$/", $newusername))
   {
      $error_message = 'Username is not valid, please check and try again!';
   }
   else
   if (!preg_match("/^[A-Za-z0-9-_!@$]{1,50}$/", $newpassword))
   {
      $error_message = 'Password is not valid, please check and try again!';
   }
   else
   if (!preg_match("/^[A-Za-z0-9-_!@$.' &]{1,50}$/", $newfullname))
   {
      $error_message = 'Fullname is not valid, please check and try again!';
   }
   else
   if (!preg_match("/^.+@.+\..+$/", $newemail))
   {
      $error_message = 'Email is not a valid email address. Please check and try again.';
   }
   if (empty($error_message))
   {
      $db = mysqli_connect($mysql_server, $mysql_username, $mysql_password);
      if (!$db)
      {
         die('Failed to connect to database server!<br>'.mysqli_error($db));
      }
      mysqli_select_db($db, $mysql_database) or die('Failed to select database<br>'.mysqli_error($db));
      mysqli_set_charset($db, 'utf8');
      $sql = "SELECT username FROM ".$mysql_table." WHERE username = '".$newusername."'";
      $result = mysqli_query($db, $sql);
      if ($data = mysqli_fetch_array($result))
      {
         $error_message = 'Username already used. Please select another username.';
      }
   }
   if (empty($error_message))
   {
      $crypt_pass = md5($newpassword);
      $newusername = mysqli_real_escape_string($db, $newusername);
      $newemail = mysqli_real_escape_string($db, $newemail);
      $newfullname = mysqli_real_escape_string($db, $newfullname);
      $sql = "INSERT `".$mysql_table."` (`username`, `password`, `fullname`, `email`, `active`, `code`, `role`) VALUES ('$newusername', '$crypt_pass', '$newfullname', '$newemail', 1, '$code', '')";
      $result = mysqli_query($db, $sql);
      mysqli_close($db);
      $subject = 'Your new account';
      $message = 'A new account has been setup.';
      $message .= "\r\nUsername: ";
      $message .= $newusername;
      $message .= "\r\nPassword: ";
      $message .= $newpassword;
      $message .= "\r\n";
      $header  = "From: james.nyakairu@students.jkuat.ac.ke"."\r\n";
      $header .= "Reply-To: james.nyakairu@students.jkuat.ac.ke"."\r\n";
      $header .= "MIME-Version: 1.0"."\r\n";
      $header .= "Content-Type: text/plain; charset=utf-8"."\r\n";
      $header .= "Content-Transfer-Encoding: 8bit"."\r\n";
      $header .= "X-Mailer: PHP v".phpversion();
      mail($newemail, $subject, $message, $header);
      header('Location: '.$success_page);
      exit;
   }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Page</title>
<link href="Jaymo1.css" rel="stylesheet">
<link href="signup.css" rel="stylesheet">
</head>
<body>
<div id="container">
<div id="Layer1" style="position:absolute;text-align:center;left:121px;top:200px;width:659px;height:682px;z-index:6;">
<div id="Layer1_Container" style="width:659px;height:682px;position:relative;margin-left:auto;margin-right:auto;margin-top:auto;margin-bottom:auto;text-align:left;">
<div id="wb_Signup1" style="position:absolute;left:149px;top:90px;width:354px;height:433px;z-index:0;">
<form name="signupform" method="post" accept-charset="UTF-8" action="<?php echo basename(__FILE__); ?>" id="signupform">
<input type="hidden" name="form_name" value="signupform">
<table id="Signup1">
<tr>
   <td class="label"><label for="fullname">Full Name</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="fullname" type="text" id="fullname"></td>
</tr>
<tr>
   <td class="label"><label for="username">User Name</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="username" type="text" id="username"></td>
</tr>
<tr>
   <td class="label"><label for="password">Password</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="password" type="password" id="password"></td>
</tr>
<tr>
   <td class="label"><label for="confirmpassword">Confirm Password</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="confirmpassword" type="password" id="confirmpassword"></td>
</tr>
<tr>
   <td class="label"><label for="email">E-mail</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="email" type="text" id="email"></td>
</tr>
<tr>
   <td><?php echo $error_message; ?></td>
</tr>
<tr>
   <td style="text-align:center;vertical-align:bottom"><input class="button" type="submit" name="signup" value="SignUp" id="signup"></td>
</tr>
</table>
</form>
</div>
<label for="" id="Label1" style="position:absolute;left:174px;top:26px;width:296px;height:33px;line-height:33px;z-index:1;">Sign up for a new account</label>
<label for="" id="Label2" style="position:absolute;left:197px;top:606px;width:153px;height:16px;line-height:16px;z-index:2;">Allready have an account </label>
<a id="Button1" href="./login.php" style="position:absolute;left:373px;top:601px;width:88px;height:35px;z-index:3;">Login</a>
</div>
</div>
</div>
<header id="Layer2" style="position:fixed;text-align:center;left:0;top:0;right:0;height:99px;z-index:7;">
<div id="Layer2_Container" style="width:900px;position:relative;margin-left:auto;margin-right:auto;text-align:left;">
<div id="wb_Text1" style="position:absolute;left:344px;top:30px;width:280px;height:38px;z-index:4;">
<span style="color:#FFFFFF;font-family:Lato;font-size:32px;"><strong>The Spice Bazaar</strong></span></div>
<div id="wb_Image1" style="position:absolute;left:249px;top:5px;width:88px;height:88px;z-index:5;">
<img src="images/7s025o3imffsulb14e9120v4oo.png" id="Image1" alt="" width="88" height="88"></div>
</div>
</header>
</body>
</html>