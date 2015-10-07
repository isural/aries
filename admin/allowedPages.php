<?php
/***************************************
* http://www.irfansural.com
* ARIES (Artificial Intelligence Distance Education Support)
* AUTHOR: Irfan SURAL
* DATE: 1 July 2015
* DETAILS: Contains an array of allowed form variables for the different admin pages,
* along with the proper filters for each variable
***************************************/

  $allowed_pages = array(
    'botpersonality' => array(
      'age' => FILTER_SANITIZE_STRING,
      'baseballteam' => FILTER_SANITIZE_STRING,
      'birthday' => FILTER_SANITIZE_STRING,
      'birthplace' => FILTER_SANITIZE_STRING,
      'botmaster' => FILTER_SANITIZE_STRING,
      'boyfriend' => FILTER_SANITIZE_STRING,
      'build' => FILTER_SANITIZE_STRING,
      'celebrities' => FILTER_SANITIZE_STRING,
      'celebrity' => FILTER_SANITIZE_STRING,
      'class' => FILTER_SANITIZE_STRING,
      'email' => FILTER_SANITIZE_STRING,
      'emotions' => FILTER_SANITIZE_STRING,
      'ethics' => FILTER_SANITIZE_STRING,
      'etype' => FILTER_SANITIZE_STRING,
      'family' => FILTER_SANITIZE_STRING,
      'favoriteactor' => FILTER_SANITIZE_STRING,
      'favoriteactress' => FILTER_SANITIZE_STRING,
      'favoriteartist' => FILTER_SANITIZE_STRING,
      'favoriteauthor' => FILTER_SANITIZE_STRING,
      'favoriteband' => FILTER_SANITIZE_STRING,
      'favoritebook' => FILTER_SANITIZE_STRING,
      'favoritecolor' => FILTER_SANITIZE_STRING,
      'favoritefood' => FILTER_SANITIZE_STRING,
      'favoritemovie' => FILTER_SANITIZE_STRING,
      'favoritemusician' => FILTER_SANITIZE_STRING,
      'favoritesong' => FILTER_SANITIZE_STRING,
      'favoritesport' => FILTER_SANITIZE_STRING,
      'feelings' => FILTER_SANITIZE_STRING,
      'footballteam' => FILTER_SANITIZE_STRING,
      'forfun' => FILTER_SANITIZE_STRING,
      'friend' => FILTER_SANITIZE_STRING,
      'friends' => FILTER_SANITIZE_STRING,
      'gender' => FILTER_SANITIZE_STRING,
      'genus' => FILTER_SANITIZE_STRING,
      'girlfriend' => FILTER_SANITIZE_STRING,
      'hockeyteam' => FILTER_SANITIZE_STRING,
      'kindmusic' => FILTER_SANITIZE_STRING,
      'kingdom' => FILTER_SANITIZE_STRING,
      'language' => FILTER_SANITIZE_STRING,
      'location' => FILTER_SANITIZE_STRING,
      'looklike' => FILTER_SANITIZE_STRING,
      'loves' => FILTER_SANITIZE_STRING,
      'master' => FILTER_SANITIZE_STRING,
      'name' => FILTER_SANITIZE_STRING,
      'nationality' => FILTER_SANITIZE_STRING,
      'order' => FILTER_SANITIZE_STRING,
      'orientation' => FILTER_SANITIZE_STRING,
      'party' => FILTER_SANITIZE_STRING,
      'phylum' => FILTER_SANITIZE_STRING,
      'president' => FILTER_SANITIZE_STRING,
      'question' => FILTER_SANITIZE_STRING,
      'religion' => FILTER_SANITIZE_STRING,
      'sign' => FILTER_SANITIZE_STRING,
      'size' => FILTER_SANITIZE_STRING,
      'species' => FILTER_SANITIZE_STRING,
      'talkabout' => FILTER_SANITIZE_STRING,
      'version' => FILTER_SANITIZE_STRING,
      'vocabulary' => FILTER_SANITIZE_STRING,
      'wear' => FILTER_SANITIZE_STRING,
      'website' => FILTER_SANITIZE_STRING,
      'newEntryName' => array(
        'filter' => FILTER_SANITIZE_STRING,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'newEntryValue' => array(
        'filter' => FILTER_SANITIZE_STRING,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'bot_id' => FILTER_SANITIZE_STRING,
      'func' => FILTER_SANITIZE_STRING,
      'action' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'clear' => array(
      'action' => FILTER_SANITIZE_STRING,
      'clearFile' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'demochat' => array(
      'page' => FILTER_SANITIZE_STRING,
    ),
    'download' => array(
      'type' => FILTER_SANITIZE_STRING,
      'filenames' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'editAiml' => array(
      'page' => FILTER_SANITIZE_STRING,
    ),
    'logs' => array(
      'showing' => FILTER_SANITIZE_STRING,
      'id' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'main' => array(
      'page' => FILTER_SANITIZE_STRING,
    ),
    'members' => array(
      'memberSelect' => FILTER_SANITIZE_STRING,
      'user_name' => FILTER_SANITIZE_STRING,
      'password' => FILTER_SANITIZE_STRING,
      'passwordConfirm' => FILTER_SANITIZE_STRING,
      'id' => FILTER_SANITIZE_STRING,
      'action' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'select_bots' => array(
      'bot_name' => FILTER_SANITIZE_STRING,
      'bot_active' => FILTER_SANITIZE_STRING,
      'format' => FILTER_SANITIZE_STRING,
      'unknown_user' => FILTER_SANITIZE_STRING,
      'debugemail' => FILTER_SANITIZE_STRING,
      'default_aiml_pattern' => FILTER_SANITIZE_STRING,
      'error_response' => FILTER_SANITIZE_STRING,
      'bot_desc' => FILTER_SANITIZE_STRING,
      'bot_parent_id' => FILTER_SANITIZE_STRING,
      'save_state' => FILTER_SANITIZE_STRING,
      'remember_up_to' => FILTER_SANITIZE_STRING,
      'conversation_lines' => FILTER_SANITIZE_STRING,
      'debugmode' => FILTER_SANITIZE_STRING,
      'debugshow' => FILTER_SANITIZE_STRING,
      'bot_id' => FILTER_SANITIZE_STRING,
      'action' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'spellcheck' => array(
      'missspell' => FILTER_SANITIZE_STRING,
      'correction' => FILTER_SANITIZE_STRING,
      'group' => FILTER_SANITIZE_STRING,
      'action' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'srai_lookup' => array(
      'page' => FILTER_SANITIZE_STRING,
    ),
    'teach' => array(
      'topic' => FILTER_SANITIZE_STRING,
      'thatpattern' => FILTER_SANITIZE_STRING,
      'pattern' => FILTER_SANITIZE_STRING,
      'template' => FILTER_SANITIZE_STRING,
      'action' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'upload' => array(
      'clearDB' => FILTER_SANITIZE_STRING,
      'bot_id' => FILTER_SANITIZE_STRING,
      'action' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'wordcensor' => array(
      'word_to_censor' => FILTER_SANITIZE_STRING,
      'replace_with' => FILTER_SANITIZE_STRING,
      'censor_id' => FILTER_SANITIZE_STRING,
      'group' => FILTER_SANITIZE_STRING,
      'action' => FILTER_SANITIZE_STRING,
      'page' => FILTER_SANITIZE_STRING,
    ),
    'login' => array(
      'page' => FILTER_SANITIZE_STRING,
      'user_name' => FILTER_SANITIZE_STRING,
      'pw' => FILTER_SANITIZE_STRING,
    ),
    'logout' => array(
      'page' => FILTER_SANITIZE_STRING,
    ),
    'stats' => array(
      'page' => FILTER_SANITIZE_STRING,
    ),
    'search' => array(
      'page' => FILTER_SANITIZE_STRING,
    ),
  );
