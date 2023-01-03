<?php
/**
 * @brief		Referrals
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		15 Aug 2014
 */

namespace IPS\nexus\modules\front\clients;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Referrals
 */
class _referrals extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{		
		\IPS\Output::i()->cssFiles = array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'clients.css', 'nexus' ) );
		\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'front_clients.js', 'nexus', 'front' ) );
		\IPS\Output::i()->sidebar['enabled'] = FALSE;
		parent::execute();
	}

	/**
	 * View List
	 *
	 * @return	void
	 */
	protected function manage()
	{
		\IPS\Output::i()->breadcrumb[] = array( \IPS\Http\Url::internal( 'app=nexus&module=clients&controller=referrals', 'front', 'clientsreferrals' ), \IPS\Member::loggedIn()->language()->addToStack('client_referrals') );
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('client_referrals');
		\IPS\Output::i()->sidebar['enabled'] = FALSE;
			
		$url = \IPS\Http\Url::internal( 'app=nexus&module=promotion&controller=referral&id=' . \IPS\Member::loggedIn()->member_id, 'front', 'referral' );
		if ( isset( \IPS\Request::i()->target ) )
		{
			$url = $url->setQueryString( 'direct', base64_encode( \IPS\Request::i()->target ) );
		}
		
		$rules = new \IPS\nexus\CommissionRule\Iterator( new \IPS\Patterns\ActiveRecordIterator( \IPS\Db::i()->select( '*', 'nexus_referral_rules' ), 'IPS\nexus\CommissionRule' ), \IPS\Member::loggedIn() );
		
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('clients')->referrals( $url, $rules );
	}
}