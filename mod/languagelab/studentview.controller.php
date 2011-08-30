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
require_once("../../config.php");
require_once("lib.php");
global $CFG;
/* old code
$action = $_POST["what"];
$languagelab = $_POST["languagelab"];
$userid = $_POST["studentid"];
$path = $_POST["path"];
$label = $_POST["label"];
$message = $_POST["message"];
$count = $_POST["count"];
$id = $_POST["submission_id"];
 *
 */
$action = optional_param("what",'',PARAM_TEXT);
$languagelab = optional_param("languagelab",0, PARAM_INT);
$userid = optional_param("userid",0,PARAM_INT); //Student id
$path = optional_param("path",'',PARAM_TEXT);
$label = optional_param("label",'', PARAM_TEXT);
$message = optional_param("message",'',PARAM_TEXT);
$count = optional_param("count",0,PARAM_INT);
$id = optional_param("submission_id",0, PARAM_INT);
$groupid = optional_param("groupid",0, PARAM_INT);

$fp = fopen('error.txt', 'w');
fwrite($fp, "action=".$action."<br>");
fwrite($fp, "languagelab=".$languagelab."<br>");
fwrite($fp, "userid=".$userid."<br>");
fwrite($fp, "path=".$path."<br>");
fwrite($fp, "label=".$label."<br>");
fwrite($fp, "message=".$message."<br>");
fwrite($fp, "count=".$count."<br>");
fwrite($fp, "id=".$id."<br>");


echo "action=".$action."<br>";
echo "languagelab=".$languagelab."<br>";
echo "userid=".$userid."<br>";
echo "path=".$path."<br>";
echo "label=".$label."<br>";
echo "message=".$message."<br>";
echo "count=".$count."<br>";
echo "id=".$id."<br>";


if ($action == 'insert_record') {

	$save_recording = new object(); {
	$save_recording->languagelab = $languagelab;
	$save_recording->userid = $userid;
        $save_recording->groupid = $groupid;
	$save_recording->path = $path;
	$save_recording->label = $label;
	$save_recording->message = $message;
        $save_recording->parentnode = '';
	$save_recording->timecreated = time();
	$save_recording->timemodified = time();
	fwrite($fp,'language lab ='.$languagelab);
	if (!insert_record('languagelab_submissions',$save_recording)) {
		fwrite($fp, 'not saved');
		} else {
		fwrite($fp, 'recording saved');
		//this will be used to create grade record in languagelab_student_eval	
			if ($count == 0) { //Will only create the record once
				$save_grade = new object();
				$save_grade->languagelab = $languagelab;
				$save_grade->userid = $userid;
				insert_record('languagelab_student_eval',$save_grade);
			}
 		}
	}
	
   

	return false ;
	
}

if ($action == 'update_record') {
	
	$update_recording = new object(); {
	$update_recording->id = $id;
	$update_recording->label = $label;
	$update_recording->message = $message;
	$update_recording->timemodified = time();
	
	if (!update_record('languagelab_submissions',$update_recording)) {
		print_simple_box (get_string('recording_failed_save','languagelab'),'center','70%');
		} else {
	
		print_simple_box (get_string('recording_saved','languagelab'),'center','70%');;
		}
	
	}
	
	return false;
	
}
fclose($fp);
?>