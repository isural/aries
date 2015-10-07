<?php

  /***************************************
  * http://www.irfansural.com
  * ARIES (Artificial Intelligence Distance Education Support)
  * AUTHOR: Irfan SURAL
  * DATE: 1 July 2015
  * DETAILS: Starting Point
  ***************************************/

  if (!file_exists('config/global_config.php'))
  {
  # No config exists we will run install
    header('location: install/install.php');
  }
  else
  {
    $get_vars = filter_input_array(INPUT_GET);
    $qs = '';
    if (!empty($get_vars))
    {
      $qs = '?';
      foreach ($get_vars as $key => $value)
      {
        $qs .= "$key=$value&";
      }
      $qs = rtrim($qs, '&');
    }
    # Config exists we will goto the bot
    $thisFile = __FILE__;
    require_once('config/global_config.php');
    /** @noinspection PhpUndefinedVariableInspection */
    $format = (isset($get_vars['format'])) ? $get_vars['format'] : $format;
    $format = strtoupper($format);
    switch ($format)
    {
      case 'JSON':
      $gui = 'jquery';
      break;
      case 'XML':
      $gui= 'xml';
      break;
      default:
      $gui = 'plain';
    }
    if (!defined('SCRIPT_INSTALLED')) header('location: ' . _INSTALL_URL_ . 'install.php');
   // else header("location: gui/$gui/$qs");
      else header("location: /aries/home/");
  }

?>
