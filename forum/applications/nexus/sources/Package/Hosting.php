<?php
/**
 * @brief		Hosting Package
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		29 Apr 2014
 */

namespace IPS\nexus\Package;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Hosting Package
 */
class _Hosting extends \IPS\nexus\Package
{
	/**
	 * @brief	Database Table
	 */
	protected static $packageDatabaseTable = 'nexus_packages_hosting';
	
	/**
	 * @brief	Which columns belong to the local table
	 */
	protected static $packageDatabaseColumns = array( 'p_queue', 'p_quota', 'p_ip', 'p_cgi', 'p_frontpage', 'p_hasshell', 'p_maxftp', 'p_maxsql', 'p_maxpop', 'p_maxlst', 'p_maxsub', 'p_maxpark', 'p_maxaddon', 'p_bwlimit' );
	
	/**
	 * @brief	Icon
	 */
	public static $icon = 'cloud';
	
	/**
	 * @brief	Title
	 */
	public static $title = 'hosting_account';
	
	/**
	 * Get additional name info
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	array
	 */
	public function getPurchaseNameInfo( \IPS\nexus\Purchase $purchase )
	{
		$stickyCustomFields = array_filter( parent::getPurchaseNameInfo( $purchase ) );
		
		if ( !\count( $stickyCustomFields ) )
		{
			try
			{
				return array( \IPS\nexus\Hosting\Account::load( $purchase->id )->domain );
			}
			catch ( \Exception $e )
			{
				return parent::getPurchaseNameInfo( $purchase );
			}
		}
		
		return $stickyCustomFields;
	}
	
	/* !ACP Package Form */
	
	/**
	 * ACP Fields
	 *
	 * @param	\IPS\nexus\Package	$package	The package
	 * @param	bool				$custom		If TRUE, is for a custom package
	 * @param	bool				$customEdit	If TRUE, is editing a custom package
	 * @return	array
	 */
	public static function acpFormFields( \IPS\nexus\Package $package, $custom=FALSE, $customEdit=FALSE )
	{
		$return = array();
		
		if ( !$customEdit ) // The acpEdit method will add these fields
		{
			$return['package_settings']['queue'] = new \IPS\Helpers\Form\Node( 'p_queue', $package->type === 'hosting' ? $package->queue : NULL, NULL, array( 'class' => 'IPS\nexus\Hosting\Queue' ), function( $val )
			{
				if ( !$val and \IPS\Request::i()->p_type == 'hosting' )
				{
					throw new \DomainException('form_required');
				}
			} );
			
			$return['package_settings']['quota'] = new \IPS\Helpers\Form\Number( 'p_quota', $package->type === 'hosting' ? $package->quota : -1, NULL, array( 'unlimited' => -1 ), NULL, NULL, 'MB' );
			$return['package_settings']['bwlimit'] = new \IPS\Helpers\Form\Number( 'p_bwlimit', $package->type === 'hosting' ? $package->bwlimit : -1, NULL, array( 'unlimited' => -1 ), NULL, NULL, \IPS\Member::loggedIn()->language()->get('mb_per_month') );
			$return['package_settings']['maxftp'] = new \IPS\Helpers\Form\Number( 'p_maxftp', $package->type === 'hosting' ? $package->maxftp : -1, FALSE, array( 'unlimited' => -1 ) );
			$return['package_settings']['maxsql'] = new \IPS\Helpers\Form\Number( 'p_maxsql', $package->type === 'hosting' ? $package->maxsql : -1, FALSE, array( 'unlimited' => -1 ) );
			$return['package_settings']['maxpop'] = new \IPS\Helpers\Form\Number( 'p_maxpop', $package->type === 'hosting' ? $package->maxpop : -1, FALSE, array( 'unlimited' => -1 ) );
			$return['package_settings']['maxlst'] = new \IPS\Helpers\Form\Number( 'p_maxlst', $package->type === 'hosting' ? $package->maxlst : -1, FALSE, array( 'unlimited' => -1 ) );
			$return['package_settings']['maxsub'] = new \IPS\Helpers\Form\Number( 'p_maxsub', $package->type === 'hosting' ? $package->maxsub : -1, FALSE, array( 'unlimited' => -1 ) );
			$return['package_settings']['maxpark'] = new \IPS\Helpers\Form\Number( 'p_maxpark', $package->type === 'hosting' ? $package->maxpark : -1, FALSE, array( 'unlimited' => -1 ) );
			$return['package_settings']['maxaddon'] = new \IPS\Helpers\Form\Number( 'p_maxaddon', $package->type === 'hosting' ? $package->maxaddon : -1, FALSE, array( 'unlimited' => -1 ) );
			$return['package_settings']['ip'] = new \IPS\Helpers\Form\YesNo( 'p_ip', $package->type === 'hosting' ? $package->ip : 0 );
			$return['package_settings']['cgi'] = new \IPS\Helpers\Form\YesNo( 'p_cgi', $package->type === 'hosting' ? $package->cgi : 0 );
			$return['package_settings']['frontpage'] = new \IPS\Helpers\Form\YesNo( 'p_frontpage', $package->type === 'hosting' ? $package->frontpage : 0 );
			$return['package_settings']['hasshell'] = new \IPS\Helpers\Form\YesNo( 'p_hasshell', $package->type === 'hosting' ? $package->hasshell : 0 );
		}

		return $return;
	}
	
	/**
	 * [Node] Format form values from add/edit form for save
	 *
	 * @param	array	$values	Values from the form
	 * @return	array
	 */
	public function formatFormValues( $values )
	{
		if( isset( $values['p_queue'] ) )
		{
			$values['p_queue'] = \is_object( $values['p_queue'] ) ? $values['p_queue']->id : $values['p_queue'];
		}
		
		return parent::formatFormValues( $values );
	}
	
	/**
	 * Updateable fields
	 *
	 * @return	array
	 */
	public static function updateableFields()
	{
		return array_merge( parent::updateableFields(), array(
			'quota',
			'bwlimit',
			'hasshell',
			'maxftp',
			'maxsql',
			'maxpop',
			'maxlst',
			'maxsub',
			'maxpark',
			'maxaddon',
		) );
	}
	
