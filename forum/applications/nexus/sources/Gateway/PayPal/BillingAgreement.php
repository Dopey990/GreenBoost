<?php
/**
 * @brief		PayPal Billing Agreement
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		16 Dec 2015
 */

namespace IPS\nexus\Gateway\PayPal;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * PayPal Billing Agreement
 */
class _BillingAgreement extends \IPS\nexus\Customer\BillingAgreement
{
	/**
	 * Get status
	 *
	 * @return	string	See STATUS_* constants
	 * @throws	\IPS\nexus\Gateway\PayPal\Exception
	 */
	public function status()
	{
		$data = $this->_getData();
		
		switch ( $data['state'] )
		{
			case 'Active':
			case 'Pending':
			case 'Reactivate':
				return static::STATUS_ACTIVE;
				break;
			case 'Suspend':
			case 'Suspended':
				return static::STATUS_SUSPENDED;
				break;
			case 'Expired':
			case 'Cancel':
			case 'Cancelled':
				return static::STATUS_CANCELED;
				break;
		}
	}
	
	/**
	 * Get term
	 *
	 * @return	\IPS\nexus\Purchase\RenewalTerm
	 * @throws	\IPS\nexus\Gateway\PayPal\Exception
	 */
	public function term()
	{
		$data = $this->_getData();
		
		$amount = $data['plan']['payment_definitions'][0]['amount']['value'];
		if ( isset( $data['plan']['payment_definitions'][0]['charge_models'][0] ) )
		{
			$amount += $data['plan']['payment_definitions'][0]['charge_models'][0]['amount']['value'];
		}
		
		return new \IPS\nexus\Purchase\RenewalTerm( new \IPS\nexus\Money( $amount, $data['plan']['payment_definitions'][0]['amount']['currency'] ), new \DateInterval( 'P' . $data['plan']['payment_definitions'][0]['frequency_interval'] . mb_substr( $data['plan']['payment_definitions'][0]['frequency'], 0, 1 ) ) );
	}
	
	/**
	 * Get next payment date
	 *
	 * @return	\IPS\DateTime
	 * @throws	\IPS\nexus\Gateway\PayPal\Exception
	 */
	public function nextPaymentDate()
	{
		$data = $this->_getData();
		
		return new \IPS\DateTime( $data['agreement_details']['next_billing_date'] );
	}
	
	/**
	 * Suspend
	 *
	 * @return	void
	 * @throws	\DomainException
	 */
	public function doSuspend()
	{
		$this->method->api( "payments/billing-agreements/{$this->gw_id}/suspend", array( 'note' => 'Suspend' ) );
	}
	
	/**
	 * Reactivate
	 *
	 * @return	void
	 * @throws	\DomainException
	 */
	public function doReactivate()
	{
		$this->method->api( "payments/billing-agreements/{$this->gw_id}/re-activate", array( 'note' => 'Reactivate' ) );
	}
	
	/**
	 * Cancel
	 *
	 * @return	void
	 * @throws	\DomainException
	 */
	public function doCancel()
	{
		$this->method->api( "payments/billing-agreements/{$this->gw_id}/cancel", array( 'note' => 'Cancel' ) );
	}
	
	/**
	 * @brief	Cached data
	 */
	protected $_payPalData = NULL;
	
	/**
	 * Get data
	 *
	 * @return	array
	 * @throws	\IPS\nexus\Gateway\PayPal\Exception
	 */
	public function _getData()
	{
		if ( $this->_payPalData === NULL )
		{
			$this->_payPalData = $this->method->api( "payments/billing-agreements/{$this->gw_id}", NULL, 'get' );
		}
		return $this->_payPalData;
	}
}