<?php

/**
 * EtherpadLite Policy Agreement class
 * @author  Christoph Becker <christoph.becker@uni-passau.de>
 * @version $Id$
 *
 */
class ilEtherpadLiteModPolicyAgreement
{
	public function  __construct() 
	{
		global $ilUser;
		$this->setUsername($ilUser->getLogin());
	}
	
	function getPolicyAgreementByType($type)
	{
		global $ilDB;
		$result = $ilDB->query("SELECT * FROM rep_robj_xct_pol_agt WHERE policy_type = ". $ilDB->quote($type, "text") ." AND username = " . $ilDB->quote($this->getUsername(), "text"));
		if($result->numRows() == 0)
		{
			return false;
		}
		while ($rec = $ilDB->fetchAssoc($result))
		{
			$this->setHash($rec["hash"]);
			$this->setConsentedAt($rec["consented_at"]);
		}
		return $this;
	}

	function saveAgreement()
	{
		global $ilDB;
		 
		if($ilDB->manipulate("INSERT INTO rep_robj_xct_pol_agt (`username`, `policy_type`, `hash`) VALUES (". 
				$ilDB->quote($this->getUsername(), "text") . ", " .
				$ilDB->quote($this->getPolicyType(), "text") .", " .
				$ilDB->quote($this->getHash(), "text") .")")
		) return true;
		else return false;
	}
    
    /** 
     * class setter and getter
     */

    /**
     * Set username
     *
     * @param    boolean
     */
    public function setUsername($a_val)
    {
    	$this->username = $a_val;
    }
    
    /**
     * Get username
     *
     * @return    string username
     */
    public function getUsername()
    {
    	return $this->username;
    }
    
    /**
     * Set policy type
     *
     * @param    boolean
     */
    public function setPolicyType($a_val)
    {
    	$this->policy_type = $a_val;
    }
    
    /**
     * Get PolicyType
     *
     * @return    string PolicyType
     */
    public function getPolicyType()
    {
    	return $this->policy_type;
    }
 
    /**
     * Set ConsentedAt
     *
     * @param    boolean
     */
    public function setConsentedAt($a_val)
    {
    	$this->consented_at = $a_val;
    }
    
    /**
     * Get ConsentedAt
     *
     * @return    datetime consented_at
     */
    public function getConsentedAt()
    {
    	return $this->consented_at;
    }
    
    /**
     * Set hash
     *
     * @param    boolean
     */
    public function setHash($a_val)
    {
    	$this->hash = $a_val;
    }
    
    /**
     * Get hash
     *
     * @return    string hash
     */
    public function getHash()
    {
    	return $this->hash;
    }
}


?>
