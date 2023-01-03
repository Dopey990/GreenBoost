<?php
/**
 * @brief		Upgrader: Custom Upgrade Options
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Forums
 * @since		5 Jun 2014
 */

$options	= array(
	new \IPS\Helpers\Form\Radio( '40000_qa_forum', 0, TRUE, array( 'options' => array( 0 => '40000_qa_forum_0', 1 => '40000_qa_forum_1' ) ) )
);