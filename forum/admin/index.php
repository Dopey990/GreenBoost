<?php
/**
 * @brief		Admin CP bootstrap
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		18 Feb 2013
 */

\define('READ_WRITE_SEPARATION', FALSE);
\define('REPORT_EXCEPTIONS', TRUE);
require_once '../init.php';
\IPS\Dispatcher\Admin::i()->run();