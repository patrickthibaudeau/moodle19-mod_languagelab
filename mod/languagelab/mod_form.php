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
//require_once ($CFG->dirroot.'/course/moodleform_mod.php');
//require($CFG->libdir.'/filelib.php');
//require($CFG->libdir.'/adminlib.php');
require_once ($CFG->dirroot.'/course/moodleform_mod.php');


class mod_languagelab_mod_form extends moodleform_mod {

    function definition() {
	 global $USER, $CFG, $COURSE;
        $mform =& $this->_form;
        $mform->addElement('header','general',get_string('general','languagelab'));
	$mform->addElement('text', 'name', get_string('name', 'languagelab'), array('size'=>'45')); //Name to be used 
	$mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('htmleditor', 'description', get_string('description', 'languagelab'));
        $mform->addRule('description', null, 'required', null, 'client');
        $this->set_upload_manager(new upload_manager('master_track', true, false, $COURSE, false, 0, true, true, false));
        $mform->addElement('file', 'master_track', get_string('master_track', 'languagelab'));
        $mform->setType('master_track', PARAM_FILE);
        $mform->setHelpButton('master_track', array('master_track', get_string('master_track','languagelab'),'languagelab'));
        $mform->addElement('static','master_track_file',get_string('master_track_file','languagelab'),'');
        
        $mform->addElement('advcheckbox','attempts',get_string('attempts','languagelab'),null);
        //$mform->addElement('text','recording_timelimit',get_string('recording_timelimit','languagelab'));
        //$mform->setDefault('recording_timelimit', 0);

        $mform->addElement('header','general',get_string('advanced','languagelab'));
        $mform->addElement('checkbox','video',get_string('use_video','languagelab'));
        $mform->setDefault('video', false);
        $mform->addElement('checkbox','use_grade_book',get_string('use_grade_book','languagelab'));
        $mform->setDefault('use_grade_book', false);
        $mform->addElement('modgrade', 'grade', get_string('grade'));
        $mform->setDefault('grade', 100);
        $mform->disabledIf('grade', 'use_grade_book');
        $mform->setDefault('grade', 0);
        
        $mform->addElement('date_time_selector', 'timeavailable', get_string('availabledate', 'languagelab'), array('optional'=>true));
        $mform->setDefault('timeavailable', time());
        $mform->addElement('date_time_selector', 'timedue', get_string('duedate', 'languagelab'), array('optional'=>true));
        $mform->setDefault('timedue', time()+7*24*3600);
        
        $ynoptions = array( 0 => get_string('no'), 1 => get_string('yes'));
                                           
        $features = new stdClass;
        $features->groups = true;
        $features->groupings = true;
        $features->groupmembersonly = true;
        $features->idnumber = false;
        $this->standard_coursemodule_elements($features);
        
        $this->add_action_buttons();
        
        
    }
    function data_preprocessing(&$default_values) {

        global $CFG;

        if ($this->_instance) {
            $languagelab = get_record('languagelab','id',$this->_instance);

            $default_values['master_track_file'] = $languagelab->master_track;

        }
    }
    

}
?>