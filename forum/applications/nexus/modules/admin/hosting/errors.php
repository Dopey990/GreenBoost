<?php
/**
 * @brief		Hosting Server Errors
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		12 Aug 2014
 */

namespace IPS\nexus\modules\admin\hosting;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Hosting Server Errors
 */
class _errors extends \IPS\Dispatcher\Controller
{	
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'errors_manage' );
		parent::execute();
	}
	
	/**
	 * Table
	 *
	 * @param	\IPS\Http\Url	$url	The URL the table will be shown on
	 * @return	void
	 */
	public static function table( $url )
	{
		/* Create the table */
		$table = new \IPS\Helpers\Table\Db( 'nexus_hosting_errors', $url );
		$table->include = array( 'e_time', 'e_server', 'e_account', 'e_function', 'e_message' );
		$table->langPrefix = 'hosting_';
		$table->rowClasses = array( 'e_message' => array( 'ipsTable_wrap' ) );
		$table->mainColumn = 'e_account';
		if ( !$table->sortBy )
		{
			$table->sortBy = 'e_time';
			$table->sortDirection = 'asc';
		}
		
		/* Parsers */
		$table->parsers = array(
			'e_time'	=> function( $val )
			{
				return \IPS\DateTime::ts( $val );
			},
			'e_server'	=> function( $val )
			{
				try
				{
					return \IPS\nexus\Hosting\Server::load( $val )->hostname;
				}
				catch ( \OutOfRangeException $e )
				{
					return '';
				}
			},
			'e_function'	=> function ( $val, $row )
			{
				$details = json_decode( $row['e_extra'], TRUE );
				if ( isset( $details['function'] ) )
				{
					return $details['function'];
				}
				return '';
			},
			'e_account'	=> function ( $val, $row )
			{
				$details = json_decode( $row['e_extra'], TRUE );
				$username = NULL;
				if ( isset( $details['params']['user'] ) )
				{
					$username = $details['params']['user'];
				}
				if ( isset( $details['params']['username'] ) )
				{
					$username = $details['params']['username'];
				}
				if ( $username )
				{
					return \IPS\Theme::i()->getTemplate('hosting', 'nexus')->accountLink( $username );
				}
				return '';
			},
			'e_message'	=> function( $val )
			{
				if ( \IPS\Member::loggedIn()->language()->checkKeyExists( 'hosting_ex_' . $val ) )
				{
					return \IPS\Member::loggedIn()->language()->addToStack( 'hosting_ex_' . $val );
				}
				else
				{
					return $val;
				}
			}
		);
		
		/* Buttons */
		$table->rowButtons = function( $row )
		{
			return array(
				'view'	=> array(
					'icon'	=> 'search',
					'title'	=> 'view',
					'link'	=> \IPS\Http\Url::internal( 'app=nexus&module=hosting&controller=errors&do=view&id=' . $row['e_id'] ),
					'data'	=> array( 'ipsDialog' => '' )
				),
				'retry'	=> array(
					'icon'	=> 'refresh',
					'title'	=> 'retry',
					'link'	=> \IPS\Http\Url::internal( 'app=nexus&module=hosting&controller=errors&do=retry&id=' . $row['e_id'] )
				),
				'delete'=> array(
					'icon'	=> 'times-circle',
					'title'	=> 'delete',
					'link'	=> \IPS\Http\Url::internal( 'app=nexus&module=hosting&controller=errors&do=delete&id=' . $row['e_id'] ),
					'data'	=> array( 'delete' => '' )
				)
			);
		};
		
		/* Return */
		return $table;
	}
	
	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
		\IPS\Output::i()->title		= \IPS\Member::loggedIn()->language()->addToStack('menu__nexus_hosting_errors');
		\IPS\Output::i()->output	= \IPS\Theme::i()->getTemplate( 'forms', 'core' )->blurb( 'hosting_errors_blurb' ) . (string) static::table( \IPS\Http\Url::internal( 'app=nexus&module=hosting&controller=errors' ) );
	}
	
	/**
	 * View
	 *
	 * @return	void
	 */
	public function view()
	{
		try
		{
			$log = \IPS\Db::i()->select( '*', 'nexus_hosting_errors', array( 'e_id=?', \IPS\Request::i()->id ) )->first();
		}
		catch ( \UnderflowException $e )
		{
			\IPS\Output::i()->error( 'node_error', '2X244/2', 404, '' );
		}
		
		$details = json_decode( $log['e_extra'], TRUE );
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'global', 'core' )->definitionTable( $details['params'], NULL, FALSE );
	}
	
	/**
	 * Retry
	 *
	 * @return	void
	 */
	public function retry()
	{
		try
		{
			$log = \IPS\Db::i()->select( '*', 'nexus_hosting_errors', array( 'e_id=?', \IPS\Request::i()->id ) )->first();
		}
		catch ( \UnderflowException $e )
		{
			\IPS\Output::i()->error( 'node_error', '2X244/3', 404, '' );
		}
		
		try
		{
			\IPS\nexus\Hosting\Server::load( $log['e_server'] )->retryError( json_decode( $log['e_extra'], TRUE ) );
			\IPS\Db::i()->delete( 'nexus_hosting_errors', array( 'e_id=?', \IPS\Request::i()->id ) );
			\IPS\Output::i()->redirect( \IPS\Http\Url::internal('app=nexus&module=hosting&controller=errors'), 'hosrting_error_retry_done' );
		}
		catch ( \UnderflowException $e )
		{
			\IPS\Output::i()->error( $e->getMessage(), '3X244/4', 500, '' );
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			$message = $e->getMessage();
			if ( \IPS\Member::loggedIn()->language()->checkKeyExists( 'hosting_ex_' . $message ) )
			{
				$message = \IPS\Member::loggedIn()->language()->addToStack( 'hosting_ex_' . $message );
			}
			
			\IPS\Output::i()->error( $message, '1X244/1', 503, '' );
		}
	}
	
	/**
	 * Delete
	 *
	 * @return	void
	 */
	public function delete()
	{
		/* Make sure the user confirmed the deletion */
		\IPS\Request::i()->confirmedDelete();
		
		\IPS\Db::i()->delete( 'nexus_hosting_errors', array( 'e_id=?', \IPS\Request::i()->id ) );
		\IPS\Output::i()->redirect( \IPS\Http\Url::internal('app=nexus&module=hosting&controller=errors'), 'deleted' );
	}
}