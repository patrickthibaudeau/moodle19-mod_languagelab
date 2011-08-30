<?php
session_cache_limiter('nocache'); //Needed for XML to load with IE
require_once("../../config.php");
require_once("lib.php");

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // languagelab ID
global $CFG;
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

//************************Get teacher information*****************************************
require_course_login($course); //Needed to gather proper course language used

$teacher = get_record("user", "id", $USER->id);
$teacherid = $USER->id;
$teachername= fullname($teacher);
if ($teacher->picture == 1) {
    $userpictureurl = $CFG->wwwroot."/user/pix.php/".$teacher->id."/f2.jpg";
} else {
    $userpictureurl =  $CFG->wwwroot."/pix/u/f2.png";
}
//***************************End Teacher Information**************************************

//************************Master track url******************************
$mastertrack = "$CFG->wwwroot/file.php/$course->id/languagelab/" .clean_filename($languagelab->name)."/mastertrack/$languagelab->master_track";

//************************End Master track url******************************
//
//**************************Student information*******************************************

$students = search_users($course->id,'','','u.lastname');

//****************************End Student information
//**********XML OUTPUT******************

$writer = new XMLWriter();
// Output directly to the user

$writer->openURI('php://output');
$writer->startDocument('1.0','UTF-8');

$writer->setIndent(4);

