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
		
		return $ilDB->manipulate("INSERT INTO rep_robj_xct_quests (pad_id, quest_id, author, quest) VALUES (" .
				$ilDB->quote($this->getPadId(), "text") . "," .
				$ilDB->quote($quest_id, "integer") . "," .
				$ilDB->quote($this->getAuthor(), "text") . "," .
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
		$result = $ilDB->query("SELECT author, quest, created_at, quest_id FROM rep_robj_xct_quests WHERE pad_id = " . $ilDB->quote($this->getPadId(), "text"));
		$rows = array();
		
		while ($rec = $ilDB->fetchAssoc($result))
		{
			$rows[] = $rec;
		}
		return $rows;
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
     * Set created at
     *
     * @param    string
     */
    public function setCreatedAt($a_val)
    {
    	$this->created_at = $a_val;
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
     * Set author
     *
     * @param    string
     */
    public function setAuthor($a_val)
    {
    	$this->author = $a_val;
    }
    
    /**
     * Get author
     *
     * @return    string
     */
    public function getAuthor()
    {
    	return $this->author;
    }
}


?>
