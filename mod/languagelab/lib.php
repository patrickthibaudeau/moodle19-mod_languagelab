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

/// (replace languagelab with the name of your module and delete this line)


$languagelab_CONSTANT = 7;     /// for example
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/lib/uploadlib.php');
/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will create a new instance and return the id number 
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted languagelab record
 **/
function languagelab_add_instance($languagelab, $mform = null) {
    global $CFG,$COURSE;
    $languagelab->timemodified = time();

     //Get uploaded file and save it.
    $um = new upload_manager(null,false,false,$COURSE,false);
    $newfilename =clean_filename($_FILES['master_track']['name']);

    if ($um->preprocess_files()) {
        $dir = "$COURSE->id/languagelab/" .clean_filename($languagelab->name)."/mastertrack/";
        $languagelab->master_track = $newfilename;

        $um->save_files($dir) ;
    }
    
    if ($languagelab->id = insert_record("languagelab", $languagelab)) {
    	       	
    }
    //Only use grade back if true
    if ($languagelab->use_grade_book == true) {
        languagelab_grade_item_update(stripslashes_recursive($languagelab));
    }

    return $languagelab->id ;

     
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function languagelab_update_instance($languagelab) {
    global $CFG, $COURSE;
    $languagelab->timemodified = time();
    $languagelab->id = $languagelab->instance;

    
     //Get uploaded file and save it.
    $um = new upload_manager(null,false,false,$COURSE,false);
    $newfilename =clean_filename($_FILES['master_track']['name']);
    
    if ($um->preprocess_files()) {
        $dir = "$COURSE->id/languagelab/" .clean_filename($languagelab->name)."/mastertrack/";
        $languagelab->master_track = $newfilename;
               
        $um->save_files($dir) ;
    }



    //Only use grade back if true
    if ($languagelab->use_grade_book == true) {
	// update grade item definition
    languagelab_grade_item_update(stripslashes_recursive($languagelab));

    // update grades - TODO: do it only when grading style changes
    languagelab_update_grades(stripslashes_recursive($languagelab), 0, false);
    }
    # May have to add extra stuff in here #
	return update_record("languagelab", $languagelab);;
     
}

/**
 * Given an ID of an instance of this module, 
 * this function will permanently delete the instance 
 * and any data that depends on it. 
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function languagelab_delete_instance($id) {
    global $CFG,$COURSE;
    if (! $languagelab = get_record("languagelab", "id", "$id")) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #
    delete_records("languagelab_student_eval", "languagelab", "$languagelab->id");
    delete_records("languagelab_submissions", "languagelab", "$languagelab->id");

    if (! delete_records("languagelab", "id", "$languagelab->id")) {
        $result = false;
    }
    languagelab_grade_item_delete($languagelab);
    
    return $result;
}

/**
 * Return a small object with summary information about what a 
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function languagelab_user_outline($course, $user, $mod, $languagelab) {
    return $return;
}

/**
 * Print a detailed representation of what a user has done with 
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function languagelab_user_complete($course, $user, $mod, $languagelab) {
    return true;
}

/**
 * Given a course and a time, this module should find recent activity 
 * that has occurred in languagelab activities and print it out. 
 * Return true if there was output, or false is there was none. 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function languagelab_print_recent_activity($course, $isteacher, $timestart) {
    global $CFG;

    return false;  //  True if anything was printed, otherwise false 
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such 
 * as sending out mail, toggling flags etc ... 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function languagelab_cron () {
    global $CFG;

    return true;
}

/**
 * Must return an array of grades for a given instance of this module, 
 * indexed by user.  It also returns a maximum allowed grade.
 * 
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
function languagelab_get_user_grades($languagelab, $userid=0) {
    global $CFG;

    $user = $userid ? "AND u.id = $userid" : "";
    $fuser = $userid ? "AND uu.id = $userid" : "";

               $sql = "SELECT u.id, u.id AS userid, AVG(g.grade) AS rawgrade
                      FROM {$CFG->prefix}user u, {$CFG->prefix}languagelab_student_eval g
                     WHERE u.id = g.userid AND g.languagelab = $languagelab->id
                           $user
                  GROUP BY u.id";

    return get_records_sql($sql);
}

/**
 * Update grades in central gradebook
 *
 */
function languagelab_update_grades($languagelab=null, $userid=0, $nullifnone=true) {
    global $CFG;
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }

    if ($languagelab != null) {
        if ($grades = languagelab_get_user_grades($languagelab, $userid)) {
            languagelab_grade_item_update($languagelab, $grades);

        } else if ($userid and $nullifnone) {
            $grade = new object();
            $grade->userid   = $userid;
            $grade->rawgrade = NULL;
            languagelab_grade_item_update($languagelab, $grade);

        } else {
            languagelab_grade_item_update($languagelab);
        }

    } else {
        $sql = "SELECT l.*, cm.idnumber as cmidnumber, l.course as courseid
                  FROM {$CFG->prefix}languagelab l, {$CFG->prefix}course_modules cm, {$CFG->prefix}modules m
                 WHERE m.name='languagelab' AND m.id=cm.module AND cm.instance=l.id";
        if ($rs = get_recordset_sql($sql)) {
            while ($languagelab = rs_fetch_next_record($rs)) {
                if ($languagelab->grade != 0) {
                    languagelab_update_grades($languagelab, 0, false);
                } else {
                    languagelab_grade_item_update($languagelab);
                }
            }
            rs_close($rs);
        }
    }
}

