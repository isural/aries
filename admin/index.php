<?PHP
/***************************************
* http://www.irfansural.com
* ARIES (Artificial Intelligence Distance Education Support)
* AUTHOR: Irfan SURAL
* DATE: 1 July 2015
* DETAILS: Gateway to the admin functions for the script
***************************************/

  $thisFile = __FILE__;
  if (!file_exists('../config/global_config.php')) header('location: ../install/install.php');
  require_once('../config/global_config.php');

  // set up error logging and display
  ini_set('log_errors', true);
  ini_set('error_log', _LOG_PATH_ . 'admin.error.log');
  ini_set('html_errors', false);
  ini_set('display_errors', false);
  set_exception_handler("handle_exceptions");

  //load shared files
  require_once(_LIB_PATH_ . 'PDO_functions.php');
  require_once(_LIB_PATH_ . 'error_functions.php');
  require_once(_LIB_PATH_ . 'misc_functions.php');
  require_once(_LIB_PATH_ . 'template.class.php');
  require_once(_ADMIN_PATH_ . 'allowedPages.php');

  // Set session parameters
  $session_name = 'ARIES_Admin';
  session_name($session_name);
  session_start();
  $msg = '';

  // Get form inputs
  $pc = print_r($_GET, true) . "\n" . print_r($_POST, true);
  $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
  $page = ($page === false || $page === null) ? 'main' : $page;
  if (!array_key_exists($page, $allowed_pages))
  {
    $msg ='Invalid argument in Allowed Pages!';
  }
  $filters = $allowed_pages[$page];
  $post_vars = filter_input_array(INPUT_POST, $filters);
  $get_vars = filter_input_array(INPUT_GET, $filters);
  $input_vars = array_merge((array) $get_vars, (array) $post_vars);

  // Set default values
  $bot_name = '<b class="red">not selected</b>';
  $hide_logo = '';
  $curPage = '';

  // Begin script execution
  $thisPath = dirname(__FILE__);
  //$template = new Template("$thisPath/default.page.htm");
  
  $dbConn = db_open();
  if ($get_vars['page'] == 'logout') logout();
  $logged_in = getLoginStatus();
  $curPage = 'logout';
  $version="2.4.7";

  switch ($logged_in)
  {
    case true:
      $curPage = (isset($get_vars['page'])) ? $get_vars['page'] : 'main';
	   $template = new Template("$thisPath/default.page.htm");
      break;
    default:
      $curPage = ($get_vars['page'] == 'login') ? login() : 'logout';
	  $template = new Template("$thisPath/login.page.htm");
  }

      $name       = (isset($_SESSION['poadmin']['name'])) ? $_SESSION['poadmin']['name'] : '';
      $ip         = (isset($_SESSION['poadmin']['ip'])) ? $_SESSION['poadmin']['ip'] : '';
      $last       = (isset($_SESSION['poadmin']['last_login'])) ? $_SESSION['poadmin']['last_login'] : '';
      $lip        = (isset($_SESSION['poadmin']['lip'])) ? $_SESSION['poadmin']['lip'] : '';
      $llast      = (isset($_SESSION['poadmin']['prior_login'])) ? $_SESSION['poadmin']['prior_login'] : '';
      $bot_name   = (isset($_SESSION['poadmin']['bot_name'])) ? $_SESSION['poadmin']['bot_name'] : $bot_name;
      $bot_id     = (isset($_SESSION['poadmin']['bot_id'])) ? $_SESSION['poadmin']['bot_id'] : 1;
      $hide_logo  = (isset($_SESSION['display'])) ? $_SESSION['display'] : '';
      $bot_format = (isset($_SESSION['poadmin']['bot_format'])) ? $_SESSION['poadmin']['bot_format'] : '';


