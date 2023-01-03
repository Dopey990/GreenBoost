<?php
/**
 * @brief		API bootstrap
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		18 Feb 2013
 */

require_once '../init.php';
\IPS\Dispatcher\Api::i()->run();