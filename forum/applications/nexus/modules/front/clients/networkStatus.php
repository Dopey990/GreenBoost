<?php
/**
 * @brief		Network Status
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		11 Sep 2014
 */

namespace IPS\nexus\modules\front\clients;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Network Status
 */
class _networkStatus extends \IPS\Dispatcher\Controller
{
	/**
	 * View
	 *
	 * @return	void
	 */
	protected function manage()
	{
		if ( !\IPS\Settings::i()->network_status )
		{
			\IPS\Output::i()->error( 'no_module_permission', '2X246/1', 403, '' );
		}
				
		if ( \IPS\Settings::i()->network_status == 2 )
		{
			$servers = \IPS\nexus\Hosting\Server::roots( NULL, NULL, array( 'server_monitor<>?', '' ) );
		}
		else
		{
			$servers = \IPS\nexus\Hosting\Server::roots( NULL, NULL, array( 'server_monitor<>? AND server_monitor_fails>0', '' ) );
		}
		
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('network_status');
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('clients')->networkStatus( $servers );
		
		unset( \IPS\Output::i()->breadcrumb['module'] );
		\IPS\Output::i()->breadcrumb[] = array( NULL, \IPS\Member::loggedIn()->language()->addToStack('network_status') );
	}
}