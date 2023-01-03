<?php
/**
 * @brief		Payment Gateways
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		10 Feb 2014
 */

namespace IPS\nexus\modules\admin\payments;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Payment Gateways
 */
class _gateways extends \IPS\Node\Controller
{
	/**
	 * Node Class
	 */
	protected $nodeClass = 'IPS\nexus\Gateway';
	
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'gateways_manage' );
		parent::execute();
	}
	
	/**
	 * Get Root Buttons
	 *
	 * @return	array
	 */
	public function _getRootButtons()
	{
		$buttons = parent::_getRootButtons();
		
		if ( isset( $buttons['add'] ) )
		{
			$buttons['add']['link'] = $buttons['add']['link']->setQueryString( '_new', TRUE );
		}
		
		return $buttons;
	}
	
	/**
	 * Add/Edit Form
	 *
	 * @return void
	 */
	protected function form()
	{
		if ( \IPS\Request::i()->id )
		{
			return parent::form();
		}
		else
		{
			if ( \IPS\IN_DEV )
			{
				\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'plupload/moxie.js', 'core', 'interface' ) );
				\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'plupload/plupload.dev.js', 'core', 'interface' ) );
			}
			else
			{
				\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'plupload/plupload.full.min.js', 'core', 'interface' ) );
			}
			\IPS\Output::i()->output = (string) new \IPS\Helpers\Wizard( array(
				'gateways_gateway'	=> function( $data )
				{
					$options = array();
					foreach ( \IPS\nexus\Gateway::gateways() as $key => $class )
					{
						$options[ $key ] = 'gateway__' . $key;
					}

					$form = new \IPS\Helpers\Form;
					$form->add( new \IPS\Helpers\Form\Radio( 'gateways_gateway', TRUE, NULL, array( 'options' => $options ) ) );
					if ( $values = $form->values() )
					{
						return array( 'gateway' => $values['gateways_gateway'] );
					}
					return $form;
				},
				'gateways_details'	=> function( $data )
				{
					$form = new \IPS\Helpers\Form('gw');
					$class = \IPS\nexus\Gateway::gateways()[ $data['gateway'] ];
					$obj = new $class;
					$obj->gateway = $data['gateway'];
					$obj->active = TRUE;
					$obj->form( $form );
					if ( $values = $form->values() )
					{

						$settings = array();
						foreach ( $values as $k => $v )
						{
							if ( $k !== 'paymethod_name' AND $k !== 'paymethod_countries' )
							{
								$settings[ mb_substr( $k, mb_strlen( $data['gateway'] ) + 1 ) ] = $v;
							}
						}
						try
						{
							$settings = $obj->testSettings( $settings );
						}
						catch ( \InvalidArgumentException $e )
						{
							$form->error = $e->getMessage();
							return $form;
						}
						
						$name = $values['paymethod_name'];
						$values = $obj->formatFormValues( $values );
						$obj->settings = json_encode( $settings );
						$obj->countries = $values['countries'];
						$obj->save();
						\IPS\Lang::saveCustom( 'nexus', "nexus_paymethod_{$obj->id}", $name );

						\IPS\Output::i()->redirect( \IPS\Http\Url::internal('app=nexus&module=payments&controller=paymentsettings&tab=gateways') );
					}
					return $form;
				}
			), \IPS\Http\Url::internal('app=nexus&module=payments&controller=paymentsettings&tab=gateways&do=form') );
		}
	}
}