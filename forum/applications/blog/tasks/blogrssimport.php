<?php
/**
 * @brief		blogrssimport Task
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Blog
 * @since		02 Apr 2014
 */

namespace IPS\blog\tasks;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * blogrssimport Task
 */
class _blogrssimport extends \IPS\Task
{
	/**
	 * Execute
	 *
	 * If ran successfully, should return anything worth logging. Only log something
	 * worth mentioning (don't log "task ran successfully"). Return NULL (actual NULL, not '' or 0) to not log (which will be most cases).
	 * If an error occurs which means the task could not finish running, throw an \IPS\Task\Exception - do not log an error as a normal log.
	 * Tasks should execute within the time of a normal HTTP request.
	 *
	 * @return	mixed	Message to log or NULL
	 * @throws	\IPS\Task\Exception
	 */
	public function execute()
	{
		try
		{
			$data = \IPS\Db::i()->select( '*', 'blog_rss_import', NULL, 'rss_last_import ASC', 1 )->first();
			$feed = \IPS\blog\Blog\Feed::constructFromData( $data );
			$feed->run();
		}
		/* UnderflowException means there's no feed, so we can disable the task */
		catch ( \UnderflowException $e )
		{
			\IPS\Db::i()->update( 'core_tasks', array( 'enabled' => 0 ), array( '`key`=?', 'blogrssimport' ) );
		}
		/* Any other exception means an error which should be logged */
		catch ( \Exception $e )
		{
			/* If there is an error, we need to log it but the error should not prevent other feeds from importing */
			if ( isset( $feed ) AND ( $feed instanceof \IPS\blog\Blog\Feed ) )
			{
				$feed->last_import = time();
				$feed->save();
			}
			
			return $e->getMessage();
		}
	}
	
	/**
	 * Cleanup
	 *
	 * If your task takes longer than 15 minutes to run, this method
	 * will be called before execute(). Use it to clean up anything which
	 * may not have been done
	 *
	 * @return	void
	 */
	public function cleanup()
	{
		
	}
}