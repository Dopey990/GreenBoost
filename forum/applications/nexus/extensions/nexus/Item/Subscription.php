<?php
/**
 * @brief		Subscriptions
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		09 Feb 2018
 */

namespace IPS\nexus\extensions\nexus\Item;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Subscriptions
 */
class _Subscription extends \IPS\nexus\Invoice\Item\Purchase
{
	/**
	 * @brief	Application
	 */
	public static $application = 'nexus';
	
	/**
	 * @brief	Application
	 */
	public static $type = 'subscription';
	
	/**
	 * @brief	Icon
	 */
	public static $icon = 'certificate';
	
	/**
	 * @brief	Title
	 */
	public static $title = 'nexus_member_subscription';
	
	/**
	 * Get Title
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	string
	 */
	public static function getTypeTitle( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			return \IPS\nexus\Subscription\Package::load( $purchase->item_id )->_title;
		}
		catch ( \Exception $e ) {}
		
		return NULL;
	}
	
	/**
	 * Image
	 *
	 * @return |IPS\File|NULL
	 */
	public function image()
	{
		try
		{
			if ( $photo = \IPS\nexus\Subscription\Package::load( $this->id )->image )
			{
				return \IPS\File::get( 'nexus_Products', $photo );
			}
		}
		catch ( \Exception $e ) {}
		
		return NULL;
	}
	
	/**
	 * Get Client Area Page HTML
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	array	array( 'packageInfo' => '...', 'purchaseInfo' => '...' )
	 */
	public static function clientAreaPage( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			$package = \IPS\nexus\Subscription\Package::load( $purchase->item_id );
			
			return array( 'packageInfo' => \IPS\Theme::i()->getTemplate( 'subscription', 'nexus' )->clientArea( $package ) );
		}
		catch ( \OutOfRangeException $e ) { }
		
		return NULL;
	}
	
	/**
	 * Image
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return |IPS\File|NULL
	 */
	public static function purchaseImage( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			if ( $photo = \IPS\nexus\Subscription\Package::load( $purchase->item_id )->image )
			{
				return \IPS\File::get( 'nexus_Products', $photo );
			}
		}
		catch ( \Exception $e ) {}
		
		return NULL;
	}
		
	/**
	 * URL
	 *
	 * @return |IPS\Http\Url|NULL
	 */
	public function url()
	{
		try
		{
			return \IPS\nexus\Subscription\Package::load( $this->id )->url();
		}
		catch ( \OutOfRangeException $e )
		{
			return NULL;
		}
	}
		
	/** 
	 * Get renewal payment methods IDs
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	array|NULL
	 */
	public static function renewalPaymentMethodIds( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			$package = \IPS\nexus\Subscription\Package::load( $purchase->item_id );
			if ( $package->gateways and $package->gateways != '*' )
			{
				return explode( ',', $package->gateways );
			}
			else
			{
				return NULL;
			}
		}
		catch ( \Exception $e ) {}

		return NULL;
	}

	/**
	 * Purchase can be renewed?
	 *
	 * @param	\IPS\nexus\Purchase $purchase	The purchase
	 * @return	boolean
	 */
	public static function canBeRenewed( \IPS\nexus\Purchase $purchase )
	{
		$package = \IPS\nexus\Subscription\Package::load( $purchase->item_id );
		$renewals = $package->renew_options ? json_decode( $package->renew_options, TRUE ) : array();

		return (boolean) \count( $renewals );
	}

	/**
	 * Can Renew Until
	 *
	 * @param	\IPS\nexus\Purchase		$purchase	The purchase
	 * @param	bool					$admin		If TRUE, is for ACP. If FALSE, is for front-end.
	 * @return	\IPS\DateTime|bool				TRUE means can renew as much as they like. FALSE means cannot renew at all. \IPS\DateTime means can renew until that date
	 */
	public static function canRenewUntil( \IPS\nexus\Purchase $purchase, $admin )
	{
		return static::canBeRenewed( $purchase );
	}
	
	/**
	 * On Purchase Generated
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @param	\IPS\nexus\Invoice	$invoice	The invoice
	 * @return	void
	 */
	public static function onPurchaseGenerated( \IPS\nexus\Purchase $purchase, \IPS\nexus\Invoice $invoice )
	{
		try
		{
			$subscription = \IPS\nexus\Subscription\Package::load( $purchase->item_id )->addMember( $purchase->member );
			$subscription->purchase_id = $purchase->id;
			$subscription->invoice_id = $invoice->id;
			$subscription->save();
		}
		catch ( \Exception $e ) {}
	}
	
	/**
	 * On Renew (Renewal invoice paid. Is not called if expiry data is manually changed)
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @param	int					$cycles		Cycles
	 * @return	void
	 */
	public static function onRenew( \IPS\nexus\Purchase $purchase, $cycles )
	{
		try
		{
			\IPS\nexus\Subscription\Package::load( $purchase->item_id )->renewMember( $purchase->member );
		}
		catch ( \Exception $e ) {}
	}
	
	/**
	 * On Purchase Expired
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public static function onExpire( \IPS\nexus\Purchase $purchase )
	{		
		try
		{
			\IPS\nexus\Subscription\Package::load( $purchase->item_id )->expireMember( $purchase->member );
		}
		catch ( \Exception $e ) {}
	}
	
	/**
	 * On Purchase Canceled
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public static function onCancel( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			\IPS\nexus\Subscription\Package::load( $purchase->item_id )->cancelMember( $purchase->member );
		}
		catch ( \Exception $e ) {}
	}
	
	/**
	 * Warning to display to admin when cancelling a purchase
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	string
	 */
	public static function onCancelWarning( \IPS\nexus\Purchase $purchase )
	{
		return NULL;
	}
	
	/**
	 * On Purchase Deleted
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public static function onDelete( \IPS\nexus\Purchase $purchase )
	{
		/* Do any cancellation cleanup needed */
		static::onCancel( $purchase );
		
		/* Delete the subscription row */
		try
		{
			$sub = \IPS\nexus\Subscription::loadByMemberAndPackage( $purchase->member, \IPS\nexus\Subscription\Package::load( $purchase->item_id ), FALSE );
			$sub->delete();
		}
		catch( \OutOfRangeException $e ) {}
	}
	
	/**
	 * On Purchase Reactivated (renewed after being expired or reactivated after being canceled)
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public static function onReactivate( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			$subs = \IPS\nexus\Subscription::loadByMemberAndPackage( $purchase->member, \IPS\nexus\Subscription\Package::load( $purchase->item_id ), FALSE );
			$subs->active = 1;
			$subs->save();
		}
		catch( \OutOfRangeException $e ) { }
		
		try
		{
			\IPS\nexus\Subscription\Package::load( $purchase->item_id )->addMember( $purchase->member );
		}
		catch ( \Exception $e ) {}
	}
	
	/**
	 * On Transfer (is ran before transferring)
	 *
	 * @param	\IPS\nexus\Purchase	$purchase		The purchase
	 * @param	\IPS\Member			$newCustomer	New Customer
	 * @return	void
	 */
	public static function onTransfer( \IPS\nexus\Purchase $purchase, \IPS\Member $newCustomer )
	{
		try
		{
			$package = \IPS\nexus\Subscription\Package::load( $purchase->item_id );
		}
		catch ( \OutOfRangeException $e )
		{
			return;
		}
				
		/* Remove the old member's record */
		$package->removeMember( $purchase->member, FALSE );
		
		/* Now if the purchase isn't cancelled... */
		if ( !$purchase->cancelled )
		{
			/* We need the Customer object */
			$newCustomer = \IPS\nexus\Customer::load( $newCustomer->member_id );
			
			/* Remove any request/invitation for the new member and add the new record */
			$package->removeMember( $newCustomer, FALSE );
			$package->addMember( $newCustomer, FALSE );
		}
	}
	
	/**
	 * Generate Invoice Form
	 *
	 * @param	\IPS\Helpers\Form	$form		The form
	 * @param	\IPS\nexus\Invoice	$invoice	The invoice
	 * @return	void
	 */
	public static function form( \IPS\Helpers\Form $form, \IPS\nexus\Invoice $invoice )
	{
		$form->add( new \IPS\Helpers\Form\Node( 'nexus_sub_package', NULL, TRUE, array( 'class' => 'IPS\nexus\Subscription\Package') ) );
	}
	
	/**
	 * Create From Form
	 *
	 * @param	array				$values		Values from form
	 * @param	\IPS\nexus\Invoice	$invoice	The invoice
	 * @return	\IPS\nexus\extensions\nexus\Item\Subscription
	 */
	public static function createFromForm( array $values, \IPS\nexus\Invoice $invoice )
	{
		$package = $values['nexus_sub_package'];
		
		$fee = $package->price( $invoice->currency );
		
		$item = new \IPS\nexus\extensions\nexus\Item\Subscription( $invoice->member->language()->get( $package->_titleLanguageKey ), $fee );
		$item->id = $package->id;
		try
		{
			$item->tax = $package->tax ? \IPS\nexus\Tax::load( $package->tax ) : NULL;
		}
		catch ( \OutOfRangeException $e ) { }
		
		if ( $package->gateways !== '*' )
		{
			$item->paymentMethodIds = explode( ',', $package->gateways );
		}
		
		$item->renewalTerm = $package->renewalTerm( $fee->currency );
		
		return $item;
	}
	
	/**
	 * Requires Billing Address
	 *
	 * @return	bool
	 * @throws	\DomainException
	 */
	public function requiresBillingAddress()
	{
		return TRUE;
	}
}