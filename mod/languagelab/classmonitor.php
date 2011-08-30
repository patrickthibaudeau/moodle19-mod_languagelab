<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

global $DB, $CFG;

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID

if ($id) {
        if (! $cm = get_record("course_modules", "id", $id)) {
            error("Course Module ID was incorrect");
        }
        

        if (! $course = get_record("course", "id", $cm->course)) {
            error("Course is misconfigured");
        }

        if (! $languagelab = get_record("languagelab", "id", $cm->instance)) {
            error("Course module is incorrect");
        }

    } else {
        if (! $languagelab = get_record("languagelab", "id", $a)) {
            error("Course module is incorrect");
        }
        if (! $course = get_record("course", "id", $languagelab->course)) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("languagelab", $languagelab->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    }

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

require_login($course, true, $cm); //Needed to gather proper course language used

$filename = "$CFG->wwwroot/mod/languagelab/CMparamxml.php?id=$id";

?>
<style type="text/css" media="screen">
		html, body { height:100%; background-color: #ffffff;}
		body { margin:0; padding:0; overflow:hidden; }
		#ClassroomMonitor { width:100%; height:100%; }
		</style>
		<div id="flashContent" style="text-align: center;">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="700" height="550" id="ClassroomMonitor" align="middle">
				<param name="movie" value="ClassroomMonitor.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
                                <param name="allowFullScreen" value="true" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
                                <param name="FlashVars" value="param=<?php echo $filename?>" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="ClassroomMonitor.swf" width="700" height="550">
					<param name="movie" value="ClassroomMonitor.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
                                        <param name="allowFullScreen" value="true" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
                                        <param name="FlashVars" value="param=<?php echo $filename?>"  />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>

<?php
/// Finish the page

?>