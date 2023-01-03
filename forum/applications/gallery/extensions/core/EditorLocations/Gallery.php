<?php
/**
 * @brief		Editor Extension: Image comments
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Gallery
 * @since		13 Mar 2014
 */

namespace IPS\gallery\extensions\core\EditorLocations;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Editor Extension: Image comments
 */
class _Gallery extends \IPS\gallery\extensions\core\EditorLocations\Images
{
}