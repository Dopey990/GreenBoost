<?php
/**
 * @brief		Member Sync
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		26 Dec 2013
 */

namespace IPS\downloads\extensions\core\MemberSync;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Member Sync
 */
class _Downloads
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
		\IPS\Db::i()->update( 'downloads_downloads', array( 'dmid' => $member->member_id ), array( 'dmid=?', $member2->member_id ) );
		\IPS\Db::i()->update( 'downloads_files', array( 'file_approver' => $member->member_id ), array( 'file_approver=?', $member2->member_id ) );
		\IPS\Db::i()->delete( 'downloads_sessions', array( 'dsess_mid=?', $member2->member_id ) );
	}
	
	/**
	 * Member is deleted
	 *
	 * @param	$member	\IPS\Member	The member
	 * @return	void
	 */
	public function onDelete( $member )
	{
		\IPS\Db::i()->delete( 'downloads_downloads', array( 'dmid=?', $member->member_id ) );
		\IPS\Db::i()->delete( 'downloads_sessions', array( 'dsess_mid=?', $member->member_id ) );
		\IPS\Db::i()->update( 'downloads_files', array( 'file_approver' => 0 ), array( 'file_approver=?', $member->member_id ) );
	}
}