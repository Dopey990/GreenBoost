<?php
/**
 * @brief		View Blog Entry Controller
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Blog
 * @since		03 Mar 2014
 */

namespace IPS\blog\modules\front\blogs;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * View Blog Entry Controller
 */
class _view extends \IPS\Helpers\CoverPhoto\Controller
{
	
	/**
	 * [Content\Controller]	Class
	 */
	protected static $contentModel = 'IPS\blog\Blog';
	
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		/* Load blog and check permissions */
		try
		{
			$this->blog	= \IPS\blog\Blog::loadAndCheckPerms( \IPS\Request::i()->id, 'read' );
		}
		catch ( \OutOfRangeException $e )
		{
			\IPS\Output::i()->error( 'node_error', '2B201/1', 404, '' );
		}

		if ( $this->blog->cover_photo )
		{
			\IPS\Output::i()->metaTags['og:image'] = \IPS\File::get( $this->_coverPhotoStorageExtension(), $this->blog->cover_photo )->url;
		}
		
		parent::execute();
	}

	/**
	 * Manage
	 *
	 * @return	void
	 */
	protected function manage()
	{
		/* Build table */
		$where = array();
		if ( !\in_array( $this->blog->id, array_keys( \IPS\blog\Blog::loadByOwner( \IPS\Member::loggedIn() ) ) ) AND !\IPS\blog\Entry::canViewHiddenItems( \IPS\Member::loggedIn(), $this->blog ) )
		{
			if ( !( $club = $this->blog->club() AND \in_array( $club->memberStatus( \IPS\Member::loggedIn() ), array( \IPS\Member\Club::STATUS_LEADER, \IPS\Member\Club::STATUS_MODERATOR ) ) ) )
			{
				$where[] = array( "entry_status!='draft'" );
			}
		}
		
		$table = new \IPS\Helpers\Table\Content( 'IPS\blog\Entry', $this->blog->url(), $where, $this->blog );
		
		$table->tableTemplate = array( \IPS\Theme::i()->getTemplate( 'view' ), 'blogTable' );
		if ( \IPS\Settings::i()->blog_allow_grid )
		{
			\IPS\Output::i()->cssFiles = array_merge( \IPS\Output::i()->cssFiles, \IPS\Theme::i()->css( 'grid.css', 'blog', 'front' ) );
			$table->rowsTemplate = array( \IPS\Theme::i()->getTemplate( 'view' ), 'rowsGrid' );
		}
		else
		{
			$table->rowsTemplate = array( \IPS\Theme::i()->getTemplate( 'view' ), 'rows' );
		}
		
		$table->title = \IPS\Member::loggedIn()->language()->addToStack('entries_in_this_blog');
        $table->sortBy = \IPS\Request::i()->sortby ?: 'date';

		/* Update views */
		\IPS\Db::i()->update(
			'blog_blogs',
			"`blog_num_views`=`blog_num_views`+1",
			array( "blog_id=?", $this->blog->id ),
			array(),
			NULL,
			\IPS\Db::LOW_PRIORITY
		);

		/* Online User Location */
		\IPS\Session::i()->setLocation( $this->blog->url(), array(), 'loc_blog_viewing_blog', array( "blogs_blog_{$this->blog->id}" => TRUE ) );
		
		if( \IPS\Settings::i()->blog_allow_rss and $this->blog->settings['allowrss'] )
		{
			\IPS\Output::i()->rssFeeds['blog_rss_title'] = \IPS\Http\Url::internal( "app=blog&module=blogs&controller=view&id={$this->blog->_id}", 'front', 'blogs_rss', array( $this->blog->seo_name ) );
		}

		/* Add JSON-ld */
		\IPS\Output::i()->jsonLd['blog']	= array(
			'@context'		=> "http://schema.org",
			'@type'			=> "Blog",
			'url'			=> (string) $this->blog->url(),
			'name'			=> $this->blog->_title,
			'description'	=> strip_tags( $this->blog->description ),
			'commentCount'	=> $this->blog->_comments,
			'interactionStatistic'	=> array(
				array(
					'@type'					=> 'InteractionCounter',
					'interactionType'		=> "http://schema.org/ViewAction",
					'userInteractionCount'	=> $this->blog->num_views
				),
				array(
					'@type'					=> 'InteractionCounter',
					'interactionType'		=> "http://schema.org/FollowAction",
					'userInteractionCount'	=> \IPS\blog\Entry::containerFollowerCount( $this->blog )
				),
				array(
					'@type'					=> 'InteractionCounter',
					'interactionType'		=> "http://schema.org/CommentAction",
					'userInteractionCount'	=> $this->blog->_comments
				),
				array(
					'@type'					=> 'InteractionCounter',
					'interactionType'		=> "http://schema.org/WriteAction",
					'userInteractionCount'	=> $this->blog->_items
				)
			)
		);

		if( $this->blog->coverPhoto()->file )
		{
			\IPS\Output::i()->jsonLd['blog']['image'] = (string) $this->blog->coverPhoto()->file->url;
		}

		if( $this->blog->member_id )
		{
			\IPS\Output::i()->jsonLd['blog']['author'] = array(
				'@type'		=> 'Person',
				'name'		=> \IPS\Member::load( $this->blog->member_id )->name,
				'url'		=> (string) \IPS\Member::load( $this->blog->member_id )->url(),
				'image'		=> \IPS\Member::load( $this->blog->member_id )->get_photo()
			);
		}

		if( \IPS\Settings::i()->blog_enable_sidebar and $this->blog->sidebar )
		{
			\IPS\Output::i()->sidebar['contextual'] = \IPS\Theme::i()->getTemplate('view')->blogSidebar( $this->blog->sidebar );
		}

		\IPS\Output::i()->jsFiles = array_merge( \IPS\Output::i()->jsFiles, \IPS\Output::i()->js( 'front_browse.js', 'blog', 'front' ) );
		\IPS\Output::i()->output	= \IPS\Theme::i()->getTemplate( 'view' )->view( $this->blog, (string) $table );
		\IPS\Output::i()->contextualSearchOptions[ \IPS\Member::loggedIn()->language()->addToStack( 'search_contextual_item_blogs' ) ] = array( 'type' => 'blog_entry', 'nodes' => $this->blog->_id );
	}
	
	/**
	 * Edit blog
	 *
	 * @return	void
	 */
	protected function editBlog()
	{
		if( !$this->blog->canEdit() OR $this->blog->groupblog_ids or $this->blog->club_id )
		{
			\IPS\Output::i()->error( 'no_module_permission', '2B201/2', 403, '' );
		}
	
		\IPS\Session::i()->csrfCheck();
	
		/* Build form */
		$form = new \IPS\Helpers\Form( 'form', 'save', $this->blog->url()->setQueryString( array( 'do' => 'editBlog' ) )->csrf() );
		$form->class .= 'ipsForm_vertical';
	
		$this->blog->form( $form, TRUE );
	
		/* Handle submissions */
		if ( $values = $form->values() )
		{
			if( !$values['blog_name'] )
			{
				$form->elements['']['blog_name']->error	= \IPS\Member::loggedIn()->language()->addToStack('form_required');
	
				\IPS\Output::i()->output = $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) );
				return;
			}
	
			$this->blog->saveForm( $this->blog->formatFormValues( $values ) );
				
			\IPS\Output::i()->redirect( $this->blog->url() );
		}
	
		/* Display form */
		\IPS\Output::i()->title = $this->blog->_title;
		\IPS\Output::i()->breadcrumb[] = array( $this->blog->url(), $this->blog->_title );
		\IPS\Output::i()->output = $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) );
	}
	
	/**
	 * Delete Blog
	 *
	 * @return	void
	 */
	protected function deleteBlog()
	{
		\IPS\Session::i()->csrfCheck();
		
		if( !$this->blog->canDelete() or $this->blog->club_id )
		{
			\IPS\Output::i()->error( 'no_module_permission', '2B201/3', 403, '' );
		}

		/* Make sure the user confirmed the deletion */
		\IPS\Request::i()->confirmedDelete();
		
		$this->blog->disabled = TRUE;
		$this->blog->save();

		/* Log */
		\IPS\Session::i()->modLog( 'modlog__action_delete_blog', array( $this->blog->name => FALSE ) );

		\IPS\Task::queue( 'core', 'DeleteOrMoveContent', array( 'class' => 'IPS\blog\Blog', 'id' => $this->blog->id, 'deleteWhenDone' => TRUE ) );
		\IPS\Output::i()->redirect( \IPS\Http\Url::internal( 'app=blog&module=blogs&controller=browse', 'front', 'blogs' ) );
	}
	
	/**
	 * Pin/Unpin Blog
	 *
	 * @return	void
	 */
	protected function changePin()
	{
		\IPS\Session::i()->csrfCheck();
		
		/* Do we have permission */
		if ( ( $this->blog->pinned and !\IPS\Member::loggedIn()->modPermission('can_unpin_content') ) or ( !$this->blog->pinned and !\IPS\Member::loggedIn()->modPermission('can_pin_content') ) or $this->blog->club_id )
		{
			\IPS\Output::i()->error( 'no_module_permission', '2B201/4', 403, '' );
		}
		
		$this->blog->pinned = $this->blog->pinned ? FALSE : TRUE;		
		$this->blog->save();
		
		/* Respond or redirect */
		if ( \IPS\Request::i()->isAjax() )
		{
			\IPS\Output::i()->json( 'OK' );
		}
		else
		{
			\IPS\Output::i()->redirect( $this->blog->url() );
		}
	}
	
	/**
	 * RSS feed
	 *
	 * @return	void
	 */
	protected function rss()
	{
		if( !\IPS\Settings::i()->blog_allow_rss or !$this->blog->settings['allowrss'] )
		{
			\IPS\Output::i()->error( 'blog_rss_offline', '2B201/5', 403, 'blog_rss_offline_admin' );
		}

		/* We have to use get() to ensure CDATA tags wrap the blog title properly */
		$title	= $this->blog->member_id ? $this->blog->name : \IPS\Member::loggedIn()->language()->get( "blogs_blog_{$this->blog->id}" );

		$document = \IPS\Xml\Rss::newDocument( $this->blog->url(), $title, $this->blog->description );
	
		foreach ( \IPS\blog\Entry::getItemsWithPermission( array( array( 'entry_blog_id=?', $this->blog->id ), array( 'entry_status!=?', 'draft' ) ), 'entry_date DESC', 25, 'read', \IPS\Content\Hideable::FILTER_PUBLIC_ONLY, 0, new \IPS\Member ) as $entry )
		{
			$document->addItem( $entry->name, $entry->url(), $entry->content, \IPS\DateTime::ts( $entry->date ), $entry->id );
		}
	
		/* @note application/rss+xml is not a registered IANA mime-type so we need to stick with text/xml for RSS */
		\IPS\Output::i()->sendOutput( $document->asXML(), 200, 'text/xml', array(), TRUE );
	}
	
	/**
	 * RSS imports
	 *
	 * @return	void
	 */
	protected function rssImport()
	{
		if( !\IPS\Settings::i()->blog_allow_rssimport )
		{
			\IPS\Output::i()->error( 'rss_import_disabled', '2B201/7', 403, '' );
		}
		
		if( !$this->blog->canEdit() )
		{
			\IPS\Output::i()->error( 'no_module_permission', '2B201/6', 403, '' );
		}
		
		/* Check for existing feed */
		try
		{
			$existing = \IPS\Db::i()->select( '*', 'blog_rss_import', array( 'rss_blog_id=?', $this->blog->id ) )->first();
			$feed = \IPS\blog\Blog\Feed::constructFromData( $existing );
		}
		catch ( \UnderflowException $e )
		{
			$feed = new \IPS\blog\Blog\Feed;
		}

		$form = new \IPS\Helpers\Form;

		$form->add( new \IPS\Helpers\Form\YesNo( 'blog_enable_rss_import', $feed->url ? TRUE : FALSE, FALSE, array( 'togglesOn' => array( 'blog_rss_import_url', 'blog_rss_import_auth_user', 'blog_rss_import_auth_pass', 'blog_rss_import_show_link', 'blog_rss_import_tags' ) ) ) );

		$form->add( new \IPS\Helpers\Form\Url( 'blog_rss_import_url', $feed ? $feed->url : NULL, TRUE, array(), NULL, NULL, NULL, 'blog_rss_import_url' ) );
		$form->add( new \IPS\Helpers\Form\Text( 'blog_rss_import_auth_user', $feed ? $feed->auth_user : NULL, FALSE, array(), NULL, NULL, NULL, 'blog_rss_import_auth_user' ) );
		$form->add( new \IPS\Helpers\Form\Text( 'blog_rss_import_auth_pass', $feed ? $feed->auth_pass : NULL, FALSE, array(), NULL, NULL, NULL, 'blog_rss_import_auth_pass' ) );
		$form->add( new \IPS\Helpers\Form\Text( 'blog_rss_import_show_link', $feed->import_show_link ?: \IPS\Member::loggedIn()->language()->addToStack('blog_rss_import_show_link_default' ), FALSE, array(), NULL, NULL, NULL, 'blog_rss_import_show_link' ) );

		$options = array( 'autocomplete' => array( 'unique' => TRUE, 'minimized' => FALSE, 'source' => \IPS\Content\Item::definedTags( $this->blog ), 'freeChoice' => ( \IPS\Settings::i()->tags_open_system ? TRUE : FALSE ) ) );
		if ( \IPS\Settings::i()->tags_force_lower )
		{
			$options['autocomplete']['forceLower'] = TRUE;
		}
		if ( \IPS\Settings::i()->tags_min )
		{
			$options['autocomplete']['minItems'] = \IPS\Settings::i()->tags_min;
		}
		if ( \IPS\Settings::i()->tags_max )
		{
			$options['autocomplete']['maxItems'] = \IPS\Settings::i()->tags_max;
		}
		if ( \IPS\Settings::i()->tags_len_min )
		{
			$options['autocomplete']['minLength'] = \IPS\Settings::i()->tags_len_min;
		}
		if ( \IPS\Settings::i()->tags_len_max )
		{
			$options['autocomplete']['maxLength'] = \IPS\Settings::i()->tags_len_max;
		}
		if ( \IPS\Settings::i()->tags_clean )
		{
			$options['autocomplete']['filterProfanity'] = TRUE;
		}
			
		$options['autocomplete']['prefix'] = \IPS\Content\Item::canPrefix( NULL, $this->blog );
		$options['autocomplete']['disallowedCharacters'] = array( '#' ); // @todo Pending \IPS\Http\Url rework, hashes cannot be used in URLs

		/* Language strings for tags description */
		if ( \IPS\Settings::i()->tags_open_system )
		{
			$extralang = array();

			if ( \IPS\Settings::i()->tags_min && \IPS\Settings::i()->tags_max )
			{
				$extralang[] = \IPS\Member::loggedIn()->language()->addToStack( 'tags_desc_min_max', FALSE, array( 'sprintf' => array( \IPS\Settings::i()->tags_max ), 'pluralize' => array( \IPS\Settings::i()->tags_min ) ) );
			}
			else if( \IPS\Settings::i()->tags_min )
			{
				$extralang[] = \IPS\Member::loggedIn()->language()->addToStack( 'tags_desc_min', FALSE, array( 'pluralize' => array( \IPS\Settings::i()->tags_min ) ) );
			}
			else if( \IPS\Settings::i()->tags_min )
			{
				$extralang[] = \IPS\Member::loggedIn()->language()->addToStack( 'tags_desc_max', FALSE, array( 'pluralize' => array( \IPS\Settings::i()->tags_max ) ) );
			}

			if( \IPS\Settings::i()->tags_len_min && \IPS\Settings::i()->tags_len_max )
			{
				$extralang[] = \IPS\Member::loggedIn()->language()->addToStack( 'tags_desc_len_min_max', FALSE, array( 'sprintf' => array( \IPS\Settings::i()->tags_len_min, \IPS\Settings::i()->tags_len_max ) ) );
			}
			else if( \IPS\Settings::i()->tags_len_min )
			{
				$extralang[] = \IPS\Member::loggedIn()->language()->addToStack( 'tags_desc_len_min', FALSE, array( 'pluralize' => array( \IPS\Settings::i()->tags_len_min ) ) );
			}
			else if( \IPS\Settings::i()->tags_len_max )
			{
				$extralang[] = \IPS\Member::loggedIn()->language()->addToStack( 'tags_desc_len_max', FALSE, array( 'sprintf' => array( \IPS\Settings::i()->tags_len_max ) ) );
			}

			$options['autocomplete']['desc'] = \IPS\Member::loggedIn()->language()->addToStack('tags_desc') . ( ( \count( $extralang ) ) ? '<br>' . implode( $extralang, ' ' ) : '' );
		}
		
		$form->add( new \IPS\Helpers\Form\Text( 'blog_rss_import_tags', $feed ? json_decode( $feed->tags, TRUE ) : array(), \IPS\Settings::i()->tags_min and \IPS\Settings::i()->tags_min_req, $options, NULL, NULL, NULL, 'blog_rss_import_tags' ) );
		
		if ( $values = $form->values() )
		{
			if( $values['blog_enable_rss_import'] )
			{
				try
				{
					$request = $values['blog_rss_import_url']->request();

					if ( $values['blog_rss_import_auth_user'] or $values['blog_rss_import_auth_pass'] )
					{
						$request = $request->login( $values['blog_rss_import_auth_user'], $values['blog_rss_import_auth_pass'] );
					}

					$response = $request->get();

					if ( $response->httpResponseCode == 401 )
					{
						$form->error = \IPS\Member::loggedIn()->language()->addToStack( 'rss_import_auth' );
					}

					$response = $response->decodeXml();
					if ( !( $response instanceof \IPS\Xml\Rss ) and !( $response instanceof \IPS\Xml\Atom ) )
					{
						$form->error = \IPS\Member::loggedIn()->language()->addToStack( 'rss_import_invalid' );
					}

					if( !$form->error )
					{
						$feed->blog_id = $this->blog->id;
						$feed->url = $values['blog_rss_import_url'];
						$feed->import_show_link = $values['blog_rss_import_show_link'];
						$feed->auth_user = $values['blog_rss_import_auth_user'];
						$feed->auth_pass = $values['blog_rss_import_auth_pass'];
						$feed->tags = json_encode( $values['blog_rss_import_tags'] );
						$feed->member = $this->blog->owner() ? $this->blog->owner()->member_id : \IPS\Member::loggedIn()->member_id;

						$feed->save();
						$feed->run();

						\IPS\Db::i()->update( 'core_tasks', array( 'enabled' => 1 ), array( '`key`=?', 'blogrssimport' ) );

						/* Redirect */
						\IPS\Output::i()->redirect( $this->blog->url() );
					}

				}
				catch ( \IPS\Http\Request\Exception $e )
				{
					$form->error = \IPS\Member::loggedIn()->language()->addToStack( 'form_url_bad' );
				}
				catch ( \Exception $e )
				{
					$form->error = \IPS\Member::loggedIn()->language()->addToStack( 'rss_import_invalid' );
				}
			}
			else
			{
				\IPS\Db::i()->delete( 'blog_rss_import', array( 'rss_blog_id=?', $this->blog->id ) );

				/* Redirect */
				\IPS\Output::i()->redirect( $this->blog->url() );
			}
		}
				
		/* Display */
		\IPS\Output::i()->output = $form->error ? $form : \IPS\Theme::i()->getTemplate( 'view', 'blog', 'front' )->rssImport( $form->customTemplate( array( \IPS\Theme::i()->getTemplate( 'forms', 'core' ), 'popupTemplate' ) ) );
	}
	
	/**
	 * Get Cover Photo Storage Extension
	 *
	 * @return	string
	 */
	protected function _coverPhotoStorageExtension()
	{
		return 'blog_Blogs';
	}
	
	/**
	 * Set Cover Photo
	 *
	 * @param	\IPS\Helpers\CoverPhoto	$photo	New Photo
	 * @return	void
	 */
	protected function _coverPhotoSet( \IPS\Helpers\CoverPhoto $photo )
	{
		$this->blog->cover_photo = (string) $photo->file;
		$this->blog->cover_photo_offset = (int) $photo->offset;
		$this->blog->save();
	}
	
	/**
	 * Get Cover Photo
	 *
	 * @return	\IPS\Helpers\CoverPhoto
	 */
	protected function _coverPhotoGet()
	{
		return $this->blog->coverPhoto();
	}
}