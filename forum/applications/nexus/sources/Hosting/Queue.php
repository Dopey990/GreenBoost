<?php
/**
 * @brief		Hosting Queue Model
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		6 Aug 2014
 */

namespace IPS\nexus\Hosting;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Hosting Queue Model
 */
class _Queue extends \IPS\Node\Model
{
	/**
	 * @brief	[ActiveRecord] Multiton Store
	 */
	protected static $multitons;
	
	/**
	 * @brief	[ActiveRecord] Database Table
	 */
	public static $databaseTable = 'nexus_hosting_queues';
	
	/**
	 * @brief	[ActiveRecord] Database Prefix
	 */
	public static $databasePrefix = 'queue_';
	
	/**
	 * @brief	[Node] Order Database Column
	 */
	public static $databaseColumnOrder = 'name';

	/**
	 * @brief	[Node] Automatically set position for new nodes
	 */
	public static $automaticPositionDetermination = FALSE;
	
	/**
	 * @brief	[Node] Sortable?
	 */
	public static $nodeSortable = FALSE;
			
	/**
	 * @brief	[Node] Node Title
	 */
	public static $nodeTitle = 'menu__nexus_hosting_queues';
			
	/**
	 * @brief	[Node] ACP Restrictions
	 * @code
	 	array(
	 		'app'		=> 'core',				// The application key which holds the restrictrions
	 		'module'	=> 'foo',				// The module key which holds the restrictions
	 		'map'		=> array(				// [Optional] The key for each restriction - can alternatively use "prefix"
	 			'add'			=> 'foo_add',
	 			'edit'			=> 'foo_edit',
	 			'permissions'	=> 'foo_perms',
	 			'delete'		=> 'foo_delete'
	 		),
	 		'all'		=> 'foo_manage',		// [Optional] The key to use for any restriction not provided in the map (only needed if not providing all 4)
	 		'prefix'	=> 'foo_',				// [Optional] Rather than specifying each  key in the map, you can specify a prefix, and it will automatically look for restrictions with the key "[prefix]_add/edit/permissions/delete"
	 * @endcode
	 */
	protected static $restrictions = array(
		'app'		=> 'nexus',
		'module'	=> 'hosting',
		'prefix' 	=> 'queues_'
	);
	
	/**
	 * Get all queues
	 *
	 * @return	\IPS\Patterns\ActiveRecordIterator
	 */
	public static function queues()
	{
		return new \IPS\Patterns\ActiveRecordIterator( \IPS\Db::i()->select( '*', 'nexus_hosting_queues', NULL, 'queue_name' ), 'IPS\nexus\Hosting\Queue' );
	}
	
	/**
	 * [Node] Add/Edit Form
	 *
	 * @param	\IPS\Helpers\Form	$form	The form
	 * @return	void
	 */
	public function form( &$form )
	{
		$form->add( new \IPS\Helpers\Form\Text( 'queue_name', $this->name, TRUE ) );
	}
	
	/**
	 * Get Title
	 *
	 * @return	string
	 */
	public function get__title()
	{
		return $this->name;
	}
	
	/**
	 * Get Active Server
	 *
	 * @return	\IPS\nexus\Hosting\Server
	 * @throws	\UnderflowException
	 */
	public function activeServer()
	{
		$where = array();
		$where[] = array( \IPS\Db::i()->findInSet( 'server_queues', array( $this->id ) ) );
		if ( !\IPS\IN_DEV )
		{
			$where[] = array( 'server_type<>?', 'none' );
		}
		
		$serverLimits = iterator_to_array( \IPS\Db::i()->select( array( 'server_id', 'server_max_accounts' ), 'nexus_hosting_servers', $where )->setKeyField('server_id')->setValueField('server_max_accounts') );
		$serverWithLeastAccounts = NULL;
		foreach ( \IPS\Db::i()->select( 'nexus_hosting_servers.server_id, count(nexus_hosting_accounts.ps_id) AS accounts', 'nexus_hosting_servers', $where, 'accounts ASC', NULL, 'server_id' )->join( 'nexus_hosting_accounts', 'account_server=server_id AND account_exists=1' ) as $serverData )
		{
			if ( !$serverWithLeastAccounts )
			{
				$serverWithLeastAccounts = $serverData['server_id'];
			}
			
			if ( !isset( $serverLimits[ $serverData['server_id'] ] ) or !$serverLimits[ $serverData['server_id'] ] or $serverLimits[ $serverData['server_id'] ] > $serverData['accounts'] )
			{
				return \IPS\nexus\Hosting\Server::load( $serverData['server_id'] );
			}
		}
		
		if ( $serverWithLeastAccounts )
		{
			return \IPS\nexus\Hosting\Server::load( $serverWithLeastAccounts );
		}
		
		throw new \UnderflowException;		
	}
}