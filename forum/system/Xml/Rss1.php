<?php
/**
 * @brief		Class for reading an RSS 1.0 document
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		18 Dec 2015
 */

namespace IPS\Xml;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Class for reading an RSS 1.0 document
 */
class _Rss1 extends Rss
{	
	/**
	 * Fetch the date
	 *
	 * @param	object	$item	RSS item
	 * @return	NULL|\IPS\DateTime
	 */
	protected function getDate( $item )
	{
		$pubDate = NULL;

		/* If we use the Dublin Core (dc) namespace, we will probably have dc:date */
		$namespaces = $this->getNamespaces( TRUE );

		if( \in_array( 'http://purl.org/dc/elements/1.1/', $namespaces ) AND $item->children( $namespaces['dc'] )->date )
		{
			$pubDate	= \IPS\DateTime::ts( strtotime( $item->children( $namespaces['dc'] )->date ) );
		}

		return $pubDate ?: parent::getDate( $item );
	}

	/**
	 * Fetch the items
	 *
	 * @return	array
	 */
	protected function getItems()
	{
		return $this->item;
	}
}