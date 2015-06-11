<#1>
<?php
$fields = array(
	'id' => array(
		'type' => 'integer',
		'length' => 4,
		'notnull' => true
	),
        'is_online' => array(
		'type' => 'integer',
		'length' => 4,
		'notnull' => false
	),
	'epadl_id' => array(
		'type' => 'text',
		'length' => 128,
		'notnull' => false
	)
);

$ilDB->createTable("rep_robj_xct_data", $fields);
$ilDB->addPrimaryKey("rep_robj_xct_data", array("id"));
?>
<#2>
<?php
if(!$ilDB->tableColumnExists("rep_robj_xct_data", "show_controls"))
{
    $query = "ALTER TABLE  `rep_robj_xct_data` ADD  `show_controls` BOOLEAN NOT NULL DEFAULT TRUE";
    $res = $ilDB->query($query);
}

if(!$ilDB->tableColumnExists("rep_robj_xct_data", "show_lines"))
{
    $query = "ALTER TABLE  `rep_robj_xct_data` ADD  `show_lines` BOOLEAN NOT NULL DEFAULT TRUE";
    $res = $ilDB->query($query);
}

if(!$ilDB->tableColumnExists("rep_robj_xct_data", "use_color"))
{
    $query = "ALTER TABLE  `rep_robj_xct_data` ADD  `use_color` BOOLEAN NOT NULL DEFAULT TRUE";
    $res = $ilDB->query($query);
}

if(!$ilDB->tableColumnExists("rep_robj_xct_data", "show_chat"))
{
    $query = "ALTER TABLE  `rep_robj_xct_data` ADD  `show_chat` BOOLEAN NOT NULL DEFAULT TRUE";
    $res = $ilDB->query($query);
}

?>
<#3>
<?php
if($ilDB->tableColumnExists("rep_robj_xct_data", "show_lines"))
{
    $query = "ALTER TABLE `rep_robj_xct_data` CHANGE `show_lines` `line_numbers` TINYINT( 1 ) NOT NULL DEFAULT '1'";
    $res = $ilDB->query($query);
}
if($ilDB->tableColumnExists("rep_robj_xct_data", "use_color"))
{
	$query = "ALTER TABLE `rep_robj_xct_data` CHANGE `use_color` `show_colors` TINYINT( 1 ) NOT NULL DEFAULT '1'";
	$res = $ilDB->query($query);
}
?>
<#4>
<?php
    $fields = array(
    'epkey' => array(
    'type' => 'text',
    'length' => 128,
    'notnull' => true
    ),
    'epvalue' => array(
        'type' => 'clob',
        'notnull' => false
    ),
    );

    $ilDB->createTable("rep_robj_xct_adm_set", $fields);
    $ilDB->addPrimaryKey("rep_robj_xct_adm_set", array("epkey"));
?>
<#5>
<?php
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_chat'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_chat",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','monospace_font'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","monospace_font",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_controls'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_controls",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_style'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_style",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_list'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_list",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_redo'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_redo",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_coloring'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_coloring",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_heading'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_heading",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_import_export'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_import_export",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','show_timeline'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","show_timeline",array("type"=>"boolean"));
    }
    if(!$ilDB->tableColumnExists('rep_robj_xct_data','old_pad'))
	{
        $ilDB->addTableColumn("rep_robj_xct_data","old_pad",array("type"=>"boolean"));
    }
