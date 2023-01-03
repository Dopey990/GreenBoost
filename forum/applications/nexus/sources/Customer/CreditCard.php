<?php
/**
 * @brief		Customer Stored Card Model
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		12 Mar 2014
 */

namespace IPS\nexus\Customer;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Customer Stored Card Model
 */
class _CreditCard extends \IPS\Patterns\ActiveRecord
{	
	/**
	 * @brief	Multiton Store
	 */
	protected static $multitons;

	/**
	 * @brief	Database Table
	 */
	public static $databaseTable = 'nexus_customer_cards';
	
	/**
	 * @brief	Database Prefix
	 */
	public static $databasePrefix = 'card_';
	
	/**
	 * Construct ActiveRecord from database row
	 *
	 * @param	array	$data							Row from database table
	 * @param	bool	$updateMultitonStoreIfExists	Replace current object in multiton store if it already exists there?
	 * @return	static
	 */
	public static function constructFromData( $data, $updateMultitonStoreIfExists = TRUE )
	{
		$gateway = \IPS\nexus\Gateway::load( $data['card_method'] );
		$classname = \IPS\nexus\Gateway::gateways()[ $gateway->gateway ] . '\\CreditCard';
		
		/* Initiate an object */
		$obj = new $classname;
		$obj->_new = FALSE;
		
		/* Import data */
		if ( static::$databasePrefix )
		{
			$databasePrefixLength = \strlen( static::$databasePrefix );
		}
		foreach ( $data as $k => $v )
		{
			if( static::$databasePrefix AND mb_strpos( $k, static::$databasePrefix ) === 0 )
			{
				$k = \substr( $k, $databasePrefixLength );
			}

			$obj->_data[ $k ] = $v;
		}
		$obj->changed = array();
		
		/* Init */
		if ( method_exists( $obj, 'init' ) )
		{
			$obj->init();
		}
				
		/* Return */
		return $obj;
	}
	
	/**
	 * Add Form
	 *
	 * @param	\IPS\nexus\Customer	$customer	The customer
	 * @param	bool				$admin		Set to TRUE if the *admin* (opposed to the customer themselves) wants to create a new payment method
	 * @return	\IPS\Helpers\Form|\IPS\nexus\CreditCard
	 */
	public static function create( \IPS\nexus\Customer $customer, $admin )
	{
		$form = new \IPS\Helpers\Form;
		$gateways = \IPS\nexus\Gateway::cardStorageGateways( $admin );
		
		$elements = array();
		$paymentMethodsToggles = array();
		foreach ( $gateways as $gateway )
		{
			$invoice = new \IPS\nexus\Invoice;
			$invoice->currency = $customer->defaultCurrency();
			foreach ( $gateway->paymentScreen( $invoice, $invoice->total, $customer, array(), 'card' ) as $element )
			{
				if ( !$element->htmlId )
				{
					$element->htmlId = $gateway->id . '-' . $element->name;
				}
				if ( isset( $element->options['save'] ) )
				{
					$element->options['save'] = NULL;
				}
				$elements[] = $element;
				$paymentMethodsToggles[ $gateway->id ][] = $element->htmlId;
			}
		}
		
		if ( \count( $gateways ) > 1 )
		{
			$showSubmitButton = FALSE;
			$options = array();
			$toggles = array();
			foreach ( $gateways as $gateway )
			{
				$options[ $gateway->id ] = $gateway->_title;
				if ( $gateway->showSubmitButton() )
				{
					$showSubmitButton = TRUE;
					$paymentMethodsToggles[ $gateway->id ][] = 'paymentMethodSubmit';
				}
			}
			
			$element = new \IPS\Helpers\Form\Radio( 'payment_method', NULL, TRUE, array( 'options' => $options, 'toggles' => $paymentMethodsToggles ) );
			$element->label = \IPS\Member::loggedIn()->language()->addToStack('card_gateway');			
			$form->add( $element );
		}
		else
		{
			foreach ( $gateways as $gateway )
			{
				$form->hiddenValues['payment_method'] = $gateway->id;
				$showSubmitButton = $gateway->showSubmitButton();
			}
		}
		
		foreach ( $elements as $element )
		{
			$form->add( $element );
		}
		
		if ( $values = $form->values() )
		{			
			if ( !$values[ \IPS\Request::i()->payment_method . '_card' ] )
			{
				$form->error = \IPS\Member::loggedIn()->language()->addToStack('card_number_invalid');
			}
			else
			{
				try
				{
					$gateway = \IPS\nexus\Gateway::load( \IPS\Request::i()->payment_method );
					$classname = \IPS\nexus\Gateway::gateways()[ $gateway->gateway ] . '\\CreditCard';
					$card = new $classname;
					$card->member = $customer;
					$card->method = $gateway;
					
					if ( \is_array( $values[ $gateway->id . '_card' ] ) )
					{
						$_card = new \IPS\nexus\CreditCard;
						$_card->token = $values[ $gateway->id . '_card' ]['token'];
						$card->card = $_card;
					}
					else
					{
						$card->card = $values[ $gateway->id . '_card' ];
					}
					$card->save();
					
					$customer->log( 'card', array( 'type' => 'add', 'number' => $card->card->lastFour ) );
					
					return $card;
				}
				catch ( \DomainException $e )
				{
					$form->error = $e->getMessage();
				}
			}
		}
		
		return $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'nexus', 'global' ), 'addPaymentMethodForm' ), $showSubmitButton );
	}
	
	/**
	 * Get member
	 *
	 * @return	\IPS\Member
	 */
	public function get_member()
	{
		return \IPS\nexus\Customer::load( $this->_data['member'] );
	}
	
	/**
	 * Set member
	 *
	 * @param	\IPS\Member	$member	Member
	 * @return	void
	 */
	public function set_member( \IPS\Member $member )
	{
		$this->_data['member'] = $member->member_id;
	}
	
	/**
	 * Get payment gateway
	 *
	 * @return	\IPS\nexus\Gateway
	 */
	public function get_method()
	{
		return \IPS\nexus\Gateway::load( $this->_data['method'] );
	}
	
	/**
	 * Set payment gateway
	 *
	 * @param	\IPS\nexus\Gateway	$gateway	Payment gateway
	 * @return	void
	 */
	public function set_method( \IPS\nexus\Gateway $gateway )
	{
		$this->_data['method'] = $gateway->id;
	}
}