<?php
/**
 * @brief		Upgrader: Custom Upgrade Options
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		5 Jun 2014
 */

$options	= array(
	new \IPS\Helpers\Form\Radio( '32000_avatar_or_photo', NULL, TRUE, array( 'options' => array( 'avatars' => 'avph_avatar', 'photos' => 'avph_photo' ) ) )
);