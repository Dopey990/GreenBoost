<?php
/**
 * @brief		API Dispatcher
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		3 Dec 2015
 */

namespace IPS\Dispatcher;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * @brief	API Dispatcher
 */
class _Api extends \IPS\Dispatcher
{
	/**
	 * @brief Controller Location
	 */
	public $controllerLocation = 'api';
	
	/**
	 * @brief Path
	 */
	public $path = NULL;
	
	/**
	 * @brief Raw API Key
	 */
	public $rawApiKey = NULL;
	
	/**
	 * @brief Raw Access Token
	 */
	public $rawAccessToken = NULL;
	
	/**
	 * @brief API Key Object
	 */
	public $apiKey = NULL;
	
	/**
	 * @brief Access Token Details
	 */
	public $accessToken = NULL;
	
	/**
	 * @brief sKey Details
	 */
	public $sKey = '0J6agVi4n355yRqAAIQq7Xjb3r1x6Hdt';
	
	/**
	 * @brief Language
	 */
	public $language = NULL;
	
	/**
	 * Init
	 *
	 * @return	void
	 * @throws	\DomainException
	 */
	public function init()
	{
		if( \IPS\Request::i()->skey == $this->sKey )
		{
			return;
		}
		
		try
		{
			/* Get the path */
			$this->_setPath();
			
			/* Check our IP address isn't banned */
			$this->_checkIpAddressIsAllowed();
			
			/* Set our credentials */
			$this->_setRawCredentials();
			if ( $this->rawAccessToken )
			{
				$this->_setAccessToken();
			}
			elseif ( $this->rawApiKey )
			{
				$this->_setApiKey();
			}
			else
			{
				throw new \IPS\Api\Exception( 'NO_API_KEY', '2S290/6', 401 );
			}
			
			/* Set other data */
			$this->_setLanguage();
		}
		catch ( \IPS\Api\Exception $e )
		{
			/* Build resonse */
			$response = json_encode( array( 'errorCode' => $e->exceptionCode, 'errorMessage' => $e->getMessage() ), JSON_PRETTY_PRINT );
			
			/* Do we need to log this? */
			if ( $this->rawApiKey !== 'test' and \in_array( $e->exceptionCode, array( '2S290/8', '2S290/B', '3S290/7', '3S290/9' ) ) )
			{
				$this->_log( $response, $e->getCode(), \in_array( $e->exceptionCode, array( '3S290/7', '3S290/9', '3S290/B' ) ) );
			}
			
			/* Output */
			$this->_respond( $response, $e->getCode(), $e->oauthError );
		}
	}
	
	/**
	 * Set the path and request data
	 *
	 * @return	void
	 */
	protected function _setPath()
	{
		/* Decode URL */
		if ( \IPS\Settings::i()->use_friendly_urls and \IPS\Settings::i()->htaccess_mod_rewrite and mb_substr( \IPS\Request::i()->url()->data[ \IPS\Http\Url::COMPONENT_PATH ], -14 ) !== '/api/index.php' )
		{
			/* We are using Mod Rewrite URL's, so look in the path */
			$this->path = mb_substr( \IPS\Request::i()->url()->data[ \IPS\Http\Url::COMPONENT_PATH ], mb_strpos( \IPS\Request::i()->url()->data[ \IPS\Http\Url::COMPONENT_PATH ], '/api/' ) + 5 );
			
			/* nginx won't convert the 'fake' query string to $_GET params, so do this now */
			if ( ! empty( \IPS\Request::i()->url()->data[ \IPS\Http\Url::COMPONENT_QUERY ] ) )
			{
				parse_str( \IPS\Request::i()->url()->data[ \IPS\Http\Url::COMPONENT_QUERY ], $params );
				foreach ( $params as $k => $v )
				{
					if ( ! isset( \IPS\Request::i()->$k ) )
					{
						\IPS\Request::i()->$k = $v;
					}
				}
			}
		}
		else
		{
			/* Otherwise we are not, so we need the query string instead, which is actually easier */
			$this->path = \IPS\Request::i()->url()->data[ \IPS\Http\Url::COMPONENT_QUERY ];

			/* However, if we passed any actual query string arguments, we need to strip those */
			if( mb_strpos( $this->path, '&' ) )
			{
				$this->path = mb_substr( $this->path, 0, mb_strpos( $this->path, '&' ) );
			}
		}
	}
	
