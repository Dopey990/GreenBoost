<?php
/**
 * @brief		Settings
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Gallery
 * @since		04 Mar 2014
 */

namespace IPS\gallery\modules\admin\gallery;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * settings
 */
class _settings extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'settings_manage' );
		parent::execute();
	}

	/**
	 * Manage settings
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$form = new \IPS\Helpers\Form;

		\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'admin_settings.js', 'gallery', 'admin' ) );
		$form->attributes['data-controller'] = 'gallery.admin.settings.settings';
		$form->hiddenValues['rebuildWatermarkScreenshots'] = \IPS\Request::i()->rebuildWatermarkScreenshots ?: 0;

		$form->addHeader( 'gallery_images' );
		$form->addMessage( \IPS\Member::loggedIn()->language()->addToStack('gallery_dims_explanation'), '', FALSE );
		$large	= ( isset( \IPS\Settings::i()->gallery_large_dims ) ) ? explode( 'x', \IPS\Settings::i()->gallery_large_dims ) : array( 1600, 1200 );
		$small	= ( isset( \IPS\Settings::i()->gallery_small_dims ) ) ? explode( 'x', \IPS\Settings::i()->gallery_small_dims ) : array( 240, 240 );
		$form->add( new \IPS\Helpers\Form\WidthHeight( 'gallery_large_dims', $large, TRUE, array( 'resizableDiv' => FALSE ) ) );
		$form->add( new \IPS\Helpers\Form\WidthHeight( 'gallery_small_dims', $small, TRUE, array( 'resizableDiv' => FALSE ) ) );
		$form->add( new \IPS\Helpers\Form\YesNo( 'gallery_use_square_thumbnails', \IPS\Settings::i()->gallery_use_square_thumbnails ) );
		$form->add( new \IPS\Helpers\Form\YesNo( 'gallery_use_watermarks', \IPS\Settings::i()->gallery_use_watermarks, FALSE, array( 'togglesOn' => array( 'gallery_watermark_path', 'gallery_watermark_images' ) ) ) );
		$form->add( new \IPS\Helpers\Form\Upload( 'gallery_watermark_path', \IPS\Settings::i()->gallery_watermark_path ? \IPS\File::get( 'core_Theme', \IPS\Settings::i()->gallery_watermark_path ) : NULL, FALSE, array( 'image' => TRUE, 'storageExtension' => 'core_Theme' ), NULL, NULL, NULL, 'gallery_watermark_path' ) );
		$form->add( new \IPS\Helpers\Form\CheckboxSet( 'gallery_watermark_images',
			\IPS\Settings::i()->gallery_watermark_images ? explode( ',', \IPS\Settings::i()->gallery_watermark_images ) : array(),
			FALSE,
			array(
				'multiple'			=> TRUE,
				'options'			=> array( 'large' => 'gallery_watermark_large', 'small' => 'gallery_watermark_small' ),
			),
			NULL,
			NULL,
			NULL,
			'gallery_watermark_images'
		) );

		$form->addHeader( 'gallery_bandwidth' );
		$form->add( new \IPS\Helpers\Form\YesNo( 'gallery_detailed_bandwidth', \IPS\Settings::i()->gallery_detailed_bandwidth ) );
		$form->add( new \IPS\Helpers\Form\Number( 'gallery_bandwidth_period', \IPS\Settings::i()->gallery_bandwidth_period, FALSE, array( 'unlimited' => -1 ) ) );

		$form->addHeader( 'gallery_options' );
		$form->add( new \IPS\Helpers\Form\YesNo( 'gallery_rss_enabled', \IPS\Settings::i()->gallery_rss_enabled ) );

		if( \IPS\GeoLocation::enabled() )
		{
			$form->add( new \IPS\Helpers\Form\YesNo( 'gallery_maps_default', \IPS\Settings::i()->gallery_maps_default ) );
		}

		if ( $values = $form->values() )
		{
			$form->saveAsSettings( array( 
				'gallery_large_dims'			=> implode( 'x', $values['gallery_large_dims'] ), 
				'gallery_small_dims'			=> implode( 'x', $values['gallery_small_dims'] ),
				'gallery_use_square_thumbnails'	=> $values['gallery_use_square_thumbnails'],
				'gallery_watermark_path'		=> (string)  $values['gallery_watermark_path'],
				'gallery_detailed_bandwidth'	=> $values['gallery_detailed_bandwidth'],
				'gallery_bandwidth_period'		=> $values['gallery_bandwidth_period'],
				'gallery_rss_enabled'			=> $values['gallery_rss_enabled'],
				'gallery_watermark_images'		=> implode( ',', $values['gallery_watermark_images'] ),
				'gallery_use_watermarks'		=> $values['gallery_use_watermarks'],
			) );
			\IPS\Session::i()->log( 'acplogs__gallery_settings' );

			if( $values['rebuildWatermarkScreenshots'] )
			{
				\IPS\Db::i()->delete( 'core_queue', array( '`app`=? OR `key`=?', 'gallery', 'RebuildGalleryImages' ) );
				\IPS\Task::queue( 'gallery', 'RebuildGalleryImages', array( ), 2 );
			}
		}
		
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('settings');
		\IPS\Output::i()->output = $form;
	}
}