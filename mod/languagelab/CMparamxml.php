<?php
session_cache_limiter('nocache'); //Needed for XML to load with IE
require_once("../../config.php");
require_once("lib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // languagelab ID

global $CFG, $USER;

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

//look for all languagelab activities within course
$coursemodules = get_coursemodules_in_course('languagelab', $course->id);

$teacher = get_record("user", "id", $USER->id);
$teacherid = $USER->id;
$teachername= fullname($teacher);


if ($teacher->picture == 1) {
    $teacherpicture = $CFG->wwwroot."/user/pix.php/".$teacher->id."/f2.jpg";
} else {
    $teacherpicture =  $CFG->wwwroot."/pix/u/f2.png";
}
//**************************Student information*******************************************

$students = get_course_students($course->id);

//****************************End Student information

$writer = new XMLWriter();
// Output directly to the user

$writer->openURI('php://output');
$writer->startDocument('1.0','UTF-8');

$writer->setIndent(4);

$writer->startElement('params');
    $writer->startElement('config');
        $writer->startElement('env');
            $writer->writeAttribute('streamingServer',$CFG->languagelab_red5server);
            $writer->writeAttribute('teacherStream', $CFG->languagelab_prefix.'_'.$USER->id.'_classmon_'.time());
            $writer->writeAttribute('stealthMode', $CFG->languagelab_stealthMode);
            $writer->writeAttribute('socketServerIP',$CFG->languagelab_xssAddress);
            $writer->writeAttribute('socketServerPort',$CFG->languagelab_xssPort);
            $writer->writeAttribute('userID',$USER->id);
            $writer->writeAttribute('userPortrait', $teacherpicture);
        $writer->endElement(); // env
        $writer->startElement('localization');
        $writer->endElement(); //localization
    $writer->endElement(); //config
    $writer->startElement('courses');
        $writer->startElement('course');
            $writer->writeAttribute('id', $course->id);
            $writer->writeAttribute('name', $course->fullname);
            //foreach should only be used in the block to view all course LL activities
            //foreach ($coursemodules as $coursemodule){
                $writer->startElement('activity');
                    $writer->writeAttribute('id', $cm->instance);
                    $writer->writeAttribute('name', $languagelab->name);
                    $writer->writeAttribute('group', 0);
                        
                    //get all student recordings for this lab
                    foreach ($students as $student){
                        $writer->startElement('student');
                        $writer->writeAttribute('id', $student->id);
                            //get this students recordings 
                            if ($student_recordings = get_records('languagelab_submissions', 'userid',$student->id,'languagelab',$languagelab->id)) {
                                foreach ($student_recordings as $sr) {
                                    $writer->startElement('recording');
                                    if (isset($sr->path)) {
                                        $writer->writeAttribute('stream', $sr->path);
                                    } else {
                                        $writer->writeAttribute('stream', '');
                                    }
                                    $writer->endElement(); //end recording
                                }
                            }
                        $writer->endElement(); // end student

                    }
                $writer->endElement(); //activity
            //}
        $writer->endElement(); //Course
    $writer->endElement(); //courses
    $writer->startElement('students');
    foreach($students as $student){
        $writer->startElement('student');
            //Get student picture Because search_users function only gives fullname and email
            //We need to get the student record in order to more information on the user.
            $studentinfo = get_record("user","id",$student->id);
            
           if ($studentinfo->picture == 1) {
                $studentpicture = $CFG->wwwroot."/user/pix.php/".$studentinfo->id."/f2.jpg";
           } else {
                $studentpicture =  $CFG->wwwroot."/pix/u/f2.png";
           }
            //Write attributes
            $writer->writeAttribute('id',$student->id);
            $writer->writeAttribute('name',fullname($student));
             $writer->writeAttribute('portrait',$studentpicture);
        $writer->endElement(); //student
    }
    $writer->endElement(); //students
$writer->endElement(); //params
?>

