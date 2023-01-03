<?php
/**
 * @brief		New Support Request Volumme
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		25 Apr 2014
 */

namespace IPS\nexus\modules\admin\support;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * volume
 */
class _volume extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'volume_manage' );
		parent::execute();
	}

	/**
	 * View
	 *
	 * @return	void
	 */
	protected function manage()
	{		
		$chart	= new \IPS\Helpers\Chart\Database( \IPS\Http\Url::internal( 'app=nexus&module=support&controller=volume' ), 'nexus_support_requests', 'r_started', '',
			array(
				'vAxis'		=> array( 'title' => \IPS\Member::loggedIn()->language()->addToStack('support_requests_created') ),
				'backgroundColor' 	=> '#ffffff',
				'colors'			=> array( '#10967e', '#ea7963', '#de6470', '#6b9dde', '#b09be4', '#eec766', '#9fc973', '#e291bf', '#55c1a6', '#5fb9da' ),
				'hAxis'				=> array( 'gridlines' => array( 'color' => '#f5f5f5' ) ),
				'lineWidth'			=> 1,
				'areaOpacity'		=> 0.4,
			),
			'AreaChart'
		);
		$chart->groupBy	= 'r_department';
		foreach( \IPS\nexus\Support\Department::roots() as $department )
		{
			$chart->addSeries( $department->_title, 'number', 'COUNT(*)', TRUE, $department->id );
		}

		$chart->tableInclude = array( 'r_id', 'r_title', 'r_member', 'r_department', 'r_status', 'r_started', 'r_last_reply', 'r_replies' );
		$chart->tableParsers = array(
			'r_member'	=> function( $val ) {
				return \IPS\Theme::i()->getTemplate('global', 'nexus')->userLink( \IPS\Member::load( $val ) );
			},
			'r_department'	=> function( $val ) {
				return \IPS\Member::loggedIn()->language()->addToStack( 'nexus_department_' . $val );
			},
			'r_status'	=> function( $val, $row )
			{
				return \IPS\Member::loggedIn()->language()->addToStack( 'nexus_status_' . $val . '_admin' );
			},
			'r_started'	=> function( $val ) {
				return \IPS\DateTime::ts( $val );
			},
			'r_last_reply'	=> function( $val ) {
				return \IPS\DateTime::ts( $val );
			}
		);

		\IPS\Output::i()->output = (string) $chart;
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__nexus_support_volume');
	}
}