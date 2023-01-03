<?php
/**
 * @brief		featuredProduct Widget
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	nexus
 * @since		18 Jul 2018
 */

namespace IPS\nexus\widgets;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * featuredProduct Widget
 */
class _featuredProduct extends \IPS\Widget\PermissionCache
{
	/**
	 * @brief	Widget Key
	 */
	public $key = 'featuredProduct';
	
	/**
	 * @brief	App
	 */
	public $app = 'nexus';
		
	/**
	 * @brief	Plugin
	 */
	public $plugin = '';
	
	/**
	 * Specify widget configuration
	 *
	 * @param	null|\IPS\Helpers\Form	$form	Form object
	 * @return	null|\IPS\Helpers\Form
	 */
	public function configuration( &$form=null )
	{
		$form = parent::configuration( $form );

		$form->add( new \IPS\Helpers\Form\Node( 'package', isset( $this->configuration['package'] ) ? $this->configuration['package'] : 0, FALSE, array(
			'class'           => '\IPS\nexus\Package',
			'permissionCheck' => 'view',
			'multiple'        => false,
			'subnodes'		  => false,
		) ) );

		return $form;
 	}

	/**
	 * Ran before saving widget configuration
	 *
	 * @param	array	$values	Values from form
	 * @return	array
	 */
	public function preConfig( $values )
	{
		$values['package'] = $values['package']->id;

		return $values;
	}

	/**
	 * Render a widget
	 *
	 * @return	string
	 */
	public function render()
	{
		//Load the product
		$package = NULL;
		if( isset( $this->configuration['package'] ) )
		{
			try
			{
				$package = \IPS\nexus\Package::load( $this->configuration['package'] );
			}
			catch ( \OutOfRangeException $e ){}
		}

		if ( !$package )
		{
			return "";
		}

		return $this->output( $package );
	}
}