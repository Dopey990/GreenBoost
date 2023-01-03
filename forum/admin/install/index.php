<?php
/**
 * @brief		Installer bootstrap
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		2 Apr 2013
 */

\define('READ_WRITE_SEPARATION', FALSE);
\define('REPORT_EXCEPTIONS', TRUE);
require_once '../../init.php';
\IPS\Dispatcher\Setup::i()->setLocation('install')->run();