<?php

/**
 * EtherpadLite Export class
 * @author  Christoph Becker <christoph.becker@uni-passau.de>
 * @version $Id$
 *
 */
class ilEtherpadLiteModExport
{
	
	protected $epadl_id = "";
	protected $created_at = "";
	protected $authors = array();
	protected $task = "";
	protected $title = "";
	protected $proposal = "";
	

	/**
	 * Constructor
	 *
	 * @param
	 */
	public function  __construct() 
	{

	}
	
	
	/**
	 * Save data to db
	 */
	public function doCreate()
	{
		global $ilDB;
		
		$ilDB->manipulate("INSERT INTO rep_robj_xct_exports (epadl_id, title, task, authors, proposal) VALUES (" .
				$ilDB->quote($this->getEpadlID(), "text") . "," .
				$ilDB->quote($this->getTitle(), "text") . "," .
				$ilDB->quote($this->getTask(), "text") . "," .
				$ilDB->quote(serialize($this->getAuthors()), "text") . "," .
				$ilDB->quote($this->getProposal(), "text") . 
			")");
	}
	
	/**
	 * update data to db
	 */
	public function doUpdate()
	{
		global $ilDB;
	
		$ilDB->manipulate($up = "UPDATE rep_robj_xct_exports SET " .
				" task = " . $ilDB->quote($this->getTask(), "text").  "," .
				" title = " . $ilDB->quote($this->getTitle(), "text").  "," .
				" proposal = " . $ilDB->quote($this->getProposal(), "text").  "," .
				" created_at = now() ," .
				" authors = " . $ilDB->quote(serialize($this->getAuthors()), "text").
				" WHERE epadl_id = " . $ilDB->quote($this->getEpadlID(), "text")
		);
	}
	
	/**
	 * Read data from db
	 */
	public function doRead()
	{
		global $ilDB;
		
		$set = $ilDB->query("SELECT * FROM rep_robj_xct_exports " .
				" WHERE epadl_id = " . $ilDB->quote($this->getEpadlID(), "text")
		);
		
		if($set->numRows() == 0)
		{
			return false;
		}
		
		while ($rec = $ilDB->fetchAssoc($set))
		{
			$this->setTask($rec["task"]);
			$this->setTitle($rec["title"]);
			$this->setProposal($rec["proposal"]);
			$this->setAuthors(unserialize($rec["authors"]));
			$this->setCreatedAt($rec["created_at"]);
		}
		
		return true;
	}
	
	/**
	 * Delete data from db
	 */
	function doDelete()
	{
		global $ilDB;
	
		if($ilDB->manipulate("DELETE FROM rep_robj_xct_exports WHERE ".
				" epadl_id = ".$ilDB->quote($this->getEpadlID(), "text")))
		{
			return true;
		}
		else {
			return false;
		}
	
	}
    
    /** 
     * class setter and getter
     */
       
    /**
     * Set pad id
     *
     * @param    string
     */
    public function setEpadlID($a_val)
    {
    	$this->epadl_id = $a_val;
    }
    
    /**
     * Get pad id
     *
     * @return    string
     */
    public function getEpadlID()
    {
    	return $this->epadl_id;
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
     * Get created at
     *
     * @return    timestamp
     */
    public function getCreatedAt()
    {
    	return $this->created_at;
    }
    
    
    /**
     * Set task
     *
     * @param    string
     */
    public function setTask($a_val)
    {
    	$this->task = $a_val;
    } 
    
    /**
     * Get task
     *
     * @return    string
     */
    public function getTask()
    {
    	return $this->task;
    }

    
   
    /**
     * Set title
     *
     * @param    string
     */
    public function setTitle($a_val)
    {
    	$this->title = $a_val;
    }
    
    /**
     * Get title
     *
     * @return    string
     */
    public function getTitle()
    {
    	return $this->title;
    }    

    
    /**
     * Set proposal
     *
     * @param    string
     */
    public function setProposal($a_val)
    {
    	$this->proposal = $a_val;
    }
    
    
    /**
     * Get proposal
     *
     * @return    string
     */
    public function getProposal()
    {
    	return $this->proposal;
    }
    
    /**
     * add author
     *
     * @param    string
     */
    public function addAuthor($a_val)
    {
    	$this->authors[] = $a_val;
    }
    
    /**
     * Set authors
     *
     * @param    array
     */
    public function setAuthors($a_val)
    {
    	$this->authors = $a_val;
    }
    
    /**
     * Get authors
     *
     * @return    array
     */
    public function getAuthors()
    {
    	return $this->authors;
    }
}


?>
