<?php

 if (!defined('MOODLE_INTERNAL')){
        error("This file cannot be loaded directly");
    }

	$description = format_text($languagelab->description,FORMAT_MOODLE);
?>
<div id="languagelab_studentview_wrapper" class="clearfix">	
	<div id="languagelab_description" >
	
	<b><?php print_string('description','languagelab') ?></b><br><br>
	
	<?php echo $description?>
	
	</div>	
	<div id="languagelab_recorderwrapper" class="rounded">
		
		<?php print_string('recorderdescription','languagelab')?>&nbsp;&nbsp;&nbsp;<a href="<?php echo $CFG->wwwroot.'/mod/languagelab/lang/'.current_language().'/help/languagelab/help.html'?>" target="_blank"><img src="<?php echo $CFG->wwwroot.'/mod/languagelab/pix/help.gif'?>" border="0"></a>
	
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="800" height="650" id="TeacherConsole" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="true" />
	<param name="wmode" value="transparent"> 
	<param name="movie" value="LanguageLabCT.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="xmlAddress=teacher.param.xml.php?id=<?php echo $id;?>"/>	<embed src="LanguageLabCT.swf" flashvars="xmlAddress=teacher.param.xml.php?id=<?php echo $id;?>" quality="high" bgcolor="#ffffff" width="800" height="650" name="TeacherConsole" align="middle" wmode="transparent" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
	</object>
                <br><a href="classmonitor.php?id=<?php echo $id?>" target='_blank'><?php echo get_string('classmonitor','languagelab');?></a>
        

</div>	
</div>