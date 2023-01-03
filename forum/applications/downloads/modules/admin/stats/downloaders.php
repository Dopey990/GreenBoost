<?php
/**
 * @brief		Top Downloaders
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		17 Dec 2013
 */

namespace IPS\downloads\modules\admin\stats;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Top Downloaders
 */
class _downloaders extends \IPS\Dispatcher\Controller
{
	/**
	 * @brief	Number of results per page
	 */
	const PER_PAGE = 25;
	
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'downloaders_manage' );
		parent::execute();
	}

	/**
	 * Top Downloaders
	 *
	 * @return	void
	 */
	protected function manage()
	{
		$where = array( array( 'dmid>0' ) );
		
		if ( isset( \IPS\Request::i()->form ) )
		{
			$form = new \IPS\Helpers\Form( 'form', 'go' );

			$default = array(
				'start' => \IPS\Request::i()->start ? \IPS\DateTime::ts( \IPS\Request::i()->start ) : NULL,
				'end' => \IPS\Request::i()->end ? \IPS\DateTime::ts( \IPS\Request::i()->end ) : NULL
			);

			$form->add( new \IPS\Helpers\Form\DateRange( 'stats_date_range', $default, FALSE, array( 'start' => array( 'max' => \IPS\DateTime::ts( time() )->setTime( 0, 0, 0 ), 'time' => FALSE ), 'end' => array( 'max' => \IPS\DateTime::ts( time() )->setTime( 23, 59, 59 ), 'time' => FALSE ) ) ) );

			if ( !$values = $form->values() )
			{
				\IPS\Output::i()->output = $form;
				return;
			}
		}

		/* Figure out start and end parameters for links */
		$params = array(
			'start' => !empty( $values['stats_date_range']['start'] ) ? $values['stats_date_range']['start']->getTimestamp() : \IPS\Request::i()->start,
			'end' => !empty( $values['stats_date_range']['end'] ) ? $values['stats_date_range']['end']->getTimestamp() : \IPS\Request::i()->end
		);

		if( $params['start'] )
		{
			$where[] = array( 'dtime>?', $params['start'] );
		}

		if( $params['end'] )
		{
			$where[] = array( 'dtime<?', $params['end'] );
		}
		
		$page = isset( \IPS\Request::i()->page ) ? \intval( \IPS\Request::i()->page ) : 1;

		if( $page < 1 )
		{
			$page = 1;
		}

		$select = \IPS\Db::i()->select( 'dmid, count(*) as downloads', 'downloads_downloads', $where, 'downloads DESC', array( ( $page - 1 ) * static::PER_PAGE, static::PER_PAGE ), 'dmid', NULL, \IPS\Db::SELECT_SQL_CALC_FOUND_ROWS );
		$mids = array();
		
		foreach( $select as $row )
		{
			$mids[] = $row['dmid'];
		}
		
		$members = array();
		
		if ( \count( $mids ) )
		{
			$members = iterator_to_array( \IPS\Db::i()->select( '*', 'core_members', array( \IPS\Db::i()->in( 'member_id', $mids ) ) )->setKeyField('member_id') );
		}
		
		$pagination = \IPS\Theme::i()->getTemplate( 'global', 'core', 'global' )->pagination(
			\IPS\Http\Url::internal( 'app=downloads&module=stats&controller=downloaders' )->setQueryString( $params ),
			ceil( $select->count( TRUE ) / static::PER_PAGE ),
			$page,
			static::PER_PAGE,
			FALSE
		);
		
		\IPS\Output::i()->sidebar['actions'] = array(
			'settings'	=> array(
				'title'		=> 'stats_date_range',
				'icon'		=> 'calendar',
				'link'		=> \IPS\Http\Url::internal( 'app=downloads&module=stats&controller=downloaders&form=1' )->setQueryString( $params ),
				'data'		=> array( 'ipsDialog' => '', 'ipsDialog-title' => \IPS\Member::loggedIn()->language()->addToStack('stats_date_range') )
			)
		);
		
		\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate('stats')->downloadsTable( $select, $pagination, $members );
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('menu__downloads_stats_downloaders');
	}
}