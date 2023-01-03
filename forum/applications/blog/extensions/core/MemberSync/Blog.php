<?php
/**
 * @brief		Member Sync
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Blog
 * @since		20 Mar 2014
 */

namespace IPS\blog\extensions\core\MemberSync;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Member Sync
 */
class _Blog
{
	/**
	 * Member is merged with another member
	 *
	 * @param	\IPS\Member	$member		Member being kept
	 * @param	\IPS\Member	$member2	Member being removed
	 * @return	void
	 */
	public function onMerge( $member, $member2 )
	{
		\IPS\Db::i()->update( 'blog_blogs', array( 'blog_member_id' => $member->member_id ), array( 'blog_member_id=?', $member2->member_id ) );
		\IPS\Db::i()->update( 'blog_rss_import', array( 'rss_member' => $member->member_id ), array( 'rss_member=?', $member2->member_id ) );

		foreach( \IPS\blog\Blog::loadByOwner( $member2 ) as $blog )
		{
			$blog->member_id	= $member->member_id;
			$blog->save();
		}
	}
	
	/**
	 * Member is deleted
	 *
	 * @param	$member	\IPS\Member	The member
	 * @return	void
	 */
	public function onDelete( $member )
	{
		\IPS\Db::i()->update( 'blog_rss_import', array( 'rss_member' => 0 ), array( 'rss_member=?', $member->member_id ) );

		foreach( \IPS\blog\Blog::loadByOwner( $member ) as $blog )
		{
			/* We only want to do this if the blog is owned by the member - loadByOwner() also returns Blogs that are assigned to member groups */
			if ( !$blog->groupblog_ids )
			{
				/* Delete blog entries & blog */
				\IPS\Task::queue( 'core', 'DeleteOrMoveContent', array( 'class' => 'IPS\blog\Blog', 'id' => $blog->id, 'deleteWhenDone' => TRUE ) );
			}
		}
	}
}