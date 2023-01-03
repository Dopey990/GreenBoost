<?php
/**
 * @brief		Upgrader: Custom Post Upgrade Message
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		28 Nov 2016
 */

$path = \IPS\ROOT_PATH . '/applications/downloads/extensions/core/ContentRouter/downloads.php';

/* Windows is case-insensitive, so we don't need to check in that case (no pun intended) */
if( \file_exists( $path ) AND !\unlink( $path ) )
{
	$message = \IPS\Theme::i()->getTemplate( 'global' )->block( NULL, "The following file could not be deleted automatically and should be manually removed:<br><pre>{$path}</pre>" );
}

