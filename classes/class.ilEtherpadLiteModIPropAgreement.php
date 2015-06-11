<?php

include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModPolicyAgreement.php");

/**
 * EtherpadLite IP Agreements class
 * @author  Christoph Becker <christoph.becker@uni-passau.de>
 * @version $Id$
 *
 */
class ilEtherpadLiteModIPropAgreement extends ilEtherpadLiteModPolicyAgreement
{

	function saveAgreement()
	{
		global $ilDB;
		 
		if($ilDB->manipulate("INSERT INTO rep_robj_xct_ip_agt (`username`, `pad_id`, `attribution`, `hash`) VALUES (". 
				$ilDB->quote($this->getUsername(), "text") . ", " .
				$ilDB->quote($this->getPadId(), "text") .", " .
				$ilDB->quote($this->getAttribution(), "integer") .", " .
				$ilDB->quote($this->getHash(), "text") .")")
		) return true;
		else return false;
	}
	
	function getIPropAgreementByPadId($PadId)
	{
		global $ilDB;
		$result = $ilDB->query("SELECT * FROM rep_robj_xct_ip_agt WHERE pad_id = ". $ilDB->quote($PadId, "text") ." AND username = " . $ilDB->quote($this->getUsername(), "text"));
		if($result->numRows() == 0)
		{
			return false;
		}
		while ($rec = $ilDB->fetchAssoc($result))
		{
			$this->setHash($rec["hash"]);
			$this->setConsentedAt($rec["consented_at"]);
			$this->setAttribution($rec["attribution"]);
		}
		return $this;
	}
    
    /** 
     * class setter and getter
     */

    /**
     * Set pad id
     *
     * @param    string
     */
    public function setPadId($a_val)
    {
    	$this->pad_id = $a_val;
    }
    
    /**
     * Get pad id
     *
     * @return    string
     */
    public function getPadId()
    {
    	return $this->pad_id;
    }
    
    
    /**
     * Set attribution
     *
     * @param    boolean
     */
    public function setAttribution($a_val)
    {
    	$this->attribution = $a_val;
    }
    
    /**
     * Get attribution
     *
     * @return    boolean attribution
     */
    public function getAttribution()
    {
    	return $this->attribution;
    }
}


?>
