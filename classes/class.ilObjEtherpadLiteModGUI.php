<?php
/*
	+-----------------------------------------------------------------------------+
	| EtherpadLite ILIAS Plugin                                                        |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 2012-2013 Jan Rocho										      |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/


include_once("./Services/Repository/classes/class.ilObjectPluginGUI.php");

/**
* User Interface class for EtherpadLite repository object.
* 
* @author Timon Amstutz <timon.amstutz@ilub.unibe.ch>
* @author Jan Rocho <jan.rocho@fh-dortmund.de>
*
* $Id$
*
*
* @ilCtrl_isCalledBy ilObjEtherpadLiteModGUI: ilRepositoryGUI, ilAdministrationGUI, ilObjPluginDispatchGUI
* @ilCtrl_Calls ilObjEtherpadLiteModGUI: ilPermissionGUI, ilInfoScreenGUI, ilObjectCopyGUI, ilCommonActionDispatcherGUI
*
*/
class ilObjEtherpadLiteModGUI extends ilObjectPluginGUI
{
		
    /**
     * Initialisation
     */
    protected function afterConstructor()
    {

    }

    /**
     * Get type.
     */
    final function getType()
    {
        return "xct";
    }

    /**
     * Handles all commmands of this class, centralizes permission checks
     */
    function performCommand($cmd)
    {
        switch ($cmd)
        {
            case "editProperties": // list all commands that need write permission here
            case "updateProperties":
            case "saveResponse":
                $this->checkPermission("write");
                $this->$cmd();
                break;

            case "showContent": // list all commands that need read permission here
            case "agreePolicy":
            case "requestForHelp":
            case "savePseudonym":
            case "showProfile":
                $this->checkPermission("read");
                $this->$cmd();
                break;
        }
    }

    /**
     * After object has been created -> jump to this command
     */
    function getAfterCreationCmd()
    {
        return "editProperties";
    }

    /**
     * Get standard command
     */
    function getStandardCmd()
    {
        return "showContent";
    }

//
// DISPLAY TABS
//

    /**
     * Set tabs
     */
    function setTabs()
    {
        global $ilTabs, $ilCtrl, $ilAccess;

        // tab for the "show content" command
        if ($ilAccess->checkAccess("read", "", $this->object->getRefId()))
        {
            $ilTabs->addTab("content", $this->txt("content"), $ilCtrl->getLinkTarget($this, "showContent"));
        }

        // standard info screen tab
        $this->addInfoTab();

        // a "properties" tab
        if ($ilAccess->checkAccess("write", "", $this->object->getRefId()))
        {
            $ilTabs->addTab("properties", $this->txt("properties"), $ilCtrl->getLinkTarget($this, "editProperties"));
        }

        // standard epermission tab
        $this->addPermissionTab();
        
       
        // profile
        if ($ilAccess->checkAccess("read", "", $this->object->getRefId()))
        {
        	$ilTabs->addTab("showProfile", "Nutzerprofil", $ilCtrl->getLinkTarget($this, "showProfile"));
        }
        
        //
        // revoke
        /* !only fr demonstration !
        if ($ilAccess->checkAccess("write", "", $this->object->getRefId()))
        {
        	$ilTabs->addTab("revokeConsent", "Einwilligungen zurückziehen (DEMO)", $ilCtrl->getLinkTarget($this, "revokeConsent"));
        }
        */

        // eagle eye
        include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
        $this->EtherpadLiteUser = new ilEtherpadLiteModUser();
        $missingPolicies = $this->EtherpadLiteUser->agreementsCompletely(array("PrivacyPolicy", "Rules", "IPropPolicy"), $this->object->getEtherpadLiteID());
        
        if($this->EtherpadLiteUser->getPseudonym() && empty($missingPolicies))
        {
	        if ($ilAccess->checkAccess("read", "", $this->object->getRefId()))
	        {
	        	$ilTabs->addTab("requestForHelp", "Eagle Eye (Bitte um Hilfe)", $ilCtrl->getLinkTarget($this, "requestForHelp"));
	        }
        }
        

    }


//
// Edit properties form
//

    /**
     * Edit Properties. This commands uses the form class to display an input form.
     */
    function editProperties()
    {
        global $tpl, $ilTabs;

        $ilTabs->activateTab("properties");
        $this->initPropertiesForm();
        $this->getPropertiesValues();
        $tpl->setContent($this->form->getHTML());
    }
    


