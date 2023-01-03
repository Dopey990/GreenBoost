<?php
/**
 * @brief		Archive Exception Class
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		28 Jul 2015
 */

namespace IPS\Archive;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Archive Exception Class
 */
class _Exception extends \RuntimeException
{
	/**
	 * @brief	Could not open archive
	 */
	const COULD_NOT_OPEN = 1;

	/**
	 * @brief	Could not write to archive
	 */
	const COULD_NOT_WRITE = 2;
}