# Build page sections
# ordered here in the order that the page is constructed
  $logo            = $template->getSection('Logo');
  $titleSpan       = $template->getSection('TitleSpan');
  $main            = $template->getSection('Main');
  $divDecoration   = '';
  $mainContent     = $template->getSection('LoginForm');
  $noLeftNav       = $template->getSection('NoLeftNav');
  $noRightNav      = $template->getSection('NoRightNav');
  $navHeader       = $template->getSection('NavHeader');
  $footer          = $template->getSection('Footer');
  $topNav          = '';
  $leftNav         = '';
  $rightNav        = '';
  $rightNavLinks   = '';
  $lowerScripts    = $template->getSection('LogoLinkScript');
  $pageTitleInfo   = '';
  $topNavLinks     = makeLinks('top','NavTopLink',makeTopLinks());
  $leftNavLinks    = makeLinks('left','NavLeftLink', makeLeftLinks());
  $mediaType       = ' media="screen"';
  $mainTitle       = 'ARIES Login';
  $FooterInfo      = '';
  $headerTitle     = '';
  $pageTitle       = 'ARIES - Artificial Intelligence Distance Education Support Login';
  $upperScripts    = '';
  $extraCSS = '';

  $_SESSION['poadmin']['curPage'] = $curPage;
  ($curPage != 'logout' || $curPage == 'login') ? include ("$curPage.php") : false;

  $bot_format_link = (!empty($bot_format)) ? "&amp;format=$bot_format" : '';
  $curPage = (isset($curPage)) ? $curPage : 'main';
  $upperScripts .= ($hide_logo == 'HideLogoCSS') ? $template->getSection('HideLogoCSS') : '';
  # Build page content from the template

  $content  = $template->getSection('Header');
  #$content .= "hide_logo = $hide_logo";
  $content .= $template->getSection('PageBody');
  
  # Replace template labels with real data
  $styleSheet = '';
  $errMsgClass   = (!empty($msg)) ? "ShowError" : "HideError";
  $errMsgStyle   = $template->getSection($errMsgClass);
  $bot_id = ($bot_id == 'new') ? 0 : $bot_id;
  $searches = array(
                    '[charset]'         => $charset,
                    '[myPage]'          => $curPage,
                    '[pageTitle]'       => $pageTitle,
                    '[styleSheet]'      => $styleSheet,
                    '[mediaType]'       => $mediaType,
                    '[extraCSS]'        => $extraCSS,
                    '[upperScripts]'    => $upperScripts,
                    '[logo]'            => $logo,
                    '[pageTitleInfo]'   => $pageTitleInfo,
                    '[topNav]'          => $topNav,
                    '[leftNav]'         => $leftNav,
                    '[rightNav]'        => $rightNav,
                    '[main]'            => $main,
                    '[footer]'          => $footer,
                    '[lowerScripts]'    => $lowerScripts,
                    '[titleSpan]'       => $titleSpan,
                    '[divDecoration]'   => $divDecoration,
                    '[topNavLinks]'     => $topNavLinks,
                    '[navHeader]'       => $navHeader,
                    '[leftNavLinks]'    => $leftNavLinks,
                    '[mainTitle]'       => $mainTitle,
                    '[mainContent]'     => $mainContent,
                    '[rightNavLinks]'   => $rightNavLinks,
                    '[FooterInfo]'      => $FooterInfo,
                    '[headerTitle]'     => $headerTitle,
                    '[errMsg]'          => $msg,
                    '[bot_id]'          => $bot_id,
                    '[bot_name]'        => $bot_name,
                    '[errMsgStyle]'     => $errMsgStyle,
                    '[noRightNav]'      => $noRightNav,
                    '[noLeftNav]'       => $noLeftNav,
                    '[version]'         => $version,
                    '[bot_format_link]' => $bot_format_link,
					'[curBot]'		    => $bot_name, // added					
                   );

  foreach ($searches as $search => $replace) {
    $content = str_replace($search, $replace, $content);
  }
  $content = str_replace('[myPage]', $curPage, $content);
  $content = str_replace('[divDecoration]', $divDecoration, $content);
  $content = str_replace('[blank]', '', $content);

  session_gc();
  exit($content);

  /**
   * Function makeLinks
   *
   * * @param $section
   * @param     $linkArray
   * @return string
   */
  function makeLinks($section, $layer, $linkArray) {
    $out = "<!-- making links for section $section -->\n";
    global $template, $curPage;
    $curPage = (empty($curPage)) ? 'main' : $curPage;
    $botName = (isset($_SESSION['poadmin']['bot_name'])) ? $_SESSION['poadmin']['bot_name'] : '<b class="red">not selected</b>';
    $botId = (isset($_SESSION['poadmin']['bot_id'])) ? $_SESSION['poadmin']['bot_id'] : 1;
    $botId = ($botId == 'new') ? 1 : $botId;
    # [linkClass][linkHref][linkOnclick][linkAlt][linkTitle]>[linkLabel]
    $linkText = $template->getSection($layer);
    foreach ($linkArray as $needle) {
      $tmp = $linkText;
      foreach ($needle as $search => $replace) {
        $tmp = str_replace($search, $replace, $tmp);
      }
      $linkClass = $needle['[linkHref]'];
      $linkClass = str_replace(' href="index.php?page=', '', $linkClass);
      $linkClass = str_replace('"', '', $linkClass);
      $curClass = ($linkClass == $curPage) ? 'active' : 'noClass';
      if ($curPage == 'main') $curClass = (stripos($linkClass,'main') !== false) ? 'active' : 'noClass';
      $tmp = str_replace('[curClass]', $curClass, $tmp);
      $out .= "$tmp\n";
    }
    #print "<!-- returning links for section $section:\n\n out = $out\n\n -->\n";
    $strippedBotName = preg_replace('~\<b class="red"\>(.*?)\</b\>~', '$1', $botName);
    $out = str_replace('[botId]', $botId, $out);
    $out = str_replace('[curBot]', $botName, $out);
    $out = str_replace('[curBot2]', $strippedBotName, $out);
    return trim($out);
  }



  /**
   * Function makeTopLinks
   *
   *
   * @return array
   */
	function makeTopLinks() {
	$out = array(
					 array(
						   '[linkClass]' => '',
						   '[linkHref]' => ' href="index.php?page=main"',
						   '[linkAlt]' => ' alt="Home"',
						   '[linkTitle]' => ' title="Home"',
						   '[linkLabel]' => ' Home',
						   '[iClass]'	 => ' class="fa fa-fw fa-dashboard"'					   
						   
						   ),
					 array(
						   '[linkClass]' => '',
						   '[linkHref]' => ' href="index.php?page=members"',
						   '[linkAlt]' => ' alt="Edit Admin Accounts"',
						   '[linkTitle]' => ' title="Edit Admin Accounts"',
						   '[linkLabel]' => ' Admin Accounts',
						   '[iClass]'	 => ' class="fa fa-fw fa-users"'					   
						   ),	   
					 array(
						   '[linkClass]' => '',
						   '[linkHref]' => ' href="index.php?page=logs"',
						   '[linkAlt]' => ' alt="View The Log Files"',
						   '[linkTitle]' => ' title="View The Log Files"',
						   '[linkLabel]' => ' Logs',
						   '[iClass]'	 => ' class="fa fa-fw fa-th"'					   
						   ),	   
					 array(
						   '[linkClass]' => '',
						   '[linkHref]' => ' href="index.php?page=stats"',
						   '[linkAlt]' => ' alt="Get System Statistics"',
						   '[linkTitle]' => ' title="Get System Statistics"',
						   '[linkLabel]' => ' Stats',
						   '[iClass]'	 => ' class="fa fa-fw fa-bar-chart-o"'					   
						   ),
					 array(
						   '[linkClass]' => '',
						   '[linkHref]' => ' href="index.php?page=logout"',
						   '[linkAlt]' => ' alt="Log Out"',
						   '[linkTitle]' => ' title="Log Out"',
						   '[linkLabel]' => ' Log Out',
						   '[iClass]'	 => ' class="fa fa-fw fa-power-off"'					   
						   )
					);
	return $out;
	}

  /**
   * Function makeLeftLinks
   *
   *
   * @return array
   */ 
  function makeLeftLinks() {
    $out = array(
                 array( # Change bot
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=select_bots"',
                       '[linkAlt]' => ' alt="Change or Edit the Current System"',
                       '[linkTitle]' => ' title="Change or Edit the Current System"',
                       '[linkLabel]' => ' Current User: ([curBot])',
					   '[iClass]'	 => ' class="fa fa-fw fa-user-md"'					   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=botpersonality"',
                       '[linkAlt]' => ' alt="Edit System\'s Personality"',
                       '[linkTitle]' => ' title="Edit System\'s Personality"',
                       '[linkLabel]' => ' System Personality',
					   '[iClass]'	 => ' class="fa fa-fw fa-female"'					   
                 ),
                
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=teach"',
                       '[linkAlt]' => ' alt="Train System"',
                       '[linkTitle]' => ' title="Train System"',
                       '[linkLabel]' => ' Teach System',
					   '[iClass]'	 => ' class="fa fa-fw fa-book"'					   
					   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=upload"',
                       '[linkAlt]' => ' alt="Upload AIML Files"',
                       '[linkTitle]' => ' title="Upload AIML Files"',
                       '[linkLabel]' => ' Upload AIML',
					   '[iClass]'	 => ' class="fa fa-fw fa-cloud-upload"'					   
					   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=download"',
                       '[linkAlt]' => ' alt="Download AIML Files"',
                       '[linkTitle]' => ' title="Download AIML Files"',
                       '[linkLabel]' => ' Download AIML',
					   '[iClass]'	 => ' class="fa fa-fw fa-cloud-download"'					   
					   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=clear"',
                       '[linkAlt]' => ' alt="Clear AIML Categories"',
                       '[linkTitle]' => ' title="Clear AIML Categories"',
                       '[linkLabel]' => ' Clear AIML Categories',
					   '[iClass]'	 => ' class="fa fa-fw fa-trash-o"'					   
					   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=spellcheck"',
                       '[linkAlt]' => ' alt="Edit the SpellCheck Entries"',
                       '[linkTitle]' => ' title="Edit the SpellCheck Entries"',
                       '[linkLabel]' => ' Spell Check',
					   '[iClass]'	 => ' class="fa fa-fw fa-check-circle"'					   
					   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=wordcensor"',
                       '[linkAlt]' => ' alt="Edit the Word Censor Entries"',
                       '[linkTitle]' => ' title="Edit the Word Censor Entries"',
                       '[linkLabel]' => 'Word Censor',
					   '[iClass]'	 => ' class="fa fa-fw fa-ban"'						   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=editAiml"',
                       '[linkAlt]' => ' alt="Search and Edit Specific AIML Categories"',
                       '[linkTitle]' => ' title="Search and Edit Specific AIML Categories"',
                       '[linkLabel]' => ' Search/Edit AIML', 
					   '[iClass]'	 => ' class="fa fa-fw fa-search"'					   
					   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=srai_lookup"',
                       '[linkAlt]' => ' alt="Search and Edit Entries in the srai_lookup Table"',
                       '[linkTitle]' => ' title="Search and Edit Entries in the srai_lookup Table"',
                       '[linkLabel]' => ' SRAI Lookup', 
					   '[iClass]'	 => ' class="fa fa-fw fa-tasks"'						   
                 ),
                 array(
                       '[linkClass]' => ' class="[curClass]"',
                       '[linkHref]' => ' href="index.php?page=demochat"',
                       '[linkAlt]' => ' alt="Run a Demo of System"',
                       '[linkTitle]' => ' title="Run a Demo of System"',
                       '[linkLabel]' => ' Test Your System',
					   '[iClass]'	 => ' class="fa fa-fw fa-play"'					   
                 )
    );
    return $out;
  }

  /**
   * Function handle_exceptions
   *
   * * @param exception $e
   * @return void
   */
  function handle_exceptions(exception $e)
  {
    global $msg;
    $trace = $e->getTrace();
    file_put_contents(_LOG_PATH_ . 'admin.exception.log', print_r($trace, true), FILE_APPEND);
    $msg .= $e->getMessage();
    return 'logout';
  }

  function login ()
  {
    global $post_vars, $get_vars, $dbConn, $msg;
    if((!isset($post_vars['user_name'])) ||(!isset($post_vars['pw']))) return 'logout';
    //$_SESSION['poadmin']['display'] = $hide_logo;
    $user_name = $post_vars['user_name'];
    $pw_hash = md5($post_vars['pw']);
    $sql = "SELECT * FROM `myprogramo` WHERE user_name = :user_name AND password = :pw_hash";
    $params = array(':user_name' => $user_name, ':pw_hash' => $pw_hash);
    $row = db_fetch($sql, $params, __FILE__, __FUNCTION__, __LINE__);
    if(!empty($row)) {
      $_SESSION['poadmin']['uid'] = $row['id'];
      $_SESSION['poadmin']['name'] = $row['user_name'];
      $_SESSION['poadmin']['lip']=$row['last_ip'];
      $_SESSION['poadmin']['prior_login']=date('l jS \of F Y h:i:s A', strtotime($row['last_login']));
      switch (true)
      {
        case (!empty($_SERVER['HTTP_CLIENT_IP'])):
          $ip = $_SERVER['HTTP_CLIENT_IP'];
          break;
        case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])):
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
          break;
        default:
          $ip = $_SERVER['REMOTE_ADDR'];
      }

      $sql = "UPDATE `myprogramo` SET `last_ip` = :ip, `last_login` = CURRENT_TIMESTAMP WHERE user_name = :user_name limit 1";
      $params = array(':ip' => $ip, ':user_name' => $user_name);
      $transact = db_write($sql, $params, false, __FILE__, __FUNCTION__, __LINE__);
      $_SESSION['poadmin']['ip'] = $ip;
      $_SESSION['poadmin']['last_login'] = date('l jS \of F Y h:i:s A');

      $sql = "SELECT * FROM `bots` WHERE bot_active = '1' ORDER BY bot_id ASC LIMIT 1";
      $row = db_fetch($sql, null, __FILE__, __FUNCTION__, __LINE__);
      $count = count($row);
      if($count > 0) {
        $_SESSION['poadmin']['bot_id'] = $row['bot_id'];
        $_SESSION['poadmin']['bot_name'] = $row['bot_name'];
      }
      else {
        $_SESSION['poadmin']['bot_id'] = -1;
        $_SESSION['poadmin']['bot_name'] = "unknown";
      }
    }
    else {
      $msg .= '
	   <div class="alert alert-danger alert-dismissable">
       <i class="fa fa-warning"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	     Incorrect Username or Password! 
       </div>';
    }
    if (empty($msg))
    {
      $_SESSION['poadmin']['logged_in'] = true;
      header('Location: index.php');
      return 'main';
    }
    return 'logout';
  }

  function logout()
  {
    global $session_name, $session_cookie_domain, $session_cookie_path;
    $_SESSION = array();
    session_destroy();
    setcookie($session_name, '', time()-3600, $session_cookie_path, $session_cookie_domain, false, false);
    header('Location: ./');
    exit();
  }

  function getLoginStatus()
  {
    return (isset($_SESSION['poadmin']['logged_in']) && $_SESSION['poadmin']['logged_in'] === true) ? true : false;
  }
