<?php
/**
 * @brief		Domain Name Lookup
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @subpackage	Nexus
 * @since		11 Oct 2016
 */

namespace IPS\nexus;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * Domain Name Lookup
 */
class _DomainLookup
{
	const DOMAIN_TYPE_DOMAIN = 1;
	const DOMAIN_TYPE_SUBDOMAIN = 2;
	
	/**
	 * Domain Name
	 */
	public $domain;
	
	/**
	 * Domain Name Type
	 */
	public $type;
	
	/**
	 * TLD
	 */
	public $tld;
	
	/**
	 * SLD
	 */
	public $sld;
	
	/**
	 * WHOIS
	 */
	public $whois = array();
	
	/**
	 * Constructor
	 *
	 * @param	string	$domain				The domain name
	 * @param	bool	$performWhoisLookup	if TRUE, a WHOIS lookup will be attempted
	 * @throws	\InvalidArgumentException
	 */
	public function __construct( $domain, $performWhoisLookup=TRUE )
	{
		$this->domain = $domain;
		
		$domainData = $this->_findDomainData( NULL, $domain, json_decode( file_get_contents( \IPS\ROOT_PATH . '/applications/nexus/data/domains.json' ), TRUE ) );
		if ( $domainData === NULL )
		{
			throw new \InvalidArgumentException;
		}
		
		$this->tld = $domainData['name'];
		$this->sld = mb_substr( $domain, 0, - mb_strlen( $this->tld ) - 1 );
		$this->type = ( mb_substr_count( $this->sld, '.' ) > 0 ) ? static::DOMAIN_TYPE_SUBDOMAIN : static::DOMAIN_TYPE_DOMAIN;
		
		foreach ( $domainData['whoisServers'] as $whoisServer )
		{
			if ( $resource = @fsockopen( $whoisServer, 43 ) )
			{
				\fwrite( $resource, $domain . "\r\n" );
				$response = '';
				while( !feof( $resource ) )
				{
					$response .= \fgets( $resource, 8192 );
				}
				fclose( $resource );
				
				$this->whois[] = $response;
			}
		}
	}
	
	/**
	 * Find the data for the deepest domain level
	 *
	 * @param	array|null	$currentVal	The value to return if no deeper value exists
	 * @param	array		$domain		Domain being examined
	 * @return	array|null
	 */
	protected function _findDomainData( $currentVal, $domain, $jsonData )
	{
		foreach ( $jsonData['domains'] as $data )
		{
			if ( mb_substr( $domain, - ( mb_strlen( $data['name'] ) + 1 ) ) === ".{$data['name']}" )
			{
				return $this->_findDomainData( $data, $domain, $data );
			}
		}
		
		return $currentVal;
	}
}