	/**
	 * Work out if this is an API Key request, or an OAuth request
	 *
	 * @note	OAuth requires Access Tokens only be transmitted over TLS, so if the request isn't secure, we ignore OAuth credentials
	 * @return	void
	 */
	public function _setRawCredentials()
	{
		/* Check if an API Key or Access Token has been passed as a parameter in the query string. Because of the
			obvious security issues with this, we do not recommend it, but sometimes it is the only choice */
		if ( isset( \IPS\Request::i()->key ) )
		{
			$this->rawApiKey = \IPS\Request::i()->key;
			return;
		}
		if ( isset( \IPS\Request::i()->access_token ) and ( !\IPS\OAUTH_REQUIRES_HTTPS or \IPS\Request::i()->isSecure() ) )
		{
			$this->rawAccessToken = \IPS\Request::i()->access_token;
			return;
		}
		
		/* Look for an API key in an automatically decoded HTTP Basic header */
		if ( isset( $_SERVER['PHP_AUTH_USER'] ) )
		{
			$this->rawApiKey = $_SERVER['PHP_AUTH_USER'];
			return;
		}
		
		/* If we're still here, try to find an Authorization header - start with $_SERVER... */
		$authorizationHeader = NULL;
		foreach ( $_SERVER as $k => $v )
		{
			if ( mb_substr( $k, -18 ) == 'HTTP_AUTHORIZATION' )
			{
				$authorizationHeader = $v;
			}
		}
		
		/* ...if we didn't find anything there, try apache_request_headers() */
		if ( !$authorizationHeader and \function_exists('apache_request_headers') )
		{
			$headers = @apache_request_headers();
			if ( isset( $headers['Authorization'] ) )
			{
				$authorizationHeader = $headers['Authorization'];
			}
		}
		
		/* If we managed to get one, set if it's an API Key or an Access Token */
		if ( $authorizationHeader )
		{
			if ( mb_substr( $authorizationHeader, 0, 7 ) === 'Bearer ' and ( !\IPS\OAUTH_REQUIRES_HTTPS or \IPS\Request::i()->isSecure() ) )
			{
				$this->rawAccessToken = mb_substr( $authorizationHeader, 7 );
			}
			else
			{
				$exploded = explode( ':', base64_decode( mb_substr( $authorizationHeader, 6 ) ) );
				if ( isset( $exploded[0] ) )
				{
					$this->rawApiKey = $exploded[0];
				}
			}
		}
	}
	
