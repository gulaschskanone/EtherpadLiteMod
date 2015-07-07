# Etherpad Lite Plugin for ILIAS - University of Passau MOD
This plugin based on jrocho/ILIAS-Etherpad-Lite-Plugin v1.1.2



## Installation ##

### Basically ###
After installing ILIAS and etherpad copy the plugin files to *Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/* in the directory structure of your ILIAS installation. 
Set write permissions on */log/*.

### Customized configuration (files) ###
Copy the files *pad.[js|css].sample* to *node_modules/ep_etherpad-lite/static/custom/pad.[js|css]* within your etherpad-lite (server) folder and set in the *settings.json*

`"minify" : false,`

`"disableIPlogging" : true,`

`"ep_toc": {
	"disable_by_default": false
},`


### Install Dependencies / Extensions ###
`npm install ep_comments_page ep_table_of_contents ep_page_view ep_spellcheck`

### Activate Plugin in ILIAS ###
... on *Administration >> Plugins*. 
Set global permissions
* Course Member: *Visible* and *Read*
* Course Tutor: additional *Edit*
* Group Administrator: all permisions
