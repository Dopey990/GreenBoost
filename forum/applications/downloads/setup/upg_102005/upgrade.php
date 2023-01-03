<?php
/**
 * @brief		4.2.2 Beta 1 Upgrade Code
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		26 Jul 2017
 */

namespace IPS\downloads\setup\upg_102005;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * 4.2.2 Beta 1 Upgrade Code
 */
class _Upgrade
{
	/**
	 * Recount containers and repair custom field URL's
	 *
	 * @return	array	If returns TRUE, upgrader will proceed to next step. If it returns any other value, it will set this as the value of the 'extra' GET parameter and rerun this step (useful for loops)
	 */
	public function finish()
	{
		\IPS\Task::queue( 'core', 'RebuildContainerCounts', array( 'class' => 'IPS\downloads\Category', 'count' => 0 ), 5, array( 'class' ) );
		
		$file = new \IPS\downloads\extensions\core\FileStorage\FileField;
		\IPS\Task::queue( 'core', 'RepairFileUrls', array( 'storageExtension' => 'filestorage__downloads_FileField', 'count' => $file->count() ), 1 );
		return TRUE;
	}
}