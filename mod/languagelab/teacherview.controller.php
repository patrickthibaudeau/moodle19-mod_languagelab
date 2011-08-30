
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

require_once ("../../config.php");
require_once("lib.php");

$action = $_POST["what"];
$languagelab = $_POST["languagelab"];
$path = $_POST["path"];
$label = $_POST["label"];
$message = $_POST["message"];
$parentnode = $_POST["parentnode"];
$studentid = $_POST["studentid"];
$teacherid = $_POST["teacherid"];
$grade = $_POST["grade"];
$correctionnotes = $_POST["correctionnotes"];
$submissionid = $_POST["submissionid"];
$LanguageLabStudentEvaluation_id = $_POST["gradeid"];

echo "action=".$action."<br>";
echo "languagelab=".$languagelab."<br>";
echo "path=".$path."<br>";
echo "label=".$label."<br>";
echo "message=".$message."<br>";
echo "parentnode=".$parentnode."<br>";
echo "studentid=".$studentid."<br>";
echo "teacherid=".$teacherid."<br>";
echo "grade=".$grade."<br>";
echo "submissionid=".$submissionid."<br>";

//**************Object need to pass grades*******************
$languagelab_obj = get_record("languagelab", "id", $languagelab);
//***********************************************************

//**************Create objects with user info for sending email*******************
//Teacher first
$teacher_info = get_record("user", "id", $teacherid);
//Now student
$student_info = get_record("user", "id", $studentid);
//***********************************************************
function update_grade($gradeid, $grade, $notes, $languagelab, $studentid, $teacherid) {
        global $CFG;
        //this will be used to create grade record in languagelab_student_eval
        //Only create one record for the grade. Multiple grade records would break the system.
        
	if ($gradeid == 0) { //Will only create the record once
                
		$save_grade = array('languagelab' => $languagelab->id, 'userid' => $studentid);
		$gradeid = insert_record('languagelab_student_eval',$save_grade);
	} 

        
            $update_grade = new object();{
            $update_grade->id = $gradeid;
            $update_grade->grade = $grade;
            $update_grade->correctionnotes = $notes;
            $update_grade->teacher = $teacherid;
            $update_grade->timemarked = time();
            $update_grade->timemodified = time();

	}

            if (!update_record('languagelab_student_eval',$update_grade)) {
                    print_simple_box (get_string('evaluation_failed_save','languagelab'),'center','70%');
                    } else {

                    print_simple_box (get_string('evaluation_saved','languagelab'),'center','70%');
                    }

        
// update grade item definition
    languagelab_update_grades($languagelab);

	return true;

};
if ($action == 'insert_record') {

    //******************Enter reply information into languagelab_submission table********************************
	//The information needed is languagelab,teacherid = userid, parentnode,label,message. Grade information will be submitted
	//in the evaluation table after this first step.
	
	$save_recording = new object(); {
	$save_recording->languagelab = $languagelab;
	$save_recording->userid = $teacherid;
	$save_recording->path = $path;
	$save_recording->label = $label;
	$save_recording->message = $message;
	$save_recording->parentnode = $parentnode;
	$save_recording->timecreated = time();
	$save_recording->timemodified = time();
	}
	
	if (!insert_record('languagelab_submissions',$save_recording)) {
		print_simple_box (get_string('evaluation_failed_save','languagelab'),'center','70%');
		} else {
		print_simple_box (get_string('evaluation_saved','languagelab'),'center','70%');
 		}
        //If grades are being used
        if ($languagelab_obj->use_grade_book == true) {
            update_grade($LanguageLabStudentEvaluation_id, $grade, $correctionnotes, $languagelab_obj, $studentid, $teacherid);
        }
    //********Send email notification to student**************
	$subject =$languagelab_obj->name;
	$body = get_string('emailgreeting','languagelab').' '.fullname($student_info)."\n";
	$body .= get_string('emailbodynewreply','languagelab')."\n";
	$body .= get_string('emailthankyou','languagelab')."\n";
	$body .= fullname($teacher_info)."\n";
	echo $studentid."<br>";
	echo $teacherid."<br>";
	echo $subject."<br>";
	echo $body;
	if (email_to_user($student_info,$teacher_info,$subject,$body)){
	echo "email sent";
	};
	//**********************************************************
	
	return false ;
	
}
if ($action == 'update_gradebook') {

    update_grade($LanguageLabStudentEvaluation_id, $grade, $correctionnotes, $languagelab_obj, $studentid, $teacherid);

}
if ($action == 'update_record') {
    
    
    $update_recording = new object(); {
	$update_recording->id = $submissionid;
	$update_recording->label = $label;
	$update_recording->message = $message;
	$update_recording->timemodified = time();

	if (!update_record('languagelab_submissions',$update_recording)) {
		print_simple_box (get_string('evaluation_failed_save','languagelab'),'center','70%');
		} else {

		print_simple_box (get_string('evaluation_saved','languagelab'),'center','70%');;
		}

	}
        //If grades are being used
        if ($languagelab_obj->use_grade_book == true) {
            update_grade($LanguageLabStudentEvaluation_id, $grade, $correctionnotes, $languagelab_obj, $studentid, $teacherid);
        }

	return false;
	
	
}

if ($action == 'delete_record') {
    
	//get parentnode from record being deleted
	$parentnode_id = get_record('languagelab_submissions','id',$submissionid);
		
	delete_records('languagelab_submissions','id',$submissionid);
	delete_records('languagelab_submissions','parentnode',$parentnode_id->path);
	
	//********Send email notification to student**************
	$subject =$languagelab_obj->name;
	$body = get_string('emailgreeting','languagelab').' '.fullname($student_info)."\n";
	$body .= get_string('emailbodydelete','languagelab')."\n";
	$body .= get_string('emailthankyou','languagelab')."\n";
	$body .= fullname($teacher_info)."\n";
	echo $studentid."<br>";
	echo $teacherid."<br>";
	echo $subject."<br>";
	echo $body;
	if (email_to_user($student_info,$teacher_info,$subject,$body)){
	//echo "email sent";
	};
	//**********************************************************
	return false;
	
}
?>