	/**
	 * Update existing purchases
	 *
	 * @param	\IPS\nexus\Purchase	$purchase							The purchase
	 * @param	array				$changes							The old values
	 * @param	bool				$cancelBillingAgreementIfNecessary	If making changes to renewal terms, TRUE will cancel associated billing agreements. FALSE will skip that change
	 * @return	void
	 */
	public function updatePurchase( \IPS\nexus\Purchase $purchase, $changes, $cancelBillingAgreementIfNecessary=FALSE )
	{
		/* Get current values */
		try
		{
			$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
		
			$update = array();
			foreach ( array( 'quota' => 'diskspaceAllowance', 'bwlimit' => 'monthlyBandwidthAllowance', 'maxftp' => 'maxFtpAccounts', 'maxsql' => 'maxDatabases', 'maxpop' => 'maxEmailAccounts', 'maxlst' => 'maxMailingLists', 'maxsub' => 'maxSubdomains', 'maxpark' => 'maxParkedDomains', 'maxaddon' => 'maxAddonDomains', 'hasshell' => 'hasSSHAccess' ) as $k => $method )
			{
				if ( isset( $changes[ $k ] ) )
				{
					/* What is the current value? */
					$currentValue = $account->$method();
					if ( $currentValue === NULL )
					{
						$currentValue = -1;
					}
					elseif ( $k == 'quota' or $k == 'bwlimit' )
					{
						$currentValue = $currentValue / 1048576 / 1.024;
					}
					
					/* If the current value matches the old value (i.e. it hasn't been manually modified), set it to the new value */
					if ( $currentValue == $changes[ $k ] )
					{
						$update[ 'p_' . $k ] = $this->$k;
					}
				}
			}
								
			if ( !empty( $update ) )
			{
				$account->edit( $update );
			}
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			$e->log();
			return parent::updatePurchase( $purchase, $changes, $cancelBillingAgreementIfNecessary );
		}
		catch ( \Exception $e )
		{
			return parent::updatePurchase( $purchase, $changes, $cancelBillingAgreementIfNecessary );
		}
		
		/* Call parent */
		return parent::updatePurchase( $purchase, $changes, $cancelBillingAgreementIfNecessary );
	}
	
	/* !Store */
	
	/**
	 * Store Form
	 *
	 * @param	\IPS\Helpers\Form	$form			The form
	 * @param	string				$memberCurrency	The currency being used
	 * @return	void
	 */
	public function storeForm( \IPS\Helpers\Form $form, $memberCurrency )
	{
		/* We need to know now the server this will go on so we have the right nameservers */
		try
		{
			$queue = \IPS\nexus\Hosting\Queue::load( $this->queue );
			$server = $queue->activeServer();
		}
		catch ( \OutOfRangeException $e )
		{
			\IPS\Output::i()->error( 'hosting_err_public', '4X245/2', 500, 'hosting_err_no_queue' );
		}
		catch ( \UnderflowException $e )
		{
			\IPS\Output::i()->error( 'hosting_err_public', '4X245/3', 500, 'hosting_err_no_server' );
		}
		
		/* Add the options to the form */
		$this->_domainForm( $form, $memberCurrency, $server, $this->maxpark === -1 or $this->maxpark > 0 );
	}
	
