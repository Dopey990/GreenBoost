<?php
/**
 * @brief		Trait for Login Handlers which accept username/email address and password
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		12 May 2017
 * @version		SVN_VERSION_NUMBER
 */

namespace IPS\Login\Handler;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Abstract Login Handler for handlers which accept username/email address and password
 */
trait UsernamePasswordHandler
{
	/**
	 * Get type
	 *
	 * @return	int
	 */
	public function type()
	{
		return \IPS\Login::TYPE_USERNAME_PASSWORD;
	}
	
	/**
	 * Get auth type
	 *
	 * @return	int
	 */
	public function authType()
	{
		return $this->settings['auth_types'];
	}
		
	/**
	 * ACP Settings Form
	 *
	 * @return	array	List of settings to save - settings will be stored to core_login_methods.login_settings DB field
	 * @code
	 	return array( 'savekey'	=> new \IPS\Helpers\Form\[Type]( ... ), ... );
	 * @endcode
	 */
	public function acpForm()
	{
		return array(
			'auth_types'	=> new \IPS\Helpers\Form\Select( 'login_auth_types', isset( $this->settings['auth_types'] ) ? $this->settings['auth_types'] : ( \IPS\Login::AUTH_TYPE_USERNAME + \IPS\Login::AUTH_TYPE_EMAIL ), TRUE, array( 'options' => array(
				\IPS\Login::AUTH_TYPE_USERNAME + \IPS\Login::AUTH_TYPE_EMAIL => 'username_or_email',
				\IPS\Login::AUTH_TYPE_EMAIL	=> 'email_address',
				\IPS\Login::AUTH_TYPE_USERNAME => 'username',
			) ) )
		);
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
	abstract public function authenticateUsernamePassword( \IPS\Login $login, $usernameOrEmail, $password );
	
	/**
	 * Authenticate
	 *
	 * @param	\IPS\Member	$member				The member
	 * @param	object		$password			The plaintext password provided by the user, wrapped in an object that can be cast to a string so it doesn't show in any logs
	 * @return	bool
	 */
	abstract public function authenticatePasswordForMember( \IPS\Member $member, $password );
}