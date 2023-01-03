<?php
/**
 * @brief		Notification Options
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		11 Dec 2014
 */

namespace IPS\downloads\extensions\core\Notifications;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Notification Options
 */
class _Files
{
	/**
	 * Get configuration
	 *
	 * @param	\IPS\Member	$member	The member
	 * @return	array
	 */
	public function getConfiguration( $member )
	{		
		return array(
			'new_file_version'	=> array( 'default' => array( 'email' ), 'disabled' => array() ),
		);
	}
	
	/**
	 * Parse notification: new_content
	 *
	 * @param	\IPS\Notification\Inline	$notification	The notification
	 * @return	array
	 * @code
	 	return array(
	 		'title'		=> "Mark has replied to A Topic",			// The notification title
	 		'url'		=> \IPS\Http\Url\Friendly::internal( ... ),	// The URL the notification should link to
	 		'content'	=> "Lorem ipsum dolar sit",					// [Optional] Any appropriate content. Do not format this like an email where the text
	 																// explains what the notification is about - just include any appropriate content.
	 																// For example, if the notification is about a post, set this as the body of the post.
	 		'author'	=>  \IPS\Member::load( 1 ),					// [Optional] The user whose photo should be displayed for this notification
	 	);
	 * @endcode
	 */
	public function parse_new_file_version( $notification )
	{
		$item = $notification->item;
		if ( !$item )
		{
			throw new \OutOfRangeException;
		}

		return array(
			'title'		=> \IPS\Member::loggedIn()->language()->addToStack( 'notification__new_file_version', FALSE, array( 'sprintf' => array( $item->author()->name, $item->mapped('title') ) ) ),
			'url'		=> $notification->item->url(),
			'content'	=> $notification->item->content(),
			'author'	=> $notification->extra ?: $notification->item->author(),
			'unread'	=> (bool) ( $item->unread() )
		);
	}
}