<?PHP

  /***************************************
  * http://www.irfansural.com
  * ARIES (Artificial Intelligence Distance Education Support)
  * AUTHOR: Irfan SURAL
  * DATE: 1 July 2015
  * DETAILS: Switchboard for the install folder
  ***************************************/
  $thisFile = __FILE__;
  if (!file_exists('../config/global_config.php'))
    header('location: install.php');
  require_once ('../config/global_config.php');
  if (!defined('SCRIPT_INSTALLED')) header('location: install.php');
  //header('Location: ' . _ADMIN_URL_);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>" />
    <title>My-Program</title>
    <link rel="stylesheet" href="<?php echo _ADMIN_URL_ ?>style.css" media="screen" type="text/css" />
    <style type="text/css">
      #main {left: 25px;}
      #main {right: 25px;}
      .errMsg {display: none;}
      .center {text-align: center; text-indent: 0;}


    </style>
  </head>
  <body>
    <div id="version">
    </div>
    <div id="wrapper">
      <div id="logo"></div>
      <div id="main">
        <div class="ul"></div>
        <div class="ll"></div>
        <div class="ur"></div>
        <div class="lr"></div>
        <div id="main_title">
         My Program
        </div>
        <div id="main_content" class="center">
          It appears that Program is already installed. If you wish to run the install script again,
          <a href="install.php">click here</a>. Otherwise, you can go to the
          <a href="<?php echo _ADMIN_URL_ ?>">admin page</a>, or check out your chatbot, <a href="<?php echo _BASE_URL_ ?>">here</a>.
        </div>
      </div>
      <div id="footer">
        <div class="ul"></div>
        <div class="ll"></div>
        <div class="ur"></div>
        <div class="lr"></div>
        <div class="validXHTML">
          <a href="http://validator.w3.org/check?uri=referer"><img src="images/valid-xhtml10.png" title="Valid XHTML 1.0 Strict" alt="Valid XHTML 1.0 Strict" height="31" width="88" style="border: none" /></a>
        </div>
        <div id="footer_content">
          <p>&#169; 2015 My Program<br /></p>
        </div>
        <div class="validCSS">
          <a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="images/vcss.gif" alt="Valid CSS!" title="Valid CSS!" /></a>
        </div>
      </div>
    </div>
  </body>
</html>