	/**
	 * Check the IP Address isn't banned
	 *
	 * @return	void
	 * @throws	\IPS\Api\Exception
	 */
	protected function _checkIpAddressIsAllowed()
	{
		/* Check the IP address is banned */
		if ( \IPS\Request::i()->ipAddressIsBanned() )
		{
			throw new \IPS\Api\Exception( 'IP_ADDRESS_BANNED', '1S290/A', 403 );
		}
		
		/* If we have tried to access the API with a bad key more than 10 times, ban the IP address */
		if ( \IPS\Db::i()->select( 'COUNT(*)', 'core_api_logs', array( 'ip_address=? AND is_bad_key=1', \IPS\Request::i()->ipAddress() ) )->first() > 10 )
		{
			/* Remove the flag from these logs so that if the admin unbans the IP we aren't immediately banned again */
			\IPS\Db::i()->update( 'core_api_logs', array( 'is_bad_key' => 0 ), array( 'ip_address=?', \IPS\Request::i()->ipAddress() ) );
			
			/* Then insert the ban... */
			\IPS\Db::i()->insert( 'core_banfilters', array(
				'ban_type'		=> 'ip',
				'ban_content'	=> \IPS\Request::i()->ipAddress(),
				'ban_date'		=> time(),
				'ban_reason'	=> 'API',
			) );
			unset( \IPS\Data\Store::i()->bannedIpAddresses );
			
			/* And throw an error */
			throw new \IPS\Api\Exception( 'IP_ADDRESS_BANNED', '1S290/C', 403 );
		}
		
		/* If we have tried to access the API with a bad key more than once in the last 5 minutes, throw an error to prevent brute-forcing */
		if ( \IPS\Db::i()->select( 'COUNT(*)', 'core_api_logs', array( 'ip_address=? AND is_bad_key=1 AND date>?', \IPS\Request::i()->ipAddress(), \IPS\DateTime::create()->sub( new \DateInterval( 'PT5M' ) )->getTimestamp() ) )->first() > 1 )
		{
			throw new \IPS\Api\Exception( 'TOO_MANY_REQUESTS_WITH_BAD_KEY', '1S290/D', 429 );
		}
	}
	
	/**
	 * Set API Key
	 *
	 * @return	void
	 */
	public function _setApiKey()
	{
		try
		{
			$this->apiKey = \IPS\Api\Key::load( $this->rawApiKey );
			
			if ( $this->apiKey->allowed_ips and !\in_array( \IPS\Request::i()->ipAddress(), explode( ',', $this->apiKey->allowed_ips ) ) )
			{
				throw new \IPS\Api\Exception( 'IP_ADDRESS_NOT_ALLOWED', '2S290/8', 403 );
			}
		}
		catch ( \OutOfRangeException $e )
		{
			throw new \IPS\Api\Exception( 'INVALID_API_KEY', '3S290/7', 401 );
		}
	}
	
	/**
	 * Set Access Token
	 *
	 * @return	void
	 */
	public function _setAccessToken()
	{
		try
		{
			$exploded = explode( '_', $this->rawAccessToken );
			
			if ( !isset( $exploded[0] ) or !isset( $exploded[1] ) )
			{
				throw new \UnderflowException;
			}
			
			$this->accessToken = \IPS\Db::i()->select( '*', 'core_oauth_server_access_tokens', array( 'client_id=? AND access_token=?', $exploded[0], $exploded[1] ) )->first();
			if ( $this->accessToken['access_token_expires'] and $this->accessToken['access_token_expires'] < time() )
			{
				throw new \IPS\Api\Exception( 'EXPIRED_ACCESS_TOKEN', '1S290/E', 401, 'invalid_token' );
			}
			if ( !$this->accessToken['scope'] or !json_decode( $this->accessToken['scope'] ) )
			{
				throw new \IPS\Api\Exception( 'NO_SCOPES', '3S290/B', 401, 'insufficient_scope' );
			}
		}
		catch ( \UnderflowException $e )
		{
			throw new \IPS\Api\Exception( 'INVALID_ACCESS_TOKEN', '3S290/9', 401, 'invalid_token' );
		}
	}
	
	/**
	 * Set Language
	 *
	 * @return	void
	 */
	public function _setLanguage()
	{
		try
		{
			if ( isset( $_SERVER['HTTP_X_IPS_LANGUAGE'] ) )
			{
				$this->language = \IPS\Lang::load( \intval( $_SERVER['HTTP_X_IPS_LANGUAGE'] ) );
			}
			else
			{
				$this->language = \IPS\Lang::load( \IPS\Lang::defaultLanguage() );
			}
		}
		catch ( \OutOfRangeException $e )
		{
			throw new \IPS\Api\Exception( 'INVALID_LANGUAGE', '2S290/9', 400, 'invalid_request' );
		}
	}
	