	/**
	 * Domain Form
	 *
	 * @param	\IPS\Helpers\Form			$form			The form
	 * @param	string						$memberCurrency	The currency being used
	 * @param	\IPS\nexus\Hosting\Server	$server			The server the account will be used on
	 * @param	bool						$canParkDomains	If the account can park domains (which controls if CNAME is an option)
	 * @return	
	 */
	protected function _domainForm( \IPS\Helpers\Form $form, $memberCurrency, \IPS\nexus\Hosting\Server $server, $canParkDomains )
	{
		$form->hiddenValues['server'] = $server->id;
		
		/* What options are available? */
		$domainTypeOptions = array();
		$domainTypeToggles = array();
		$domainPrices = NULL;
		if ( \IPS\Settings::i()->nexus_enom_un and $domainPrices = json_decode( \IPS\Settings::i()->nexus_domain_prices, TRUE ) and \count( $domainPrices ) )
		{
			$domainTypeOptions['buy'] = 'ha_domain_buy';
			$domainTypeToggles['buy'] = array( 'ha_domain_to_buy' );
		}
		if ( \IPS\Settings::i()->nexus_hosting_subdomains )
		{
			$domainTypeOptions['sub'] = 'ha_domain_sub';
			$domainTypeToggles['sub'] = array( 'ha_subdomain_to_use' );
		}
		if ( \IPS\Settings::i()->nexus_hosting_allow_own_domain )
		{
			$ownDomainOptions = explode( ',', \IPS\Settings::i()->nexus_hosting_own_domains );
			if ( \in_array( 'nameservers', $ownDomainOptions ) or \in_array( 'aname', $ownDomainOptions ) or ( \in_array( 'cname', $ownDomainOptions ) and $canParkDomains ) )
			{
				$domainTypeOptions['own'] = 'ha_domain_own';
				$domainTypeToggles['own'] = array( 'ha_domain_to_use' );
			}
		}
		if ( !\count( $domainTypeOptions ) )
		{
			\IPS\Output::i()->error( 'hosting_err_public', '4X245/4', 500, 'hosting_err_no_domains' );
		}
		if ( \count( $domainTypeOptions ) > 1 )
		{
			$form->add( new \IPS\Helpers\Form\Radio( 'ha_domain_type', NULL, TRUE, array( 'options' => $domainTypeOptions, 'toggles' => $domainTypeToggles ) ) );
		}
		else
		{
			$_domainTypeOptions = array_keys( $domainTypeOptions );
			$form->hiddenValues['ha_domain_type'] = array_pop( $_domainTypeOptions );
		}
		
		/* Buy Domain */
		if ( array_key_exists( 'buy', $domainTypeOptions ) )
		{
			$form->add( new \IPS\Helpers\Form\Custom( 'ha_domain_to_buy', NULL, NULL, array(
				'getHtml'	=> function( $field ) use ( $domainPrices, $memberCurrency )
				{
					$prices = array();
					foreach ( $domainPrices as $tld => $_prices )
					{
						$prices[ $tld ] = new \IPS\nexus\Money( $_prices[ $memberCurrency ]['amount'], $memberCurrency );
					}
					
					return \IPS\Theme::i()->getTemplate('store')->domainBuy( $field, $prices );
				},
				'validate'	=> function( $field )
				{
					if ( \IPS\Request::i()->ha_domain_type === 'buy' )
					{
						if ( !$field->value['tld'] or !$field->value['sld'] )
						{
							throw new \DomainException('form_required');
						}
						else
						{
							$enom = new \IPS\nexus\DomainRegistrar\Enom( \IPS\Settings::i()->nexus_enom_un, \IPS\Settings::i()->nexus_enom_pw );
							if ( !$enom->check( $field->value['sld'], $field->value['tld'] ) )
							{
								throw new \DomainException('domain_not_available');
							}
						}
					}
				}
			), NULL, NULL, NULL, 'ha_domain_to_buy' ) );
		}
		
		/* Choose Subdomain */
		if ( array_key_exists( 'sub', $domainTypeOptions ) )
		{
			$form->add( new \IPS\Helpers\Form\Custom( 'ha_subdomain_to_use', NULL, NULL, array(
				'getHtml'	=> function( $field )
				{
					return \IPS\Theme::i()->getTemplate( 'store', 'nexus' )->domainSub( $field );
				},
				'validate'	=> function( $field )
				{
					if ( \IPS\Request::i()->ha_domain_type === 'sub' )
					{
						if ( !$field->value['subdomain'] or !$field->value['domain'] )
						{
							throw new \DomainException('form_required');
						}
						else
						{
							/* Subdomain cannot contain any dots */
							if ( mb_strpos( $field->value['subdomain'], '.' ) !== FALSE )
							{
								throw new \DomainException('subdomain_cannot_contain_dot');
							}
							
							/* Subdomain cannot contain any spaces */
							if ( mb_strpos( $field->value['subdomain'], ' ' ) !== FALSE )
							{
								throw new \DomainException('subdomain_cannot_contain_space');
							}
							
							/* Subdomain cannot contain any of these characters: ~!$&'()*+,;= */
							if ( (bool) preg_match( '/([~!\$&\'\"\(\)\*\+,;=]+)/', $field->value['subdomain'] ) )
							{
								throw new \DomainException('subdomain_invalid_characters');
							}
							
							return static::_validateDomain( $field->value['subdomain'] . '.' . $field->value['domain'] );
						}
					}
				}
			), NULL, NULL, NULL, 'ha_subdomain_to_use' ) );
		}
		
		/* Use own domain */
		if ( array_key_exists( 'own', $domainTypeOptions ) )
		{
			$form->add( new \IPS\Helpers\Form\Text( 'ha_domain_to_use', NULL, NULL, array(), function( $value )
			{
				if ( \IPS\Request::i()->ha_domain_type === 'own' )
				{
					if ( !$value )
					{
						throw new \DomainException('form_required');
					}
					else
					{
						static::_validateDomain( $value );
						
						try
						{
							$domain = new \IPS\nexus\DomainLookup( $value, FALSE );
							
							$options = explode( ',', \IPS\Settings::i()->nexus_hosting_own_domains );
							if ( $domain->type === \IPS\nexus\DomainLookup::DOMAIN_TYPE_DOMAIN )
							{
								if ( !\in_array( 'nameservers', $options ) )
								{
									throw new \DomainException('domain_must_be_subdomain');
								}
							}
							else
							{
								if ( !\in_array( 'aname', $options ) and ( !\in_array( 'cname', $options ) or !$this->maxpark ) )
								{
									throw new \DomainException('domain_cannot_be_subdomain');
								}
							}
						}
						catch ( \DomainException $e )
						{
							throw $e;
						}
						catch ( \Exception $e )
						{
							throw new \DomainException('domain_not_valid');
						}
						
					}
				}
			}, 'http://', NULL, 'ha_domain_to_use' ) );			
		}
	}
	
	/**
	 * Additional Secondary Page to show on checkout
	 *
	 * @param	array		$values			Values from store form
	 * @param	string|NULL	$cnameTarget	If a there is known target for a CNAME, otherwise NULL to assign a domain
	 * @return	string|NULL
	 */
	public function storeAdditionalPage( array $values, $cname=NULL )
	{
		if ( $values['ha_domain_type'] === 'own' )
		{
			$domain = new \IPS\nexus\DomainLookup( $values['ha_domain_to_use'] );
			$options = explode( ',', \IPS\Settings::i()->nexus_hosting_own_domains );
			
			if ( $domain->type === \IPS\nexus\DomainLookup::DOMAIN_TYPE_DOMAIN )
			{
				if ( !\in_array( 'nameservers', $options ) )
				{
					throw new \DomainException('domain_must_be_subdomain');
				}
				
				$nameservers = explode( ',', mb_strtolower( \IPS\Settings::i()->nexus_hosting_nameservers ) );
				$isValid = FALSE;
				
				foreach ( $domain->whois as $whoisData )
				{
					$whoisData = mb_strtolower( $whoisData );
					foreach( $nameservers as $nameserver )
					{
						if ( mb_strpos( $whoisData, $nameserver ) !== FALSE )
						{
							$isValid = TRUE;
							break;
						}
					}
				}
				
				if ( !$isValid )
				{
					return \IPS\Theme::i()->getTemplate( 'hosting', 'nexus', 'front' )->ownDomainName( $values, $nameservers );
				}
			}
			else
			{
				$aname = NULL;
				if ( \in_array( 'aname', $options ) )
				{
					$aname = \IPS\nexus\Hosting\Server::load( $values['server'] )->ip;
				}
				
				if ( \in_array( 'cname', $options ) and $this->maxpark )
				{
					if ( !$cname )
					{
						$cname = isset( $values['cname'] ) ? $values['cname'] : str_replace( '.', '', mb_strpos( $domain->sld, '.' ) ? mb_substr( $domain->sld, mb_strpos( $domain->sld, '.' ) ) : $domain->sld );
						
						while ( \IPS\Db::i()->select( 'COUNT(*)', 'nexus_hosting_accounts', array( 'account_domain=? AND account_exists=1', $cname . '.' . \IPS\Settings::i()->nexus_hosting_own_domain_sub ) )->first() )
						{
							$cname .= rand( 0, 9 );
						}
						
						$values['cname'] = $cname;
						$cname .= '.' . \IPS\Settings::i()->nexus_hosting_own_domain_sub;
					}
				}
				
				$isValid = FALSE;
				$records = @dns_get_record( $values['ha_domain_to_use'], 0 + ( $aname ? DNS_A : 0 ) + ( $cname ? DNS_CNAME : 0 ) );
				if ( \is_array( $records ) )
				{
					foreach ( $records as $record )
					{
						if ( $aname and $record['type'] === 'A' and $record['ip'] === $aname )
						{
							$isValid = TRUE;
							break;
						}
						if ( $cname and $record['type'] === 'CNAME' and $record['target'] === $cname )
						{
							$isValid = TRUE;
							break;
						}
					}
				}
				
				if ( !$isValid )
				{
					return \IPS\Theme::i()->getTemplate( 'hosting', 'nexus', 'front' )->ownDomainName( $values, NULL, $cname, $aname );
				}
			}
		}
		
		return NULL;
	}
	
