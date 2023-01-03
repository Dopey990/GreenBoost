<?php
/**
 * @brief		Template Plugin - Expression
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		18 Feb 2013
 */

namespace IPS\Output\Plugin;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Template Plugin - Expression
 */
class _Expression
{
	/**
	 * @brief	Can be used when compiling CSS
	 */
	public static $canBeUsedInCss = TRUE;
	
	/**
	 * Run the plug-in
	 *
	 * @param	string 		$data	  The initial data from the tag
	 * @param	array		$options    Array of options
	 * @return	string		Code to eval
	 */
	public static function runPlugin( $data, $options )
	{
		if( isset( $options['raw'] ) AND $options['raw'] )
		{
			return \IPS\Theme::expandShortcuts( $data );
		}
		else
		{
			return 'htmlspecialchars( ' . \IPS\Theme::expandShortcuts( $data ) . ', ENT_QUOTES | ENT_DISALLOWED, \'UTF-8\', FALSE )';
		}
	}
}