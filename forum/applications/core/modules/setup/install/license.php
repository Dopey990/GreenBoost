<?php
/**
 * @brief		Installer: License
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		2 Apr 2013
 */
 
namespace IPS\core\modules\setup\install;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Installer: License
 */
class _license extends \IPS\Dispatcher\Controller
{
	/**
	 * Show Form
	 *
	 * @return	void
	 */
	public function manage()
	{
		//IV
		\IPS\Settings::i()->change_iv_url = '0';
		
		$form = new \IPS\Helpers\Form( 'license', 'continue', \IPS\Http\Url::external( ( \IPS\Request::i()->isSecure() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '?controller=license' ) );
		$form->add( new \IPS\Helpers\Form\Text( 'lkey', NULL, TRUE, array( 'size' => 50 ), function( $val )
		{
			\IPS\IPS::checkLicenseKey( $val, ( \IPS\Request::i()->isSecure() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . mb_substr( $_SERVER['SCRIPT_NAME'], 0, -mb_strlen( \IPS\CP_DIRECTORY . '/install/index.php' ) ) );
		}, NULL, '<a href="' . \IPS\Http\Url::external( 'http://invision-virus.com/index.php?goto=licenseinformation' ) . '" target="_blank" rel="noopener">' . \IPS\Member::loggedIn()->language()->addToStack('lkey_help') . '</a>' ) );
		//IV
		$form->add( new \IPS\Helpers\Form\Checkbox( 'eula', TRUE, TRUE, array( 'label' => 'eula_suffix' ), function( $val )
		{
			if ( !$val )
			{
				throw new \InvalidArgumentException('eula_err');
			}
		}, "<textarea disabled style='width: 100%; height: 250px'>" . file_get_contents( 'eula.txt' ) . "</textarea><br>" ) );
		
		if ( $values = $form->values() )
		{
			$values['lkey'] = trim( $values['lkey'] );
			
			//IV
			/*if ( mb_substr( $values['lkey'], -12 ) === '-TESTINSTALL' )
			{
				$values['lkey'] = mb_substr( $values['lkey'], 0, -12 );
			}*/
			
			$toWrite = "<?php\n\n" . '$INFO = ' . var_export( array( 'lkey' => $values['lkey'] ), TRUE ) . ';';
			
			try
			{
				$file = @\file_put_contents( \IPS\ROOT_PATH . '/conf_global.php', $toWrite );
				if ( !$file )
				{
					throw new \Exception;
				}
				else
				{
					/* PHP 5.5 - clear opcode cache or details won't be seen on next page load */
					if ( \function_exists( 'opcache_invalidate' ) )
					{
						@opcache_invalidate( \IPS\ROOT_PATH . '/conf_global.php' );
					}

					\IPS\Output::i()->redirect( \IPS\Http\Url::internal( 'controller=applications' ) );
				}
			}
			catch( \Exception $ex )
			{
				\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack( 'error' );
				$errorform = new \IPS\Helpers\Form( 'license', 'continue' );
				$errorform->class = '';
				$errorform->add( new \IPS\Helpers\Form\TextArea( 'conf_global_error', $toWrite, FALSE ) );
				
				foreach( $values as $k => $v )
				{
					$errorform->hiddenValues[ $k ] = $v;
				}
				
				\IPS\Output::i()->output = \IPS\Theme::i()->getTemplate( 'global' )->confWriteError( $errorform, \IPS\ROOT_PATH );
				return;
			}
		}
		
		\IPS\Output::i()->title		= \IPS\Member::loggedIn()->language()->addToStack('license');
		\IPS\Output::i()->output 	= \IPS\Theme::i()->getTemplate( 'global' )->block( 'license', $form, TRUE, TRUE );
	}
}