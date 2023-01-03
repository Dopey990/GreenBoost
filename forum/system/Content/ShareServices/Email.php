<?php
/**
 * @brief		Email share link
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		11 Sept 2013
 */

namespace IPS\Content\ShareServices;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Email share link
 */
class _Email extends \IPS\Content\ShareServices
{
	/**
	 * Return the HTML code to show the share link
	 *
	 * @return	string
	 */
	public function __toString()
	{		
		return \IPS\Theme::i()->getTemplate( 'sharelinks', 'core' )->email( (string) $this->url->setQueryString( array( 'do' => 'email' ) ) );
	}
}