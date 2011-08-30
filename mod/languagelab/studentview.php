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

    if (!defined('MOODLE_INTERNAL')){
        error("This file cannot be loaded directly");
    }
    
	//We need to determine if activity is available for the times chosen by teacher
	$now =time();
	$available = $languagelab->timeavailable < $now && ($now < $languagelab->timedue || !$languagelab->timedue);
	

	
	/* ********************I am commenting this out because this will all be taken cared in the XML param file**************
	//get previous recordings. These will be displayed so that the students can listen to their previous recordings
	$LanguageLabSubmissions = get_records_select('LanguageLabSubmissions','userid='.$USER->id.' 
								AND languagelab='.$languagelab->id,
								'timecreated DESC');
	//number of records submitted is important for when teacher selects only one recording per student. Used in if statement later
	$number_of_submissions = count_records('LanguageLabSubmissions','userid',$USER->id,
								'languagelab',$languagelab->id);
	//if only one record is available, must cgather information
	if ($number_of_submissions == 1) {
	$languagelab_submitted = get_record('LanguageLabSubmissions','userid',$USER->id,
								'languagelab',$languagelab->id);
	}
	
	$languagelab_feedback = get_record('LanguageLabStudentEvaluation','userid',$USER->id,
								'languagelab',$languagelab->id);
	//Path to be used for the new recording.
	$path = $languagelab->id.'_'.$USER->id.'_'.time();
	*******************************End ********************************************************** */
	
	//Activity description Still needed to be displayed on page.

	$description = format_text($languagelab->description,FORMAT_MOODLE);
	
?>
<!-- Student content layout-->
<script src="<?php echo $CFG->wwroot?>/mod/languagelab/rounded.js"></script>
<div id="languagelab_studentview_wrapper" class="clearfix">

	<div id="languagelab_description" >
	
	<b><?php print_string('description','languagelab') ?></b><br><br>
	
	<?php echo $description?>
	
	</div>
	
	<div id="languagelab_recorderwrapper" class="rounded">
		
		
	
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="400" height="650" id="SimpleRecorderGB2" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="false" />
	<param name="wmode" value="transparent"> 
	<param name="movie" value="LanguageLabCT.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="xmlAddress=student.param.xml.php?id=<?php echo $id;?>"/>	<embed src="LanguageLabCT.swf" wmode="transparent" flashvars="xmlAddress=student.param.xml.php?id=<?php echo $id;?>" quality="high" bgcolor="#ffffff" width="400" height="650" name="StudentConsole" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
	</object>

</div>
 
<?php 	if (!$available)  {
	
		//Enter the proper date/time or a text message if no date/time
		if (empty($languagelab->timedue)) {
				$timedue = get_string('no_due_date','languagelab');
		} else {
				$timedue = userdate($languagelab->timedue);
		}
		
		if (empty($languagelab->timeavailable)) {
				$timeavailable = get_string('no_available_date','languagelab');
		} else {
				$timeavailable = userdate($languagelab->timeavailable);
		}
		
		//Activity not availabe
		print_box(get_string('not_available','languagelab').'<p>'.get_string('availabledate','languagelab').$timeavailable.'<br>'.get_string('duedate','languagelab').$timedue,'languagelab_submit', 'languagelab_submit');
		
	}
echo '</div> <!-- End of studentview_wrapper -->';
?>
<script type="text/javascript">
Rounded('rounded',10,10, 10, 10);
</script>