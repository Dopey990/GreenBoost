<?php
/**
 * @brief		PayPal Gateway
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		07 Mar 2014
 */

\define('REPORT_EXCEPTIONS', TRUE);
require_once '../../../../init.php';
\IPS\Session\Front::i();

/* Load Transaction */
try
{
	$transaction = \IPS\nexus\Transaction::load( \IPS\Request::i()->nexusTransactionId );
	
	if ( $transaction->status !== \IPS\nexus\Transaction::STATUS_PENDING )
	{
		throw new \OutofRangeException;
	}
}
catch ( \OutOfRangeException $e )
{
	\IPS\Output::i()->redirect( \IPS\Http\Url::internal( "app=nexus&module=checkout&controller=checkout&do=transaction&id=&t=" . \IPS\Request::i()->nexusTransactionId, 'front', 'nexus_checkout' ) );
}

/* Process */
try
{	
	/* Check fraud rules */
	$maxMind = NULL;
	if ( \IPS\Settings::i()->maxmind_key and ( !\IPS\Settings::i()->maxmind_gateways or \IPS\Settings::i()->maxmind_gateways == '*' or \in_array( $transaction->method->id, explode( ',', \IPS\Settings::i()->maxmind_gateways ) ) ) )
	{
		$maxMind = new \IPS\nexus\Fraud\MaxMind\Request;
		$maxMind->setTransaction( $transaction );
		$maxMind->setTransactionType( 'paypal' );
	}
	$fraudResult = $transaction->runFraudCheck( $maxMind );
	if ( $fraudResult === \IPS\nexus\Transaction::STATUS_REFUSED )
	{
		$transaction->executeFraudAction( $fraudResult, FALSE );
		$transaction->sendNotification();
		\IPS\Output::i()->redirect( $transaction->url() );
	}
	
	/* Billing Agreement */
	if ( isset( \IPS\Request::i()->billingAgreement ) )
	{
		/* Execute */
		$response = $transaction->method->api( "payments/billing-agreements/" . \IPS\Request::i()->token . "/agreement-execute" );
		$agreementId = $response['id'];
		if ( isset( $response['payer'] ) and isset( $response['payer']['status'] ) )
		{
			$extra = $transaction->extra;
			$extra['verified'] = $response['payer']['status'];
			$transaction->extra = $extra;
		}
				
		/* Create Billing Agreement */
		$billingAgreement = new \IPS\nexus\Gateway\PayPal\BillingAgreement;
		$billingAgreement->gw_id = $agreementId;
		$billingAgreement->method = $transaction->method;
		$billingAgreement->member = $transaction->member;
		$billingAgreement->started = \IPS\DateTime::create();
		$billingAgreement->next_cycle = \IPS\DateTime::create()->add( new \DateInterval( 'P' . $response['plan']['payment_definitions'][0]['frequency_interval'] . mb_substr( $response['plan']['payment_definitions'][0]['frequency'], 0, 1 ) ) );
		$billingAgreement->save();
		$transaction->billing_agreement = $billingAgreement;
		$transaction->save();
				
		/* Just save that we're waiting for the webhook */
		$transaction->status = \IPS\nexus\Transaction::STATUS_GATEWAY_PENDING;
		$transaction->save();
		$transaction->sendNotification();
		\IPS\Output::i()->redirect( $transaction->url() );
	}
	
	/* Normal */
	else
	{
		$response = $transaction->method->api( "payments/payment/{$transaction->gw_id}/execute", array(
			'payer_id'	=> \IPS\Request::i()->PayerID,
		) );
		$transaction->gw_id = $response['transactions'][0]['related_resources'][0]['authorization']['id']; // Was previously a payment ID. This sets it to the actual transaction ID for the authorization. At capture, it will be updated again to the capture transaction ID
		$transaction->auth = \IPS\DateTime::ts( strtotime( $response['transactions'][0]['related_resources'][0]['authorization']['valid_until'] ) );
		if ( isset( $response['payer'] ) and isset( $response['payer']['status'] ) )
		{
			$extra = $transaction->extra;
			$extra['verified'] = $response['payer']['status'];
			$transaction->extra = $extra;
		}
		$transaction->save();
	}
	
	/* Capture */
	if ( $fraudResult )
	{
		$transaction->executeFraudAction( $fraudResult, TRUE );
	}
	if ( !$fraudResult or $fraudResult === \IPS\nexus\Transaction::STATUS_PAID )
	{
		$memberJustCreated = $transaction->captureAndApprove();
		if ( $memberJustCreated )
		{
			\IPS\Session::i()->setMember( $memberJustCreated );
			\IPS\Member\Device::loadOrCreate( $memberJustCreated, FALSE )->updateAfterAuthentication( NULL );
		}
	}
	$transaction->sendNotification();
	
	/* Redirect */
	\IPS\Output::i()->redirect( $transaction->url() );
}
catch ( \Exception $e )
{
	\IPS\Log::log( $e, 'paypal' );
	\IPS\Output::i()->redirect( $transaction->invoice->checkoutUrl()->setQueryString( array( '_step' => 'checkout_pay', 'err' => $e->getMessage() ) ) );
}