	/**
	 * Add To Cart
	 *
	 * @param	\IPS\nexus\extensions\nexus\Item\Package	$item			The item
	 * @param	array										$values			Values from form
	 * @param	string										$memberCurrency	The currency being used
	 * @return	array	Additional items to add
	 */
	public function addToCart( \IPS\nexus\extensions\nexus\Item\Package $item, array $values, $memberCurrency )
	{
		try
		{
			$server = \IPS\nexus\Hosting\Server::load( \IPS\Request::i()->server );
			if ( !\in_array( $this->queue, explode( ',', $server->queues ) ) )
			{
				throw new \OutOfRangeException;
			}
		}
		catch ( \OutOfRangeException $e )
		{
			\IPS\Output::i()->error( 'generic_error', '3X245/5', 403, 'hosting_no_server' );
		}
		$item->extra['server'] = $server->id;
				
		switch ( $values['ha_domain_type'] )
		{
			case 'buy':
				$domainPrices = json_decode( \IPS\Settings::i()->nexus_domain_prices, TRUE );
				$cost = new \IPS\nexus\Money( $domainPrices[ $values['ha_domain_to_buy']['tld'] ][ $memberCurrency ]['amount'], $memberCurrency );
				
				$tax = NULL;
				if ( \IPS\Settings::i()->nexus_domain_tax )
				{
					try
					{
						$tax = \IPS\nexus\Tax::load( \IPS\Settings::i()->nexus_domain_tax );
					}
					catch ( \OutOfRangeException $e ) { }
				}
				
				$domain = new \IPS\nexus\extensions\nexus\Item\Domain( $values['ha_domain_to_buy']['sld'] . '.' . $values['ha_domain_to_buy']['tld'], $cost );
				$domain->renewalTerm = new \IPS\nexus\Purchase\RenewalTerm( $cost, new \DateInterval('P1Y'), $tax );
				$domain->paymentMethodIds = $item->paymentMethodIds;
				$domain->tax = $tax;
				$domain->extra = array_merge( $values['ha_domain_to_buy'], array( 'nameservers' => $server->nameservers() ) );
				$item->extra['domain'] = $values['ha_domain_to_buy']['sld'] . '.' . $values['ha_domain_to_buy']['tld'];
				return array( $domain );
			
			case 'sub':
				$item->extra['domain'] = $values['ha_subdomain_to_use']['subdomain'] . '.' . $values['ha_subdomain_to_use']['domain'];
				return array();
				
			case 'own':
				if ( isset( \IPS\Request::i()->cname ) )
				{
					$item->extra['domain'] = \IPS\Request::i()->cname . '.' . \IPS\Settings::i()->nexus_hosting_own_domain_sub;
					$item->extra['parkDomain'] = $values['ha_domain_to_use'];
				}
				else
				{
					$item->extra['domain'] = $values['ha_domain_to_use'];
				}
				return array();
		}
	}
	
	/**
	 * Validate a domain
	 *
	 * @param	string	$domain	The domain
	 * @return	void
	 * @throws	\DomainException
	 */
	public static function _validateDomain( $domain )
	{
		$domain = mb_strtolower( $domain );				
		$data = @parse_url( 'http://' . $domain );
		if ( $data === FALSE or $data['host'] != $domain or !\IPS\Http\Url::validateComponent( \IPS\Http\Url::COMPONENT_HOST, $domain ) )
		{
			throw new \DomainException('domain_not_valid');
		}
		
		try
		{
			\IPS\Db::i()->select( '*', 'nexus_hosting_accounts', array( 'account_domain=? AND account_exists=1', $domain ) )->first();
			throw new \DomainException('domain_not_available');
		}
		catch ( \UnderflowException $e ) { }
	}
		
	/* !ACP */
	
	/**
	 * ACP Generate Invoice Form
	 *
	 * @param	\IPS\Helpers\Form	$form	The form
	 * @param	string				$k		The key to add to the field names
	 * @return	void
	 */
	public function generateInvoiceForm( \IPS\Helpers\Form $form, $k )
	{
		$class = \get_class();
		$field = new \IPS\Helpers\Form\Text( 'ha_domain_to_use' . $k, NULL, NULL, array(), function( $value ) use ( $class )
		{
			if ( !$value )
			{
				throw new \DomainException('form_required');
			}
			else
			{
				return $class::_validateDomain( $value );
			}
		}, 'http://', NULL, 'ha_domain_to_use' );
		$field->label = \IPS\Member::loggedIn()->language()->addToStack( 'ha_domain_to_use' );
		$form->add( $field );
	}
	
	/**
	 * ACP Add to invoice
	 *
	 * @param	\IPS\nexus\extensions\nexus\Item\Package	$item			The item
	 * @param	array										$values			Values from form
	 * @param	string										$k				The key to add to the field names
	 * @param	\IPS\nexus\Invoice							$invoice		The invoice
	 * @return	void
	 */
	public function acpAddToInvoice( \IPS\nexus\extensions\nexus\Item\Package $item, array $values, $k, \IPS\nexus\Invoice $invoice )
	{
		try
		{
			$item->extra['server'] = \IPS\nexus\Hosting\Queue::load( $this->queue )->activeServer()->id;
		}
		catch ( \UnderflowException $e )
		{			
			\IPS\Output::i()->error( 'hosting_no_server', '3X245/6', 403, '' );
		}		
		$item->extra['domain'] = $values[ 'ha_domain_to_use' . $k ];
	}
	
