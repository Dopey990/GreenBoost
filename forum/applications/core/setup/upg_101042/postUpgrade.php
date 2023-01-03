<?php
/**
 * @brief		Upgrader: Custom Post Upgrade Message
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		16 May 2016
 */

if ( \IPS\Application::appIsEnabled('cms' ) )
{
	$message = \IPS\Theme::i()->getTemplate( 'global' )->block( NULL, "Please check any custom moderator permissions (ACP -> Members -> Moderators) for Pages Database categories. These have been reset to 'All Categories'." );
}	
