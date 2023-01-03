<?php
/**
 * @brief		Field Model
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Downloads
 * @since		3 Oct 2013
 */

namespace IPS\downloads;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Field Node
 */
class _Field extends \IPS\CustomField
{
	/**
	 * @brief	[ActiveRecord] Multiton Store
	 */
	protected static $multitons;
	
	/**
	 * @brief	[ActiveRecord] Database Table
	 */
	public static $databaseTable = 'downloads_cfields';
	
	/**
	 * @brief	[ActiveRecord] Database Prefix
	 */
	public static $databasePrefix = 'cf_';
		
	/**
	 * @brief	[Node] Order Database Column
	 */
	public static $databaseColumnOrder = 'position';

	/**
	 * @brief	[CustomField] Column Map
	 */
	public static $databaseColumnMap = array(
		'not_null'	=> 'not_null'
	);
	
	/**
	 * @brief	[Node] Node Title
	 */
	public static $nodeTitle = 'ccfields';
	
	/**
	 * @brief	[CustomField] Title/Description lang prefix
	 */
	protected static $langKey = 'downloads_field';

	/**
	 * @brief	[Node] Title prefix.  If specified, will look for a language key with "{$key}_title" as the key
	 */
	public static $titleLangPrefix = 'downloads_field_';
	
	/**
	 * @brief	[CustomField] Content Table
	 */
	public static $contentDatabaseTable = 'downloads_ccontent';
	
	/**
	 * @brief	[Node] ACP Restrictions
	 */
	protected static $restrictions = array(
		'app'		=> 'downloads',
		'module'	=> 'downloads',
		'prefix'	=> 'fields_',
	);

	/**
	 * @brief	[CustomField] Editor Options
	 */
	public static $editorOptions = array( 'app' => 'downloads', 'key' => 'Downloads' );
	
	/**
	 * @brief	[CustomField] FileStorage Extension for Upload fields
	 */
	public static $uploadStorageExtension = 'downloads_FileField';

	/**
	 * Get topic format
	 *
	 * @return	void
	 */
	public function get_topic_format()
	{
		return $this->format;
	}

	/**
	 * [Node] Add/Edit Form
	 *
	 * @param	\IPS\Helpers\Form	$form	The form
	 * @return	void
	 */
	public function form( &$form )
	{
		parent::form( $form );

		$form->add( new \IPS\Helpers\Form\Radio( 'downloads_field_location', $this->id ? $this->display_location : 'below', FALSE, array( 'options' => array( 'sidebar' => 'idm_cfield_sidebar', 'below' => 'idm_cfield_below', 'tab' => 'idm_cfield_tab' ) ), NULL, NULL, NULL, 'idm_field_location' ) );
		
		if ( \IPS\Application::appIsEnabled( 'forums' ) )
		{
			$form->addHeader('category_forums_integration');
			$form->add( new \IPS\Helpers\Form\YesNo( 'cf_topic', $this->topic ) );
			$form->add( new \IPS\Helpers\Form\TextArea( 'pf_format', $this->id ? $this->topic_format : '', FALSE ) );

			\IPS\Member::loggedIn()->language()->words['pf_format_desc'] = \IPS\Member::loggedIn()->language()->addToStack('cf_format_desc');
		}
	}
	
	/**
	 * [Node] Format form values from add/edit form for save
	 *
	 * @param	array	$values	Values from the form
	 * @return	array
	 */
	public function formatFormValues( $values )
	{
		if ( \IPS\Application::appIsEnabled( 'forums' ) AND isset( $values['cf_topic'] ) )
		{
			$values['topic'] = $values['cf_topic'];
			unset( $values['cf_topic'] );
		}

		$values['search_type']			= ( $values['pf_search_type'] === NULL ) ? '' : $values['pf_search_type'];
		$values['allow_attachments']	= $values['pf_allow_attachments'];
		unset( $values['pf_search_type'] );
		unset( $values['pf_allow_attachments'] );

		$values['display_location'] = $values['downloads_field_location'];
		unset( $values['downloads_field_location'] );

		return parent::formatFormValues( $values );
	}
}