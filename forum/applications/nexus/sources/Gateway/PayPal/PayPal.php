<?php
/**
 * @brief		PayPal Gateway
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		10 Feb 2014
 */

namespace IPS\nexus\Gateway;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * PayPal Gateway
 */
class _PayPal extends \IPS\nexus\Gateway
{
	/* !Features */
	
	const SUPPORTS_REFUNDS = TRUE;
	const SUPPORTS_PARTIAL_REFUNDS = TRUE;
	
	/**
	 * Check the gateway can process this...
	 *
	 * @param	$amount			\IPS\nexus\Money		The amount
	 * @param	$billingAddress	\IPS\GeoLocation|NULL	The billing address, which may be NULL if one if not provided
	 * @param	$customer		\IPS\nexus\Customer		The customer (Default NULL value is for backwards compatibility - it should always be provided.)
	 * @param	array			$recurrings				Details about recurring costs
	 * @return	bool
	 */
	public function checkValidity( \IPS\nexus\Money $amount, \IPS\GeoLocation $billingAddress = NULL, \IPS\nexus\Customer $customer = NULL, $recurrings = array() )
	{		
		$settings = json_decode( $this->settings, TRUE );
	
		/* Card payments require name and billing address */
        if ( isset( $settings['type'] ) and $settings['type'] === 'card' and ( !$customer->cm_first_name or !$customer->cm_last_name or !$billingAddress ) )
        {
            return FALSE;
        }
		
		/* Billing agreements require a billing address */
		elseif ( \count( $recurrings ) == 1 and ( isset( $settings['billing_agreements'] ) and $settings['billing_agreements'] == 'required' ) and !$billingAddress )
		{
			return FALSE;
		}
		
		/* Check transaction limit */
		switch ( $amount->currency )
		{
			case 'AUD':
				if ( $amount->amount->compare( new \IPS\Math\Number( '12500' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'BRL':
				if ( $amount->amount->compare( new \IPS\Math\Number( '20000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'CAD':
				if ( $amount->amount->compare( new \IPS\Math\Number( '12500' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'CZK':
				if ( $amount->amount->compare( new \IPS\Math\Number( '240000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'DKK':
				if ( $amount->amount->compare( new \IPS\Math\Number( '60000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'EUR':
				if ( $amount->amount->compare( new \IPS\Math\Number( '8000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'GBP':
				if ( $amount->amount->compare( new \IPS\Math\Number( '5500' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'HKD':
				if ( $amount->amount->compare( new \IPS\Math\Number( '80000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'HUF':
				if ( $amount->amount->compare( new \IPS\Math\Number( '2000000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'ILS':
				if ( $amount->amount->compare( new \IPS\Math\Number( '40000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'JPY':
				if ( $amount->amount->compare( new \IPS\Math\Number( '1000000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'MYR':
				if ( $amount->amount->compare( new \IPS\Math\Number( '40000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'MXN':
				if ( $amount->amount->compare( new \IPS\Math\Number( '110000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'TWD':
				if ( $amount->amount->compare( new \IPS\Math\Number( '330000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'NZD':
				if ( $amount->amount->compare( new \IPS\Math\Number( '15000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'NOK':
				if ( $amount->amount->compare( new \IPS\Math\Number( '70000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'PHP':
				if ( $amount->amount->compare( new \IPS\Math\Number( '500000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'PLN':
				if ( $amount->amount->compare( new \IPS\Math\Number( '32000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'RUB':
				if ( $amount->amount->compare( new \IPS\Math\Number( '550000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'SGD':
				if ( $amount->amount->compare( new \IPS\Math\Number( '16000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'SEK':
				if ( $amount->amount->compare( new \IPS\Math\Number( '80000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'CHF':
				if ( $amount->amount->compare( new \IPS\Math\Number( '13000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'THB':
				if ( $amount->amount->compare( new \IPS\Math\Number( '360000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'TRY':
				if ( $amount->amount->compare( new \IPS\Math\Number( '25000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			case 'USD':
				if ( $amount->amount->compare( new \IPS\Math\Number( '10000' ) ) !== -1 )
				{
					return FALSE;
				}
				break;
			default:
				return FALSE;	
		}
		
		/* Pass to parent */
		return parent::checkValidity( $amount, $billingAddress, $customer, $recurrings );
	}
	
	/**
	 * Can store cards?
	 *
	 * @param	bool	$adminCreatableOnly	If TRUE, will only return gateways where the admin (opposed to the user) can create a new option
	 * @return	bool
	 */
	public function canStoreCards( $adminCreatableOnly = FALSE )
	{
		$settings = json_decode( $this->settings, TRUE );
		return ( isset( $settings['type'] ) and $settings['type'] === 'card' and isset( $settings['vault'] ) and $settings['vault'] );
	}
	
	/**
	 * Admin can manually charge using this gateway?
	 *
	 * @param	\IPS\nexus\Customer	$customer	The customer we're wanting to charge
	 * @return	bool
	 */
	public function canAdminCharge( \IPS\nexus\Customer $customer )
	{
		$settings = json_decode( $this->settings, TRUE );
		return ( isset( $settings['type'] ) and $settings['type'] === 'card' );
	}
	
	/**
	 * Supports billing agreements?
	 *
	 * @return	bool
	 */
	public function billingAgreements()
	{
		$settings = json_decode( $this->settings, TRUE );
		return ( ( isset( $settings['type'] ) and $settings['type'] === 'paypal' ) or !isset( $settings['type'] ) ) and ( isset( $settings['billing_agreements'] ) and \in_array( $settings['billing_agreements'], array( 'required', 'optional' ) ) );
	}
		
	/* !Payment Gateway */
	
	/**
	 * Payment Screen Fields
	 *
	 * @param	\IPS\nexus\Invoice		$invoice	Invoice
	 * @param	\IPS\nexus\Money		$amount		The amount to pay now
	 * @param	\IPS\nexus\Customer		$member		The member the payment screen is for (if in the ACP charging to a member's card) or NULL for currently logged in member
	 * @param	array					$recurrings	Details about recurring costs
	 * @param	bool					$type		'checkout' means the cusotmer is doing this on the normal checkout screen, 'admin' means the admin is doing this in the ACP, 'card' means the user is just adding a card
	 * @return	array
	 */
	public function paymentScreen( \IPS\nexus\Invoice $invoice, \IPS\nexus\Money $amount, \IPS\nexus\Customer $member = NULL, $recurrings = array(), $type = 'checkout' )
	{
		$settings = json_decode( $this->settings, TRUE );
		if ( isset( $settings['type'] ) and $settings['type'] === 'card' )
		{
			return array( 'card' => new \IPS\nexus\Form\CreditCard( $this->id . '_card', NULL, TRUE, array(
				'types' 	=> array( \IPS\nexus\CreditCard::TYPE_VISA, \IPS\nexus\CreditCard::TYPE_MASTERCARD, \IPS\nexus\CreditCard::TYPE_DISCOVER, \IPS\nexus\CreditCard::TYPE_AMERICAN_EXPRESS ),
				'save'		=> ( isset( $settings['vault'] ) and $settings['vault'] ) ? $this : NULL,
				'member'	=> $member
			) ) );
		}
		elseif ( isset( $settings['billing_agreements'] ) and $settings['billing_agreements'] == 'optional' and \count( $recurrings ) == 1 and $invoice->billaddress )
		{
			return array( 'billing_agreement' => new \IPS\Helpers\Form\Checkbox( 'paypal_billing_agreement', TRUE, FALSE ) );
		}
		return array();
	}
	
	/**
	 * Authorize
	 *
	 * @param	\IPS\nexus\Transaction					$transaction	Transaction
	 * @param	array|\IPS\nexus\Customer\CreditCard	$values			Values from form OR a stored card object if this gateway supports them
	 * @param	\IPS\nexus\Fraud\MaxMind\Request|NULL	$maxMind		*If* MaxMind is enabled, the request object will be passed here so gateway can additional data before request is made	
	 * @param	array									$recurrings		Details about recurring costs
	 * @param	string|NULL								$source			'checkout' if the customer is doing this at a normal checkout, 'renewal' is an automatically generated renewal invoice, 'manual' is admin manually charging. NULL is unknown
	 * @return	\IPS\DateTime|NULL						Auth is valid until or NULL to indicate auth is good forever
	 * @throws	\LogicException							Message will be displayed to user
	 */
	public function auth( \IPS\nexus\Transaction $transaction, $values, \IPS\nexus\Fraud\MaxMind\Request $maxMind = NULL, $recurrings = array(), $source = NULL )
	{		
		/* We need a transaction ID */
		$transaction->save();
		
		/* Do it */
		$settings = json_decode( $this->settings, TRUE );
		if ( isset( $settings['type'] ) and $settings['type'] === 'card' )
		{
			return $this->_cardAuth( \is_array( $values ) ? $values[ $this->id . '_card' ] : $values, $transaction, $maxMind );
		}
		else
		{
			if ( \count( $recurrings ) == 1 and ( $settings['billing_agreements'] == 'required' or ( $settings['billing_agreements'] == 'optional' and $values['paypal_billing_agreement'] ) ) )
			{
				foreach ( $recurrings as $recurrance )
				{
					break;
				}
				return $this->_billingAgreementAuth( $transaction, $maxMind, $recurrance['term'], $recurrance['items'] );
			}
			else
			{			
				return $this->_paypalAuth( $transaction, $maxMind );
			}
		}
	}
	
	/**
	 * Authorize Card Payment
	 *
	 * @param	\IPS\nexus\CreditCard|\IPS\nexus\Customer\CreditCard	$card	The card to charge
	 * @param	\IPS\nexus\Transaction					$transaction	Transaction
	 * @param	\IPS\nexus\Fraud\MaxMind\Request|NULL	$maxMind		*If* MaxMind is enabled, the request object will be passed here so gateway can additional data before request is made	
	 * @return	\IPS\DateTime|NULL		Auth is valid until or NULL to indicate auth is good forever
	 * @throws	\LogicException			Message will be displayed to user
	 */
	protected function _cardAuth( $card, \IPS\nexus\Transaction $transaction, \IPS\nexus\Fraud\MaxMind\Request $maxMind = NULL )
	{
		/* Stored Card */	
		if ( $card instanceof \IPS\nexus\Customer\CreditCard )
		{
			$payer = array(
				'payment_method'		=> 'credit_card',
				'funding_instruments'	=> array(
					array(
						'credit_card_token'	=> array(
							'credit_card_id'	=> $card->data
						)
					)
				)
			);
		}
		/* New Card */
		else
		{
			if ( $card->save and !$transaction->member->member_id )
			{
				$transaction->member = $transaction->invoice->createAccountForGuest();
				\IPS\Session::i()->setMember( $transaction->member );
				\IPS\Member\Device::loadOrCreate( $transaction->member, FALSE )->updateAfterAuthentication( NULL );
			}
			
			if ( $maxMind )
			{
				$maxMind->setCard( $card );
			}
			
			switch ( $card->type )
			{
				case \IPS\nexus\CreditCard::TYPE_VISA:
					$cardType = 'visa';
					break;
				case \IPS\nexus\CreditCard::TYPE_MASTERCARD:
					$cardType = 'mastercard';
					break;
				case \IPS\nexus\CreditCard::TYPE_DISCOVER:
					$cardType = 'discover';
					break;
				case \IPS\nexus\CreditCard::TYPE_AMERICAN_EXPRESS:
					$cardType = 'amex';
					break;
			}

			$payer = array(
				'payment_method'		=> 'credit_card',
				'funding_instruments'	=> array(
					array(
						'credit_card'		=> array(
							'number'			=> $card->number,
							'type'				=> $cardType,
							'expire_month'		=> \intval( $card->expMonth ),
							'expire_year'		=> \intval( $card->expYear ),
							'cvv2'				=> $card->ccv,
							'first_name'		=> $this->_getFirstName( $transaction ),
							'last_name'			=> $this->_getLastName( $transaction ),
							'billing_address'	=> $this->_getAddress( $transaction->invoice->billaddress, $transaction->member )
						)
					),
				)
			);
		}
		
		/* Send the request */
		$response = $this->api( 'payments/payment', array(
			'intent'		=> 'authorize',
			'payer'			=> $payer,
			'transactions'	=> array( $this->_getTransactions( $transaction ) ),
			'redirect_urls'	=> array(
				'return_url'	=> \IPS\Settings::i()->base_url . 'applications/nexus/interface/gateways/paypal.php?nexusTransactionId=' . $transaction->id,
				'cancel_url'	=> (string) $transaction->invoice->checkoutUrl(),
			)
		) );	
		
		/* Set transaction data */	
		$transaction->gw_id = $response['transactions'][0]['related_resources'][0]['authorization']['id']; // The transaction ID for the authorization. At capture, it will be updated again to the capture transaction ID
		
		/* Save the card first if the user wants */
		if ( $card->save )
		{			
			try
			{
				$storedCard = new \IPS\nexus\Gateway\PayPal\CreditCard;
				$storedCard->member = $transaction->member;
				$storedCard->method = $this;
				$storedCard->card = $card;
				$storedCard->save();
			}
			catch ( \Exception $e ) {  /* If there's any issue with saving (which may happen for a duplicate card) we can just carry on since we already auth'd */ }
		}
		
		/* And return */
		return \IPS\DateTime::ts( strtotime( $response['transactions'][0]['related_resources'][0]['authorization']['valid_until'] ) );
	}
	
	/**
	 * Authorize PayPal Payment
	 *
	 * @param	\IPS\nexus\Transaction					$transaction	Transaction
	 * @param	\IPS\nexus\Fraud\MaxMind\Request|NULL	$maxMind		*If* MaxMind is enabled, the request object will be passed here so gateway can additional data before request is made	
	 * @return	\IPS\DateTime|NULL		Auth is valid until or NULL to indicate auth is good forever
	 * @throws	\LogicException			Message will be displayed to user
	 */
	protected function _paypalAuth( \IPS\nexus\Transaction $transaction, \IPS\nexus\Fraud\MaxMind\Request $maxMind = NULL )
	{
		/* Send the request */
		$response = $this->api( 'payments/payment', array(
			'intent'		=> 'authorize',
			'payer'			=> array( 'payment_method' => 'paypal' ),
			'transactions'	=> array( $this->_getTransactions( $transaction ) ),
			'redirect_urls'	=> array(
				'return_url'	=> \IPS\Settings::i()->base_url . 'applications/nexus/interface/gateways/paypal.php?nexusTransactionId=' . $transaction->id,
				'cancel_url'	=> (string) $transaction->invoice->checkoutUrl(),
			)
		) );

		/* Set transaction data */		
		$transaction->gw_id = $response['id']; // This is a payment ID ("PAY-XXX"). At this time we do not have a real transaction ID
		$transaction->save();
		
		/* Redirect */
		foreach ( $response['links'] as $link )
		{
			if ( $link['rel'] === 'approval_url' )
			{
				\IPS\Output::i()->redirect( \IPS\Http\Url::external( $link['href'] ) );
			}
		}
		throw new \RuntimeException;
	}
	
	/**
	 * Authorize Billing Agreement
	 *
	 * @param	\IPS\nexus\Transaction					$transaction	Transaction
	 * @param	\IPS\nexus\Fraud\MaxMind\Request|NULL	$maxMind		*If* MaxMind is enabled, the request object will be passed here so gateway can additional data before request is made	
	 * @param	\IPS\nexus\Purchase\RenewalTerm			$term			Renewal Term
	 * @param	array									$items			Items
	 * @return	\IPS\DateTime|NULL		Auth is valid until or NULL to indicate auth is good forever
	 * @throws	\LogicException			Message will be displayed to user
	 */
	protected function _billingAgreementAuth( \IPS\nexus\Transaction $transaction, \IPS\nexus\Fraud\MaxMind\Request $maxMind = NULL, \IPS\nexus\Purchase\RenewalTerm $term, $items )
	{
		/* Work out the name */
		$titles = array();
		foreach ( $items as $item )
		{
			$titles[] = ( $item->name . ( $item->quantity > 1 ? " x{$item->quantity}" : '' ) );
		}
		$title = implode( ', ', $titles );
		if ( mb_strlen( $title ) > 128 )
		{
			$title = mb_substr( $title, 0, 125 ) . '...';
		}
		$description = sprintf( $transaction->member->language()->get('transaction_number'), $transaction->id );
		
		/* Create Billing Plan */
		$paymentDefinitions = array();
		$definition = array(
			'name'	=> 'Payment Definition',
			'type'	=> 'REGULAR',
		);
		if ( $term->interval->y )
		{
			$definition['frequency_interval'] = $term->interval->y;
			$definition['frequency'] = 'YEAR';
		}
		elseif ( $term->interval->m )
		{
			$definition['frequency_interval'] = $term->interval->m;
			$definition['frequency'] = 'MONTH';
		}
		elseif ( $term->interval->d )
		{
			$definition['frequency_interval'] = $term->interval->d;
			$definition['frequency'] = 'DAY';
		}
		$definition['cycles'] = '0';
		$definition['amount'] = array(
			'currency'			=> $term->cost->currency,
			'value'				=> $term->cost->amountAsString()
		);
		if ( $term->tax )
		{
			$taxableAmount = $term->cost->amount;
			$taxRate = new \IPS\Math\Number( (string) $term->tax->rate( $transaction->invoice->billaddress ) );
			$taxAmount = new \IPS\nexus\Money( $taxableAmount->multiply( $taxRate ), $term->cost->currency );
			
			$definition['charge_models'] = array(
				array(
					'type'				=> 'TAX',
					'amount'			=> array(
						'currency'			=> $term->cost->currency,
						'value'				=> $taxAmount->amountAsString()
					)
				),
			);
		}
		$paymentDefinitions[] = $definition;
		$response = $this->api( 'payments/billing-plans', array(
			'name'					=> $description,
			'description'			=> $title,
			'type'					=> 'INFINITE',
			'payment_definitions'	=> $paymentDefinitions,
			'merchant_preferences'	=> array(
				'setup_fee'				=> array(
					'currency'				=> $transaction->amount->currency,
					'value'					=> $transaction->amount->amountAsString(),
				),
				'cancel_url'					=> (string) $transaction->invoice->checkoutUrl(),
				'return_url'					=> \IPS\Settings::i()->base_url . 'applications/nexus/interface/gateways/paypal.php?billingAgreement=1&nexusTransactionId=' . $transaction->id,
				'initial_fail_amount_action'	=> 'CANCEL',
			)
		) );
		$billingPlanId = $response['id'];
		
		/* Activate it */
		$response = $this->api( 'payments/billing-plans/' . $billingPlanId, array(
			array(
				'path'	=> '/',
				'value'	=> array(
					'state'	=> 'ACTIVE'
				),
				'op'	=> 'replace',
			)
		), 'patch' );
		
		/* Create Billing Agreement */
		$payerInfo = array( 'email' => $transaction->member->email );
		if ( $transaction->member->cm_phone )
		{
			$payerInfo['phone'] = $transaction->member->cm_phone;
		}
		$payerInfo['billing_address'] = $this->_getAddress( $transaction->invoice->billaddress, $transaction->invoice->member );
		$billingAgreementData = array(
			'name'			=> $description,
			'description'	=> $title,
			'start_date'	=> \IPS\DateTime::create()->add( $term->interval )->rfc3339(),
			'payer'			=> array(
				'payment_method'	=> 'paypal',
				'payer_info'		=> $payerInfo,
			)
		);
		if ( $transaction->invoice->shipaddress )
		{
			$billingAgreementData['shipping_address'] = $this->_getAddress( $transaction->invoice->shipaddress, $transaction->invoice->member );
		}
		$settings = json_decode( $this->settings, TRUE );
		if ( isset( $settings['billing_agreement_allowed_fails'] ) )
		{
			$billingAgreementData['override_merchant_preferences'] = array(
				'max_fail_attempts'	=> (string) $settings['billing_agreement_allowed_fails']
			);
		}
		$billingAgreementData['plan'] = array( 'id' => $billingPlanId );
		$response = $this->api( 'payments/billing-agreements', $billingAgreementData );
				
		/* Redirect */
		foreach ( $response['links'] as $link )
		{
			if ( $link['rel'] === 'approval_url' )
			{
				\IPS\Output::i()->redirect( \IPS\Http\Url::external( $link['href'] ) );
			}
		}
		throw new \RuntimeException;
	}
	
	/**
	 * Void
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction
	 * @return	void
	 * @throws	\Exception
	 */
	public function void( \IPS\nexus\Transaction $transaction )
	{
		/* If this is is the intitial transaction for a billing agreement which hasn't
			been processed yet, cancel the billing agreement */
		if ( $transaction->status === $transaction::STATUS_GATEWAY_PENDING and $transaction->billing_agreement )
		{
			$transaction->billing_agreement->cancel();
			return;
		}
		
		/* Try to find the authorization ID */
		if ( mb_substr( $transaction->gw_id, 0, 4 ) === 'PAY-' )
		{
			$authId = NULL;
			$payment = $this->api( "payments/payment/{$transaction->gw_id}", NULL, 'get' );
			foreach ( $payment['transactions'][0]['related_resources'] as $rr )
			{
				if ( isset( $rr['authorization'] ) )
				{
					$authId = $rr['authorization']['id'];
				}
			}
			
			if ( !$authId )
			{
				throw new \RuntimeException;
			}
		}
		else
		{
			$authId = $transaction->gw_id;
		}
		
		/* Void it */
		return $this->api( "payments/authorization/{$authId}/void" );
	}
		
	/**
	 * Capture
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction
	 * @return	void
	 * @throws	\LogicException
	 */
	public function capture( \IPS\nexus\Transaction $transaction )
	{
		/* Try to find the authorization ID */
		if ( mb_substr( $transaction->gw_id, 0, 4 ) === 'PAY-' )
		{
			$authId = NULL;
			$payment = $this->api( "payments/payment/{$transaction->gw_id}", NULL, 'get' );
			foreach ( $payment['transactions'][0]['related_resources'] as $rr )
			{
				if ( isset( $rr['authorization'] ) )
				{
					$authId = $rr['authorization']['id'];
				}
			}
			
			if ( !$authId )
			{
				throw new \RuntimeException;
			}
		}
		else
		{
			$authId = $transaction->gw_id;
		}
		
		/* Capture it */
		try
		{
			$response = $this->api( "payments/authorization/{$authId}/capture", array(
				'amount'			=> array(
					'currency'			=> $transaction->amount->currency,
					'total'				=> $transaction->amount->amountAsString(),
				),
				'is_final_capture'	=> TRUE,
			) );
			$transaction->gw_id = $response['id']; // We now set the gateway ID to the capture ID
			$transaction->save();
		}
		catch ( \IPS\nexus\Gateway\PayPal\Exception $e )
		{
			if ( $e->getName() == 'AUTHORIZATION_ALREADY_COMPLETED' )
			{
				return TRUE;
			}
			throw $e;
		}
		return TRUE;
	}
		
	/**
	 * Refund
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction to be refunded
	 * @param	float|NULL				$amount			Amount to refund (NULL for full amount - always in same currency as transaction)
	 * @return	mixed									Gateway reference ID for refund, if applicable
	 * @throws	\Exception
 	 */
	public function refund( \IPS\nexus\Transaction $transaction, $amount = NULL )
	{
		/* The capture ID is *normally* the gateway transaction ID */
		$captureId = $transaction->gw_id;
		
		/* But if it starts with I- (or is a blank but known to be a billing agreement payment) - that's a billing agreement */
		if ( ( $transaction->billing_agreement and !$transaction->gw_id ) or ( mb_substr( $transaction->gw_id, 0, 2 ) === 'I-' ) )
		{
			$transactions = $this->api( "payments/billing-agreements/{$transaction->billing_agreement->gw_id}/transactions?start_date=" . $transaction->date->sub( new \DateInterval('P1D') )->format('Y-m-d') . '&end_date=' . $transaction->date->format('Y-m-d'), NULL, 'get' );
			foreach ( $transactions['agreement_transaction_list'] as $t )
			{
				if ( $t['status'] == 'Completed' )
				{
					$transaction->gw_id = $t['transaction_id'];
					$transaction->save();
					$captureId = $transaction->gw_id;
					break;
				}
			}
		}
		/* And if it starts with PAY-, that's a payment */
		elseif ( mb_substr( $transaction->gw_id, 0, 4 ) === 'PAY-' )
		{
			$payment = $this->api( "payments/payment/{$transaction->gw_id}", NULL, 'get' );		
			$captureId = NULL;
			foreach ( $payment['transactions'][0]['related_resources'] as $rr )
			{
				if ( isset( $rr['capture'] ) )
				{
					$captureId = $rr['capture']['id'];
					break;
				}
			}
		}
		
		/* Process Refund */
		$amount = $amount ? new \IPS\nexus\Money( $amount, $transaction->currency ) : $transaction->amount;
		$response = $this->api( "payments/capture/{$captureId}/refund", array( 'amount' => array(
			'currency'	=> $amount->currency,
			'total'		=> $amount->amountAsString()
		) ) );
		return $response['id'];
	}
	
	/**
	 * Extra data to show on the ACP transaction page
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction
	 * @return	string
 	 */
	public function extraData( \IPS\nexus\Transaction $transaction )
	{
		return \IPS\Theme::i()->getTemplate( 'transactions', 'nexus', 'admin' )->paypalStatus( $transaction );
	}
	
	/* !ACP Configuration */
	
	/**
	 * Settings
	 *
	 * @param	\IPS\Helpers\Form	$form	The form
	 * @return	void
	 */
	public function settings( &$form )
	{
		$settings = json_decode( $this->settings, TRUE );
		
		if ( isset( $settings['type'] ) and $settings['type'] === 'card' )
		{
			$form->add( new \IPS\Helpers\Form\Radio( 'paypal_type', $settings['type'], TRUE, array( 'options' => array( 'paypal' => 'paypal_type_paypal', 'card' => 'paypal_type_card' ), 'toggles' => array( 'paypal' => array( 'paypal_billing_agreements' ), 'card' => array( 'paypal_vault' ) ) ) ) );
		}

		$form->add( new \IPS\Helpers\Form\Radio( 'paypal_billing_agreements', ( $this->id AND isset( $settings['billing_agreements'] ) ) ? (string) $settings['billing_agreements'] : '', FALSE, array(
			'options' => array(
				'required'	=> 'paypal_billing_agreements_req',
				'optional'	=> 'paypal_billing_agreements_opt',
				''			=> 'paypal_billing_agreements_dis',
			),
			'toggles' => array(
				'required'	=> array( 'paypal_billing_agreement_allowed_fails' ),
				'optional'	=> array( 'paypal_billing_agreement_allowed_fails' ),
			)
		), function( $val ) {
			if ( $val )
			{
				if ( \IPS\Http\Url::internal('')->data['scheme'] !== 'https' )
				{
					throw new \DomainException('paypal_billing_agreements_https');
				}
			}
		}, NULL, NULL, 'paypal_billing_agreements' ) );
		
		$form->add( new \IPS\Helpers\Form\Number( 'paypal_billing_agreement_allowed_fails', ( $this->id AND isset( $settings['billing_agreement_allowed_fails'] ) ) ? $settings['billing_agreement_allowed_fails'] : 0, FALSE, array( 'unlimited' => 0, 'min' => 1 ), NULL, \IPS\Member::loggedIn()->language()->addToStack('paypal_billing_agreement_allowed_fails_prefix'), \IPS\Member::loggedIn()->language()->addToStack('paypal_billing_agreement_allowed_fails_suffix'), 'paypal_billing_agreement_allowed_fails' ) );
		
		if ( isset( $settings['type'] ) and $settings['type'] === 'card' )
		{
			$form->add( new \IPS\Helpers\Form\YesNo( 'paypal_vault', ( $this->id and isset( $settings['vault'] ) ) ? $settings['vault'] : TRUE, FALSE, array(), NULL, NULL, NULL, 'paypal_vault' ) );
		}
		
		$form->add( new \IPS\Helpers\Form\Text( 'paypal_client_id', $settings['client_id'], TRUE ) );
		$form->add( new \IPS\Helpers\Form\Text( 'paypal_secret', $settings['secret'], TRUE ) );
	}
	
	/**
	 * Test Settings
	 *
	 * @param	array	$settings	Settings
	 * @return	array
	 * @throws	\InvalidArgumentException
	 */
	public function testSettings( $settings )
	{
		try
		{
			$token = $this->getNewToken( $settings );
			$settings['token'] = $token['access_token'];
			$settings['token_expire'] = ( time() + $token['expires_in'] );
			
			if ( isset( $settings['billing_agreements'] ) and $settings['billing_agreements'] )
			{			
				$correctWebhookUrl = \IPS\Settings::i()->base_url . 'applications/nexus/interface/gateways/paypal-webhook.php';
				$webhookId = NULL;
				$webhooks = $this->api( 'notifications/webhooks', NULL, 'get', TRUE, $settings );
				foreach ( $webhooks['webhooks'] as $webhook )
				{
					if ( $webhook['url'] === $correctWebhookUrl )
					{
						foreach ( $webhook['event_types'] as $eventType )
						{
							if ( $eventType['name'] === '*' )
							{
								$webhookId = $webhook['id'];
								break 2;
							}
						}
					}
				}
				if ( !$webhookId )
				{
					$response = $this->api( 'notifications/webhooks', array(
						'url'			=> $correctWebhookUrl,
						'event_types'	=> array(
							array(
								'name'	=> '*'
							)
						)
					), 'post', TRUE, $settings );
					$webhookId = $response['id'];
				}
				$settings['webhook_id'] = $webhookId;
			}
			
			\IPS\core\AdminNotification::remove( 'nexus', 'ConfigurationError', "pm{$this->id}" );
		}
		catch ( \Exception $e )
		{
			throw new \InvalidArgumentException( $e->getMessage() ?: \IPS\Member::loggedIn()->language()->addToStack('paypal_connection_error'), $e->getCode() );
		}
				
		return $settings;
	}
	
	/* !Utility Methods */
	
	/**
	 * Send API Request
	 *
	 * @param	string		$uri			The API to request (e.g. "payments/payment")
	 * @param	array		$data		The data to send
	 * @param	string		$method		Method (get/post)
	 * @param	array|NULL	$settings	Settings (NULL for saved setting)
	 * @return	array|null
	 * @throws	\IPS\Http|Exception
	 * @throws	\IPS\nexus\Gateway\PayPal\Exception
	 */
	public function api( $uri, $data=NULL, $method='post', $expectResponse=TRUE, $settings=NULL )
	{
		if ( !$settings )
		{
			$settings = json_decode( $this->settings, TRUE );
			if ( !isset( $settings['token'] ) or $settings['token_expire'] < time() )
			{
				$token = $this->getNewToken();
				$settings['token'] = $token['access_token'];
				$settings['token_expire'] = ( time() + $token['expires_in'] );
				$this->settings = json_encode( $settings );
				$this->save();
			}
		}
		
		$response = \IPS\Http\Url::external( 'https://' . ( \IPS\NEXUS_TEST_GATEWAYS ? 'api.sandbox.paypal.com' : 'api.paypal.com' ) . '/v1/' . $uri )
			->request( \IPS\LONG_REQUEST_TIMEOUT )
			->forceTls()
			->setHeaders( array(
				'Content-Type'					=> 'application/json',
				'Authorization'					=> "Bearer {$settings['token']}",
				'PayPal-Partner-Attribution-Id'	=> 'InvisionPower_SP'
			) )
			->$method( $data === NULL ? NULL : json_encode( $data ) );
					
		if ( mb_substr( $response->httpResponseCode, 0, 1 ) !== '2' )
		{
			throw new \IPS\nexus\Gateway\PayPal\Exception( $response, mb_substr( $uri, -7 ) === '/refund' );
		}
		
		if ( \in_array( $method, array( 'delete', 'patch' ) ) or $response->httpResponseCode == 204 )
		{
			return NULL;
		}
		else
		{
			return $response->decodeJson();
		}
	}
	
	/**
	 * Get Token
	 *
	 * @param	array|NULL	$settings	Settings (NULL for saved setting)
	 * @return	array
	 * @throws	\IPS\Http|Exception
	 * @throws	\UnexpectedValueException
	 */
	protected function getNewToken( $settings = NULL )
	{
		$settings = $settings ?: json_decode( $this->settings, TRUE );
				
		$response = \IPS\Http\Url::external( 'https://' . ( \IPS\NEXUS_TEST_GATEWAYS ? 'api.sandbox.paypal.com' : 'api.paypal.com' ) . '/v1/oauth2/token' )
			->request()
			->forceTls()
			->setHeaders( array(
				'Accept'			=> 'application/json',
				'Accept-Language'	=> 'en_US',
			) )
			->login( $settings['client_id'], $settings['secret'] )
			->post( array( 'grant_type' => 'client_credentials' ) )
			->decodeJson();
			
		if ( !isset( $response['access_token'] ) )
		{
			throw new \UnexpectedValueException( isset( $response['error_description'] ) ? $response['error_description'] : $response );
		}

		return $response;
	}
	
	/**
	 * Get address for PayPal
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction
	 * @return	array
	 */
	protected function _getAddress( \IPS\GeoLocation $address, \IPS\nexus\Customer $customer )
	{
		/* PayPal requires short codes for states */
		$state = $address->region;
		if ( isset( \IPS\nexus\Customer\Address::$stateCodes[ $address->country ] ) )
		{
			if ( !array_key_exists( $state, \IPS\nexus\Customer\Address::$stateCodes[ $address->country ] ) )
			{
				$_state = array_search( $address->region, \IPS\nexus\Customer\Address::$stateCodes[ $address->country ] );
				if ( $_state !== FALSE )
				{
					$state = $_state;
				}
			}
		}
		
		/* Construct */
		$address = array(
			'line1'				=> $address->addressLines[0],
			'line2'				=> isset( $address->addressLines[1] ) ? $address->addressLines[1] : '',
			'city'				=> $address->city,
			'country_code'		=> $address->country,
			'postal_code'		=> $address->postalCode,
			'state'				=> $state,
		);

		/* Add phone number */
		if ( $customer->cm_phone )
		{
			$address['phone'] = preg_replace( '/[^\+0-9\s]/', '', $customer->cm_phone );
		}
		
		/* Return */
		return $address;
	}
	
	/**
	 * Get first name for PayPal
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction
	 * @return	array
	 */
	protected function _getFirstName( \IPS\nexus\Transaction $transaction )
	{
		return $transaction->invoice->member->member_id ? $transaction->invoice->member->cm_first_name : $transaction->invoice->guest_data['member']['cm_first_name'];
	}
	
	/**
	 * Get last name for PayPal
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction
	 * @return	array
	 */
	protected function _getLastName( \IPS\nexus\Transaction $transaction )
	{
		return $transaction->invoice->member->member_id ? $transaction->invoice->member->cm_last_name : $transaction->invoice->guest_data['member']['cm_last_name'];
	}
	
	/**
	 * Get transaction data for PayPal
	 *
	 * @param	\IPS\nexus\Transaction	$transaction	Transaction
	 * @return	array
	 */
	protected function _getTransactions( \IPS\nexus\Transaction $transaction )
	{
		/* Init */
		$payPalTransactionData = array(
			'amount'	=> array(
				'currency'	=> $transaction->amount->currency,
				'total'		=> $transaction->amount->amountAsString(),
			),
			'invoice_number'=> \IPS\Settings::i()->site_secret_key . '-' . $transaction->id,
		);
		
		/* If we're paying the whole invoice, we can add item data... */
		if ( $transaction->amount->amount->compare( $transaction->invoice->total->amount ) === 0 )
		{
			$summary = $transaction->invoice->summary();
			
			/* Shipping / Tax */
			$payPalTransactionData['amount']['details'] = array(
				'shipping'	=> $summary['shippingTotal']->amountAsString(),
				'subtotal'	=> $summary['subtotal']->amountAsString(),
				'tax'		=> $summary['taxTotal']->amountAsString(),
			);

			/* Items */
			$payPalTransactionData['item_list'] = array( 'items' => array() );
			foreach ( $summary['items'] as $item )
			{
				$payPalTransactionData['item_list']['items'][] = array(
					'quantity'	=> $item->quantity,
					'name'		=> mb_strlen( $item->name ) > 127 ? mb_substr( $item->name, 0, 124 ) . '...' : $item->name,
					'price'		=> $item->price->amountAsString(),
					'currency'	=> $transaction->amount->currency,
				);
			}

			/* Shipping Address */
			if ( $transaction->invoice->shipaddress )
			{
				$payPalTransactionData['item_list']['shipping_address'] = array_merge(
					array( 'recipient_name'	=> $this->_getFirstName( $transaction ) . ' ' . $this->_getLastName( $transaction ) ),
					$this->_getAddress( $transaction->invoice->shipaddress, $transaction->invoice->member )
				);
			}
		}
		/* Otherwise just use a generic description */
		else
		{
			$payPalTransactionData['description'] = sprintf( $transaction->member->language()->get('partial_payment_desc'), $transaction->invoice->id );
		}
		
		return $payPalTransactionData;
	}
}
