<?php
/**
 * @brief		Dashboard extension: IV News
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		13 Aug 2013
 */

namespace IPS\core\extensions\core\Dashboard;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * @brief	Dashboard extension: IV News
 */
class _IVNews
{
	/**
	* Can the current user view this dashboard item?
	*
	* @return	bool
	*/
	public function canView()
	{
		return TRUE;
	}
	
	/**
	 * Return the block to show on the dashboard
	 *
	 * @return	string
	 */
	public function getBlock()
	{
		$ivNews = ( isset( \IPS\Data\Store::i()->iv_news ) ) ? json_decode( \IPS\Data\Store::i()->iv_news, TRUE ) : array();
		
		if( empty( $ivNews ) or $ivNews['time'] < ( time() - 1 ) ) //43200
		{
			try
			{
				$this->refreshNews();
				$ivNews = ( isset( \IPS\Data\Store::i()->iv_news ) ) ? json_decode( \IPS\Data\Store::i()->iv_news, TRUE ) : array();
			}
			catch ( \IPS\Http\Exception $e ) {}
			catch( \IPS\Http\Request\Exception $e ) {}
			catch( \RuntimeException $e ) {}
		}
		
		return \IPS\Theme::i()->getTemplate( 'dashboard' )->ipsNews( isset( $ivNews['content'] ) ? $ivNews['content'] : NULL );
	}

	/**
	 * Updates news store
	 *
	 * @return	void
	 * @throws	\IPS\Http\Request\Exception
	 */
	protected function refreshNews()
	{
		\IPS\Data\Store::i()->iv_news = json_encode( array(
			'content'	=> \IPS\Http\Url::iv( 'ivnews.php' )->request()->get()->decodeJson(),
			'time'		=> time()
		) );
	}
}