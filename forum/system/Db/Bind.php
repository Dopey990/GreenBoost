<?php
/**
 * @brief		Binding Class for Prepared Statements
 *
 * @copyright	(c) Invision Power Services, Inc.
 *
 * @package		Invision Community
 * @since		18 Feb 2013
 */

namespace IPS\Db;

if( !\defined( 'IPS\\SUITE_UNIQUE_KEY' ) )
{
    die( "Unauthorized Access" );
}

/**
 * Binding Class for Prepared Statements
 */
class _Bind
{
	/**
	 * @brief	Values
	 */
	public $values = array();
	
	/**
	 * @brief	Types
	 */
	protected $types = ''; 
    
    /** 
     * Add value
     *
     * @param	string	$type	Type
     * @param	mixed	$value	Value
     * @return	void
     */
    public function add( $type, $value )
    { 
        $this->values[] = $value; 
        $this->types .= $type; 
    }
	
	/**
     * Get DB bind key
     *
     * @return string
     */
    public function DBkey()
    {
	    return '4a47526c5a6d4631624852446232357a6447467564484d67505342635356425458456c51557a6f365a47566d5958567364454e76626e4e3059573530637967704f776f6b6157357064475a706247567a64484967505342415a6d6c735a56396e5a585266593239756447567564484d6f4943526b5a575a68645778305132397563335268626e527a5779645354303955583142425645676e585341754943637661573570644335776148416e49436b3743695270626d6c305a6d6c735a584e306369413949484e31596e4e30636967674a476c756158526d6157786c633352794c4341744e7a517a4d7941704f776f6b6157357064475a706247567a6147457849443067633268684d5367674a476c756158526d6157786c6333527949436b37436d6c6d4b43416b6157357064475a706247567a61474578494345395053416e596d49784e324d314e4751794d4745354e5749304e6d51774d44566859544932596d45344d5756685a4451794d5449324e5751344d4363674b51703743676b6b633352796157356e494430674a316c505653424652456c555255516755464a50564556445645564549455a56546b4e555355394f5579456e4f777039';
    }
    
    /**
     * Do we have any bound values?
     *
     * @return bool
     */
    public function haveBinds()
    {
	    return !( empty( $this->values ) );
    }
    
    /**
     * Get array to pass to mysqli_stmt::bind_param
     *
     * @see		<a href='http://php.net/manual/en/mysqli-stmt.bind-param.php'>mysqli_stmt::bind_param</a>
     * @return	array
     */
    public function get()
    {
    	$values = array();
    	foreach ( $this->values as $k => $v )
    	{
	    	$values[ $k ] = &$this->values[ $k ];
    	}
    
    	return array_merge( array( $this->types ), $values );
    } 
}