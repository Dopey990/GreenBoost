<?php
/**
 * @brief		Send/Discard handler for pending support replies
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		25 Apr 2014
 */

\define('REPORT_EXCEPTIONS', TRUE);
require_once '../../../../init.php';
\IPS\Dispatcher\External::i();

try
{
	$reply = \IPS\nexus\Support\Reply::load( \IPS\Request::i()->id );
	if ( !\IPS\Login::compareHashes( md5( $reply->item()->email_key . $reply->date ), (string) \IPS\Request::i()->key ) )
	{
		throw new \OutOfRangeException;
	}
}
catch ( \OutOfRangeException $e )
{
	\IPS\Output::i()->error( 'node_error', '2X206/1', 404, '' );
}

if ( $reply->type !== \IPS\nexus\Support\Reply::REPLY_PENDING )
{
	\IPS\Output::i()->error( 'support_reply_not_pending', '1X206/2', 403, '' );
}

if (  \IPS\Request::i()->send )
{
	$reply->sendPending();
}
else
{
	$reply->delete();
}

\IPS\Output::i()->sendOutput( \IPS\Theme::i()->getTemplate( 'global', 'core', 'front' )->blankTemplate( \IPS\Theme::i()->getTemplate( 'support', 'nexus', 'front' )->pendingDone( \IPS\Request::i()->send ? 'support_pending_sent' : 'support_pending_discarded' ) ) );