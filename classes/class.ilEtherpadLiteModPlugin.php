<?php

include_once("./Services/Repository/classes/class.ilRepositoryObjectPlugin.php");
 
/**
* EtherpadLiteMod repository object plugin
*
* @author Jan Rocho <jan@rocho.eu>
* @version $Id$
*
*/
class ilEtherpadLiteModPlugin extends ilRepositoryObjectPlugin
{
	function getPluginName()
	{
		return "EtherpadLiteMod";
	}
}
?>