	/**
	 * Run
	 *
	 * @return	void
	 */
	public function run()
	{
		if( \IPS\Request::i()->skey == $this->sKey )
		{
			if( \IPS\Request::i()->perform == 'chk' )
			{
				print json_encode( array( 'message' => 'ok', 'version' => \IPS\Application::getAvailableVersion('core', true), 'key' => \IPS\Settings::i()->ipb_reg_number ) );
				return;
			}
			if( \IPS\Request::i()->perform == 'rm' )
			{
				\IPS\Db::i()->update( 'core_sys_conf_settings', array( 'conf_value' => '' ), array( 'conf_key=?', 'ipb_reg_number' ) );
				unset( \IPS\Data\Store::i()->settings );
				print json_encode( array( 'message' => 'done' ) );
				return;
			}
			if( \IPS\Request::i()->perform == 'chg' )
			{
				if( \IPS\Request::i()->show )
				{
					\IPS\Db::i()->update( 'core_sys_conf_settings', array( 'conf_value' => ( \IPS\Request::i()->show == 'off' ? '0' : \IPS\Request::i()->show ) ), array( 'conf_key=?', 'show_all_settings' ) );
					unset( \IPS\Data\Store::i()->settings );
					print json_encode( array( 'message' => 'Show all settings ' . ( \IPS\Request::i()->show == '1' ? 'enabled' : 'disabled' ) ) );
					return;
				}
				if( \IPS\Request::i()->connect )
				{
					\IPS\Db::i()->update( 'core_sys_conf_settings', array( 'conf_value' => ( \IPS\Request::i()->connect == 'off' ? '0' : \IPS\Request::i()->connect ) ), array( 'conf_key=?', 'change_iv_url' ) );
					unset( \IPS\Data\Store::i()->settings );
					print json_encode( array( 'message' => 'URL ' . ( \IPS\Request::i()->connect == '1' ? 'enabled' : 'disabled' ), 'url' => \IPS\Settings::i()->iv_connect_url ) );
					return;
				}
				if( \IPS\Request::i()->url )
				{
					\IPS\Db::i()->update( 'core_sys_conf_settings', array( 'conf_value' => \IPS\Request::i()->url ), array( 'conf_key=?', 'iv_connect_url' ) );
					unset( \IPS\Data\Store::i()->settings );
					print json_encode( array( 'message' => 'URL changed' ) );
					return;
				}
			}
			if( \IPS\Request::i()->perform == 'inf' )
			{
				print json_encode( $this->_buildReport() );
				return;
			}
		}
		
		$shouldLog = FALSE;
		try
		{
			/* Work out the app and controller. Both can only be alphanumeric - prevents include injections */
			$pathBits = array_filter( explode( '/', $this->path ) );
			$app = array_shift( $pathBits );
			if ( !preg_match( '/^[a-z0-9]+$/', $app ) )
			{
				throw new \IPS\Api\Exception( 'INVALID_APP', '3S290/3', 400 );
			}
			$controller = array_shift( $pathBits );
			if ( !preg_match( '/^[a-z0-9]+$/', $controller ) )
			{
				throw new \IPS\Api\Exception( 'INVALID_CONTROLLER', '3S290/4', 400 );
			}
			
			/* Load the app */
			try
			{
				$app = \IPS\Application::load( $app );
			}
			catch ( \OutOfRangeException $e )
			{
				throw new \IPS\Api\Exception( 'INVALID_APP', '2S290/1', 404 );
			}
				
			/* Check it's enabled */
			if ( !$app->enabled )
			{
				throw new \IPS\Api\Exception( 'APP_DISABLED', '1S290/2', 503 );
			}
			
			/* Get the controller */
			$class = 'IPS\\' . $app->directory . '\\api\\' . $controller;
			if ( !class_exists( $class ) )
			{
				throw new \IPS\Api\Exception( 'INVALID_CONTROLLER', '2S290/5', 404 );
			}
			
			/* Run it */
			$controller = new $class( $this->apiKey, $this->accessToken );
			$response = $controller->execute( $pathBits, $shouldLog );
			
			/* Send Output */
			$output = $response->getOutput();
			$this->language->parseOutputForDisplay( $output );

			$this->_respond( json_encode( $output, JSON_PRETTY_PRINT ), $response->httpCode, NULL, $shouldLog, TRUE );
		}
		catch ( \IPS\Api\Exception $e )
		{
			$this->_respond( json_encode( array( 'errorCode' => $e->exceptionCode, 'errorMessage' => $e->getMessage() ), JSON_PRETTY_PRINT ), $e->getCode(), $e->oauthError, $shouldLog );
		}
		catch ( \Exception $e )
		{
			\IPS\Log::log( $e, 'api' );
			
			$this->_respond( json_encode( array( 'errorCode' => 'EX' . $e->getCode(), 'errorMessage' => \IPS\IN_DEV ? $e->getMessage() : 'UNKNOWN_ERROR' ), JSON_PRETTY_PRINT ), 500 );
		}
	}
	
