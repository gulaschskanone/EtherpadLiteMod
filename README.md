# Etherpad Lite Plugin for ILIAS - University of Passau MOD
This plugin based on jrocho/ILIAS-Etherpad-Lite-Plugin v1.1.2

tested with EPL version 1.5.7


## Installation ##

### Basically ###
After installing ILIAS and etherpad copy the plugin files to *Customizing/global/plugins/Services/Repository/RepositoryObject/EtherpadLiteMod/* in the directory structure of your ILIAS installation. Set write permissions on */log/*.

### Customized configuration (files) ###
Copy the files *pad.[js|css].sample* to *node_modules/ep_etherpad-lite/static/custom/pad.[js|css]* within your etherpad-lite (server) folder and set in *settings.json*

`"minify" : false,`

`"disableIPlogging" : true,`

`"ep_toc": {
	"disable_by_default": false
},`

### Install Dependencies / Extensions ###
`npm install ep_comments_page ep_table_of_contents ep_page_view ep_spellcheck ep_xmlexport ep_brightcolorpicker ep_resizable_bars`

### Activate Plugin in ILIAS ###
... on *Administration >> Plugins* and set permissions accordingly.


## sources / libs ##
* etherpad-lite-client MOD by Jan Rocho <jan.rocho@fh-dortmund.de>
* fpdf by Olivier Plathey <oliver@fpdf.org>
* fpdi by Jan Slabon <info@setasign.com>
* HTML conversion script for fpdf by Clément Lavoillotte <ac.lavoillotte@noos.fr>
* MultiCell with bullet script for fpdf by Patrick Benny
