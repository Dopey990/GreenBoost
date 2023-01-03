<?php
/**
 * @brief		Hosting Exception
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		11 Aug 2014
 */

namespace IPS\nexus\Hosting;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Hosting Exception
 */
class _Exception extends \RuntimeException
{
	/**
	 * @brief	Time
	 */
	protected $time;
	
	/**
	 * @brief	Server
	 */
	protected $server;
	
	/**
	 * @brief	Details
	 */
	protected $details;
	
	/**
	 * Constructor
	 *
	 * @param	\IPS\nexus\Hosting\Server	$server		Server
	 * @param	string						$message	Message
	 * @return	void
	 */
	public function __construct( \IPS\nexus\Hosting\Server $server, $message )
	{
		$this->time = new \IPS\DateTime;
		$this->server = $server;
		$this->details = $server->lastCallData;
		
		return parent::__construct( $message );
	} 
	
	/**
	 * Log
	 *
	 * @return	void
	 */
	public function log()
	{		
		\IPS\Db::i()->insert( 'nexus_hosting_errors', array(
			'e_time'	=> $this->time->getTimestamp(),
			'e_server'	=> $this->server->id,
			'e_message'	=> $this->getMessage(),
			'e_extra'	=> json_encode( $this->details )
		) );
		
		\IPS\core\AdminNotification::send( 'nexus', 'HostingError', NULL, TRUE, array( 'server' => $this->server, 'message' => $this->getMessage() ) );
	}
}