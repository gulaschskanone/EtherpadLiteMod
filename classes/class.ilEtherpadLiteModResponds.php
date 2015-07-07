<?php

/**
 * EtherpadLite Responds class
 * @author  Christoph Becker <christoph.becker@uni-passau.de>
 * @version $Id$
 *
 */
class ilEtherpadLiteModResponds
{
	public function  __construct() 
	{

	}
	
	
	/**
	 * add quest
	 */
	function addRespond()
	{
		global $ilDB;
		 	
		return $ilDB->manipulate("INSERT INTO rep_robj_xct_responds (quest_id, author, respond) VALUES (" .
				$ilDB->quote($this->getQuestId(), "integer") . "," .
				$ilDB->quote($this->getAuthor(), "text") . "," .
				$ilDB->quote($this->getRespond(), "text") .
			")");
	}
	
	
	/**
	 * count quests
	 
	function numberOfQuests()
	{
		global $ilDB;
		$result = $ilDB->query("SELECT quest_id FROM rep_robj_xct_quests WHERE pad_id = " . $ilDB->quote($this->getPadId(), "text"));
		
		return $result->numRows();
	}
	*/
	
	/**
	 * get quests
	 */
	function getRespondRow()
	{
		global $ilDB;
		$result = $ilDB->query("SELECT author, respond, created_at FROM rep_robj_xct_responds WHERE quest_id = " . $ilDB->quote($this->getQuestId(), "integer"));
		
		return $ilDB->fetchAssoc($result);
	}


    
    /** 
     * class setter and getter
     */
       
    /**
     * Set quest id
     *
     * @param    int
     */
    public function setQuestId($a_val)
    {
    	$this->quest_id = $a_val;
    }
    
    /**
     * Get quest id
     *
     * @return    int
     */
    public function getQuestId()
    {
    	return $this->quest_id;
    }
    
    /**
     * Set respond
     *
     * @param    string
     */
    public function setRespond($a_val)
    {
    	$this->respond = $a_val;
    }
    
    /**
     * Get respond
     *
     * @return    string
     */
    public function getRespond()
    {
    	return $this->respond;
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