	/**
	 * Get ACP Page HTML
	 *
	 * @return	string
	 */
	public function acpPage( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
			
			$bandwidthAddons = new \IPS\Patterns\ActiveRecordIterator( \IPS\Db::i()->select( '*', 'nexus_purchases', array( 'ps_app=? AND ps_type=? AND ps_parent=?', 'nexus', 'bandwidth', $purchase->id ) ), 'IPS\nexus\Purchase' );
			
			return \IPS\Theme::i()->getTemplate('purchases')->hosting( $purchase, $account, $bandwidthAddons );
		}
		catch ( \OutOfRangeException $e )
		{
			return parent::acpPage( $purchase );
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			return \IPS\Theme::i()->getTemplate('purchases')->hostingNoConnect( $purchase, $account );
		}
	}
	
	/** 
	 * ACP Edit Form
	 *
	 * @param	\IPS\nexus\Purchase				$purchase	The purchase
	 * @param	\IPS\Helpers\Form				$form	The form
	 * @param	\IPS\nexus\Purchase\RenewalTerm	$renewals	The renewal term
	 * @return	string
	 */
	public function acpEdit( \IPS\nexus\Purchase $purchase, \IPS\Helpers\Form $form, $renewals )
	{
		parent::acpEdit( $purchase, $form, $renewals );
		
		try
		{
			$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
			if ( $account->exists )
			{	
				\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'admin_hosting.js', 'nexus', 'admin' ) );
				$form->attributes['data-controller'] = 'nexus.admin.hosting.accountform';
							
				$form->addHeader('hosting_account');
				$form->add( new \IPS\Helpers\Form\Node( 'account_server', $account->server, TRUE, array( 'class' => 'IPS\nexus\Hosting\Server' ) ) );
				$form->add( new \IPS\Helpers\Form\Text( 'account_username', $account->username, TRUE, array(), NULL, NULL, \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) );
				$form->add( new \IPS\Helpers\Form\Text( 'account_password', $account->password, TRUE, array(), NULL, NULL, \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) );
				$form->add( new \IPS\Helpers\Form\Text( 'account_domain', $account->domain, TRUE, array(), NULL, NULL, \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_quota', ( $account->diskspaceAllowance() !== NULL ? \intval( $account->diskspaceAllowance() ) / 1048576 / 1.024 : -1 ), TRUE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ), NULL, NULL, 'MB' ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_bwlimit', ( $account->monthlyBandwidthAllowance() !== NULL ? \intval( $account->monthlyBandwidthAllowance() ) / 1048576 / 1.024 : -1 ), TRUE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ), NULL, NULL, \IPS\Member::loggedIn()->language()->get('mb_per_month') ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_maxftp', ( $account->maxFtpAccounts() !== NULL ? \intval( $account->maxFtpAccounts() ) : -1 ), FALSE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_maxsql', ( $account->maxDatabases() !== NULL ? \intval( $account->maxDatabases() ) : -1 ), FALSE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_maxpop', ( $account->maxEmailAccounts() !== NULL ? \intval( $account->maxEmailAccounts() ) : -1 ), FALSE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_maxlst', ( $account->maxMailingLists() !== NULL ? \intval( $account->maxMailingLists() ) : -1 ), FALSE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_maxsub', ( $account->maxSubdomains() !== NULL ? \intval( $account->maxSubdomains() ) : -1 ), FALSE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_maxpark', ( $account->maxParkedDomains() !== NULL ? \intval( $account->maxParkedDomains() ) : -1 ), FALSE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_maxaddon', ( $account->maxAddonDomains() !== NULL ? \intval( $account->maxAddonDomains() ) : -1 ), FALSE, array( 'unlimited' => -1, 'endSuffix' => \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) ) );
				$form->add( new \IPS\Helpers\Form\YesNo( 'p_hasshell', $account->hasSSHAccess(), FALSE, array(), NULL, NULL, \IPS\Theme::i()->getTemplate('hosting')->accountEditWarning() ) );
				$form->add( new \IPS\Helpers\Form\Custom( 'do_not_update_server', FALSE, FALSE, array(
					'rowHtml'	=> function()
					{
						return \IPS\Theme::i()->getTemplate('hosting')->accountEditToggle();
					}
				) ) );
				
				if ( \count( \IPS\Db::i()->select( '*', 'nexus_purchases', array( 'ps_app=? AND ps_type=? AND ps_parent=?', 'nexus', 'bandwidth', $purchase->id ) ) ) )
				{
					\IPS\Member::loggedIn()->language()->words['p_bwlimit_warning'] = \IPS\Member::loggedIn()->language()->addToStack('bandwidth_edit_warn');
				}
			}
		}
		catch ( \Exception $e ) { }
	}
	
	/** 
	 * ACP Edit Save
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @param	array				$values		Values from form
	 * @return	string
	 */
	public function acpEditSave( \IPS\nexus\Purchase $purchase, array $values )
	{
		try
		{
			$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
			if ( $account->exists )
			{
				/* Update server if necessary */
				if ( $values['account_server']->id != $account->server->id )
				{
					$account->server = $values['account_server'];
					$account->save();
				}
				
				/* Send API calls */
				if ( !$values['do_not_update_server'] )
				{
					try
					{
						$account->edit( $values );
					}
					catch ( \IPS\nexus\Hosting\Exception $e )
					{
						\IPS\Output::i()->error( $e->getMessage(), '3X245/1', 503, '' );
					}
				}
			
				/* Update local database */
				$account->username = $values['account_username'];
				$account->password = $values['account_password'];
				$account->domain = $values['account_domain'];
				$account->save();
			}
		}
		catch ( \Exception $e ) { }
			
		parent::acpEditSave( $purchase, $values );
	}
	
	/**
	 * ACP Action
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	string|void
	 */
	public function acpAction( \IPS\nexus\Purchase $purchase )
	{
		switch ( \IPS\Request::i()->act )
		{
			case 'bandwidth':
				$bandwidthOptions = json_decode( \IPS\Settings::i()->nexus_hosting_bandwidth, TRUE );
				
				$form = new \IPS\Helpers\Form('bwinvoice', 'send');
				$form->addMessage( \IPS\Member::loggedIn()->language()->addToStack( 'admin_bandwidth_invoice_info', FALSE, array( 'sprintf' => $purchase->acpUrl()->setQueryString('do', 'edit') ) ), 'ipsMessage ipsMessage_info', FALSE );
				$options = array();
				foreach ( $bandwidthOptions as $amount => $prices )
				{
					if ( $purchase->renewal_currency and isset( $prices[ $purchase->renewal_currency ] ) )
					{
						$currency = $purchase->renewal_currency;
					}
					elseif ( $memberDefaultCurrency = $purchase->member->defaultCurrency() and isset( $prices[ $memberDefaultCurrency ] ) )
					{
						$currency = $memberDefaultCurrency;
					}
					else
					{
						foreach ( $prices as $currency => $value ) break; // That looks weird but it's right
					}
					
					$options[ $amount ] = \IPS\Member::loggedIn()->language()->addToStack( 'bandwidth_purchase_option_admin', FALSE, array( 'sprintf' => array( \IPS\Output\Plugin\Filesize::humanReadableFilesize( $amount * 1000000, TRUE ), (string) new \IPS\nexus\Money( $prices[ $currency ]['amount'], $currency ) ) ) );
				}
				$options['x'] = 'other';
				$form->add( new \IPS\Helpers\Form\Radio( 'bandwidth_to_buy', NULL, TRUE, array( 'options' => $options, 'toggles' => array( 'x' => array( 'bandwidth_to_buy_amount', 'p_base_price', 'bandwidth_expire' ) ) ) ) );
				$form->add( new \IPS\Helpers\Form\Number( 'bandwidth_to_buy_amount', 0, NULL, array(), NULL, NULL, 'MB', 'bandwidth_to_buy_amount' ) );
				$form->add( new \IPS\Helpers\Form\Number( 'p_base_price', NULL, NULL, array( 'decimals' => TRUE ), NULL, NULL, $purchase->renewal_currency ?: $purchase->member->defaultCurrency(), 'p_base_price' ) );
				$form->add( new \IPS\Helpers\Form\Date( 'bandwidth_expire', \IPS\DateTime::create()->add( new \DateInterval('P1M') ), NULL, array(), NULL, NULL, NULL, 'bandwidth_expire' ) );
				
				if ( $values = $form->values() )
				{
					if ( $values['bandwidth_to_buy'] === 'x' )
					{
						$item = new \IPS\nexus\extensions\nexus\Item\Bandwidth(
							sprintf( $purchase->member->language()->get('bandwidth_purchase_name'), \IPS\Output\Plugin\Filesize::humanReadableFilesize( $values['bandwidth_to_buy_amount'] * 1000000, TRUE ) ),
							new \IPS\nexus\Money( $values['p_base_price'], $purchase->renewal_currency ?: $purchase->member->defaultCurrency() )
						);
						$item->parent = $purchase;
						$item->expireDate = $values['bandwidth_expire'];
						$item->extra['bwAmount'] = $values['bandwidth_to_buy_amount'];
					}
					else
					{
						if ( $purchase->renewal_currency and isset( $bandwidthOptions[ $values['bandwidth_to_buy'] ][ $purchase->renewal_currency ] ) )
						{
							$currency = $purchase->renewal_currency;
						}
						elseif ( $memberDefaultCurrency = $purchase->member->defaultCurrency() and isset( $bandwidthOptions[ $values['bandwidth_to_buy'] ][ $memberDefaultCurrency ] ) )
						{
							$currency = $memberDefaultCurrency;
						}
						else
						{
							foreach ( $bandwidthOptions[ $values['bandwidth_to_buy'] ] as $currency => $value ) break; // That looks weird but it's right
						}
						
						$item = new \IPS\nexus\extensions\nexus\Item\Bandwidth(
							sprintf( $purchase->member->language()->get('bandwidth_purchase_name'), \IPS\Output\Plugin\Filesize::humanReadableFilesize( $values['bandwidth_to_buy'] * 1000000, TRUE, FALSE, TRUE ) ),
							new \IPS\nexus\Money( $bandwidthOptions[ $values['bandwidth_to_buy'] ][ $currency ]['amount'], $currency )
						);
						$item->parent = $purchase;
						$item->expireDate = \IPS\DateTime::create()->add( new \DateInterval('P1M') );
						$item->extra['bwAmount'] = $values['bandwidth_to_buy'];
					}
										
					$invoice = new \IPS\nexus\Invoice;
					$invoice->member = $purchase->member;
					$invoice->addItem( $item );
					$invoice->return_uri = "app=nexus&module=clients&controller=purchases&do=view&id={$purchase->id}";
					$invoice->save();
					$invoice->sendNotification();
					
					\IPS\Output::i()->redirect( $invoice->acpUrl() );
				}
				
				return (string) $form;
			break;
			
			default:
				return parent::acpAction( $purchase );
			break;
		}
	}
	
	/**
	 * Get ACP Support View HTML
	 *
	 * @return	string
	 */
	public function acpSupportView( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
						
			return \IPS\Theme::i()->getTemplate('purchases')->hostingSupport( $purchase, $account );
		}
		catch ( \Exception $e )
		{
			return '';
		}
	}
	
	/* !Client Area */
	
	/**
	 * Show Purchase Record?
	 *
	 * @return	bool
	 */
	public function showPurchaseRecord()
	{
		return TRUE;
	}
	
	/**
	 * Get Client Area Page HTML
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	array( 'packageInfo' => '...', 'purchaseInfo' => '...' )
	 */
	public function clientAreaPage( \IPS\nexus\Purchase $purchase )
	{
		$parent = parent::clientAreaPage( $purchase );
		
		return array(
			'packageInfo'	=> $parent['packageInfo'],
			'purchaseInfo'	=> $parent['purchaseInfo'] . $this->acpPage( $purchase ) /* Although we're calling acpPage, it will use the front-end templates */,
		);
	}
	
	/**
	 * Client Area Action
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	string
	 */
	public function clientAreaAction( \IPS\nexus\Purchase $purchase )
	{
		switch ( \IPS\Request::i()->act )
		{
			case 'changepass':
				$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
				$form = new \IPS\Helpers\Form;
				$form->add( new \IPS\Helpers\Form\Password( 'new_password' ) );
				if ( $values = $form->values() )
				{
					try
					{
						$account->changePassword( $values['new_password'] );
					}
					catch ( \IPS\nexus\Hosting\Exception $e )
					{
						\IPS\Output::i()->error( 'generic_error', '4X245/7', 500, $e->getMessage() );
					}
					$account->password = $values['new_password'];
					$account->save();
					return;
				}
				return $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) );
			
			case 'bandwidth':
				$bandwidthOptions = json_decode( \IPS\Settings::i()->nexus_hosting_bandwidth, TRUE );
				
				$form = new \IPS\Helpers\Form;
				$options = array();
				foreach ( $bandwidthOptions as $amount => $prices )
				{
					if ( $purchase->renewal_currency and isset( $prices[ $purchase->renewal_currency ] ) )
					{
						$currency = $purchase->renewal_currency;
					}
					elseif ( $memberDefaultCurrency = $purchase->member->defaultCurrency() and isset( $prices[ $memberDefaultCurrency ] ) )
					{
						$currency = $memberDefaultCurrency;
					}
					else
					{
						foreach ( $prices as $currency => $value ) break; // That looks weird but it's right
					}
					
					$options[ $amount ] = \IPS\Member::loggedIn()->language()->addToStack( 'bandwidth_purchase_option', FALSE, array( 'sprintf' => array( \IPS\Output\Plugin\Filesize::humanReadableFilesize( $amount * 1000000, TRUE ), (string) new \IPS\nexus\Money( $prices[ $currency ]['amount'], $currency ) ) ) );
				}
				$form->add( new \IPS\Helpers\Form\Radio( 'bandwidth_to_buy', NULL, TRUE, array( 'options' => $options ) ) );
				
				if ( $values = $form->values() )
				{
					if ( $purchase->renewal_currency and isset( $bandwidthOptions[ $values['bandwidth_to_buy'] ][ $purchase->renewal_currency ] ) )
					{
						$currency = $purchase->renewal_currency;
					}
					elseif ( $memberDefaultCurrency = $purchase->member->defaultCurrency() and isset( $bandwidthOptions[ $values['bandwidth_to_buy'] ][ $memberDefaultCurrency ] ) )
					{
						$currency = $memberDefaultCurrency;
					}
					else
					{
						foreach ( $bandwidthOptions[ $values['bandwidth_to_buy'] ] as $currency => $value ) break; // That looks weird but it's right
					}
					
					$item = new \IPS\nexus\extensions\nexus\Item\Bandwidth(
						sprintf( $purchase->member->language()->get('bandwidth_purchase_name'), \IPS\Output\Plugin\Filesize::humanReadableFilesize( $values['bandwidth_to_buy'] * 1000000, TRUE ) ),
						new \IPS\nexus\Money( $bandwidthOptions[ $values['bandwidth_to_buy'] ][ $currency ]['amount'], $currency )
					);
					$item->parent = $purchase;
					$item->expireDate = \IPS\DateTime::create()->add( new \DateInterval('P1M') );
					$item->extra['bwAmount'] = $values['bandwidth_to_buy'];
					
					$invoice = new \IPS\nexus\Invoice;
					$invoice->member = \IPS\nexus\Customer::loggedIn();
					$invoice->addItem( $item );
					$invoice->return_uri = "app=nexus&module=clients&controller=purchases&do=view&id={$purchase->id}";
					
					\IPS\Output::i()->redirect( $invoice->checkoutUrl() );
					return;
				}
				
				return $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) );
				
			case 'changedomain':
				$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
				$form = new \IPS\Helpers\Form( 'change_domain', 'change_domain' );
				$currency = $purchase->renewals ? $purchase->renewals->cost->currency : $purchase->defaultCurrency();
				try
				{
					$activeDomainPurchase = \IPS\nexus\Purchase::constructFromData( \IPS\Db::i()->select( '*', 'nexus_purchases', array( 'ps_parent=? AND ps_app=? AND ps_type=? AND ps_active=1', $purchase->id, 'nexus', 'domain' ) )->first() );
				}
				catch ( \UnderflowException $e )
				{
					$activeDomainPurchase = NULL;
				}
				$this->_domainForm( $form, $currency, $account->server, $account->maxParkedDomains() === NULL or $this->maxpark === -1 or $this->maxpark > $account->maxParkedDomains() );
				if ( $values = $form->values() )
				{					
					if ( $additionalPage = $this->storeAdditionalPage( $_POST, $account->domain ) )
					{
						return \IPS\Theme::i()->getTemplate( 'purchases', 'nexus', 'front' )->hostingChangeDomain( $purchase, $additionalPage, $activeDomainPurchase );
					}
					else
					{
						switch ( $values['ha_domain_type'] )
						{
							case 'buy':
								$domainPrices = json_decode( \IPS\Settings::i()->nexus_domain_prices, TRUE );
								$cost = new \IPS\nexus\Money( $domainPrices[ $values['ha_domain_to_buy']['tld'] ][ $currency ]['amount'], $currency );
								
								$tax = NULL;
								if ( \IPS\Settings::i()->nexus_domain_tax )
								{
									try
									{
										$tax = \IPS\nexus\Tax::load( \IPS\Settings::i()->nexus_domain_tax );
									}
									catch ( \OutOfRangeException $e ) { }
								}
								
								$domain = new \IPS\nexus\extensions\nexus\Item\Domain( $values['ha_domain_to_buy']['sld'] . '.' . $values['ha_domain_to_buy']['tld'], $cost );
								$domain->renewalTerm = new \IPS\nexus\Purchase\RenewalTerm( $cost, new \DateInterval('P1Y'), $tax );
								$domain->paymentMethodIds = $item->paymentMethodIds;
								$domain->tax = $tax;
								$domain->extra = array_merge( $values['ha_domain_to_buy'], array( 'nameservers' => $server->nameservers(), 'registerTo' => $purchase->id ) );
								
								$invoice = new \IPS\nexus\Invoice;
								$invoice->currency = $currency;
								$invoice->member = \IPS\Member::loggedIn();
								$invoice->addItem( $domain );
								$invoice->save();
								
								\IPS\Output::i()->redirect( $invoice->checkoutUrl() );
							
							case 'sub':
								$account->domain = $values['ha_subdomain_to_use']['subdomain'] . '.' . $values['ha_subdomain_to_use']['domain'];
								$account->save();
								$account->changeDomain( $account->domain );
								return array();
								
							case 'own':
								if ( isset( \IPS\Request::i()->cname ) )
								{
									$account->changeDomain( $values['ha_domain_to_use'], TRUE );
								}
								else
								{
									$account->domain = $values['ha_domain_to_use'];
									$account->save();
									$account->changeDomain( $account->domain );
								}
								return array();
						}
					}
				}
				return \IPS\Theme::i()->getTemplate( 'purchases', 'nexus', 'front' )->hostingChangeDomain( $purchase, $form, $activeDomainPurchase );
				
			default:
				return parent::clientAreaAction( $purchase );
		}
	}
	
	/* !Actions */
	
	/**
	 * On Purchase Generated
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @param	\IPS\nexus\Invoice	$invoice	The invoice
	 * @return	void
	 */
	public function onPurchaseGenerated( \IPS\nexus\Purchase $purchase, \IPS\nexus\Invoice $invoice )
	{
		/* Get Server */
		try
		{
			$server = \IPS\nexus\Hosting\Server::load( $purchase->extra['server'] );
			if ( !\in_array( $this->queue, explode( ',', $server->queues ) ) )
			{
				throw new \OutOfRangeException;
			}
		}
		catch ( \OutOfRangeException $e )
		{
			try
			{
				$server = \IPS\nexus\Hosting\Queue::load( $this->queue )->activeServer();
			}
			catch ( \Exception $e )
			{
				return;
			}
		}
		
		/* Create Account */
		try
		{
			/* Create a username */
			$username = mb_substr( preg_replace("/[^a-z\s]/", '', mb_strtolower( $purchase->extra['domain'] ) ), 0, 8 );
			while ( mb_strlen( $username ) < 8 )
			{
				$username .= \chr( rand( 97, 122 ) );
			}
			
			/* If it already exists, change characters until we get one that doesn't */
			do
			{
				$select = \IPS\Db::i()->select( '*', 'nexus_hosting_accounts', array( 'account_username=? AND account_server=?', $username, $server->id ) );			
				if ( \count( $select ) or !$server->checkUsername( $username ) )
				{
					$charToChange = rand( 0, 8 - 1 );
					$username[ $charToChange ] = \chr( rand( 97, 122 ) );
				}
			}
			while ( \count( $select ) or !$server->checkUsername( $username ) );
			
			/* Create the account */
			$availableTypes = \IPS\nexus\Hosting\Account::accountTypes();
			$class = $availableTypes[ mb_ucfirst( $server->type ) ];
			$account = new $class;
			$account->ps_id = $purchase->id;
			$account->server = $server;
			$account->domain = $purchase->extra['domain'];
			$account->username = $username;
			$account->create( $this, $invoice->member );
			$account->save();
			
			/* Park a domain if necessary */
			if ( isset( $purchase->extra['parkDomain'] ) )
			{
				$account->parkDomain( $purchase->extra['parkDomain'] );
			}
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			/* If it fails - try a different server */
			try
			{
				$account->server = \IPS\nexus\Hosting\Queue::load( $this->queue )->activeServer();
				$account->create( $this, $invoice->member );
			}
			/* If it failed, log the original error */
			catch ( \Exception $f )
			{
				$e->log();
			}
		}
	}
		
	/**
	 * On Purchase Expired
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public function onExpire( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			if ( \IPS\Settings::i()->nexus_hosting_terminate )
			{
				\IPS\nexus\Hosting\Account::load( $purchase->id )->suspend();
			}
			else
			{
				$purchase->cancelled = TRUE;
				$purchase->can_reactivate = FALSE;
				$purchase->save();
			}
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			$e->log();
		}
	}
	
	/**
	 * On Purchase Reactivated (renewed after being expired or reactivated after being canceled)
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public function onReactivate( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			\IPS\nexus\Hosting\Account::load( $purchase->id )->unsuspend();
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			$e->log();
		}
	}
	
	/**
	 * On Purchase Canceled
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public function onCancel( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
			$account->terminate();
			$account->exists = FALSE;
			$account->save();
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			$e->log();
		}
	}

	/**
	 * Warning to display to admin when cancelling a purchase
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	string
	 */
	public function onCancelWarning( \IPS\nexus\Purchase $purchase )
	{
		if ( \IPS\Settings::i()->nexus_hosting_terminate )
		{
			if ( \IPS\Settings::i()->nexus_hosting_terminate == -1 )
			{
				return \IPS\Member::loggedIn()->language()->addToStack( 'hosting_cancel_warning_nt' );
			}
			else
			{
				return \IPS\Member::loggedIn()->language()->addToStack( 'hosting_cancel_warning', FALSE, array( 'pluralize' => array( \IPS\Settings::i()->nexus_hosting_terminate ) ) );
			}
		}
		else
		{
			return \IPS\Member::loggedIn()->language()->addToStack( 'hosting_cancel_warning_at' );
		}
	}
	
	/**
	 * On Purchase Deleted
	 *
	 * @param	\IPS\nexus\Purchase	$purchase	The purchase
	 * @return	void
	 */
	public function onDelete( \IPS\nexus\Purchase $purchase )
	{
		try
		{
			$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
			if ( $account->exists )
			{
				$account->terminate();
			}
			$account->delete();
		}
		catch ( \IPS\nexus\Hosting\Exception $e )
		{
			$e->log();
		}
		catch ( \OutOfRangeException $e ) { }
	}
	
	
	/**
	 * On Upgrade/Downgrade
	 *
	 * @param	\IPS\nexus\Purchase							$purchase				The purchase
	 * @param	\IPS\nexus\Package							$newPackage				The package to upgrade to
	 * @param	int|NULL|\IPS\nexus\Purchase\RenewalTerm	$chosenRenewalOption	The chosen renewal option
	 * @return	void
	 */
	public function onChange( \IPS\nexus\Purchase $purchase, \IPS\nexus\Package $newPackage, $chosenRenewalOption = NULL )
	{
		$account = \IPS\nexus\Hosting\Account::load( $purchase->id );
		
		$update = array();
		foreach ( array( 'quota' => 'diskspaceAllowance', 'bwlimit' => 'monthlyBandwidthAllowance', 'maxftp' => 'maxFtpAccounts', 'maxsql' => 'maxDatabases', 'maxpop' => 'maxEmailAccounts', 'maxlst' => 'maxMailingLists', 'maxsub' => 'maxSubdomains', 'maxpark' => 'maxParkedDomains', 'maxaddon' => 'maxAddonDomains', 'hasshell' => 'hasSSHAccess' ) as $k => $method )
		{
			/* What is the current value? */
			$currentValue = $account->$method();
			if ( $currentValue === NULL )
			{
				$currentValue = -1;
			}
			elseif ( $k == 'quota' or $k == 'bwlimit' )
			{
				$currentValue = $currentValue / 1048576 / 1.024;
			}
			
			/* If the current value matches the old package (i.e. it hasn't been manually modified), set it to the new package */
			if ( $currentValue == $this->$k )
			{
				$update[ 'p_' . $k ] = $newPackage->$k;
			}			
		}
				
		if ( !empty( $update ) )
		{
			$account->edit( $update );
		}
		
		parent::onChange( $purchase, $newPackage, $chosenRenewalOption );
	}
}