$writer->startElement('playerParam');

	$writer->startElement('localization');
		$writer->startElement('XMLLoadFail');
		$writer->writeCData(get_string('XMLLoadFail','languagelab'));
		$writer->endElement();
		$writer->startElement('warningLossOfWork');
		$writer->writeCData(get_string('warningLossOfWork','languagelab'));
		$writer->endElement();
		$writer->startElement('prerequisitesNotMet');
		$writer->writeCData(get_string('prerequisitesNotMet','languagelab'));
		$writer->endElement();
		$writer->startElement('newRecording');
		$writer->writeCData(get_string('newRecording','languagelab'));
		$writer->endElement();
		$writer->startElement('newReply');
		$writer->writeCData(get_string('newReply','languagelab'));
		$writer->endElement();
		$writer->startElement('timesOut');
		$writer->writeCData(get_string('timesOut','languagelab'));
		$writer->endElement();
		$writer->startElement('subject');
		$writer->writeCData(get_string('subject','languagelab'));
		$writer->endElement();
        $writer->startElement('submitBlank');
		$writer->writeCData(get_string('submitBlank','languagelab'));
		$writer->endElement();
        $writer->startElement('submitNew');
		$writer->writeCData(get_string('submitNew','languagelab'));
		$writer->endElement();
        $writer->startElement('submitChanges');
		$writer->writeCData(get_string('submitChanges','languagelab'));
		$writer->endElement();	
		$writer->startElement('message');
		$writer->writeCData(get_string('message','languagelab'));
		$writer->endElement();	
		
		$writer->startElement('btnDiscard');
		$writer->writeCData(get_string('btnDiscard','languagelab'));
		$writer->endElement();

		$writer->startElement('btnCancel');
		$writer->writeCData(get_string('btnCancel','languagelab'));
		$writer->endElement();
		
		$writer->startElement('submitGrade');
		$writer->writeCData(get_string('submitGrade','languagelab'));
		$writer->endElement();
		
		$writer->startElement('agoBefore');
		$writer->writeCData(get_string('agoBefore','languagelab'));
		$writer->endElement();
		
		$writer->startElement('agoAfter');
		$writer->writeCData(get_string('agoAfter','languagelab'));
		$writer->endElement();
		
		$writer->startElement('days');
		$writer->writeCData(get_string('days','languagelab'));
		$writer->endElement();
		
		$writer->startElement('hours');
		$writer->writeCData(get_string('hours','languagelab'));
		$writer->endElement();
		
		$writer->startElement('minutes');
		$writer->writeCData(get_string('minutes','languagelab'));
		$writer->endElement();
		
		$writer->startElement('seconds');
		$writer->writeCData(get_string('seconds','languagelab'));
		$writer->endElement();
		
		$writer->startElement('grading');
		$writer->writeCData(get_string('grading','languagelab'));
		$writer->endElement();
		
		$writer->startElement('grade');
		$writer->writeCData(get_string('grade','languagelab'));
		$writer->endElement();
		
		$writer->startElement('startOver');
		$writer->writeCData(get_string('startOver','languagelab'));
		$writer->endElement();
		
		$writer->startElement('corrNotes');
		$writer->writeCData(get_string('corrNotes','languagelab'));
		$writer->endElement();
 //days of the week
		
        $writer->startElement('monday');
		$writer->writeCData(get_string('monday','languagelab'));
		$writer->endElement();
        $writer->startElement('tuesday');
		$writer->writeCData(get_string('tuesday','languagelab'));
		$writer->endElement();
        $writer->startElement('wednesday');
		$writer->writeCData(get_string('wednesday','languagelab'));
		$writer->endElement();
        $writer->startElement('thursday');
		$writer->writeCData(get_string('thursday','languagelab'));
		$writer->endElement();
        $writer->startElement('friday');
		$writer->writeCData(get_string('friday','languagelab'));
		$writer->endElement();
        $writer->startElement('saturday');
		$writer->writeCData(get_string('saturday','languagelab'));
		$writer->endElement();
        $writer->startElement('sunday');
		$writer->writeCData(get_string('sunday','languagelab'));
		$writer->endElement();
        //months
        $writer->startElement('january');
		$writer->writeCData(get_string('january','languagelab'));
		$writer->endElement();
        $writer->startElement('february');
		$writer->writeCData(get_string('february','languagelab'));
		$writer->endElement();
        $writer->startElement('march');
		$writer->writeCData(get_string('march','languagelab'));
		$writer->endElement();
        $writer->startElement('april');
		$writer->writeCData(get_string('april','languagelab'));
		$writer->endElement();
        $writer->startElement('may');
		$writer->writeCData(get_string('may','languagelab'));
		$writer->endElement();
        $writer->startElement('june');
		$writer->writeCData(get_string('june','languagelab'));
		$writer->endElement();
        $writer->startElement('july');
		$writer->writeCData(get_string('july','languagelab'));
		$writer->endElement();
        $writer->startElement('august');
		$writer->writeCData(get_string('august','languagelab'));
		$writer->endElement();
        $writer->startElement('september');
		$writer->writeCData(get_string('september','languagelab'));
		$writer->endElement();
        $writer->startElement('october');
		$writer->writeCData(get_string('october','languagelab'));
		$writer->endElement();
        $writer->startElement('november');
		$writer->writeCData(get_string('november','languagelab'));
		$writer->endElement();
        $writer->startElement('december');
		$writer->writeCData(get_string('december','languagelab'));
		$writer->endElement();

	$writer->endElement(); //End localization

	$writer->startElement('basicParam');
          $writer->startElement('envVar');
            $writer->writeAttribute('targetServer','rtmp://'.$CFG->languagelab_red5server.'/oflaDemo');
            $writer->writeAttribute('targetPost','./teacherview.controller.php');
            $writer->writeAttribute('languageLabId',$languagelab->id);
            $writer->writeAttribute('courseModuleId',$cm->id);
            $writer->writeAttribute('xssAddress',$CFG->languagelab_xssAddress);
            $writer->writeAttribute('xssPort',$CFG->languagelab_xssPort);
            $writer->writeAttribute('courseName',$course->fullname);
            $writer->writeAttribute('activityName',$languagelab->name);
            $writer->writeAttribute('useGradeBook',$languagelab->use_grade_book);
            $writer->writeAttribute('masterTrack',$mastertrack);
        $writer->endElement(); //end envVar

        $writer->startElement('permissions');
			$writer->writeAttribute('bCanRecordNewStreams','0');
			$writer->writeAttribute('bCanReply','1');
			$writer->writeAttribute('bMultipleRecordings','0');
			$writer->writeAttribute('bAutoSubmit','0');
			$writer->writeAttribute('recording_timelimit','0');
		$writer->endElement(); //end permissions

		$writer->startElement('recordParam');
			$writer->writeAttribute('targetRecording', $CFG->languagelab_prefix.'_'.$languagelab->id.'_'.$USER->id.'_'.time());
			$writer->writeAttribute('author',$teachername);//Teacher
			$writer->writeAttribute('authorId',$teacherid);//Teacher
			$writer->writeAttribute('portrait',$userpictureurl);
                        $writer->writeAttribute('videoMode', $languagelab->video);
		$writer->endElement(); // End recordParam

                $writer->startElement('teacherParam');
                    $writer->writeAttribute('bTeach', '1');
                    $writer->writeAttribute('bEvaluated', $languagelab->use_grade_book);
                $writer->endElement(); // End teacherParam

	$writer->endElement(); //End basicParam

	$writer->startElement('previousRecordings');
	//Note: This has to be in a loop
        foreach ($students as $student) {

			if (isstudent($course->id,$student->id)) {
				//echo fullname($student)."<br>";
				//Student element gathers student name and ID for use in the tree function
				//***************Get the students grade******************************
				$student_grade = get_record('languagelab_student_eval','userid',$student->id,'languagelab',$languagelab->id);
				//*******************************************************************
				$writer->startElement('student');
				$writer->writeAttribute('label',fullname($student));
				$writer->writeAttribute('studentId',$student->id);
				if (isset($student_grade->grade)){
                                    $writer->writeAttribute('grade',$student_grade->grade);
                                } else {
                                    $writer->writeAttribute('grade','');
                                }
                                if (isset($student_grade->id)){
                                    $writer->writeAttribute('gradeid',$student_grade->id);//languagelab_student_evalution->id
                                } else {
                                    $writer->writeAttribute('gradeid','');
                                }
				//Get student picture Because search_users function only gives fullname and email
				  //We need to get the student record in order to more information on the user.
				  $studentinfo = get_record("user", "id", $student->id);
					if ($studentinfo->picture == 1) {
						$studentpictureurl = $CFG->wwwroot."/user/pix.php/".$student->id."/f2.jpg";
						} else {
						$studentpictureurl =  $CFG->wwwroot."/pix/u/f2.png";
						}

				  
				  //get users recordings
				  $studentrecordings = get_records_select('languagelab_submissions','userid='.$student->id.' AND languagelab='.$languagelab->id);
					foreach ($studentrecordings as $studentrecording) {
					
						$writer->startElement('recording');
							$writer->writeAttribute('recURI',$studentrecording->path);
							$writer->writeAttribute('lastUpdate',$studentrecording->timemodified);
							$writer->writeAttribute('label',str_replace('@@',"'",$studentrecording->label));
							$writer->writeAttribute('author',fullname($student));
							$writer->writeAttribute('userid',$student->id);
							$writer->writeAttribute('portrait',$studentpictureurl);
							$writer->writeAttribute('tMessage',$studentrecording->message);
							$writer->writeAttribute('recordingid',$studentrecording->id);
							if (isset($student_grade->grade)){
                                                            $writer->writeAttribute('grade',$student_grade->grade);
                                                        } else {
                                                            $writer->writeAttribute('grade','');
                                                        }
                                                        if (isset($student_grade->correctionnotes)){
                                                            $writer->writeAttribute('corrNotes',$student_grade->correctionnotes);
                                                        } else {
                                                            $writer->writeAttribute('corrNotes','');
                                                        }
                                                        
							$parentnode = "parentnode='".$studentrecording->path."' AND languagelab=".$languagelab->id;
														
							//now to verify if there are child nodes to this recording
							$replyrecordings = get_records_select('languagelab_submissions',$parentnode);
							
								foreach ($replyrecordings as $replyrecording) {
								$writer->startElement('reply');
									$writer->writeAttribute('recURI',$replyrecording->path);
									$writer->writeAttribute('lastUpdate',$replyrecording->timemodified);
									$writer->writeAttribute('label',$replyrecording->label);
									$writer->writeAttribute('author',$teachername);
									$writer->writeAttribute('userid',$teacherid);
									$writer->writeAttribute('portrait',$userpictureurl);
									$writer->writeAttribute('tMessage',$replyrecording->message);
									$writer->writeAttribute('recordingid',$replyrecording->id);
								$writer->endElement(); // End reply
								}
						$writer->endElement(); //end recording
					}
				$writer->endElement(); //End Student
			}
		}

	$writer->endElement(); //End previousRecordings

	

$writer->endElement(); // end playerParam
 ?>