?>
<#6>
<?php
// import old configuration file if it exists
$file = "./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/etherpadlite.ini.php";
if(file_exists($file))
{
	$ini = new ilIniFile($file);
	$ini->read();
	
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'host','".$ini->readVariable("etherpadlite", "host")."' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'host');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'port','".$ini->readVariable("etherpadlite", "port")."' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'port');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'apikey','".$ini->readVariable("etherpadlite", "apikey")."' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'apikey');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'domain','".$ini->readVariable("etherpadlite", "domain")."' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'domain');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'https','".$ini->readVariable("etherpadlite", "https")."' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'https');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'defaulttext','".$ini->readVariable("etherpadlite", "defaulttext")."' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'defaulttext');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'old_group','".$ini->readVariable("etherpadlite", "group")."' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'old_group');";
} else {
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'host','etherpad.ilias.local' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'host');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'port','9001' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'port');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'apikey','See in Apikey.txt' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'apikey');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'domain','.ilias.local' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'domain');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'https',false FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'https');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'defaulttext','Etherpad-Lite für Ilias' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'defaulttext');";
	$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'old_group',NULL FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'old_group');";
}
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_chat',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_chat');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_monospace_font',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_monospace_font');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_line_numbers',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_line_numbers');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_colors',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_colors');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls_default_show_style',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls_default_show_style');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls_default_show_list',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls_default_show_list');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls_default_show_redo',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls_default_show_redo');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls_default_show_heading',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls_default_show_heading');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls_default_show_import_export',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls_default_show_import_export');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls_default_show_timeline',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls_default_show_timeline');";
$sql[] = "INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'default_show_controls_default_show_coloring',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'default_show_controls_default_show_coloring');";

foreach($sql as $s)
{
    $ilDB->manipulate($s);
}
?>
<#7>
<?php
// set all existing pads to old_pad, activate all features
	
	if($ilDB->tableColumnExists('rep_robj_xct_data','old_pad'))
	{
		$sql7[] = "UPDATE `rep_robj_xct_data` set old_pad = 1 where old_pad IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_chat = 1 where show_chat IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set line_numbers = 1 where line_numbers IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set monospace_font = 1 where monospace_font IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_colors = 1 where show_colors IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_controls = 1 where show_controls IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_style = 1 where show_style IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_list = 1 where show_list IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_redo = 1 where show_redo IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_coloring = 1 where show_coloring IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_heading = 1 where show_heading IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_import_export = 1 where show_import_export IS NULL";
		$sql7[] = "UPDATE `rep_robj_xct_data` set show_timeline = 1 where show_timeline IS NULL";
		
		foreach($sql7 as $s7)
		{
			$res = $ilDB->query($s7);
		}
	}
?>
<#8>

<#9>
<?php
	// tables which need to be updated
	$update_tables = array(
						'show_controls',
						'line_numbers',
						'show_colors',
						'show_chat');
						
	foreach($update_tables as $table)
	{
		$res = $ilDB->query('ALTER TABLE `rep_robj_xct_data` CHANGE `'.$table.'` `'.$table.'` TINYINT( 1 ) NULL DEFAULT NULL');		
	}
	
?>

<#10>
<?php
	$res = $ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'path',NULL FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'path');");
?>

<#11>
<?php
	$res = $ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'https_validate_curl',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'https_validate_curl');");
?>

<#12>
<?php
    if(!$ilDB->tableColumnExists("rep_robj_xct_data", "read_only_id"))
    {
	$query = "ALTER TABLE  `rep_robj_xct_data` ADD  `read_only_id` VARCHAR( 128 ) NOT NULL";
	$res = $ilDB->query($query);
    }

    if(!$ilDB->tableColumnExists("rep_robj_xct_data", "read_only"))
    {
	$query = "ALTER TABLE  `rep_robj_xct_data` ADD  `read_only` TINYINT ( 1 )  NOT NULL";
	$res = $ilDB->query($query);
    }
?>

<#13>
<?php
    include_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/classes/class.ilEtherpadLiteModConfig.php");
    require_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/libs/etherpad-lite-client/etherpad-lite-client.php");
    
    $adminSettings = new ilEtherpadLiteModConfig();
		
    try
    {
	
	$query = "SELECT id, epadl_id FROM rep_robj_xct_data WHERE read_only_id = ''";
	$ids = $ilDB->query($query)->fetchAll();
	
	$epCon = new EtherpadLiteClient($adminSettings->getValue("apikey"), ($adminSettings->getValue("https") ? "https" : "http"). '://' . 
			$adminSettings->getValue("host") . ':' . $adminSettings->getValue("port") . $adminSettings->getValue("path") . '/api',
            		$adminSettings->getValue("https_validate_curl"));

        foreach ($ids as $id) {
    	    $roid_a = $epCon->getReadOnlyID($id["1"]);
    	    $roid = $roid_a->readOnlyID;
    	    $rid = $id["0"];
    
    	    $query = "UPDATE rep_robj_xct_data SET read_only_id = '$roid' WHERE id = '$rid'";
    	    $res = $ilDB->query($query);
        }
            		
    }
    catch (Exception $e)
    {
        include_once("./Services/UICore/exceptions/class.ilCtrlException.php");
        throw new ilCtrlException($e->getMessage());
    }
    
