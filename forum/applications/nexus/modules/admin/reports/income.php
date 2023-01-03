<?php
/**
 * @brief		Income Report
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		14 Aug 2014
 */

namespace IPS\nexus\modules\admin\reports;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Income Report
 */
class _income extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'income_manage' );
		parent::execute();
	}

	/**
	 * View Report
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$currencies = \IPS\nexus\Money::currencies();

		$tabs = array( 'totals' => 'nexus_report_income_totals' );

		if( \count( $currencies ) == 1 )
		{
			$tabs['members'] = 'nexus_report_income_members';
		}
		else
		{
			foreach ( $currencies as $currency )
			{
				$tabs[ 'members_' . $currency ] = \IPS\Member::loggedIn()->language()->addToStack( 'nexus_report_income_by_member', NULL, array( 'sprintf' => array( $currency ) ) );
			}
		}

		foreach ( $currencies as $currency )
		{
			$tabs[ $currency ] = \IPS\Member::loggedIn()->language()->addToStack( 'nexus_report_income_by_method', NULL, array( 'sprintf' => array( $currency ) ) );
		}
		
		$activeTab = ( isset( \IPS\Request::i()->tab ) and array_key_exists( \IPS\Request::i()->tab, $tabs ) ) ? \IPS\Request::i()->tab : 'totals';

		$chart = new \IPS\Helpers\Chart\Database(
			\IPS\Http\Url::internal( 'app=nexus&module=reports&controller=income&tab=' . $activeTab ),
			'nexus_transactions',
			't_date',
			'',
			array(
				'backgroundColor' 	=> '#ffffff',
				'colors'			=> array( '#10967e', '#ea7963', '#de6470', '#6b9dde', '#b09be4', '#eec766', '#9fc973', '#e291bf', '#55c1a6', '#5fb9da' ),
				'hAxis'				=> array( 'gridlines' => array( 'color' => '#f5f5f5' ) ),
				'lineWidth'			=> 1,
				'areaOpacity'		=> 0.4,
			),
			( \IPS\Request::i()->tab == 'members' ) ? 'PieChart' : 'AreaChart',
			'monthly',
			array( 'start' => 0, 'end' => 0 ),
			array(),
			$activeTab
		);
		$chart->where[] = array( '( t_status=? OR t_status=? ) AND t_method>0', \IPS\nexus\Transaction::STATUS_PAID, \IPS\nexus\Transaction::STATUS_PART_REFUNDED );
		
		if ( $activeTab === 'totals' )
		{
			$chart->groupBy = 't_currency';
			
			foreach ( $currencies as $currency )
			{
				$chart->addSeries( $currency, 'number', 'SUM(t_amount)-SUM(t_partial_refund)', TRUE, $currency );
			}
		}
		elseif( mb_strpos( $activeTab, 'members' ) === 0 )
		{
			$chart->groupBy = 't_member';

			if( \count( $currencies ) === 1 )
			{
				$chart->format = array_pop( $currencies );
			}
			else
			{
				$chart->format	= mb_substr( $activeTab, 8 );
				$chart->where[]	= array( 't_currency=?', $chart->format );
			}
			
			foreach ( \IPS\Db::i()->select( 't_member, SUM(t_amount)-SUM(t_partial_refund) as _amount', 'nexus_transactions', $chart->where, '_amount DESC', array( 0, 20 ), 't_member' ) as $member )
			{
				$chart->addSeries( \IPS\Member::load( $member['t_member'] )->name, 'number', 'SUM(t_amount)-SUM(t_partial_refund)', TRUE, $member['t_member'] );
			}
		}
		else
		{
			$chart->where[] = array( 't_currency=?', $activeTab );
			$chart->groupBy = 't_method';
			$chart->format = $activeTab;
			
			foreach ( \IPS\nexus\Gateway::roots() as $gateway )
			{
				$chart->addSeries( $gateway->_title, 'number', 'SUM(t_amount)-SUM(t_partial_refund)', TRUE, $gateway->id );
			}
		}

		$chart->tableInclude = array( 't_id', 't_member', 't_invoice', 't_method', 't_amount', 't_date' );
		$chart->tableParsers = array(
			't_member'	=> function( $val ) {
				return \IPS\Theme::i()->getTemplate('global', 'nexus')->userLink( \IPS\Member::load( $val ) );
			},
			't_method'	=> function( $val ) {
				if ( $val )
				{
					try
					{
						return \IPS\nexus\Gateway::load( $val )->_title;
					}
					catch ( \OutOfRangeException $e )
					{
						return '';
					}
				}
				else
				{
					return \IPS\Member::loggedIn()->language()->addToStack('account_credit');
				}
			},
			't_amount'	=> function( $val, $row )
			{
				return (string) new \IPS\nexus\Money( $val, $row['t_currency'] );
			},
			't_invoice'	=> function( $val )
			{
				try
				{
					return \IPS\Theme::i()->getTemplate('invoices', 'nexus')->link( \IPS\nexus\Invoice::load( $val ) );
				}
				catch ( \OutOfRangeException $e )
				{
					return '';
				}
			},
			't_date'	=> function( $val ) {
				return \IPS\DateTime::ts( $val );
			}
		);
		
		if ( \IPS\Request::i()->isAjax() )
		{
			\IPS\Output::i()->output = (string) $chart;
		}
		else
		{	
			\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__nexus_reports_income');
			\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'global', 'core' )->tabs( $tabs, $activeTab, (string) $chart, \IPS\Http\Url::internal( "app=nexus&module=reports&controller=income" ) );
		}
	}
}