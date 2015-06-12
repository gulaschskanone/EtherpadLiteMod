<?php

/**
 * EtherpadLite User class
 * @author  Christoph Becker <christoph.becker@uni-passau.de>
 * @version $Id$
 *
 */
class ilEtherpadLiteModUser
{
	public function  __construct() 
	{
		global $ilUser;
		$this->setUsername($ilUser->getLogin());
		$this->init();
	}
	
    /**
     * Get user values
     */
    function init()
    {
    	global $ilDB;
		$result = $ilDB->query("SELECT * FROM rep_robj_xct_user WHERE username = " . $ilDB->quote($this->getUsername(), "text"));
		if($result->numRows() == 0)
		{
			$this->addUser(); 
		}
		while ($rec = $ilDB->fetchAssoc($result))
		{
			$this->setPseudonym($rec["pseudonym"]);
		}
		return true;
    }
    
    
    /**
     * New user
     */
	public function addUser(){
		global $ilDB;
			    
	    $ilDB->manipulate("INSERT INTO rep_robj_xct_user (username) VALUES (" .
	    		$ilDB->quote($this->getUsername(), "text") . 
	    ")");	    
	}
	
	/**
	 * update user
	 */
	public function updateUser(){
		global $ilDB;
		 
		if($ilDB->manipulate("UPDATE rep_robj_xct_user SET 
			`pseudonym` = ". $ilDB->quote($this->getPseudonym(), "text") . "
			WHERE `username` = ". $ilDB->quote($this->getUsername(), "text")
		)) {
				include_once("./Services/Logging/classes/class.ilLog.php");
				$log = new ilLog("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/log", "eplmod.log", "changed pseudonym");
				$log->write("user '" . $this->getUsername() . "' has updated his pseudonym to '".$this->getPseudonym()."'");
			
			
			return true;
		}
		else return false;
		
	}
	
	/**
	 * agree policy
	 */
	public function agreePolicy($type, $hash, $PadId = "error", $attribution = 0)
	{

		
		if($type == "IPropPolicy")
		{
			include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModIPropAgreement.php");
			$this->ip_agreement = new ilEtherpadLiteModIPropAgreement();
				
			$this->ip_agreement->setPadId($PadId);
			$this->ip_agreement->setHash($hash);
			$this->ip_agreement->setAttribution($attribution);
				
			if($this->ip_agreement->saveAgreement()) return true;
			else return false;
		}
		else
		{
			include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModPolicyAgreement.php");
			$this->policy_agreement = new ilEtherpadLiteModPolicyAgreement();
			
			$this->policy_agreement->setPolicyType($type);
			$this->policy_agreement->setHash($hash);
			
			if($this->policy_agreement->saveAgreement()) return true;
			else return false;
		}
	}
	
	
	
	/**
	 * get policy agreement by type
	 */
	public function getPolicyAgreement($type, $PadId)
	{
		if($type == "IPropPolicy")
		{
			include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModIPropAgreement.php");
			$this->ip_agreement = new ilEtherpadLiteModIPropAgreement();
			return $this->ip_agreement->getIPropAgreementByPadId($PadId);
		}
		else
		{
			include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModPolicyAgreement.php");
			$this->policy_agreement = new ilEtherpadLiteModPolicyAgreement();
			return $this->policy_agreement->getPolicyAgreementByType($type);
		}
	}
	
	/**
	 * agreements completely ?
	 */
	public function agreementsCompletely($necessaryTypes, $PadId)
	{
		include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModPolicyAgreement.php");
		$this->policy_agreement = new ilEtherpadLiteModPolicyAgreement();
		
		include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModIPropAgreement.php");
		$this->ip_agreement = new ilEtherpadLiteModIPropAgreement();
		
		$missing = array();
		
		foreach($necessaryTypes as $type)
		{
			if($type == "IPropPolicy")
			{
				if(!$this->ip_agreement->getIPropAgreementByPadId($PadId))
				{
					$missing[] = $type;
				}
			}
			else
			{
				if(!$this->policy_agreement->getPolicyAgreementByType($type))
				{
					$missing[] = $type;
				}
			}
		}
		return $missing;
	}
	
	/**
	 * Revoke Consent
	 * ! only for demonstration !
	 */
	public function revokeConsent(){
		global $ilDB;
		 $ilDB->manipulate("DELETE FROM rep_robj_xct_ip_agt
				WHERE username = " . $ilDB->quote($this->getUsername(), "text"));
		 $ilDB->manipulate("DELETE FROM rep_robj_xct_pol_agt
				WHERE username = " . $ilDB->quote($this->getUsername(), "text"));
		 return true;
	}
    
    /** 
     * class setter and getter
     */
       
    /**
     * Set pseudonym
     *
     * @param    boolean
     */
    public function setPseudonym($a_val)
    {
    	$this->pseudonym = $a_val;
    }
    
    /**
     * Get pseudonym
     *
     * @return    boolean
     */
    public function getPseudonym()
    {
    	return $this->pseudonym;
    }
    
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



}


?>
