<?php
/**
 * @brief		Editor Media: Images
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Gallery
 * @since		04 Mar 2014
 */

namespace IPS\gallery\extensions\core\EditorMedia;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Editor Media: Images
 */
class _Images
{
	/**
	 * Get Counts
	 *
	 * @param	\IPS\Member	$member		The member
	 * @param	string		$postKey	The post key
	 * @param	string|null	$search		The search term (or NULL for all)
	 * @return	array		array( 'Title' => 0 )
	 */
	public function count( $member, $postKey, $search=NULL )
	{		
		$where = array(
			array( "image_member_id=? AND image_approved=?", $member->member_id, 1 ),
		);
		
		if ( $search )
		{
			$where[] = array( "image_caption LIKE ( CONCAT( '%', ?, '%' ) )", $search );
		}
						
		return \IPS\Db::i()->select( 'COUNT(*)', 'gallery_images', $where )->first();
	}
	
	/**
	 * Get Files
	 *
	 * @param	\IPS\Member	$member	The member
	 * @param	string|null	$search	The search term (or NULL for all)
	 * @param	string		$postKey	The post key
	 * @param	int			$page	Page
	 * @param	int			$limit	Number to get
	 * @return	array		array( 'Title' => array( (IPS\File, \IPS\File, ... ), ... )
	 */
	public function get( $member, $search, $postKey, $page, $limit )
	{
		$where = array(
			array( "image_member_id=? AND image_approved=?", $member->member_id, 1 ),
		);
		
		if ( $search )
		{
			$where[] = array( "image_caption LIKE ( CONCAT( '%', ?, '%' ) )", $search );
		}
		
		$return = array();
		foreach ( \IPS\Db::i()->select( '*', 'gallery_images', $where, 'image_date DESC', array( ( $page - 1 ) * $limit, $limit ) ) as $row )
		{
			$image = \IPS\gallery\Image::load( $row['image_id'] );
			$obj = \IPS\File::get( 'gallery_Images', $image->masked_file_name );
			$obj->contextInfo = $image->caption;
			$return[ (string) $image->url() ] = $obj;
		}
		
		return $return;
	}
}