    /**
     * Init  form.
     *
     * @param        int        $a_mode        Edit Mode
     */
    public function initPropertiesForm()
    {
        global $ilCtrl;

        include_once("./Services/Form/classes/class.ilPropertyFormGUI.php");
        $this->form = new ilPropertyFormGUI();

        // hidden Inputfield for ID
        $epadlid_input = new ilHiddenInputGUI("epadl_id");
        $this->form->addItem($epadlid_input);

        // title
        $ti = new ilTextInputGUI($this->txt("title"), "title");
        $ti->setRequired(true);
        $this->form->addItem($ti);

        // description
        $ta = new ilTextAreaInputGUI($this->txt("description"), "desc");
        $this->form->addItem($ta);
	        
        //online
        $cb = new ilCheckboxInputGUI($this->lng->txt("online"), "online");
        $this->form->addItem($cb);

// 	    // time restriction
// 		$restriction = new ilRadioGroupInputGUI($this->txt("time_restriction"), "time_restriction");
// 			$offline = new ilRadioOption($this->txt("offline"),"offline", null);
// 		    $now = new ilRadioOption($this->txt("online"),"online", null);
// 		    $timeframe = new ilRadioOption($this->txt("restricted"),"restricted", null);
		    	
// 		    	include_once("./Services/Form/classes/class.ilDateDurationInputGUI.php");
// 		    	$duration = new ilDateDurationInputGUI("Zeitraum", "duration", null);
// 		    	$duration->setShowTime(true);
// 		    	$duration->setMinuteStepSize("15");
		    	
// 		    $timeframe->addSubItem($duration);

// 		$restriction->addOption($offline);
// 		$restriction->addOption($now);
// 		$restriction->addOption($timeframe);		    
// 		$this->form->addItem($restriction);
        
        
		// lecturer mail
		$lm = new ilRadioGroupInputGUI("E-Mail-Adresse des Dozenten", "xct_eagle_eye_mail");
			$option_owner = new ilRadioOption(ilObjUser::_lookupEmail($this->object->getOwner()),"owner", "Besitzer des Pads");
			$lm->addOption($option_owner);
			$option_other = new ilRadioOption("Andere","other", null);
				$mail = new ilEMailInputGUI("E-Mail-Adresse", "other_lecturer_mail");
				$mail->setInfo("Bitte verwenden Sie nur Uni-interne E-Mail-Adressen.");
       			$option_other->addSubItem($mail);
			$lm->addOption($option_other);	        
	    $this->form->addItem($lm);
	    
	    
		// available questions
        $aq = new ilSelectInputGUI("Anzahl möglicher Fragen", "xct_av_questions");
        $options = array();
        for ($i = 2; $i<10; $i++) { $options[$i] = $i; }
        $aq->setOptions($options);
        $aq->setRequired(true);
        $this->form->addItem($aq);

	    
        // Show Elements depending on settings in the administration of the plugin
        include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModConfig.php");
        $this->adminSettings = new ilEtherpadLiteModConfig();
                	
        	
			// read only
		        if($this->adminSettings->getValue("allow_read_only"))
		        {
					$ro = new ilCheckboxInputGUI($this->txt("read_only"), "read_only");
					$this->form->addItem($ro);
				}


	        // show Chat
		        if($this->adminSettings->getValue("conf_show_chat"))
		        {
		
		            $chat = new ilCheckboxInputGUI($this->txt("show_chat"), "show_chat");
		            //$chat->setInfo($this->txt("info_show_chat"));
		            $this->form->addItem($chat);
		        }

        	// show line number
		        if($this->adminSettings->getValue("conf_line_numbers"))
		        {
		            $line = new ilCheckboxInputGUI($this->txt("show_line_numbers"), "show_line_numbers");
		            //$line->setInfo($this->txt("info_show_line_numbers"));
		            $this->form->addItem($line);
		        }

        	// monospace font
		        if($this->adminSettings->getValue("conf_monospace_font"))
		        {
		            $font = new ilCheckboxInputGUI($this->txt("monospace_font"), "monospace_font");
		            $font->setInfo($this->txt("info_monospace_font"));
		            $this->form->addItem($font);
		        }


       		// show colors
		        if($this->adminSettings->getValue("conf_show_colors"))
		        {
		            $colors = new ilCheckboxInputGUI($this->txt("show_colors"), "show_colors");
		            //$colors->setInfo($this->txt("info_show_colors"));
		            $this->form->addItem($colors);
		        }


        // show controls
        if($this->adminSettings->getValue("conf_show_controls"))
        {
            $controls = new ilCheckboxInputGUI($this->txt("show_controls"), "show_controls");
            //$controls->setInfo($this->txt("info_show_controls"));



            // show style
            if($this->adminSettings->getValue("conf_show_controls_conf_show_style"))
            {
                $style = new ilCheckboxInputGUI($this->txt("show_style"), "show_style");
                $style->setInfo($this->txt("info_show_style"));
                $controls->addSubItem($style);
            }


             // show list
            if($this->adminSettings->getValue("conf_show_controls_conf_show_list"))
            {
                $list = new ilCheckboxInputGUI($this->txt("show_list"), "show_list");
                $list->setInfo($this->txt("info_show_list"));
                $controls->addSubItem($list);
            }


            // show redo
            if($this->adminSettings->getValue("conf_show_controls_conf_show_redo"))
            {
                $redo = new ilCheckboxInputGUI($this->txt("show_redo"), "show_redo");
                //$redo->setInfo($this->txt("info_show_redo"));
                $controls->addSubItem($redo);
            }


            // show coloring
            if($this->adminSettings->getValue("conf_show_controls_conf_show_coloring"))
            {
                $coloring = new ilCheckboxInputGUI($this->txt("show_coloring"), "show_coloring");
                $coloring->setInfo($this->txt("info_show_coloring"));
                $controls->addSubItem($coloring);
            }


            // show heading
            if($this->adminSettings->getValue("conf_show_controls_conf_show_heading"))
            {
                $heading = new ilCheckboxInputGUI($this->txt("show_heading"), "show_heading");
                $heading->setInfo($this->txt("info_show_heading"));
                $controls->addSubItem($heading);
            }


            // show import/export
            if($this->adminSettings->getValue("conf_show_controls_conf_show_imp_exp"))
            {
                $import = new ilCheckboxInputGUI($this->txt("show_import_export"), "show_import_export");
                $import->setInfo($this->txt("info_show_import_export"));
                $controls->addSubItem($import);
            }


            // show timeline
            if($this->adminSettings->getValue("conf_show_controls_conf_show_timeline"))
            {
                $timeline = new ilCheckboxInputGUI($this->txt("show_timeline"), "show_timeline");
                $timeline->setInfo($this->txt("info_show_timeline"));
                $controls->addSubItem($timeline);
            }
            
            // show comment button
            if($this->adminSettings->getValue("conf_show_controls_conf_show_comment"))
            {
            	$comment = new ilCheckboxInputGUI($this->txt("show_comment"), "show_comment");
            	$comment->setInfo($this->txt("info_show_comment"));
            	$controls->addSubItem($comment);
            }

            $this->form->addItem($controls);
        }

        $this->form->addCommandButton("updateProperties", $this->txt("save"));

        $this->form->setTitle($this->txt("edit_properties"));
        $this->form->setFormAction($ilCtrl->getFormAction($this));
    }

