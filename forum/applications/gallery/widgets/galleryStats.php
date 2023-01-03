<?php
/**
 * @brief		Gallery statistics widget
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Gallery
 * @since		25 Mar 2014
 */

namespace IPS\gallery\widgets;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Gallery statistics widget
 */
class _galleryStats extends \IPS\Widget\PermissionCache
{
	/**
	 * @brief	Widget Key
	 */
	public $key = 'galleryStats';
	
	/**
	 * @brief	App
	 */
	public $app = 'gallery';
		
	/**
	 * @brief	Plugin
	 */
	public $plugin = '';
	
	/**
	 * Initialize widget
	 *
	 * @return	null
	 */
	public function init()
	{
		\IPS\Output::i()->cssFiles = array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'widgets.css', 'gallery', 'front' ) );

		parent::init();
	}

	/**
	 * Render a widget
	 *
	 * @return	string
	 */
	public function render()
	{
		$stats					= \IPS\Db::i()->select( 'COUNT(*) AS totalImages, SUM(image_comments) AS totalComments', 'gallery_images', array( "image_approved=?", 1 ) )->first();
		$stats['totalAlbums']	= \IPS\Db::i()->select( 'COUNT(*)', 'gallery_albums' )->first();
		
		$latestImage = NULL;
		foreach ( \IPS\gallery\Image::getItemsWithPermission( array(), NULL, 1, 'read', \IPS\Content\Hideable::FILTER_PUBLIC_ONLY ) as $latestImage )
		{
			break;
		}
		
		return $this->output( $stats, $latestImage );
	}
}