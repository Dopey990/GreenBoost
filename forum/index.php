<?php
/**
 * @brief		Public bootstrap
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		18 Feb 2013
 */ 
\define('REPORT_EXCEPTIONS', TRUE);
$_SERVER['SCRIPT_FILENAME']	= __FILE__;
require_once 'init.php';
\IPS\Dispatcher\Front::i()->run();