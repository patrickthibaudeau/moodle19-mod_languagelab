<?php
//***********************************************************
//**               LANGUAGE LAB MODULE 1                   **
//***********************************************************
//**@package languagelab                                   **
//**@Institution: Campus Saint-Jean, University of Alberta **
//**@authors : Patrick Thibaudeau, Guillaume Bourbonnière  **
//**@version $Id: version.php,v 1.0 2009/05/25 7:33:00    **
//**@Moodle integration: Patrick Thibaudeau                **
//**@Flash programming: Guillaume Bourbonnière             **
//**@CSS Developement: Brian Neeland                       **
//***********************************************************
//***********************************************************
$settings->add(new admin_setting_configtext('languagelab_red5server', get_string('red5server', 'languagelab'),
                   get_string('red5server', 'languagelab'), get_string('red5config','languagelab'), PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_prefix', get_string('prefix', 'languagelab'),
                   get_string('prefixhelp', 'languagelab'), 'mdl', PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_xssAddress', get_string('xssAddress', 'languagelab'),
                   get_string('xssAddresshelp', 'languagelab'), get_string('xssAddress_name','languagelab'), PARAM_RAW));
$settings->add(new admin_setting_configtext('languagelab_xssPort', get_string('xssPort', 'languagelab'),
                   get_string('xssPorthelp', 'languagelab'), '2468', PARAM_INT));
$settings->add(new admin_setting_configtext('languagelab_max_users', get_string('maxusers', 'languagelab'),
                   get_string('maxusershelp', 'languagelab'), '25', PARAM_INT));
$settings->add(new admin_setting_configcheckbox('languagelab_stealthMode', get_string('stealthmode', 'languagelab'),
                   get_string('stealthmodehelp', 'languagelab'), '0', 1,0));
//ffmpeg for file conversion
$settings->add(new admin_setting_configexecutable('ffmpeg', get_string('ffmpeg', 'languagelab'),
                   get_string('ffmpeghelp','languagelab'), ''));

?>