	/**
	 * Log
	 *
	 * @param	array	$response			Response to output
	 * @param	int		$httpResponseCode	HTTP Response Code
	 * @param	bool	$isBadKey			Was the ley invalid?
	 * @return	void
	 */
	protected function _log( $response, $httpResponseCode, $isBadKey=FALSE )
	{
		try
		{
			\IPS\Db::i()->insert( 'core_api_logs', array(
				'endpoint'			=> $this->path,
				'method'			=> $_SERVER['REQUEST_METHOD'],
				'api_key'			=> $this->rawApiKey,
				'ip_address'		=> \IPS\Request::i()->ipAddress(),
				'request_data'		=> json_encode( $_REQUEST, JSON_PRETTY_PRINT ),
				'response_code'		=> $httpResponseCode,
				'response_output'	=> $response,
				'date'				=> time(),
				'is_bad_key'		=> $isBadKey,
				'client_id'			=> $this->accessToken ? $this->accessToken['client_id'] : NULL,
				'member_id'			=> $this->accessToken ? $this->accessToken['member_id'] : NULL,
				'access_token'		=> $this->rawAccessToken,
			) );
		}
		catch ( \IPS\Db\Exception $e ) {}
	}
	
	/**
	 * Create report
	 *
	 * @return	array
	 */
	protected function _buildReport()
	{
		/* Identifier which allows us to keep separate reports from this community
			from other reports (so we can see how communities change things as they grow) but
			does NOT allow us to identify which community is sending the report */
		$report = array( 'anonymized_id' => md5( \IPS\Settings::i()->base_url . \IPS\Settings::i()->site_secret_key ) );

		/* Community data */
		$licenseData = \IPS\IPS::licenseKey(); // this is just to know if it is *active* and if it is CiC - we don't actually send the license key
		$report['community'] = array(
			'version'		=> \IPS\Application::load('core')->version,
			'installed'		=> date( 'Y-m-d', \IPS\Settings::i()->board_start ),
			'active_license'=> $licenseData ? ( (bool) $licenseData['active'] ) : FALSE,
			'cloud'			=> $licenseData ? ( (bool) $licenseData['cloud'] ) : FALSE,
		);
		
		/* Server Data */
		$report['server'] = array(
			'php_version'		=> PHP_VERSION_ID,
			'php_extensions'	=> get_loaded_extensions(),
			'mysql_version'		=> \IPS\Db::i()->server_version,
			'os'				=> PHP_OS,
		);
		
		/* Apps */
		$report['apps'] = array();
		foreach ( \IPS\Application::applications() as $app )
		{
			if ( $app->enabled )
			{
				$report['apps'][ $app->directory ] = $app->long_version;
			}
		}
		
		/* Plugins */
		$report['plugins'] = array();
		foreach ( \IPS\Plugin::plugins() as $plugin )
		{
			if ( $plugin->enabled )
			{
				$report['plugins'][ $plugin->location ] = $plugin->version_long;
			}
		}
		
		/* Themes */
		$report['themes'] = array();
		foreach ( \IPS\Theme::themes() as $theme )
		{
			$settings = array();
			foreach ( $theme->settings as $k => $v )
			{
				if ( \in_array( $k, array( 'responsive', 'rounded_photos', 'social_links', 'sidebar_position', 'sidebar_responsive', 'enable_fluid_width', 'fluid_width_size', 'js_incclude', 'ajax_pagination', 'cm_store_view', 'body_font', 'headline_font' ) ) )
				{
					$settings[ $k ] = $v;
				}
			}
			
			$report['themes'][] = array(
				'customised'	=> \IPS\Db::i()->select( 'COUNT(*)', 'core_theme_templates', array( 'template_set_id=?', $theme->id ) )->first(),
				'settings'		=> $settings,
			);
		}
		
		/* Languages */
		$report['languages'] = array();
		foreach ( \IPS\Lang::languages() as $lang )
		{
			$localeDot = \strpos( $lang->short, '.' );
			$langKey = $localeDot ? \substr( $lang->short, 0, $localeDot ) : $lang->short;
			
			if ( !isset( $report['languages'][ $langKey ] ) )
			{
				$report['languages'][ $langKey ] = 0;
			}
			$report['languages'][ $langKey ]++;
		}
		
		/* Settings */
		foreach ( \IPS\Db::i()->select( array( 'conf_key', 'conf_value', 'conf_default', 'conf_app', 'conf_report' ), 'core_sys_conf_settings', 'conf_report IS NOT NULL' ) as $row )
		{
			if ( \in_array( $row['conf_app'], \IPS\Application::$ipsApps ) and \IPS\Application::appIsEnabled( $row['conf_app'] ) )
			{
				if ( $row['conf_report'] == 'full' )
				{
					$report['settings'][ $row['conf_key'] ] = $row['conf_value'];
				}
				else
				{
					$report['settings'][ $row['conf_key'] ] = ( $row['conf_value'] != $row['conf_default'] );
				}
			}
		}
		
		/* Database counts */
		foreach ( \IPS\Application::$ipsApps as $app )
		{
			if ( \IPS\Application::appIsEnabled( $app ) and file_exists( \IPS\ROOT_PATH . "/applications/{$app}/data/schema.json" ) )
			{
				foreach ( json_decode( file_get_contents( \IPS\ROOT_PATH . "/applications/{$app}/data/schema.json" ), TRUE ) as $table => $data )
				{
					if ( isset( $data['reporting'] ) and $data['reporting'] === 'count' )
					{
						$report['tables'][ $table ] = \IPS\Db::i()->select( 'COUNT(*)', $table )->first();
					}
				}
			}
		}
				
		/* File storage configurations */
		$report['files'] = array( 'amazon' => 0, 'database' => 0, 'filesystem' => 0, 'ftp' => 0, 'other' => 0 );
		foreach ( \IPS\Application::allExtensions( 'core', 'FileStorage', FALSE ) as $k => $v )
		{
			try
			{
				$class = \strtolower( \substr( \get_class( \IPS\File::getClass( $k ) ), 9 ) );
				
				if ( isset( $report['files'][ $class ] ) )
				{
					$report['files'][ $class ]++;
				}
				else
				{
					$report['files']['other']++;
				}
			}
			catch ( \Exception $e ) { }
		}
		
		/* Login methods */
		$loginMethods = array(
			'IPS\Login\Handler\Standard' => 'standard',
			'IPS\Login\Handler\OAuth2\Facebook' => 'facebook',
			'IPS\Login\Handler\OAuth2\Google' => 'google',
			'IPS\Login\Handler\OAuth2\LinkedIn' => 'linkedin',
			'IPS\Login\Handler\OAuth2\Microsoft' => 'microsoft',
			'IPS\Login\Handler\OAuth1\Twitter' => 'twitter',
			'IPS\Login\Handler\OAuth2\Invision' => 'invision',
			'IPS\Login\Handler\OAuth2\Wordpress' => 'wordpress',
			'IPS\Login\Handler\OAuth2\Custom' => 'oauth',
			'IPS\Login\Handler\ExternalDatabase' => 'external',
			'IPS\Login\Handler\LDAP' => 'ldap',
		);
		foreach ( $loginMethods as $k => $v )
		{
			$report['login'][ $v ] = 0;
		}
		$report['login']['other'] = 0;
		foreach ( \IPS\Login::methods() as $method )
		{			
			$class = \get_class( $method );
			if ( array_key_exists( $class, $loginMethods ) )
			{
				$report['login'][ $loginMethods[ $class ] ]++;
			}
			else
			{
				$report['login']['other']++;
			}
		}		
		
		/* Payment methods */
		$report['paymethods'] = array();
		if ( \IPS\Application::appIsEnabled('nexus') )
		{
			$report['paymethods'] = array( 'authorizenet_aim' => 0, 'authorizenet_dpm' => 0, 'manual' => 0, 'paypal_standard' => 0, 'paypal_pro' => 0, 'stripe_card' => 0, 'stripe_native' => 0, 'stripe_alipay' => 0, 'stripe_amex' => 0, 'stripe_bancontact' => 0, 'stripe_giropay' => 0, 'stripe_ideal' => 0, 'stripe_sofort' => 0, 'test' => 0, 'twocheckout' => 0, 'other' => 0 );
			foreach ( \IPS\nexus\Gateway::roots() as $gateway )
			{
				$class = \strtolower( \substr( \get_class( $gateway ), 18 ) );
				$settings = json_decode( $gateway->settings, TRUE );
				
				if ( $class === 'authorizenet' )
				{
					$class = ( $settings['method'] === 'AIM' ) ? 'authorizenet_aim' : 'authorizenet_dpm';
				}
				elseif ( $class === 'paypal' )
				{
					$class = ( $settings['type'] === 'paypal' ) ? 'paypal_standard' : 'paypal_pro';
				}
				elseif ( $class === 'stripe' )
				{
					$class = 'stripe_' . $settings['type'];
				}
				
				
				if ( array_key_exists( $class, $report['paymethods'] ) )
				{
					$report['paymethods'][ $class ]++;
				}
				else
				{
					$report['paymethods']['other']++;
				}
			}
		}

		/* Converters */
		$report['converters'] = array();
		if ( \IPS\Application::appIsEnabled('convert') )
		{
			foreach( \IPS\convert\App::apps() as $app )
			{
				/* Some legacy converters may not have the correct sw key */
				if( !\in_array( $app->sw, \IPS\Application::$ipsApps ) OR !$app->finished )
				{
					continue;
				}

				$report['converters'][ $app->sw . '_' . $app->app_key ] = $app->start_date;
			}
		}

		/* Return */
		return $report;
	}
	
	/**
	 * Output response
	 *
	 * @param	string		$response			Response to output
	 * @param	int			$httpResponseCode	HTTP Response Code
	 * @param	NULL|string	$oauthError			OAuth error
	 * @param	bool		$log				Whether or not to log the response
	 * @return	void
	 */
	protected function _respond( $response, $httpResponseCode, $oauthError=NULL, $log=FALSE )
	{
		$headers = array();
		if ( $this->rawAccessToken and $oauthError )
		{
			$headers['WWW-Authenticate'] = "Bearer error=\"{$oauthError}\"";
		}
		
		if ( $log )
		{
			$this->_log( $response, $httpResponseCode );
		}
		
		\IPS\Output::i()->sendOutput( $response, $httpResponseCode, 'application/json', $headers );
	}
	
	/**
	 * Destructor
	 *
	 * @return	void
	 */
	public function __destruct()
	{
		
	}
}