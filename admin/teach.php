<?php
/***************************************
* http://www.irfansural.com
* ARIES (Artificial Intelligence Distance Education Support)
* AUTHOR: Irfan SURAL
* DATE: 1 July 2015
* DETAILS: Admin interface for "teaching" an AIML chatbot
***************************************/
  $upperScripts = "";
    $post_vars = filter_input_array(INPUT_POST);
  $msg = '';
  if ((isset ($post_vars['action'])) && ($post_vars['action'] == "Teach"))
  {
    $msg = insertAIML();
  }
  $teachContent = $template->getSection('TeachBotForm');
  $showHelp = $template->getSection('TeachShowHelp');
  $topNav = $template->getSection('TopNav');
  $leftNav = $template->getSection('LeftNav');
  $main = $template->getSection('Main');
  $navHeader = $template->getSection('NavHeader');
  $FooterInfo = getFooter();
  $errMsgClass = (!empty ($msg)) ? "ShowError" : "HideError";
  $errMsgStyle = $template->getSection($errMsgClass);
  $noLeftNav = '';
  $noTopNav = '';
  $noRightNav = $template->getSection('NoRightNav');
  $headerTitle = 'Actions:';
  $pageTitle = 'ARIES - Teaching Interface';
  $mainContent = $template->getSection('TeachMain');
  #$mainContent   = 'Hello!';
  $mainTitle = "System Teaching Interface for $bot_name";
  $mainContent = str_replace('[bot_name]', $bot_name, $mainContent);
  $mainContent = str_replace('[teach_content]', $teachContent, $mainContent);
  $mainContent = str_replace('[showHelp]', $showHelp, $mainContent);
 
  /**
  * Function insertAIML
  *
  *
  * @return string
  */
  function insertAIML()
  {
  //db globals
    global $template, $msg, $post_vars, $dbConn;
    $aiml = "<category><pattern>[pattern]</pattern>[thatpattern]<template>[template]</template></category>";
    $aimltemplate = trim($post_vars['template']);
    $pattern = trim($post_vars['pattern']);
    $pattern = (IS_MB_ENABLED) ? mb_strtoupper($pattern) : strtoupper($pattern);
    $thatpattern = trim($post_vars['thatpattern']);
    $thatpattern = (IS_MB_ENABLED) ? mb_strtoupper($thatpattern) : strtoupper($thatpattern);
    $aiml = str_replace('[pattern]', $pattern, $aiml);
    $aiml = (empty ($thatpattern)) ? str_replace('[thatpattern]', "<that>$thatpattern</that>", $aiml) : $aiml;
    $aiml = str_replace('[template]', $aimltemplate, $aiml);
    $topic = trim($post_vars['topic']);
    $topic = (IS_MB_ENABLED) ? mb_strtoupper($topic) : strtoupper($topic);
    $bot_id = (isset ($_SESSION['poadmin']['bot_id'])) ? $_SESSION['poadmin']['bot_id'] : 1;
    if (($pattern == "") || ($aimltemplate == ""))
    {
      $msg = '
	   <div class="alert alert-info alert-dismissable">
       <i class="fa fa-info"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	     &nbsp; You must enter a user input and bot response.
	 ';
    }
    else
    {
      $sql = 'INSERT INTO `aiml` (`id`,`bot_id`, `aiml`, `pattern`,`thatpattern`,`template`,`topic`,`filename`) VALUES (NULL, :bot_id, :aiml, :pattern, :thatpattern, :aimltemplate, :topic, :file)';
      $params = array(
        ':bot_id' => $bot_id,
        ':aiml' => $aiml,
        ':pattern' => $pattern,
        ':thatpattern' => $thatpattern,
        ':aimltemplate' => $aimltemplate,
        ':topic' => $topic,
        ':file' => 'admin_added.aiml'
      );
      $affectedRows = db_write($sql, $params, false, __FILE__, __FUNCTION__, __LINE__);
      if ($affectedRows > 0)
      {
        $msg = 
		'
		<div class="alert alert-success alert-dismissable">
       <i class="fa fa-info"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	     &nbsp; You have added AIML to system successfully.  
       </div>

		';
      }
      else
      {
        $msg =		
		'
		<div class="alert alert-danger alert-dismissable">
       <i class="fa fa-warning"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	     &nbsp; There was a problem adding the AIML - no changes made.   
       </div>
		';
      }
    }
    return $msg;
  }
