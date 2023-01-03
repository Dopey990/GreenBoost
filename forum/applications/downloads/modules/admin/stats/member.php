<?php
/**
 * @brief		Member Stats
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		13 Dec 2013
 */

namespace IPS\downloads\modules\admin\stats;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Member Stats
 *
 * @deprecated
 */
class _member extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Output::i()->redirect( \IPS\Http\Url::internal("app=core&module=members&controller=members&do=view&id=" . \IPS\Request::i()->id . "&blockKey=core_ContentStatistics&block[core_ContentStatistics]=downloads_Downloads") );
	}
}