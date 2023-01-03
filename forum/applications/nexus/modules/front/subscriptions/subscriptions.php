<?php
/**
 * @brief		subscriptions
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Commerce
 * @since		09 Feb 2018
 */

namespace IPS\nexus\modules\front\subscriptions;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * subscriptions
 */
class _subscriptions extends \IPS\Dispatcher\Controller
{
	/**
	 * Show the subscription packages
	 *
	 * @return	void
	 */
	protected function manage()
	{
		if ( ! \IPS\Settings::i()->nexus_subs_enabled )
		{ 
			\IPS\Output::i()->error( 'nexus_no_subs', '2X379/1', 404, '' );
		}
		
		/* Create the table */
		$table = new \IPS\nexus\Subscription\Table( \IPS\Http\Url::internal( 'app=nexus&module=subscriptions&controller=subscriptions', 'front', 'nexus_subscriptions' ) );
		
		\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'front_subscriptions.js', 'nexus', 'front' ) );
		\IPS\Output::i()->cssFiles = array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'subscriptions.css', 'nexus' ) );

		\IPS\Output::i()->breadcrumb['module'] = array( \IPS\Http\Url::internal( 'app=nexus&module=subscriptions&controller=subscriptions', 'front', 'nexus_subscriptions' ), \IPS\Member::loggedIn()->language()->addToStack('nexus_front_subscriptions') );

		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('nexus_front_subscriptions');
		\IPS\Output::i()->output = $table;
	}
	
	/**
	 * Change packages. It allows you to change packages. I mean again, the whole concept of PHPDoc seems to point out the obvious. A bit like GPS navigation for your front room. There's the sofa. There's the cat.
	 *
	 * @return void just like life, it is meaningless and temporary so live in the moment, enjoy each day and eat chocolate unless you have an allergy in which case don't. See your GP before starting any new diet.
	 */
	protected function change()
	{
		/* CSRF Check */
		\IPS\Session::i()->csrfCheck();
		
		try
		{
			$newPackage = \IPS\nexus\Subscription\Package::load( \IPS\Request::i()->id );
		}
		catch( \OutOfRangeException $e )
		{
			\IPS\Output::i()->error( 'nexus_no_subs_package', '2X379/2', 404, '' );
		}

		/* Is the subscription purchasable ? */
		if ( !$newPackage->enabled )
		{
			\IPS\Output::i()->error( 'node_error', '2X379/7', 403, '' );
		}

		
		try
		{
			$subscription = \IPS\nexus\Subscription::loadActiveByMember( \IPS\Member::loggedIn() );
		}
		catch( \OutOfRangeException $e )
		{
			\IPS\Output::i()->error( 'nexus_no_subs_subs', '2X379/3', 404, '' );
		}
		
		/* Fetch purchase */
		$purchase = NULL;
		foreach ( \IPS\nexus\extensions\nexus\Item\Subscription::getPurchases( \IPS\nexus\Customer::loggedIn(), $subscription->package->id ) as $row )
		{
			if ( $row->active and ! $row->cancelled )
			{
				$purchase = $row;
				break;
			}
		}
		
		if ( $purchase === NULL )
		{
			\IPS\Output::i()->error( 'nexus_sub_no_purchase', '2X379/4', 404, '' );
		}
		
		/* Right, that's all the "I'll tamper with the URLs for a laugh" stuff out of the way... */
		$upgradeCost = $newPackage->costToUpgrade( $subscription->package, \IPS\nexus\Customer::loggedIn() );
		
		if ( $upgradeCost === NULL )
		{
			\IPS\Output::i()->error( 'nexus_no_subs_nocost', '2X379/5', 404, '' );
		}
		
		$invoice = $subscription->package->upgradeDowngrade( $purchase, $newPackage );
		
		if ( $invoice )
		{
			\IPS\Output::i()->redirect( $invoice->checkoutUrl() );
		}
		
		$purchase->member->log( 'subscription', array( 'type' => 'change', 'id' => $purchase->id, 'old' => $purchase->name, 'name' => $newPackage->titleForLog(), 'system' => FALSE ) );
		
		\IPS\Output::i()->redirect( $purchase->url() );
	}
	
	/**
	 * Cancels a subscription doens't it? Yes it does.
	 *
	 * @return void
	 */
	protected function cancel()
	{
		/* CSRF Check */
		\IPS\Session::i()->csrfCheck();
		
		$package = \IPS\nexus\Subscription\Package::load( \IPS\Request::i()->id );
		$subscription = \IPS\nexus\Subscription::loadActiveByMember( \IPS\Member::loggedIn() );
		
		/* Build Form */
		$form = new \IPS\Helpers\Form;
		$form->class = 'ipsForm_vertical';
		
		if ( $subscription->renews )
		{
			$form->add( new \IPS\Helpers\Form\Radio( 'nexus_sub_cancel_type', 0, TRUE, array(
				'options'	=> array( 0 => 'nexus_sub_cancel_type_renewal', 1 => 'nexus_sub_cancel_type_immediate' )
			), NULL, NULL, NULL, 'nexus_sub_cancel_type' ) );
		}
		else
		{
			$form->add( new \IPS\Helpers\Form\YesNo( 'nexus_sub_cancel_now', 0, TRUE, array(), NULL, NULL, NULL, 'nexus_sub_cancel_now' ) );
		}
		
		if( $subscription->_expire )
		{
			\IPS\Member::loggedIn()->language()->words['nexus_sub_cancel_type_renewal_desc'] = \IPS\Member::loggedIn()->language()->addToStack( 'nexus_sub_cancel_type_renewal__desc', NULL, array( 'sprintf' => array( $subscription->_expire->dayAndMonth() . ' ' . $subscription->_expire->format('Y') ) ) );
		}
	
		if ( $values = $form->values() )
		{
			if ( ( isset( $values['nexus_sub_cancel_now'] ) and $values['nexus_sub_cancel_now'] ) or ( isset( $values['nexus_sub_cancel_type'] ) and $values['nexus_sub_cancel_type'] == 1 ) )
			{
				/* Cancel purchase */
				foreach ( \IPS\nexus\extensions\nexus\Item\Subscription::getPurchases( \IPS\nexus\Customer::loggedIn(), $package->id ) as $purchase )
				{
					$purchase->cancelled = TRUE;
					$purchase->member->log( 'purchase', array( 'type' => 'cancel', 'id' => $purchase->id, 'name' => $purchase->name ) );
					$purchase->can_reactivate = FALSE;
					$purchase->save();
				}
				
				/* Cancel the subscription */
				$package->cancelMember( \IPS\nexus\Customer::loggedIn() );
			}
			else if ( isset( $values['nexus_sub_cancel_type'] ) and $values['nexus_sub_cancel_type'] == 0 )
			{
				/* Just stop the renewal bud */
				$subscription->renews = 0;
				$subscription->save();
				
				/* Cancel renewals */
				foreach ( \IPS\nexus\extensions\nexus\Item\Subscription::getPurchases( \IPS\nexus\Customer::loggedIn(), $package->id ) as $purchase )
				{
					$purchase->renewals = NULL;
					$purchase->save();
				}

				$purchase->member->log( 'subscription', array( 'type' => 'cancelrenewals', 'id' => $package->id ) );
			}
			
			\IPS\Output::i()->redirect( \IPS\Http\Url::internal( 'app=nexus&module=subscriptions&controller=subscriptions', 'front', 'nexus_subscriptions' ) );
		}
		
		/* Display */
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('nexus_subs_cancel');
		\IPS\Output::i()->output = \IPS\Request::i()->isAjax() ? $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) ) : $form;
	}
	
	/**
	 * Purchase
	 *
	 * @return	void
	 */
	protected function purchase()
	{
		/* CSRF Check */
		\IPS\Session::i()->csrfCheck();
		
		/* Already purchased a subscription? */
		if ( \IPS\nexus\Subscription::loadActiveByMember( \IPS\nexus\Customer::loggedIn() ) )
		{
			\IPS\Output::i()->error( 'nexus_subs_already_got_package', '2X379/6', 403, '' );
		}
		
		$package = \IPS\nexus\Subscription\Package::load( \IPS\Request::i()->id );

		/* Is the subscription purchasable ? */
		if ( !$package->enabled )
		{
			\IPS\Output::i()->error( 'node_error', '2X379/7', 403, '' );
		}

		$price = $package->price();
		
		$item = new \IPS\nexus\extensions\nexus\Item\Subscription( \IPS\nexus\Customer::loggedIn()->language()->get( $package->_titleLanguageKey ), $price );
		$item->id = $package->id;
		try
		{
			$item->tax = \IPS\nexus\Tax::load( $package->tax );
		}
		catch ( \OutOfRangeException $e ) { }
		
		if ( $package->gateways !== '*' )
		{
			$item->paymentMethodIds = explode( ',', $package->gateways );
		}
		
		$item->renewalTerm = $package->renewalTerm( $price->currency );
		
		/* Generate the invoice */
		$invoice = new \IPS\nexus\Invoice;
		$invoice->currency = $price->currency;
		$invoice->member = \IPS\nexus\Customer::loggedIn();
		$invoice->addItem( $item );
		$invoice->return_uri = "app=nexus&module=subscriptions&controller=subscriptions";
		$invoice->save();
		
		/* Take them to it */
		\IPS\Output::i()->redirect( $invoice->checkoutUrl() );
	}

}