    /**
     * Get values for edit properties form
     */
    function getPropertiesValues()
    {
        $values["title"]    = $this->object->getTitle();
        $values["desc"]     = $this->object->getDescription();
        $values["online"]   = $this->object->getOnline();
        $values["epadl_id"] = $this->object->getEtherpadLiteID();
        $values["show_chat"]= $this->object->getShowChat();
        $values["show_line_numbers"]= $this->object->getLineNumbers();
        $values["monospace_font"]= $this->object->getMonospaceFont();
        $values["show_colors"]= $this->object->getShowColors();
        $values["show_controls"]= $this->object->getShowControls();
        $values["show_style"]= $this->object->getShowStyle();
        $values["show_list"]= $this->object->getShowList();
        $values["show_coloring"]= $this->object->getShowColoring();
        $values["show_redo"]= $this->object->getShowRedo();
        $values["show_heading"]= $this->object->getShowHeading();
        $values["show_import_export"]= $this->object->getShowImportExport();
        $values["show_timeline"]= $this->object->getShowTimeline();
        $values["show_comment"]= $this->object->getShowComment();
        $values["read_only"]= $this->object->getReadOnly();
               
        if($this->object->getEagleEyeMail() == "owner")
        {
        	$values["xct_eagle_eye_mail"] = "owner";
        }
        else
        {
        	$values["xct_eagle_eye_mail"] = "other";
        	$values["other_lecturer_mail"] = $this->object->getEagleEyeMail();
        }			
        
        $values["xct_av_questions"]= $this->object->getAvailableQuestions();
        
        $this->form->setValuesByArray($values);
    }

