<?PHP

/***************************************
* http://www.irfansural.com
* ARIES (Artificial Intelligence Distance Education Support)
* AUTHOR: Irfan SURAL
* DATE: 1 July 2015
* DETAILS: Clears out AIML categories from the DB for the currently selected chatbot
***************************************/
  $content = "";
  $upperScripts = $template->getSection('UpperScripts');
  $post_vars = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  if ((isset ($post_vars['action'])) && ($post_vars['action'] == "clear"))
  {
    $msg = clearAIML();
  }
  elseif ((isset ($post_vars['clearFile'])) && ($post_vars['clearFile'] != "null"))
  {
    $msg = clearAIMLByFileName($post_vars['clearFile']);
  }
  else
  {
  }
  $content .= buildMain();
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
  $pageTitle = "ARIES - Clear AIML Categories";
  $mainContent = $content;
  $mainTitle = "Clear AIML Categories for $bot_name";
  $showHelp = $template->getSection('ClearAIMLHelp');
  #$mainTitle = str_replace('[helpLink]', $template->getSection('HelpLink'), $mainTitle);
  $mainContent = str_replace('[showHelp]', $showHelp, $mainContent);
  #$mainContent = str_replace('[upperScripts]', $upperScripts, $mainContent);
  if (!$msg==""){
	  $msg='
      <div class="alert alert-success alert-dismissable">
        <i class="fa fa-info"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	     &nbsp; '.$msg.'   
       </div>
	   <br /><br /><br />
  ';
	  }
  /**
  * Function clearAIML
  *
  *
  * @return string
  */
  function clearAIML()
  {
    global $dbn, $bot_id, $bot_name, $dbConn;
    $sql = "DELETE FROM `aiml` WHERE `bot_id` = $bot_id;";
    $affectedRows = db_write($sql, null, false, __FILE__, __FUNCTION__, __LINE__);
    $msg = "<strong>All AIML categories cleared for $bot_name!</strong><br />";
    return $msg;
  }

  /**
  * Function clearAIMLByFileName
  *
  * * @param $filename
  * @return string
  */
  function clearAIMLByFileName($filename)
  {
    global $dbn, $bot_id, $dbConn;
    $sql = "delete from `aiml` where `filename` like '$filename' and `bot_id` = $bot_id;";
    $affectedRows = db_write($sql, null, false, __FILE__, __FUNCTION__, __LINE__);
    $msg = "<br/><strong>AIML categories cleared for file $filename!</strong><br />";
    return $msg;
  }

  /**
  * Function buildSelOpts
  *
  *
  * @return string
  */
  function buildSelOpts()
  {
    global $bot_id, $bot_name, $msg;
    $sql = "SELECT DISTINCT filename FROM `aiml` where `bot_id` = $bot_id order by `filename`;";
    $result = db_fetchAll($sql, null, __FILE__, __FUNCTION__, __LINE__);
    if (count($result) == 0)
    {
      $msg = "The chatbot '$bot_name' has no AIML categories to clear.";
      return false;
    }
    $out = "                  <!-- Start Selectbox Options -->\n";
    $optionTemplate = "                  <option value=\"[val]\">[val]</option>\n";
    foreach ($result as $row)
    {
      if (empty ($row['filename']))
      {
        $curOption = "                  <option value=\"\">{No Filename entry}</option>\n";
      }
      else
        $curOption = str_replace('[val]', $row['filename'], $optionTemplate);
      $out .= $curOption;
    }
    $out .= "                  <!-- End Selectbox Options -->\n";
    return $out;
  }

  /**
  * Function buildMain
  *
  *
  * @return string
  */
  function buildMain()
  {
    global $msg, $template;
    $selectOptions = buildSelOpts();
    if ($selectOptions === false) return false; #"<div class=\"bold red center\">$msg</div><br>\n";
    $clearForm = $template->getSection('ClearAIMLForm');
    $content = $template->getSection('ClearAIMLMain');
	$content = str_replace('[ClearAIML_content]', $clearForm, $content);
    $content = str_replace('[selectOptions]', $selectOptions, $content);
    $content = str_replace('[blank]', '', $content);
    return $content;
  }

?>