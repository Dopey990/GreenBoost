<?php
/**
 * @brief		MaxMind Request
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		07 Mar 2014
 */

namespace IPS\nexus\Fraud\MaxMind;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * MaxMind Request
 */
class _Request
{
	/**
	 * @brief	Data that will be posted
	 */
	protected $data = array();
	
	/**
	 * Constructor
	 *
	 * @param	bool		$session	Set session data (set to FALSE if this is being initiated outside the checkout sequence)
	 * @param	NULL|string	$maxmindKey	MaxMind License Key (NULL to get from settings)
	 * @return	void
	 */
	public function __construct( $session=TRUE, $maxmindKey=NULL )
	{
		$this->data['license_key']	= $maxmindKey ?: \IPS\Settings::i()->maxmind_key;

		if ( $session )
		{
			$this->setIpAddress( \IPS\Request::i()->ipAddress() );
			
			$this->data['sessionID'] = session_id();

			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) and $_SERVER['HTTP_USER_AGENT'] )
			{
				$this->data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			}
			
			if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) and $_SERVER['HTTP_ACCEPT_LANGUAGE'] )
			{
				$this->data['accept_language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			}
		}
	}
	
	/**
	 * Set IP Address
	 *
	 * @param	string	$ipAddress	IP Address
	 * @return	void
	 */
	public function setIpAddress( $ipAddress )
	{
		$this->data['i'] = $ipAddress;
	}
	
	/**
	 * Set Transaction
	 *
	 * @param	\IPS\nexus\Transaction	$transaction
	 * @return	void
	 */
	public function setTransaction( \IPS\nexus\Transaction $transaction )
	{
		$this->setMember( $transaction->member->member_id ? $transaction->member : $transaction->invoice->member );
		
		if ( $billingAddress = $transaction->invoice->billaddress )
		{
			$this->setBillingAddress( $transaction->invoice->billaddress );
		}
		if ( $shippingAddress = $transaction->invoice->shipaddress )
		{
			$this->setShippingAddress( $transaction->invoice->shipaddress );
		}
		
		$this->data['order_amount']		= (string) $transaction->amount->amount;
		$this->data['order_currency']	= $transaction->amount->currency;
	}
	
	/**
	 * Set Billing Address
	 *
	 * @param	\IPS\GeoLocation	$billingAddress
	 * @return	void
	 */
	public function setBillingAddress( \IPS\GeoLocation $billingAddress )
	{
		$this->data['city']			= $billingAddress->city;
		$this->data['region']		= $billingAddress->region;
		$this->data['postal']		= $billingAddress->postalCode;
		$this->data['country']		= $billingAddress->country;
	}
	
	/**
	 * Set Shipping Address
	 *
	 * @param	\IPS\GeoLocation	$shippingAddress
	 * @return	void
	 */
	public function setShippingAddress( \IPS\GeoLocation $shippingAddress )
	{
		$this->data['shipAddr']		= implode( ', ', $shippingAddress->addressLines );
		$this->data['shipCity']		= $shippingAddress->city;
		$this->data['shipRegion']	= $shippingAddress->region;
		$this->data['shipPostal']	= $shippingAddress->postalCode;
		$this->data['shipCountry']	= $shippingAddress->country;
	}
	
	/**
	 * Set Member
	 *
	 * @param	\IPS\Member		$member
	 * @return	void
	 */
	public function setMember( \IPS\Member $member )
	{
		$this->data['domain']		= mb_substr( $member->email, mb_strrpos( $member->email, '@' ) + 1 );
		$this->data['emailMD5']		= md5( $member->email );
		$this->data['usernameMD5']	= md5( $member->name );
	}
	
	/**
	 * Set Phone Number
	 *
	 * @param	string	$phoneNumber
	 * @return	void
	 */
	public function setPhone( $phoneNumber )
	{
		$this->data['custPhone']	= $phoneNumber;
	}
	
	/**
	 * Set Credit Card
	 *
	 * @param	\IPS\nexus\CreditCard|string	$card	The card number
	 * @return	void
	 */
	public function setCard( $card )
	{
		$this->data['txn_type']		= 'creditcard';
		
		$cardNumber = ( $card instanceof \IPS\nexus\CreditCard ) ? $card->number : $card;
		$this->data['bin'] = mb_substr( $cardNumber, 0, 6 );
	}
	
	/**
	 * Set Transaction Type
	 *
	 * @param	string	$type		Transaction Type
	 * @return	void
	 */
	public function setTransactionType( $type )
	{
		$this->data['txn_type']		= $type;
	}
	
	/**
	 * Set AVS Result
	 *
	 * @param	string	$code	AVS Code
	 * @return	void
	 */
	public function setAVS( $code )
	{
		$this->data['avs_result'] = $code;
	}
	
	/**
	 * Set CVV Result
	 *
	 * @param	bool	$result	CVV check result (boolean only - do not provide actual code)
	 * @return	void
	 */
	public function setCVV( $result )
	{
		$this->data['cvv_result'] = $result ? 'Y' : 'N';
	}
		
	/**
	 * Make Request
	 *
	 * @return	\IPS\nexus\Fraud\MaxMind\Response
	 * @throws	\IPS\Http\Request\Exception
	 */
	public function request()
	{		
		$response = \IPS\Http\Url::external( 'https://minfraud.maxmind.com/app/ccv2r' )->request()->post( $this->data );
		
		if ( isset( $response->httpHeaders['Content-Type'] ) and preg_match( '/; charset=(.+?)$/', $response->httpHeaders['Content-Type'], $matches ) )
		{
			$response = mb_convert_encoding( $response, 'UTF-8', $matches[1] );
		}
		
		return new \IPS\nexus\Fraud\MaxMind\Response( (string) $response );
	}
}