    /**
     * Update properties
     */
    public function updateProperties()
    {
        global $tpl, $lng, $ilCtrl;

        $this->initPropertiesForm();
        if ($this->form->checkInput())
        {
            $this->object->setTitle($this->form->getInput("title"));
            $this->object->setDescription($this->form->getInput("desc"));
            $this->object->setEtherpadLiteID($this->form->getInput("epadl_id"));
            $this->object->setOnline($this->form->getInput("online"));            
            $this->object->setShowChat($this->form->getInput("show_chat"));
            $this->object->setLineNumbers($this->form->getInput("show_line_numbers"));
            $this->object->setMonospaceFont($this->form->getInput("monospace_font"));
            $this->object->setShowColors($this->form->getInput("show_colors"));
            $this->object->setShowControls($this->form->getInput("show_controls"));
            $this->object->setShowStyle($this->form->getInput("show_style"));
            $this->object->setShowList($this->form->getInput("show_list"));
            $this->object->setShowColoring($this->form->getInput("show_coloring"));
            $this->object->setShowRedo($this->form->getInput("show_redo"));
            $this->object->setShowHeading($this->form->getInput("show_heading"));
            $this->object->setShowImportExport($this->form->getInput("show_import_export"));
            $this->object->setShowTimeline($this->form->getInput("show_timeline"));
            $this->object->setShowComment($this->form->getInput("show_comment"));
            $this->object->setReadOnly($this->form->getInput("read_only"));
            
            if($this->form->getInput("xct_eagle_eye_mail") == "owner")
            {
            	$this->object->setEagleEyeMail("owner");
            }
            else
            {
            	$this->object->setEagleEyeMail($this->form->getInput("other_lecturer_mail"));
            }
            
            $this->object->setAvailableQuestions($this->form->getInput("xct_av_questions"));

            $this->object->update();
            ilUtil::sendSuccess($lng->txt("msg_obj_modified"), true);
            $ilCtrl->redirect($this, "editProperties");
        }

        $this->form->setValuesByPost();
        $tpl->setContent($this->form->getHtml());
    }


    
    /**
     * save pseudonym
     */
    function savePseudonym(){
    	global $lng, $ilCtrl;
    	
    	include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
    	$this->EtherpadLiteUser = new ilEtherpadLiteModUser();
    	
    	if($_POST["nameform-input"])
    	{
	    	if($this->EtherpadLiteUser->setPseudonym(ilUtil::stripSlashes($_POST["nameform-input"])))
	    	{
	    		ilUtil::sendFailure("error on setPseudonym()", true);
	    	}
	    	elseif(!$this->EtherpadLiteUser->updateUser())
	    	{
	    		ilUtil::sendFailure("error on updateUser()", true);
	    	}
    	}
    	ilUtil::sendSuccess($lng->txt("msg_obj_modified"), true);
    	$ilCtrl->redirect($this, "showContent");
    }
    
    
// --------------------------------------------------------------------------------------
//
// Agree Policies
//    
    /**
     * agree policy
     */
    function agreePolicy()
    {
    	global $lng, $ilCtrl;
    	
    	include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
    	$this->EtherpadLiteUser = new ilEtherpadLiteModUser();
    	
    	if($_POST['tac-submit'])
    	{
    		$policiesContent = $this->policiesContent();
    		
    		$attribution = ($_POST['attribution'] == "yes") ? 1 : 0;
    		
    		foreach($_POST['tac'] as $type)
    		{
    		    if($policiesContent[$type]['hash'] && $this->EtherpadLiteUser->agreePolicy(
    		    		$type, 
    		    		$policiesContent[$type]['hash'], 
    		    		$this->object->getEtherpadLiteID(),
						$attribution)
				)
	    		{
	    			ilUtil::sendSuccess($lng->txt("msg_obj_modified"), true);
	    		}
	    		else
	    		{
	    			ilUtil::sendFailure("error", true);
	    		}
    		}
    	}
    	$ilCtrl->redirect($this, "showContent");

    }

//
// revoke consent
// !!! only for demonstration !!!
/*
     function revokeConsent()
     {
     global $lng, $ilCtrl;
       
     	include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
    	$this->EtherpadLiteUser = new ilEtherpadLiteModUser();
        	 
        if($this->EtherpadLiteUser->revokeConsent())	{
        	// ilUtil::sendSuccess($lng->txt("msg_obj_modified"), true);
        } else {
        	ilUtil::sendFailure("error", true);
        }
		$ilCtrl->redirect($this, "showContent");
    }
*/
   
//
// eagle eye (request for help)
//
	/**
	 * Request for help
	 */
	function requestForHelp()
	{
		
		global $tpl, $ilTabs, $ilCtrl, $ilUtil, $ilAccess, $lng;
		$questquota = $this->object->getAvailableQuestions();
		$ilTabs->activateTab("requestForHelp");
		
		include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModQuests.php");
		$this->EtherpadLiteQuests = new ilEtherpadLiteModQuests();
		$this->EtherpadLiteQuests->setPadId($this->object->getEtherpadLiteID());
		
		
		$customTpl = new ilTemplate("tpl.requestforhelp.html", true, true, "./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod");
		$customTpl->setVariable("QUESTQUOTA", $questquota);
		$customTpl->setVariable("QUESTLINK",$ilCtrl->getLinkTarget($this, "requestForHelp"));
		
		// save to DB, if POST and quota not achieved
		if(isset($_POST["quest-submit"]) && $this->EtherpadLiteQuests->numberOfQuests() < $questquota)
		{
			include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
    		$this->EtherpadLiteUser = new ilEtherpadLiteModUser();

			include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModConfig.php");
			$this->adminSettings = new ilEtherpadLiteModConfig();
			
			$this->EtherpadLiteQuests->setAuthor($this->EtherpadLiteUser->getPseudonym());
			$this->EtherpadLiteQuests->setQuest(ilUtil::stripSlashes($_POST["quest-input"]));
			
			if($this->EtherpadLiteQuests->addQuest())
			{ 
				// send mail to DOZENT
				$mail_to = ($this->object->getEagleEyeMail() == "owner") ? ilObjUser::_lookupEmail($this->object->getOwner()) : $this->object->getEagleEyeMail();
				$subject = "compliant teamwork | Neue Frage";
				
				$headers = "From: noreply@ct.uni-passau.de\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				
				$mailTpl = new ilTemplate("tpl.requestforhelpMail.html", true, true, "./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod");
				
				// require_once("Services/Init/classes/class.ilInitialisation.php");
				// ilInitialisation::initILIAS();
				$mailTpl->setVariable("LINK", ILIAS_HTTP_PATH ."/". $ilCtrl->getLinkTarget($this, "requestForHelp"));
				
				if(mail($mail_to, $subject, $mailTpl->get(), $headers))
				{
					ilUtil::sendSuccess("Frage gesendet!", true);
				}
				else 
				{
					ilUtil::sendError("E-Mail konnte nicht gesendet werden!", true);
				}
			}
		}
		
		// hide form, if quota achieved
		if($this->EtherpadLiteQuests->numberOfQuests() < $questquota)
		{
			$customTpl->setVariable("FORMDIV", "block");
		}
		else
		{
			$customTpl->setVariable("FORMDIV", "none");
		}
		
		// list all quests
		$list = "";
		if($this->EtherpadLiteQuests->numberOfQuests() > 0)
		{		
			include_once("./Services/UIComponent/Panel/classes/class.ilPanelGUI.php");
			foreach ($this->EtherpadLiteQuests->getQuests() as $row)
			{
				$panel = ilPanelGUI::getInstance();
				$panel->setHeading("<i>".$row["author"]."</i> schrieb am ". date("d.m.Y, H:i",strtotime($row["created_at"])));
				$panel->setBody($row["quest"]);
				$panel->setHeadingStyle(ilPanelGUI::HEADING_STYLE_SUBHEADING);
				$list .= $panel->getHTML();
				
				
				include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModResponds.php");
				$this->EtherpadLiteRespond = new ilEtherpadLiteModResponds();
				$this->EtherpadLiteRespond->setQuestId($row["quest_id"]);
				
				if($row = $this->EtherpadLiteRespond->getRespondRow())
				{
					$panel = ilPanelGUI::getInstance();
					$panel->setHeading("<i>".$row["author"]."</i> antwortete am ". date("d.m.Y, H:i",strtotime($row["created_at"])));
					$panel->setBody($row["respond"]);
					$panel->setHeadingStyle(ilPanelGUI::HEADING_STYLE_BLOCK);
					$list .= $panel->getHTML();
				}
				elseif($ilAccess->checkAccess("write", "", $this->object->getRefId()))
				{
					include_once("./Services/Form/classes/class.ilPropertyFormGUI.php");
					$form = new ilPropertyFormGUI();
					$form->setFormAction($ilCtrl->getFormAction($this));
					// $form->setTitle("<br/>");
					                
					// text input
					$text_prop = new ilTextAreaInputGUI("Antwort", "response");
					$text_prop->setInfo("Max. 500 Zeichen. Ihre Antwort lässt sich nicht bearbeiten oder löschen.");
					$text_prop->setRequired(true);
					$form->addItem($text_prop);
					
					// hidden
					$hidden = new ilHiddenInputGui("quest_id");
					$hidden->setValue($this->EtherpadLiteRespond->getQuestId());
					$form->addItem($hidden);
					
					$form->addCommandButton("saveResponse", $lng->txt("save"));

					$list .= $form->getHTML();

				}
			}
		}
			
		$tpl->setContent($customTpl->get().$list);
		
	}
// --------------------------------------------------------------------------------------	
	
//
// save respond
//
	function saveResponse(){
		global $lng, $ilCtrl;
		 
		include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModResponds.php");
		$this->EtherpadLiteModRespond = new ilEtherpadLiteModResponds();
		
		include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
		$this->EtherpadLiteUser = new ilEtherpadLiteModUser();
				 
		if($_POST["response"])
		{
			if($this->EtherpadLiteModRespond->setRespond(ilUtil::stripSlashes($_POST["response"])))
			{
				ilUtil::sendFailure("error on setRespond()", true);
			}
			elseif($this->EtherpadLiteModRespond->setAuthor($this->EtherpadLiteUser->getPseudonym()))
			{
				ilUtil::sendFailure("error on setAuthor()", true);
			}
			elseif($this->EtherpadLiteModRespond->setQuestId($_POST["quest_id"]))
			{
				ilUtil::sendFailure("error on setQuestId()", true);
			}
			elseif(!$this->EtherpadLiteModRespond->addRespond())
			{
				ilUtil::sendFailure("error on updateUser()", true);
			}
		}
		
		ilUtil::sendSuccess("Antwort gesendet!", true);
		$ilCtrl->redirect($this, "requestForHelp");
	}
	
	
//
//	show profile
//
	/**
	 * show profile
	 */
	function showProfile(){
		global $tpl, $ilTabs, $ilUser, $lng, $ilCtrl;
		
		include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
		$this->EtherpadLiteUser = new ilEtherpadLiteModUser();
		
		$ilTabs->activateTab("showProfile");
		$profile = new ilTemplate("tpl.profile.html", true, true, "Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod");
		
		/* 
		 * pseudonym
		 */
		$profile->setVariable("NAMEFORMLINK",$ilCtrl->getLinkTarget($this, "savePseudonym"));
		$profile->setVariable("VALUE",$this->EtherpadLiteUser->getPseudonym());
		
		/* 
		 * agreements
		 */
		$missingPolicies = $this->EtherpadLiteUser->agreementsCompletely(array("PrivacyPolicy", "Rules", "IPropPolicy"), $this->object->getEtherpadLiteID());
		$policiesContent = $this->policiesContent();
		foreach($policiesContent as $type => $data)
		{
			$profile->setVariable($type."DISPLAY","none");
				
			if(!in_array($type, $missingPolicies))
			{
				$profile->setVariable(
						$type."MODAL",
						$this->buildModal(
								$type."MODAL",
								$data['heading'],
								$data['content'],
								$data['pdf']
						)->getHTML()
				);
				$profile->setVariable($type."DISPLAY","block");
				$profile->setVariable($type."DATETIME",$this->EtherpadLiteUser->getPolicyAgreement($type, $this->object->getEtherpadLiteID())->getConsentedAt());
				if($type == "IPropPolicy")
				{
					$attribution = ($this->EtherpadLiteUser->getPolicyAgreement($type, $this->object->getEtherpadLiteID())->getAttribution()) ?
						"Namensnennung bei Veröffentlichung zugestimmt" : "Namensnennung bei Veröffentlichung verweigert";
					$profile->setVariable("ATTRIBUTION",$attribution);
				}
			}
		}
		
		$tpl->setContent($profile->get());
	}
	
//
// Show content
//

