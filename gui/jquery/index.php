<?php
/***************************************
  * http://www.irfansural.com
  * ARIES (Artificial Intelligence Distance Education Support)
  * AUTHOR: Irfan SURAL
  * DATE: 1 July 2015
  * DETAILS: This is the interface for the Program O JSON API
  ***************************************/
  $cookie_name = 'ARIES_JSON_GUI';
  $botId = filter_input(INPUT_GET, 'bot_id');
  $convo_id = (isset($_COOKIE[$cookie_name])) ? $_COOKIE[$cookie_name] : jq_get_convo_id();
  $bot_id = (isset($_COOKIE['bot_id'])) ? $_COOKIE['bot_id'] :($botId !== false && $botId !== null) ? $botId : 1;
  setcookie('bot_id', $bot_id);
  // Experimental code
  $base_URL  = 'http://' . $_SERVER['HTTP_HOST'];                                   // set domain name for the script
  $this_path = str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__)));  // The current location of this file, normalized to use forward slashes
  $this_path = str_replace($_SERVER['DOCUMENT_ROOT'], $base_URL, $this_path);       // transform it from a file path to a URL
  $url = str_replace('gui/jquery', 'chatbot/conversation_start.php', $this_path);   // and set it to the correct script location
/*
  $display = "The URL for the API is currently set as:<br />\n$url.<br />\n";
  $display .= 'Please make sure that you edit this file to change the value of the variable $url in this file to reflect the correct URL address of your chatbot, and to remove this message.' . PHP_EOL;
  #$display = '';
*/
  /**
   * Function jq_get_convo_id
   *
   *
   * @return string
   */
  function jq_get_convo_id()
  {
    global $cookie_name;
    session_name($cookie_name);
    session_start();
    $convo_id = session_id();
    session_destroy();
    setcookie($cookie_name, $convo_id);
    return $convo_id;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="main.css" media="all" />
    <link rel="icon" href="./favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>ARIES - Artificial Intelligence Distance Education Support System</title>
    <meta name="Description" content="ARIES - Artificial Intelligence Distance Education Support System" />
    <meta name="keywords" content="Open Source, AIML, PHP, MySQL" />
   <style type="text/css">
      h3 {
        text-align: center;
      }
      hr {
        width: 80%;
        color: green;
        margin-left: 0;
      }

      .user_name {
        color: rgb(16, 45, 178);
      }
      .bot_name {
        color: rgb(204, 0, 0);
      }
      #shameless_plug, #urlwarning {
        position: absolute;
        right: 10px;
        bottom: 10px;
        border: 1px solid red;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-shadow: 2px 2px 2px 0 #808080;
        padding: 5px;
        border-radius: 5px;
      }
      #urlwarning {
        right: auto;
        left: 10px;
        width: 50%;
        font-size: large;
        font-weight: bold;
        background-color: white;
      }
      .leftside {
        text-align: right;
        float: left;
        width: 48%;
      }
      .rightside {
        text-align: left;
        float: right;
        width: 48%;
      }
      .centerthis {
        width: 90%;
      }
      #chatdiv {
        margin-top: 20px;
        text-align: center;
        width: 100%;
      }
      p.center {
        text-align: center;
      }
      hr.center {
        margin: 0 auto;
      }

    </style>
  </head>
  <body>
  <div class="centerthis">
      <div class="rightside">
      <div class="manspeech">
        <div  class="triangle-border bottom blue">
          <div class="botsay">Hey!</div>
        </div>
      </div>
      <div class="man"></div>
      </div>
      <div class="leftside">
      <div class="dogspeech">
        <div  class="triangle-border-right bottom orange">
          <div class="usersay">&nbsp;</div>
        </div>
      </div><br />
      <div class="dog"></div>
      </div>
    </div>
    <div class="clearthis"></div>
    <div class="centerthis">
      <form method="post" name="talkform" id="talkform" action="index.php">
        <div id="chatdiv">
          <label for="submit">Say:</label>
          <input type="text" name="say" id="say" size="60"/>
          <input type="submit" name="submit" id="submit" class="submit"  value="say" />
          <input type="hidden" name="convo_id" id="convo_id" value="<?php echo $convo_id;?>" />
          <input type="hidden" name="bot_id" id="bot_id" value="<?php echo $bot_id;?>" />
          <input type="hidden" name="format" id="format" value="json" />
        </div>
      </form>
    </div>
    
    <script type="text/javascript" src="jquery-1.9.1.min.js"></script>
    <script type="text/javascript" >
     $(document).ready(function() {
      // put all your jQuery goodness in here.
        $('#talkform').submit(function(e) {
          e.preventDefault();
          var user = $('#say').val();
          $('.usersay').text(user);
          var formdata = $("#talkform").serialize();
          $('#say').val('')
          $('#say').focus();
          $.post('<?php echo $url ?>', formdata, function(data){
            var b = data.botsay;
            if (b.indexOf('[img]') >= 0) {
              b = showImg(b);
            }
            if (b.indexOf('[link') >= 0) {
              b = makeLink(b);
            }
            var usersay = data.usersay;
            if (user != usersay) {
              $('.usersay').text(usersay);
            }
            $('.botsay').html(b);
          }, 'json').fail(function(xhr, textStatus, errorThrown){
            $('#urlwarning').html("Something went wrong! Error = " + errorThrown);
          });
          return false;
        });
      });
      function showImg(input) {
        var regEx = /\[img\](.*?)\[\/img\]/;
        var repl = '<br><a href="$1" target="_blank"><img src="$1" alt="$1" width="150" /></a>';
        var out = input.replace(regEx, repl);
        console.log('out = ' + out);
        return out
      }
      function makeLink(input) {
        var regEx = /\[link=(.*?)\](.*?)\[\/link\]/;
        var repl = '<a href="$1" target="_blank">$2</a>';
        var out = input.replace(regEx, repl);
        console.log('out = ' + out);
        return out;
      }
    </script>
  </body>
</html>
