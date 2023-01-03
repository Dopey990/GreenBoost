<?php
/**
 * @brief		Loyalty discount input class for Form Builder
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		2 May 2014
 */

namespace IPS\nexus\Form;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Loyalty discount input class for Form Builder
 */
class _DiscountLoyalty extends \IPS\Helpers\Form\FormAbstract
{
	/** 
	 * Get HTML
	 *
	 * @return	string
	 */
	public function html()
	{
		$selectedPackage = NULL;
		try
		{
			$selectedPackage = $this->value['package'] ? \IPS\nexus\Package::load( $this->value['package'] ) : NULL;
		}
		catch ( \OutOfRangeException $e ) {}
		
		$nodeSelect = new \IPS\Helpers\Form\Node( "{$this->name}[package]", $selectedPackage, FALSE, array( 'class' => 'IPS\nexus\Package\Group', 'permissionCheck' => function( $node )
		{
			return !( $node instanceof \IPS\nexus\Package\Group );
		} ) );
		
		return \IPS\Theme::i()->getTemplate( 'discountforms' )->loyalty( $this, $nodeSelect->html() );
	}
}