    /**
     * Show content
     */
    function showContent()
    {
        global $tpl, $ilTabs, $ilUser, $lng, $ilCtrl;
        
        try
        {
            $this->object->init();
            $ilTabs->activateTab("content");
            $tpl->addCss("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/templates/css/etherpad.css");
            $tpl->addJavaScript("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/js/ilEtherpadLite.js");
            			            
            // build javascript required to load the pad
            $pad = new ilTemplate("tpl.pad.html", true, true, "Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod");
            $pad->setVariable("POLICIESDIV","none");
            $pad->setVariable("NAMEDIV","none");
            
            // Show Elements depending on settings in the administration of the plugin
            include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModConfig.php");
            $this->adminSettings = new ilEtherpadLiteModConfig();
            
            include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModUser.php");
            $this->EtherpadLiteUser = new ilEtherpadLiteModUser();
            
            // admins and tutors: voller name als nickname
            global $ilAccess;
            if ($ilAccess->checkAccess("write", "", $this->object->getRefId()) && !$this->EtherpadLiteUser->getPseudonym())
            {
            	$this->EtherpadLiteUser->setPseudonym($ilUser->getFullname());
            	$this->EtherpadLiteUser->updateUser();
            }
            
            
            $missingPolicies = $this->EtherpadLiteUser->agreementsCompletely(array("PrivacyPolicy", "Rules", "IPropPolicy"), $this->object->getEtherpadLiteID());
            
            // writeable pad
            $padID = $this->object->getEtherpadLiteID();
            
			if($this->object->getReadOnly()) 
			{ 
				$padID = $this->object->getReadOnlyID(); 
				ilUtil::sendInfo($this->txt("read_only_notice"), true);
			} 
			elseif(!$this->EtherpadLiteUser->getPseudonym())
			{	
				$padID = $this->object->getReadOnlyID();
				$pad->setVariable("NAMEDIV","block");
				$pad->setVariable("NAMEFORMLINK",$ilCtrl->getLinkTarget($this, "savePseudonym"));
				ilUtil::sendFailure($this->txt("read_only_notice"));
			}
			elseif(!empty($missingPolicies))
			{
				$padID = $this->object->getReadOnlyID();
			
				$pad->setVariable("POLICIESDIV","block");
				$pad->setVariable("CONSENTLINK",$ilCtrl->getLinkTarget($this, "agreePolicy"));
			
				// Modals
				$policiesContent = $this->policiesContent();
				foreach($policiesContent as $type => $data)
				{
					$pad->setVariable($type."DISPLAY","none");
					$pad->setVariable($type."DISABLED","disabled");
					$pad->setVariable($type."TYPE","hidden");
					
					if(in_array($type, $missingPolicies))
					{
						$pad->setVariable(
								$type."MODAL",
								$this->buildModal(
										$type."MODAL",
										$data['heading'],
										$data['content'],
										$data['pdf']
								)->getHTML()
						);
						$pad->setVariable($type."DISPLAY","block");
						$pad->setVariable($type."DISABLED","");
						$pad->setVariable($type."TYPE","checkbox");
					}
				}
					
				// 	ilUtil::sendFailure("Es liegen noch keine Einwilligung in die datenschutz- und urheberrechtlichen Erklärungen vor!", true);
				ilUtil::sendFailure($this->txt("read_only_notice"));
			}
				
			
			


			
            $pad->setVariable("ENTER_FULLSCREEN",$this->txt("enter_fullscreen"));
            $pad->setVariable("LEAVE_FULLSCREEN",$this->txt("leave_fullscreen"));
            $pad->setVariable("PROTOCOL",($this->adminSettings->getValue("https") ? "https" : "http"));
            $pad->setVariable("HOST",($this->adminSettings->getValue("host")));
            $pad->setVariable("PORT",($this->adminSettings->getValue("port")));
            $pad->setVariable("PATH",($this->adminSettings->getValue("path")));
            $pad->setVariable("ETHERPADLITE_ID",$padID);
                       
            // $pad->setVariable("USER_NAME",(!$this->adminSettings->getValue("author_identification_conf") ? rawurlencode($ilUser->getFullname()) : $this->constructAuthorIdentification($this->object->getAuthorIdentification())));
            $pad->setVariable("USER_NAME",rawurlencode(($this->EtherpadLiteUser->getPseudonym()) ? $this->EtherpadLiteUser->getPseudonym() :  $this->txt("unknown_identity")));
            
            // $pad->setVariable("SHOW_CHAT",($this->object->getShowChat() ? "true" : "false"));
            $pad->setVariable("SHOW_CHAT",($this->object->getShowChat() &&  !$this->object->getShowComment()? "true" : "false"));
            
            $pad->setVariable("SHOW_CONTROLS",($this->object->getShowControls() ? "true" : "false"));
            $pad->setVariable("SHOW_LINE_NUMBERS",($this->object->getLineNumbers() ? "true" : "false"));
            $pad->setVariable("USE_MONOSPACE_FONT",($this->object->getMonospaceFont()? "true" : "false"));
            $pad->setVariable("NO_COLORS",($this->object->getShowColors()? "false" : "true"));
            $pad->setVariable("SHOW_STYLE_BLOCK",($this->object->getShowStyle()? "true" : "false"));
            $pad->setVariable("SHOW_LIST_BLOCK",($this->object->getShowList()? "true" : "false"));
            $pad->setVariable("SHOW_REDO_BLOCK",($this->object->getShowRedo()? "true" : "false"));
            $pad->setVariable("SHOW_COLOR_BLOCK",($this->object->getShowColoring()? "true" : "false"));
            $pad->setVariable("SHOW_HEADING_BLOCK",($this->object->getShowHeading()? "true" : "false"));
            $pad->setVariable("SHOW_IMPORT_EXPORT_BLOCK",($this->object->getShowImportExport()? "true" : "false"));
            $pad->setVariable("SHOW_TIMELINE_BLOCK",($this->object->getShowTimeline()? "true" : "false"));
            $pad->setVariable("SHOW_COMMENT_BLOCK",($this->object->getShowComment()? "true" : "false"));
            $pad->setVariable("LANGUAGE",$lng->getUserLanguage());			
            $pad->setVariable("EPADL_VERSION",($this->adminSettings->getValue("epadl_version")));
            $tpl->setContent($pad->get());

            // Add Permalink
            include_once("./Services/PermanentLink/classes/class.ilPermanentLinkGUI.php");
            $permalink = new ilPermanentLinkGUI('xct', $this->object->getRefId());
            $this->tpl->setVariable('PRMLINK', $permalink->getHTML());
        } catch (Exception $e)
        {
            $ilTabs->activateTab("content");
            $tpl->setContent($this->txt("load_error")." ".$e->getMessage());
        }
    }

    
    //
    // MODAL
    //
    private function buildModal($tplvar, $heading, $content, $pdf)
    {
    	$link = "<p align='right'><a target='_blank' href='".$pdf."'>als PDF</a></p>";
    	include_once("./Services/UIComponent/Modal/classes/class.ilModalGUI.php");
    	$modal = ilModalGUI::getInstance();
    	$modal->setHeading($heading);
    	$modal->setId("il".$tplvar);
    	$modal->setBody($content.$link);
    	$modal->setType(ilModalGUI::TYPE_LARGE);
    	return $modal;
    }
    
