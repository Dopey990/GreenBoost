<?php
/**
 * @brief		Upgrader: Custom Upgrade Options
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		4 Dec 2014
 */

/* Should friends be converted to followers ? */
$options	= array(
	new \IPS\Helpers\Form\Radio( '100003_follow_options', 'no_convert', TRUE, array( 'options' => array( 'no_convert' => '100003_no_convert', 'convert' => '100003_convert' ) ) )
);