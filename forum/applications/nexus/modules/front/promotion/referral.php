<?php
/**
 * @brief		Incoming Referrals
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		15 Aug 2014
 */

namespace IPS\nexus\modules\front\promotion;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Incoming Referrals
 */
class _referral extends \IPS\Dispatcher\Controller
{
	/**
	 * Handle Referral
	 *
	 * @return	void
	 */
	protected function manage()
	{		
		\IPS\Request::i()->setCookie( 'referred_by', \intval( \IPS\Request::i()->id ), \IPS\DateTime::create()->add( new \DateInterval( 'P1Y' ) ) );
		
		try
		{
			$target = \IPS\Request::i()->direct ? \IPS\Http\Url::createFromString( base64_decode( \IPS\Request::i()->direct ) ) : \IPS\Http\Url::baseUrl();
		}
		catch( \IPS\Http\Url\Exception $e )
		{
			$target = NULL;
		}

		if ( $target instanceof \IPS\Http\Url\Internal )
		{
			\IPS\Output::i()->redirect( $target );
		}
		else
		{
			\IPS\Output::i()->redirect( \IPS\Settings::i()->base_url );
		}
	}
}