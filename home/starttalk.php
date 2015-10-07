<?php
/***************************************
  * http://www.irfansural.com
  * ARIES (Artificial Intelligence Distance Education Support)
  * AUTHOR: Irfan SURAL
  * DATE: 1 July 2015
  * DETAILS: This is the interface for the JSON API
  ***************************************/
  $cookie_name = 'ARIES_TALK';
  $botId = filter_input(INPUT_GET, 'bot_id');
  $convo_id = (isset($_COOKIE[$cookie_name])) ? $_COOKIE[$cookie_name] : jq_get_convo_id();
  $bot_id = (isset($_COOKIE['bot_id'])) ? $_COOKIE['bot_id'] :($botId !== false && $botId !== null) ? $botId : 1;
  setcookie('bot_id', $bot_id);
  // Experimental code
  $base_URL  = 'http://' . $_SERVER['HTTP_HOST'];                                   // set domain name for the script
  $this_path = str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__)));  // The current location of this file, normalized to use forward slashes
  $this_path = str_replace($_SERVER['DOCUMENT_ROOT'], $base_URL, $this_path);       // transform it from a file path to a URL
  //$url = str_replace('talk', 'chatbot/conversation_start.php', $this_path);   // and set it to the correct script location
  $root_url = str_replace('home', '', $this_path);   // and set it to the correct script location
  $url = $root_url . 'chatbot/conversation_start.php';   // and set it to the correct script location
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
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>ARIES | Artificial Intelligence Distance Education Support System</title>
		<meta name="generator" content="Irfan SURAL" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="includes/css/bootstrap.min2.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="includes/css/talk.css" rel="stylesheet">
	</head>
	<body>
<!-- begin template -->
<div class="navbar navbar-custom navbar-fixed-top">
 <div class="navbar-header"><a class="navbar-brand" href="starttalk.php">Ask to ARIES</a>
      <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"></li>
        <li></li>
        <li><a href="#"></a></li>
        <li>&nbsp;</li>
      </ul>
      <form class="navbar-form" method="post" name="talkform" id="talkform" action="starttalk.php">
        <div class="form-group" style="display:inline;">
          <div class="input-group">
            <div class="input-group-btn">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-chevron-down"></span></button>
              <ul class="dropdown-menu">
                <li><a href="index.php">Home Page</a></li>
                <li><a href="index.php#services">About ARIES</a></li>
                <li><a href="index.php#contactUs">Contact Us</a></li>
              </ul>
            </div>
            <input name="say" type="text" id="say" class="form-control" placeholder="What are you searching for?">
            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span> </span>
          </div>
        </div>
         <select id="sbBot_id" name="bot_id" class="form-control-static" hidden>
        <option value="1">Unknown</option>
       </select>
       <input type="hidden" name="convo_id" id="convo_id" value="<?php echo $convo_id;?>" />
       <input type="hidden" name="format" id="format" value="json" />
      </form>
    </div>
</div>
<div class="container-fluid" id="main">
  <div class="row" id="chatDiv">
    <h2>Conversation</h2>
    <!-- item list -->
      <div class="panel panel-default">
        <div class="question me h4" id="usersay">About ARIES?</div>
      </div>
      <div class="response" id="chatlog"></div>
      <hr> 
  </div>
</div>
    <script type="text/javascript" src="includes/js/jquery-1.9.1.min.js"></script> 
    <script type="text/javascript" >
      var gbURL = '<?php echo $root_url ?>getbots.php';
	  var userGuide='<p class="text-info">Welcome to Artificial Intelligence Distance Education Support Service <br>' +
	            'You can chat/talk with the system and get as quick as possible responses.' + 
				'Please note that system is still in development phase.' +
	  		    '</p>';
	  var bool=true;			
      $(document).ready(function() {
		  $('#chatlog').html(userGuide); // inital guide
        // Load multiple chatbots into the selectbox
        $.getJSON(gbURL, function(data){
          $('#sbBot_id').html("\n");
          $.each(data.bots, function(bot_id,bot_name){
            $('#sbBot_id').append('<option value="' + bot_id + '">' + bot_name + "</option>\n");
          });
        });
		$('.clearconversation').click(function(e){
    	  e.preventDefault();
   		  $('#chatlog').html(userGuide);
		   bool=true;
  		});
        $('#sbBot_id').on('change', function(){
          var bn = $('#sbBot_id option:selected').text();
          //$('.botTitle').html(bn + ": ");
          $('#chatlog').html('Now chatting with ' + bn  + "<br>\n");
        });

        // Form submission - This is where the magic happens!
        $('#talkform').submit(function(e) {
		  if (bool){$('#chatlog').html('');}
		  bool=false;
		  var div = $("#chatDiv");
          e.preventDefault();
          var bot_name = $('#sbBot_id option:selected').text();
          var user = $('#say').val();
          $('#usersay').html(user);
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
			$('#chatlog').html(b);
			  div.scrollTop=0;
			//div.scrollTop(div.prop('scrollHeight')); // Scroll down chat
			}, 'json').fail(function(xhr, textStatus, errorThrown){
            $('#chatlog').html("Something went wrong! Error = " + errorThrown);
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
<!-- end template -->
	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="includes/js/bootstrap.min2.js"></script>
	</body>
</html>