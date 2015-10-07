<?php

/***************************************
* http://www.irfansural.com
* ARIES (Artificial Intelligence Distance Education Support)
* AUTHOR: Irfan SURAL
* DATE: 1 July 2015
* DETAILS: Displays the "Home"  section of the admin page
***************************************/

    $noRightNav    = $template->getSection('NoRightNav');
    $logo          = $template->getSection('Logo');
    $topNav        = $template->getSection('TopNav');
    $leftNav       = $template->getSection('LeftNav');
    $main          = $template->getSection('Main');
    $rightNav      = '';
    $footer        = trim($template->getSection('Footer'));
    #$lowerScripts  = '';
    #$pageTitleInfo = '';
    $divDecoration = $template->getSection('DivDecoration');
    
    $navHeader     = $template->getSection('NavHeader');
    
    $mainTitle     = 'Admin Home Page';
    $rightNavLinks = '';
    $FooterInfo    = getFooter();
    $titleSpan     = $template->getSection('TitleSpan');
    $errMsgStyle   = (!empty($msg)) ? "ShowError" : "HideError";
    $errMsgStyle   = $template->getSection($errMsgStyle);
    $mediaType     = ' media="screen"';
    $upperScripts  = '';
    $noLeftNav     = '';
    $noTopNav      = '';
    $pageTitle     = 'ARIES - Artificial Intelligence Distance Education Support';
    $headerTitle   = 'Actions:';
    $mainContent   = <<<endMain
			<br />
			<div class="lead">
			Welcome to Artificial Intelligence Distance Education Support System.	</div>
			<br />
			<p class="text-justify">
			Please use the links above or to the left to perform administrative tasks,
			as needed. For further support please email to <a href="mailto:sural@uwm.edu">Super Admin</a>
			<br />
<br />
This system has been developed to perform research on UWM. Intelligent systems have the potential to provide continuous and fast support where there are insufficient human resources. In this context, the general purpose of this research is to examine the structure of intelligent support in distance education, to develop an intelligent support system and to investigate the efficiency, effectiveness and attractiveness of intelligent support systems.
			</p>
<br />
<br />
<br />
<br />
<br />
			
endMain;
//  $mainContent = str_replace('[rssOutput]', getRSS(), $mainContent);

?>