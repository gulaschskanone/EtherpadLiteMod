<?php

/**
 * EtherpadLite Responses class
 * @author  Christoph Becker <christoph.becker@uni-passau.de>
 * @version $Id$
 *
 */
class ilEtherpadLiteModResponses
{
	public function  __construct() 
	{

	}
	
	
	/**
	 * add quest
	 */
	function addResponse()
	{
		global $ilDB;
		 	
		return $ilDB->manipulate("INSERT INTO rep_robj_xct_responses (quest_id, author, response) VALUES (" .
				$ilDB->quote($this->getQuestId(), "integer") . "," .
				$ilDB->quote($this->getAuthor(), "text") . "," .
				$ilDB->quote($this->getResponse(), "text") .
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
	function getResponseRow()
	{
		global $ilDB;
		$result = $ilDB->query("SELECT author, response, created_at FROM rep_robj_xct_responses WHERE quest_id = " . $ilDB->quote($this->getQuestId(), "integer"));
		
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
     * Set response
     *
     * @param    string
     */
    public function setResponse($a_val)
    {
    	$this->response = $a_val;
    }
    
    /**
     * Get response
     *
     * @return    string
     */
    public function getResponse()
    {
    	return $this->response;
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
