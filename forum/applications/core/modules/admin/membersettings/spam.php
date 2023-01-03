<?php
/**
 * @brief		spam
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community

 * @since		18 Apr 2018
 */

namespace IPS\core\modules\admin\membersettings;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * spam
 * @deprecated
 */
class _spam extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Output::i()->redirect(\IPS\Http\Url::internal( 'app=core&module=moderation&controller=spam&tab=' . \IPS\Request::i()->tab ) );
	}

	
	// Create new methods with the same name as the 'do' parameter which should execute it
}