<?php
/**
 * @brief		Album Comment Model
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Gallery
 * @since		17 Mar 2017
 */

namespace IPS\gallery\Album;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Album Comment Model
 */
class _Comment extends \IPS\Content\Comment implements \IPS\Content\EditHistory, \IPS\Content\Hideable, \IPS\Content\Searchable, \IPS\Content\Embeddable
{
	use \IPS\Content\Reactable, \IPS\Content\Reportable;

	/**
	 * @brief	[ActiveRecord] Multiton Store
	 */
	protected static $multitons;
	
	/**
	 * @brief	[Content\Comment]	Item Class
	 */
	public static $itemClass = 'IPS\gallery\Album\Item';
	
	/**
	 * @brief	[ActiveRecord] Database Table
	 */
	public static $databaseTable = 'gallery_album_comments';
	
	/**
	 * @brief	[ActiveRecord] Database Prefix
	 */
	public static $databasePrefix = 'comment_';
	
	/**
	 * @brief	Database Column Map
	 */
	public static $databaseColumnMap = array(
		'item'				=> 'album_id',
		'author'			=> 'author_id',
		'author_name'		=> 'author_name',
		'content'			=> 'text',
		'date'				=> 'post_date',
		'ip_address'		=> 'ip_address',
		'edit_time'			=> 'edit_time',
		'edit_member_name'	=> 'edit_name',
		'edit_show'			=> 'append_edit',
		'approved'			=> 'approved'
	);
	
	/**
	 * @brief	Application
	 */
	public static $application = 'gallery';
	
	/**
	 * @brief	Title
	 */
	public static $title = 'gallery_album_comment';
	
	/**
	 * @brief	Icon
	 */
	public static $icon = 'camera';

	/**
	 * @brief	[Content]	Key for hide reasons
	 */
	public static $hideLogKey = 'gallery-albums';
	
	/**
	 * Get items with permisison check
	 *
	 * @note	We override in order to provide checking against album restrictions
	 * @param	array		$where				Where clause
	 * @param	string		$order				MySQL ORDER BY clause (NULL to order by date)
	 * @param	int|array	$limit				Limit clause
	 * @param	string		$permissionKey		A key which has a value in the permission map (either of the container or of this class) matching a column ID in core_permission_index
	 * @param	mixed		$includeHiddenItems	Include hidden comments? NULL to detect if currently logged in member has permission, -1 to return public content only, TRUE to return unapproved content and FALSE to only return unapproved content the viewing member submitted
	 * @param	int			$queryFlags			Select bitwise flags
	 * @param	\IPS\Member	$member				The member (NULL to use currently logged in member)
	 * @param	bool		$joinContainer		If true, will join container data (set to TRUE if your $where clause depends on this data)
	 * @param	bool		$joinComments		If true, will join comment data (set to TRUE if your $where clause depends on this data)
	 * @param	bool		$joinReviews		If true, will join review data (set to TRUE if your $where clause depends on this data)
	 * @param	bool		$countOnly				If true will return the count
	 * @param	array|null	$joins					Additional arbitrary joins for the query
	 * @return	array|NULL|\IPS\Content\Comment		If $limit is 1, will return \IPS\Content\Comment or NULL for no results. For any other number, will return an array.
	 */
	public static function getItemsWithPermission( $where=array(), $order=NULL, $limit=10, $permissionKey='read', $includeHiddenItems=\IPS\Content\Hideable::FILTER_AUTOMATIC, $queryFlags=0, \IPS\Member $member=NULL, $joinContainer=FALSE, $joinComments=FALSE, $joinReviews=FALSE, $countOnly=FALSE, $joins=NULL )
	{
		$where[] = \IPS\gallery\Album\Item::getItemsWithPermissionWhere( $where, $member, $joins );
		return parent::getItemsWithPermission( $where, $order, $limit, $permissionKey, $includeHiddenItems, $queryFlags, $member, $joinContainer, $joinComments, $joinReviews, $countOnly, $joins );
	}
	
	/**
	 * Get URL for doing stuff
	 *
	 * @param	string|NULL		$action		Action
	 * @return	\IPS\Http\Url
	 */
	public function url( $action='find' )
	{
		return parent::url( $action )->setQueryString( 'tab', 'comments' );
	}

	/**
	 * Do stuff after creating (abstracted as comments and reviews need to do different things)
	 *
	 * @return	void
	 */
	public function postCreate()
	{
		parent::postCreate();

		$this->item()->asNode()->setLastComment( $this );
		$this->item()->asNode()->save();
	}

	/**
	 * Get snippet HTML for search result display
	 *
	 * @param	array		$indexData		Data from the search index
	 * @param	array		$authorData		Basic data about the author. Only includes columns returned by \IPS\Member::columnsForPhoto()
	 * @param	array		$itemData		Basic data about the item. Only includes columns returned by item::basicDataColumns()
	 * @param	array|NULL	$containerData	Basic data about the container. Only includes columns returned by container::basicDataColumns()
	 * @param	array		$reputationData	Array of people who have given reputation and the reputation they gave
	 * @param	int|NULL	$reviewRating	If this is a review, the rating
	 * @param	string		$view			'expanded' or 'condensed'
	 * @return	callable
	 */
	public static function searchResultSnippet( array $indexData, array $authorData, array $itemData, array $containerData = NULL, array $reputationData, $reviewRating, $view )
	{
		$url = \IPS\Http\Url::internal( \IPS\gallery\Album\Item::$urlBase . $indexData['index_item_id'], 'front', \IPS\gallery\Album\Item::$urlTemplate, \IPS\Http\Url\Friendly::seoTitle( $indexData['index_title'] ?: $itemData[ \IPS\gallery\Album\Item::$databasePrefix . \IPS\gallery\Album\Item::$databaseColumnMap['title'] ] ) );
		$images	= ( isset( $itemData['extra'] ) AND \count( $itemData['extra'] ) ) ? $itemData['extra'] : array();
				
		return \IPS\Theme::i()->getTemplate( 'global', 'gallery', 'front' )->searchResultAlbumCommentSnippet( $indexData, $itemData, $images, $url, $reviewRating, $view == 'condensed' );
	}

	/**
	 * @brief	A classname applied to the search result block
	 */
	public static $searchResultClassName = 'cGalleryAlbumSearchResult';

	/**
	 * Reaction Type
	 *
	 * @return	string
	 */
	public static function reactionType()
	{
		return 'album_comment';
	}

	/**
	 * Get content for embed
	 *
	 * @param	array	$params	Additional parameters to add to URL
	 * @return	string
	 */
	public function embedContent( $params )
	{
		\IPS\Output::i()->cssFiles = array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'embed.css', 'gallery', 'front' ) );
		return \IPS\Theme::i()->getTemplate( 'global', 'gallery' )->embedAlbumComment( $this, $this->item(), $this->url()->setQueryString( $params ) );
	}

	/**
	 * Get the container item class to use for mod permission checks
	 *
	 * @return	string|NULL
	 * @note	By default we will return NULL and the container check will execute against Node::$contentItemClass, however
	 *	in some situations we may need to override this (i.e. for Gallery Albums)
	 */
	protected static function getContainerModPermissionClass()
	{
		return 'IPS\gallery\Album\Item';
	}
}