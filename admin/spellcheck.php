<?php
/***************************************
* http://www.irfansural.com
* ARIES (Artificial Intelligence Distance Education Support)
* AUTHOR: Irfan SURAL
* DATE: 1 July 2015
* DETAILS: Displays the admin page for the spellcheck plugin and provides access to various features
***************************************/
  $msg = '';
  $upperScripts ="";
  $post_vars = filter_input_array(INPUT_POST);
  $get_vars = filter_input_array(INPUT_GET);

  $group = (isset($get_vars['group'])) ? $get_vars['group'] : 1;
  $content  = $template->getSection('SearchSpellForm');
  $sc_action = isset($_REQUEST['action']) ? strtolower($_REQUEST['action']) : '';
  $sc_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : -1;
  if (!empty($sc_action)) {
    switch($sc_action) {
      case 'search':
        $content .= runSpellSearch();
        $content .= spellCheckForm();
        break;
      case 'update':
        $x = updateSpell();
        $content .= spellCheckForm();
        break;
      case 'delete':
        $content .= ($sc_id >= 0) ? delSpell($sc_id) . spellCheckForm() : spellCheckForm();
        break;
      case 'edit':
        $content .= ($sc_id >= 0) ? editSpellForm($sc_id) : spellCheckForm();
        break;
      case 'add':
        $x = insertSpell();
        $content .= spellCheckForm();
        break;
      default:
        $content .= spellCheckForm();
    }
  }
  else {
    $content .= spellCheckForm();
  }
  $content = str_replace('[group]', $group, $content);
  $sc_enabled = (USE_SPELL_CHECKER) ? 'enabled' : 'disabled';

    $topNav        = $template->getSection('TopNav');
    $leftNav       = $template->getSection('LeftNav');
    $main          = $template->getSection('Main');
    
    $navHeader     = $template->getSection('NavHeader');
    $rightNav      = $template->getSection('RightNav');
    
    $rightNavLinks = getMisspelledWords();
    $FooterInfo    = getFooter();
    $errMsgClass   = (!empty($msg)) ? "ShowError" : "HideError";
    $errMsgStyle   = $template->getSection($errMsgClass);
    $noLeftNav     = '';
    $noTopNav      = '';
    $noRightNav    = '';
    $headerTitle   = 'Actions:';
    $pageTitle     = 'ARIES - Spellcheck Editor';
    $mainContent   = $content;
    $mainTitle     = 'Spellcheck Editor';
    
    $mainContent = str_replace('[spellCheckForm]', spellCheckForm(), $mainContent);
    $mainContent = str_replace('[sc_enabled]', $sc_enabled, $mainContent);
    $rightNav    = str_replace('[rightNavLinks]', $rightNavLinks, $rightNav);
    $rightNav    = str_replace('[navHeader]', $navHeader, $rightNav);
    $rightNav    = str_replace('[headerTitle]', scPaginate(), $rightNav);
  
    $Spell_Main=$template->getSection('SpellCheckMain');
    $Spell_Main = str_replace('[Spell_Left]', $mainContent, $Spell_Main);
	$Spell_Main = str_replace('[Spell_Right]', $rightNav, $Spell_Main);
    $mainContent= $Spell_Main;  
  if (!$msg==""){
	  $msg='
      <div class="alert alert-success alert-dismissable">
        <i class="fa fa-info"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	     &nbsp; '.$msg.'   
       </div>
  ';
	  }

  /**
   * Function scPaginate
   *
   *
   * @return string
   */
  function scPaginate()
  {
    global $dbConn, $get_vars;
    $sql = "select count(*) from `spellcheck` where 1";
    $row = db_fetch($sql, null, __FILE__, __FUNCTION__, __LINE__);
    $rowCount = $row['count(*)'];
    $lastPage = intval($rowCount / 50);
    $remainder = ($rowCount / 50) - $lastPage;
    if ($remainder > 0) $lastPage++;
    $out = "Missspelled Words<br />\n50 words per page:<br />\n";
    $link=" - <a class=\"paginate\" href=\"index.php?page=spellcheck&amp;group=[group]\">[label]</a>";
    $curStart = (isset($get_vars['group'])) ? $get_vars['group'] : 1;
    $firstPage = 1;
    $prev  = ($curStart > ($firstPage + 1)) ? $curStart - 1 : -1;
    $next = ($lastPage > ($curStart + 1)) ? $curStart + 1 : -1;
    $firstLink = ($firstPage != $curStart) ? str_replace('[group]', '1', $link) : '';
    $prevLink = ($prev > 0) ? str_replace('[group]', $prev, $link) : '';
    $curLink = "- $curStart ";
    if (empty($firstLink) and empty($prevLink)) $curLink = $curStart;
    $nextLink = ($next > 0) ? str_replace('[group]', $next, $link) : '';
    $lastLink = ($lastPage > $curStart) ? str_replace('[group]', $lastPage, $link) : '';
    $firstLink = str_replace('[label]', 'first', $firstLink);
    $prevLink = str_replace('[label]', '&lt;&lt;', $prevLink);
    $nextLink = str_replace('[label]', '&gt;&gt;', $nextLink);
    $lastLink = str_replace('[label]', 'last', $lastLink);
    $out .= ltrim("$firstLink\n$prevLink\n$curLink\n$nextLink\n$lastLink", " - ");
    return $out;
  }

  /**
   * Function getMisspelledWords
   *
   *
   * @return string
   */
  function getMisspelledWords() {
    global $dbConn, $template, $get_vars;
    # pagination variables
    $group = (isset($get_vars['group'])) ? $get_vars['group'] : 1;
    $_SESSION['poadmin']['group'] = $group;
    $startEntry = ($group - 1) * 50;
    $end = $group + 50;
    $_SESSION['poadmin']['page_start'] = $group;
    
    $curID = (isset($get_vars['id'])) ? $get_vars['id'] : -1;
    $sql = "select `id`,`missspelling` from `spellcheck` where 1 order by abs(`id`) asc limit $startEntry, 50;";
    $baseLink = $template->getSection('NavLink');
    $links = '      <div class="userlist">' . "\n";
    $result = db_fetchAll($sql, null, __FILE__, __FUNCTION__, __LINE__);
    $count = 0;
    foreach ($result as $row) {
      $linkId = $row['id'];
      $linkClass = ($linkId == $curID) ? 'selected' : 'noClass';
      $missspelling = $row['missspelling'];
      $tmpLink = str_replace('[linkClass]', " class=\"$linkClass\"", $baseLink);
      $linkHref = " href=\"index.php?page=spellcheck&amp;action=edit&amp;id=$linkId&amp;group=$group#$linkId\" name=\"$linkId\"";
      $tmpLink = str_replace('[linkHref]', $linkHref, $tmpLink);
      $tmpLink = str_replace('[linkOnclick]', '', $tmpLink);
      $tmpLink = str_replace('[linkTitle]', " title=\"Edit spelling correction for the word '$missspelling'\"", $tmpLink);
      $tmpLink = str_replace('[linkLabel]', $missspelling, $tmpLink);
      $links .= "$tmpLink\n";
      $count++;
    }
    $page_count = intval($count / 50);
    $_SESSION['poadmin']['page_count'] = $page_count + (($count / 50) > $page_count) ? 1 : 0;
    $links .= "\n      </div>\n";
    return $links;
  }

  /**
   * Function spellCheckForm
   *
   *
   * @return mixed|string
   */
  function spellCheckForm() {
  global $template, $get_vars;
  $out = $template->getSection('SpellcheckForm');
  $group = (isset($get_vars['group'])) ? $get_vars['group'] : 1;
  $out  = str_replace('[group]', $group, $out);
  return $out;
}

  /**
   * Function insertSpell
   *
   *
   * @return string
   */
  function insertSpell() {
    global $dbConn, $template, $msg, $post_vars;
    $correction = trim($post_vars['correction']);
    $missspell = trim($post_vars['missspell']);

    if(($correction == "") || ($missspell == "")) {
        $msg = ' You must enter a spelling mistake and the correction.' . "\n";
    }
    else {
        $sql = "INSERT INTO `spellcheck` VALUES (NULL,'$missspell','$correction')";
        $params = array(
          ':missspell' => $missspell,
          ':correction' => $correction
        );
        $affectedRows = db_write($sql, $params, false, __FILE__, __FUNCTION__, __LINE__);
        if($affectedRows > 0) {
            $msg = ' Correction added.';
        }
        else {
            $msg = ' There was a problem editing the correction - no changes made.';
        }
    }

    return $msg;
}

  /**
   * Function delSpell
   *
   * * @param $id
   * @return void
   */
  function delSpell($id) {
    global $dbConn, $template, $msg;
    
    if($id=="") {
        $msg = 'There was a problem editing the correction - no changes made.';
    }
    else {
        $sql = "DELETE FROM `spellcheck` WHERE `id` = :id LIMIT 1";
        $params = array(':id' => $id);
        $affectedRows = db_write($sql, $params, false, __FILE__, __FUNCTION__, __LINE__);
        if($affectedRows > 0) {
            $msg = 'Correction deleted.';
        }
        else {
            $msg = 'There was a problem editing the correction - no changes made.';
        }
    }
}


  /**
   * Function runSpellSearch
   *
   *
   * @return string
   */
  function runSpellSearch() {
    global $dbConn, $template, $post_vars;
    
    $i=0;
    $search = trim($post_vars['search']);
    $sql = "SELECT * FROM `spellcheck` WHERE `missspelling` LIKE '%$search%' OR `correction` LIKE '%$search%' LIMIT 50";
    $result = db_fetchAll($sql, null, __FILE__, __FUNCTION__, __LINE__);
    $htmltbl = '<table class="table table-striped table-bordered table-hover">
            	  <thead>
                    <tr>
                      <th>Missspelling</th>
                      <th>Correction</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                <tbody>';
    foreach ($result as $row) {
        $i++;
        $misspell = strtoupper($row['missspelling']);
        $correction = strtoupper($row['correction']);
        $id = $row['id'];
        $group = round(($id / 50));
        $action = "<a href=\"index.php?page=spellcheck&amp;action=edit&amp;id=$id&amp;group=$group#$id\"><img src=\"images/edit.png\" border=0 width=\"15\" height=\"15\" alt=\"Edit this entry\" title=\"Edit this entry\" /></a>
                    <a href=\"index.php?page=spellcheck&amp;action=del&amp;id=$id&amp;group=$group#$id\" onclick=\"return confirm('Do you really want to delete this missspelling? You will not be able to undo this!')\";><img src=\"images/del.png\" border=0 width=\"15\" height=\"15\" alt=\"Edit this entry\" title=\"Edit this entry\" /></a>";
        $htmltbl .= "<tr valign=top>
                            <td>$misspell</td>
                            <td>$correction</td>
                            <td align=center>$action</td>
                        </tr>";
    }
    $htmltbl .= "</tbody></table>";

    if($i >= 50) {
        $msg = "Found more than 50 results for '<b>$search</b>', please refine your search further";
    }
    elseif($i == 0) {
        $msg = "Found 0 results for '<b>$search</b>'. You can use the form below to add that entry.";
        $htmltbl="";
    }
    else {
        $msg = "Found $i results for '<b>$search</b>'";
    }
    $htmlresults = "<div class=\"text-info\">$msg</div>".$htmltbl;
    return $htmlresults;
}

  /**
   * Function editSpellForm
   *
   * * @param $id
   * @return mixed|string
   */
  function editSpellForm($id) {
  global $dbConn, $template, $get_vars;
  $group = (isset($get_vars['group'])) ? $get_vars['group'] : 1;
  $form   = $template->getSection('EditSpellForm');
  
  $sql    = "SELECT * FROM `spellcheck` WHERE `id` = '$id' LIMIT 1";
  $row = db_fetch($sql, null, __FILE__, __FUNCTION__, __LINE__);
  $uc_missspelling = (IS_MB_ENABLED) ? mb_strtoupper($row['missspelling']) : strtoupper($row['missspelling']);
  $uc_correction = (IS_MB_ENABLED) ? mb_strtoupper($row['correction']) : strtoupper($row['correction']);
  $form   = str_replace('[id]', $row['id'], $form);
  $form   = str_replace('[missspelling]', $uc_missspelling, $form);
  $form   = str_replace('[correction]', $uc_correction, $form);
  $form   = str_replace('[group]', $group, $form);
  return $form;
}

function updateSpell() {
  global $dbConn, $template, $msg, $post_vars;
  $missspelling = trim($post_vars['missspelling']);
  $correction = trim($post_vars['correction']);
  $id = trim($post_vars['id']);
  if(($id=="")||($missspelling=="")||($correction=="")) {
    $msg = ' There was a problem editing the correction - no changes made.';
  }
  else {
    $sql = "UPDATE `spellcheck` SET `missspelling` = :missspelling,`correction` = :correction WHERE `id` = :id LIMIT 1";
    $params = array(
      ':missspelling' => $missspelling,
      ':correction' => $correction,
      ':id' => $id
    );
    $affectedRows = db_write($sql, $params, false, __FILE__, __FUNCTION__, __LINE__);
    if($affectedRows > 0) {
      $msg = ' Correction edited.';
    }
    else {
      $msg = ' There was a problem editing the correction - no changes made.';
    }
  }
}
