<?php
/**
 * @brief		rss
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		13 Oct 2016
 */

namespace IPS\core\modules\admin\discovery;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * rss
 */
class _rss extends \IPS\Node\Controller
{
	/**
	 * Node Class
	 */
	protected $nodeClass = '\IPS\core\Rss';
	
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'rss_export_manage' );
		parent::execute();
	}
	
	/**
	 * Manage
	 *
	 * @return	void
	 */
	public function manage()
	{
		\IPS\Output::i()->output .= \IPS\Theme::i()->getTemplate( 'forms', 'core' )->blurb( \IPS\Member::loggedIn()->language()->addToStack( 'rss_feed_blurb' ) );
		
		parent::manage();
	}
}