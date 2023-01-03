<?php
/**
 * @brief		Member Stats
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Gallery
 * @since		26 Mar 2014
 */

namespace IPS\gallery\modules\admin\stats;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Member Stats
 */
class _member extends \IPS\Dispatcher\Controller
{
	/**
	 * Images
	 *
	 * @return	void
	 */
	protected function images()
	{
		\IPS\Output::i()->redirect( \IPS\Http\Url::internal("app=core&module=members&controller=members&do=view&id=" . \IPS\Request::i()->id . "&blockKey=core_ContentStatistics&block[core_ContentStatistics]=gallery_Gallery") );
	}
}