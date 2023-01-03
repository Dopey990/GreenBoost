<?php
/**
 * @brief		Monitoring Respond
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		07 Aug 2014
 */

\define('REPORT_EXCEPTIONS', TRUE);
require_once '../../../../init.php';
\IPS\Dispatcher\External::i();
try
{
	$server = \IPS\nexus\Hosting\Server::load( \IPS\Request::i()->server );
}
catch ( \OutOfRangeException $e )
{
	\IPS\Output::i()->error( 'node_error', '2X230/1', 404, '' );
}

if ( !$server->monitor_fails )
{
	\IPS\Output::i()->error( 'monitor_respond_err_online', '1X230/2', 403, '' );
}

if ( \IPS\Request::i()->a === 'a' )
{
	if ( $server->monitor_acknowledged )
	{
		\IPS\Output::i()->error( 'monitor_respond_err_acknowledged', '1X230/3', 403, '' );
	}
	else
	{
		$server->monitor_acknowledged = TRUE;
		$server->save();
		$server->monitoringAcknowledged( \IPS\Request::i()->by );
		
		\IPS\Output::i()->sendOutput( \IPS\Theme::i()->getTemplate( 'global', 'core', 'front' )->blankTemplate( \IPS\Theme::i()->getTemplate( 'hosting', 'nexus', 'front' )->monitorRespond( 'monitor_respond_acknowleged' ) ), 200, 'text/html' );

	}
}
elseif ( \IPS\Request::i()->a === 'r' )
{
	$server->monitor_fails = 0;
	$server->monitor_acknowledged = 0;
	$server->save();
	$server->monitoringReset( \IPS\Request::i()->by );
	
	\IPS\Output::i()->sendOutput( \IPS\Theme::i()->getTemplate( 'global', 'core', 'front' )->blankTemplate( \IPS\Theme::i()->getTemplate( 'hosting', 'nexus', 'front' )->monitorRespond( 'monitor_respond_reset' ) ), 200, 'text/html' );
}