<#14>
<?php
	$res = $ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'allow_read_only',true FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'allow_read_only');");
?>

<#15>
<?php
	$res = $ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'epadl_version',130 FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'epadl_version');");	
?>

<#16>
<?php
	$res = $ilDB->query("UPDATE `lng_data` set `identifier` = 'rep_robj_xct_default_show_controls_default_show_import_expo' where `identifier` = 'rep_robj_xct_default_show_controls_default_show_import_expo' and `lang_key` = 'en'");	
	$res = $ilDB->query("UPDATE `lng_data` set `identifier` = 'rep_robj_xct_default_show_controls_default_show_imp_expo' where `identifier` = 'rep_robj_xct_default_show_controls_default_show_import_expo' and `lang_key` = 'de'");	
?>


<#17>
	<?php 
	/**
	 * c.t. mod
	 */

	/*
	 * user profile
	 *
	 * 	CREATE TABLE IF NOT EXISTS `rep_robj_xct_user` (
	 *	 `username` VARCHAR(30) NOT NULL,
	 *	 `pseudonym` VARCHAR(128) NULL,
	 *	 PRIMARY KEY (`username`),
	 *	 UNIQUE INDEX `username_UNIQUE` (`username` ASC));
	 */
	$table_name = 'rep_robj_xct_user';
	$table_fields = array(
			'username' => array(
					'type' => 'text',
					'length' => 30,
					'notnull' => false
			),
			'pseudonym' => array(
					'type' => 'text',
					'length' => 128
			)
	);
	if(!$ilDB->tableExists($table_name))
	{
		$ilDB->createTable($table_name, $table_fields);
		$ilDB->addPrimaryKey($table_name, array("username"));
	}
	
	
	/*
	 * policy agreements 
	 * 
	 * 	CREATE TABLE IF NOT EXISTS `rep_robj_xct_policy_agreement` (
	 *	 `username` VARCHAR(30) NOT NULL,
	 *	 `policy_type` VARCHAR(30) NOT NULL,
	 *	 `datetime` DATETIME NOT NULL,
	 *	 `hash` VARCHAR(100) NOT NULL,
	 *	 PRIMARY KEY (`username`, `policy_type`),
	 * 	INDEX `fk_policy_agreement_user_idx` (`username` ASC),
	 *  UNIQUE INDEX `username_UNIQUE` (`username` ASC));
	 * 
	 */
	$table_name = 'rep_robj_xct_pol_agt';
	$table_fields = array(
			'username' => array(
					'type' => 'text',
					'length' => 30,
					'notnull' => false
			),
			'policy_type' => array(
					'type' => 'text',
					'length' => 30,
					'notnull' => false
			),
			'hash' => array(
					'type' => 'text',
					'length' => 100,
					'notnull' => false
			),
			'consented_at' => array(
					'type' => 'timestamp',
					'default' => 'CURRENT_TIMESTAMP',
					'notnull' => true
			)
	);

	if(!$ilDB->tableExists($table_name))
	{
		$ilDB->createTable($table_name, $table_fields);
		$ilDB->addPrimaryKey($table_name, array("username", "policy_type"));
	}
	
	
	/*
	 * intellectual property - policy agreement
	 *  
	 * 	CREATE TABLE IF NOT EXISTS `rep_robj_xct_ip_agreeement` (
	 *	 `username` VARCHAR(30) NOT NULL,
	 *	 `pad_id` INT NOT NULL,
	 *	 `datetime` DATETIME NOT NULL,
	 *	 `hash` VARCHAR(100) NOT NULL,
	 *	 `attribution` TINYINT (1) NOT NULL,
	 *	 PRIMARY KEY (`username`, `pad_id`));
	 * 
	 */
	$table_name = 'rep_robj_xct_ip_agt';
	$table_fields = array(
			'username' => array(
					'type' => 'text',
					'length' => 30,
					'notnull' => false
			),
			'pad_id' => array(
					'type' => 'text',
					'length' => 128,
					'notnull' => false
			),
			'hash' => array(
					'type' => 'text',
					'length' => 100,
					'notnull' => false
			),
			'consented_at' => array(
					'type' => 'timestamp',
					'default' => 'CURRENT_TIMESTAMP',
					'notnull' => true
			),
			'attribution' => array(
					'type' => 'integer',
					'length' => 1,
					'notnull' => false
			),
	);

	if(!$ilDB->tableExists($table_name))
	{
		$ilDB->createTable($table_name, $table_fields);
		$ilDB->addPrimaryKey($table_name, array("username", "pad_id"));
	}
	
	
	/*
	 *   admin set defaults - consent text paths
	 */
	$ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'policy_paths_iprop_html','/templates/policies/iprop.html' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'policy_paths_iprop_html');");
	$ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'policy_paths_iprop_pdf','/templates/policies/iprop.pdf' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'policy_paths_iprop_pdf');");
	
	$ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'policy_paths_privacy_html','/templates/policies/privacy.html' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'policy_paths_privacy_html');");
	$ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'policy_paths_privacy_pdf','/templates/policies/privacy.pdf' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'policy_paths_privacy_pdf');");
	
	$ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'policy_paths_rules_html','/templates/policies/rules.html' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'policy_paths_rules_html');");
	$ilDB->query("INSERT INTO `rep_robj_xct_adm_set` (epkey, epvalue) SELECT 'policy_paths_rules_pdf','/templates/policies/rules.pdf' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM rep_robj_xct_adm_set WHERE epkey = 'policy_paths_rules_pdf');");

	
	
	/*
	 * "eagle eye" feature
	 */
	if(!$ilDB->tableColumnExists('rep_robj_xct_data','xct_eagle_eye_mail'))
	{
		$ilDB->addTableColumn("rep_robj_xct_data", "xct_eagle_eye_mail", array(
				'type' => 'text',
				'length' => 250,
				'notnull' => true,
				'default' => "owner"
			)
		);
	}

	if(!$ilDB->tableColumnExists('rep_robj_xct_data','xct_av_questions'))
	{
		$ilDB->addTableColumn("rep_robj_xct_data", "xct_av_questions", array(
				'type' => 'integer',
				'length' => 1,
				'notnull' => true,
				'default' => 2
			)
		);
	}
	
	
	/*
	 * 	CREATE TABLE IF NOT EXISTS `rep_robj_xct_quests` (
	 *	`quest_id` int(4) NOT NULL auto_increment,
	 *	`pad_id` int(4) NOT NULL,
	 *	`username` varchar(128) NOT NULL,
	 *	`quest` varchar(500) NOT NULL,
	 *	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	 *	PRIMARY KEY  (`quest_id`, `pad_id`));
	 *
	 */
	
	$table_name = 'rep_robj_xct_quests'; 
	$table_fields = array(
			'pad_id' => array(
				'type' => 'text',
				'length' => 128,
				'notnull' => true
			),
			'quest_id' => array(
				'type' => 'integer',
				'length' => 4,
				'notnull' => true
			),
			'username' => array(
				'type' => 'text',
				'length' => 30,
				'notnull' => true
			),
			'quest' => array(
				'type' => 'text',
				'length' => 500,
				'notnull' => true
			),
			'created_at' => array(
				'type' => 'timestamp',
				'default' => 'CURRENT_TIMESTAMP',
				'notnull' => true
			)
	);

	if(!$ilDB->tableExists($table_name))
	{
		$ilDB->createTable($table_name, $table_fields);
		$ilDB->addPrimaryKey($table_name, array("pad_id", "quest_id"));
		$ilDB->createSequence($table_name);
	}

?>