/**
 * Create grade item for given lesson
 *
 * @param object $lesson object with extra cmidnumber
 * @param mixed optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return int 0 if ok, error code otherwise
 */
function languagelab_grade_item_update($languagelab, $grades=NULL) {
    global $CFG;
    
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }

    if (array_key_exists('cmidnumber', $languagelab)) { //it may not be always present
        echo 'array key exits';
        $params = array('itemname'=>$languagelab->name, 'idnumber'=>$languagelab->cmidnumber);
    } else {
        $params = array('itemname'=>$languagelab->name);
      // echo 'array key does not exits '.$languagelab->name;
    }

    if ($languagelab->grade > 0) {
        $params['gradetype']  = GRADE_TYPE_VALUE;
        $params['grademax']   = $languagelab->grade;
        $params['grademin']   = 0;

    } else {
        $params['gradetype']  = GRADE_TYPE_NONE;
    }

    if ($grades  === 'reset') {
        $params['reset'] = true;
        $grades = NULL;
    } else if (!empty($grades)) {
        // Need to calculate raw grade (Note: $grades has many forms)
        if (is_object($grades)) {
            $grades = array($grades->userid => $grades);
        } else if (array_key_exists('userid', $grades)) {
            $grades = array($grades['userid'] => $grades);
        }
        foreach ($grades as $key => $grade) {
            if (!is_array($grade)) {
                $grades[$key] = $grade = (array) $grade;
            }
            $grades[$key]['rawgrade'] = ($grade['rawgrade'] * $languagelab->grade / 100);
        }
    }
    
    return grade_update('mod/languagelab', $languagelab->course, 'mod', 'languagelab', $languagelab->id, 0, $grades, $params);
}

/**
 * Delete grade item for given lesson
 *
 * @param object $lesson object
 * @return object lesson
 */
function languagelab_grade_item_delete($languagelab) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('mod/languagelab', $languagelab->course, 'mod', 'languagelab', $languagelab->id, 0, NULL, array('deleted'=>1));
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of languagelab. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed boolean/array of students
 **/
function languagelab_get_participants($languagelabid) {
    return false;
}

/**
 * This function returns if a scale is being used by one languagelab
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $languagelabid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function languagelab_scale_used ($languagelabid,$scaleid) {
    $return = false;

    //$rec = get_record("languagelab","id","$languagelabid","scale","-$scaleid");
    //
    //if (!empty($rec)  && !empty($scaleid)) {
    //    $return = true;
    //}
   
    return $return;
}

//Needed for ajax to get languagelabid 
function get_languagelab_id($languagelab) {
    $languagelabid = $languagelab;
    return $languagelabid;
}




//////////////////////////////////////////////////////////////////////////////////////
/// Any other languagelab functions go here.  Each of them must have a name that 
/// starts with languagelab_


?>
