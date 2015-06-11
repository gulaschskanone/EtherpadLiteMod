<?php

/**
 * EtherpadLite Quests class
 * @author  Christoph Becker <christoph.becker@uni-passau.de>
 * @version $Id$
 *
 */
class ilEtherpadLiteModQuests
{
	public function  __construct() 
	{

	}
	
	
	/**
	 * add quest
	 */
	function addQuest()
	{
		global $ilDB;
		 
		$quest_id = $ilDB->nextID('rep_robj_xct_quests');
		
		return $ilDB->manipulate("INSERT INTO rep_robj_xct_quests (pad_id, quest_id, username, quest) VALUES (" .
				$ilDB->quote($this->getPadId(), "text") . "," .
				$ilDB->quote($quest_id, "integer") . "," .
				$ilDB->quote($this->getUsername(), "text") . "," .
				$ilDB->quote($this->getQuest(), "text") .
				")");
	}
	
	
	/**
	 * count quests
	 */
	function numberOfQuests()
	{
		global $ilDB;
		$result = $ilDB->query("SELECT quest_id FROM rep_robj_xct_quests WHERE pad_id = " . $ilDB->quote($this->getPadId(), "text"));
		
		return $result->numRows();
	}
	
	/**
	 * get quests
	 */
	function getQuests()
	{
		global $ilDB;
		$result = $ilDB->query("SELECT username, quest, created_at FROM rep_robj_xct_quests WHERE pad_id = " . $ilDB->quote($this->getPadId(), "text"));
		$rows = array();
		
		while ($rec = $ilDB->fetchAssoc($result))
		{
			$rows[] = $rec;
		}
		return $rows;
	}
	
	/**
	 * Revoke Questions
	 * ! only for demonstration !
	 */
	public function revokeQuestions(){
		global $ilDB;
		return $ilDB->manipulate("DELETE FROM rep_robj_xct_quests
				WHERE pad_id = " . $ilDB->quote($this->getPadId(), "text"));
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
     * Set quest
     *
     * @param    string
     */
    public function setQuest($a_val)
    {
    	$this->quest = $a_val;
    }
    
    /**
     * Get quest
     *
     * @return    string
     */
    public function getQuest()
    {
    	return $this->quest;
    }
    
    /**
     * Set username
     *
     * @param    string
     */
    public function setUsername($a_val)
    {
    	$this->username = $a_val;
    }
    
    /**
     * Get username
     *
     * @return    string
     */
    public function getUsername()
    {
    	return $this->username;
    }
}


?>
