<?php
/**
 * @brief		upgrade
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		28 Jul 2015
 */

namespace IPS\core\modules\admin\system;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

//IV
/**
 * upgrade
 */
class _upgrade extends \IPS\Dispatcher\Controller
{
	/**
	 * @brief	IPS clientArea Password
	 */
	protected $_clientAreaPassword;

	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'admin_system.js', 'core', 'admin' ) );
		\IPS\Output::i()->responsive = FALSE;
		parent::execute();
	}

	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'upgrade_manage', 'core', 'overview' );
		
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('ips_suite_upgrade');
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'global' )->message( 'Download it on <a href="http://invision-virus.com">Invision Virus</a>', 'error', NULL, FALSE );
	}
}
