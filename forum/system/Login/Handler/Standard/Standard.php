<?php
/**
 * @brief		Standard Internal Database Login Handler
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		12 May 2017
 */

namespace IPS\Login\Handler;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Standard Internal Database Login Handler
 */
class _Standard extends \IPS\Login\Handler
{
	use UsernamePasswordHandler;
	
	/**
	 * Get title
	 *
	 * @return	string
	 */
	public static function getTitle()
	{
		return 'login_handler_Internal';
	}
	
	/**
	 * Authenticate
	 *
	 * @param	\IPS\Login	$login				The login object
	 * @param	string		$usernameOrEmail		The username or email address provided by the user
	 * @param	object		$password			The plaintext password provided by the user, wrapped in an object that can be cast to a string so it doesn't show in any logs
	 * @return	\IPS\Member
	 * @throws	\IPS\Login\Exception
	 */
	public function authenticateUsernamePassword( \IPS\Login $login, $usernameOrEmail, $password )
	{
		/* Get member(s) */
		$where = array();
		$params = array();
		if ( $this->authType() & \IPS\Login::AUTH_TYPE_USERNAME )
		{
			$where[] = 'name=?';
			$params[] = $usernameOrEmail;
			
			if ( $usernameOrEmail !== \IPS\Request::legacyEscape( $usernameOrEmail ) )
			{
				$where[] = 'name=?';
				$params[] = \IPS\Request::legacyEscape( $usernameOrEmail );
			}
		}
		if ( $this->authType() & \IPS\Login::AUTH_TYPE_EMAIL )
		{
			$where[] = 'email=?';
			$params[] = $usernameOrEmail;
			
			if ( $usernameOrEmail !== \IPS\Request::legacyEscape( $usernameOrEmail ) )
			{
				$where[] = 'email=?';
				$params[] = \IPS\Request::legacyEscape( $usernameOrEmail );
			}
		}
		$members = new \IPS\Patterns\ActiveRecordIterator( \IPS\Db::i()->select( '*', 'core_members', array_merge( array( implode( ' OR ', $where ) ), $params ) ), 'IPS\Member' );
		
		/* If we didn't match any, throw an exception */
		if ( !\count( $members ) )
		{
			$type = 'username_or_email';
			switch ( $this->authType() )
			{
				case \IPS\Login::AUTH_TYPE_USERNAME + \IPS\Login::AUTH_TYPE_EMAIL:
					$type = 'username_or_email';
					break;
					
				case \IPS\Login::AUTH_TYPE_USERNAME:
					$type = 'username';
					break;
					
				case \IPS\Login::AUTH_TYPE_EMAIL:
					$type = 'email_address';
					break;
			}
			throw new \IPS\Login\Exception( \IPS\Member::loggedIn()->language()->addToStack('login_err_no_account', FALSE, array( 'sprintf' => array( \IPS\Member::loggedIn()->language()->addToStack( $type ) ) ) ), \IPS\Login\Exception::NO_ACCOUNT );
		}
		
		/* Check the password for each possible account */
		foreach ( $members as $member )
		{
			if ( $this->authenticatePasswordForMember( $member, $password ) )
			{				
				/* If it's the old style, convert it to the new */
				if ( $member->members_pass_salt )
				{
					$member->setLocalPassword( $password );
					$member->save();
				}
				
				/* Return */
				return $member;
			}
		}

		/* Still here? Throw a password incorrect exception */
		throw new \IPS\Login\Exception( 'login_err_bad_password', \IPS\Login\Exception::BAD_PASSWORD, NULL, $member );
	}
	
	/**
	 * Authenticate
	 *
	 * @param	\IPS\Member	$member		The member
	 * @param	object		$password	The plaintext password provided by the user, wrapped in an object that can be cast to a string so it doesn't show in any logs
	 * @return	bool
	 */
	public function authenticatePasswordForMember( \IPS\Member $member, $password )
	{
		if ( password_verify( $password, $member->members_pass_hash ) === TRUE )
		{
			return TRUE;
		}
		elseif ( $member->members_pass_salt and mb_strlen( $member->members_pass_hash ) === 32 )
		{
			return $member->verifyLegacyPassword( $password );
		}
		
		return FALSE;
	}
	
	/**
	 * Can this handler process a login for a member? 
	 *
	 * @return	bool
	 */
	public function canProcess( \IPS\Member $member )
	{
		return (bool) $member->members_pass_hash;
	}
	
	/**
	 * Can this handler process a password change for a member? 
	 *
	 * @return	bool
	 */
	public function canChangePassword( \IPS\Member $member )
	{
		return $this->canProcess( $member );
	}
	
	/**
	 * Change Password
	 *
	 * @param	\IPS\Member	$member			The member
	 * @param	object		$newPassword		New Password wrapped in an object that can be cast to a string so it doesn't show in any logs
	 * @return	void
	 */
	public function changePassword( \IPS\Member $member, $newPassword )
	{
		$member->setLocalPassword( $newPassword );
		$member->save();
	}
	
	/**
	 * Show in Account Settings?
	 *
	 * @param	\IPS\Member|NULL	$member	The member, or NULL for if it should show generally
	 * @return	bool
	 */
	public function showInUcp( \IPS\Member $member = NULL )
	{
		return FALSE;
	}
}