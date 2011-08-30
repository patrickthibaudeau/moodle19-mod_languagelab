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
?>

<?php

/// (Replace languagelab with the name of your module)
	//require_once ($CFG->dirroot.'/course/moodleform_mod.php');
    require_once("../../config.php");
    require_once("lib.php");
    echo '<link href="'.$CFG->wwwroot.'/mod/languagelab/languagelab.css" rel="stylesheet" type="text/css" />';
	
    //$courseid = required_param('course','id',PARAM_INT);
	$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
        $a  = optional_param('a', 0, PARAM_INT);  // languagelab ID
	$action = optional_param('what', 'view', PARAM_CLEAN); 
	$usehtmleditor = can_use_html_editor();
	

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
	
//echo "id= ".$id."<br> a= ".$a."<br> cm= ".$cm->instance;
    require_login($course->id);
    
    if (groupmode($course, $cm) == SEPARATEGROUPS) {
        $groupid = get_current_group($course->id);
    } else {
        $groupid = 0;
    }

    add_to_log($course->id, "languagelab", "view", "view.php?id=$cm->id", "$languagelab->id");

/// Print the page header

    if ($course->category) {
        $navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
    } else {
        $navigation = '';
    }

    $strlanguagelabs = get_string("modulenameplural", "languagelabs");
    $strlanguagelab  = get_string("modulename", "languagelab");

    print_header("$course->shortname: $languagelab->name", "$course->fullname",
                 "$navigation <a href=index.php?id=$course->id>$strlanguagelabs</a> -> $languagelab->name", 
                  "", "", true, update_module_button($cm->id, $course->id, $strlanguagelab), 
                  navmenu($course, $cm));

/// Print the main part of the page

    if (isstudent($course->id)){
    	
    	include 'studentview.php';

}
	    	

		
	
    else if (isteacheredit($course->id)){
    	
    	include 'teacherview.php';
    }


/// Finish the page
    print_footer($course);

?>
