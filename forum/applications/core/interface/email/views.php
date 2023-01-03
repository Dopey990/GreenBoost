<?php
/**
* @brief		View tracking for email advertisements
*
* @copyright	(c) Invision Power Services, Inc.
*
* @package		Invision Community
* @since		6 Dec 2018
*/

/* Init */
define('REPORT_EXCEPTIONS', TRUE);
require_once str_replace( 'applications/core/interface/email/views.php', '', str_replace( '\\', '/', __FILE__ ) ) . 'init.php';

/* Track advertisement views */
if( isset( \IPS\Request::i()->ads ) AND \IPS\Request::i()->ads )
{
	$ads = explode( ',', \IPS\Request::i()->ads );
	$ads = array_map( "intval", $ads );

	\IPS\Db::i()->update( 'core_advertisements', "ad_email_views=COALESCE(ad_email_views,0)+1", array( 'ad_id IN(' . implode( ',', $ads ) . ')' ) );
}

/* Outputs a 1x1 transparent gif, disabling additional parsing we don't need */
\IPS\Output::i()->sendOutput( base64_decode( "R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" ), 200, 'image/gif', array( 'Expires' => 0, 'Pragma' => 'no-cache', 'Cache-Control' => 'no-cache, no-store, must-revalidate' ), FALSE, FALSE, FALSE, FALSE );