    private function policiesContent()
    {
    	// Show texts depending on settings in the administration of the plugin
    	include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModConfig.php");
    	$this->adminSettings = new ilEtherpadLiteModConfig();
    	
    	$rootdir = "./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod";

    	return array(
    			"PrivacyPolicy" => array(
    					"heading" => "Datenschutz und Privatsphäre",
    					"content" => file_get_contents($rootdir.$this->adminSettings->getValue("policy_paths_privacy_html")),
    					"hash" => hash('sha1', file_get_contents($rootdir.$this->adminSettings->getValue("policy_paths_privacy_html"))),
    					"pdf" => $rootdir.$this->adminSettings->getValue("policy_paths_privacy_pdf")
    			),
    			"IPropPolicy" => array(
    					"heading" => "Urheberrecht",
    					"content" => file_get_contents($rootdir.$this->adminSettings->getValue("policy_paths_iprop_html")),
    					"hash" => hash('sha1',file_get_contents($rootdir.$this->adminSettings->getValue("policy_paths_iprop_html"))),
    					"pdf" => $rootdir.$this->adminSettings->getValue("policy_paths_iprop_pdf")
    			),
    			"Rules" => array(
    					"heading" => "Nutzungsbedingungen",
    					"content" => file_get_contents($rootdir.$this->adminSettings->getValue("policy_paths_rules_html")),
    					"hash" => hash('sha1',file_get_contents($rootdir.$this->adminSettings->getValue("policy_paths_rules_html"))),
    					"pdf" => $rootdir.$this->adminSettings->getValue("policy_paths_rules_pdf")
    			)
    	);
    }
    
    
	/*
    private function constructAuthorIdentification($type)
    {
    	global $ilUser;
    	switch (true)
    	{
    		case stripos($type,'UDF') !== false:
    			$field_id = substr($type, strpos($type, ":")+1);
    			return $this->getUDFValue($field_id) ? rawurlencode($this->getUDFValue($field_id)) : $this->txt("unknown_identity"); break;			
    		case $type === 'username':
    			return rawurlencode($ilUser->getPublicName()); break;
    		case $type === 'fullname':
    		default:
    			return rawurlencode($ilUser->getFullname());
    	}
    }    
    
    
    private function getUDFValue($field_id)
    {
    	global $ilUser;
    	$user_defined_data = $ilUser->getUserDefinedData();
    	return $user_defined_data['f_'.$field_id] ? $user_defined_data['f_'.$field_id] : false;
    }
    */
    

  /*
    public function initCreateForm($a_new_type)
    {
    	$form = parent::initCreateForm($a_new_type);

    	include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModConfig.php");
    	$this->adminSettings = new ilEtherpadLiteModConfig();
    	if($this->adminSettings->getValue("author_identification_conf")) 
    	{
    		$av = new ilCustomInputGUI("", "");
    		$av->setHtml($this->txt("info_author_identification") . " " . $this->txt("info_author_identification_selectable"));
    		$form->addItem($av);
    	}

    	return $form;
    }
   */

}
?>
