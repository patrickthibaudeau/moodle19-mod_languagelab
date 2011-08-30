<?php  //$Id: upgrade.php,v 1.1.8.1 2008/05/01 20:51:20 skodak Exp $

// This file keeps track of upgrades to 
// the label module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_languagelab_upgrade($oldversion=0) {

  global $CFG, $THEME, $db;
  
	$result = true;
       if ($result && $oldversion < 2010082501) {

    /// Define field timedue to be dropped from languagelab
        $table = new XMLDBTable('languagelab');
        $field = new XMLDBField('words');

    /// Launch drop field timedue
        $result = $result && drop_field($table, $field);
    }
    if ($result && $oldversion < 2011082800) {

    /// Define field video, use_grade_book, group_type, master_track to be added to languagelab
        $table = new XMLDBTable('languagelab');
        $field = new XMLDBField('video');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, null, null, null, null, '0', 'attempts');
        $field2 = new XMLDBField('use_grade_book');
        $field2->setAttributes(XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, null, null, null, null, '0', 'attempts');
        $field3 = new XMLDBField('group_type');
        $field3->setAttributes(XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, null, null, null, null, '0', 'attempts');
        $field4 = new XMLDBField('master_track');
        $field4->setAttributes(XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null, 'group_type');

    /// Launch add fields
        $result = $result && add_field($table, $field) && add_field($table, $field2) && add_field($table, $field3) && add_field($table, $field4);
    }
    if ($result && $oldversion < 2011082801) {

    /// Changing nullability of field groupid on table languagelab_submissions to null
        $table = new XMLDBTable('languagelab_submissions');
        $field = new XMLDBField('groupid');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', null, null, null, null, null, null, 'userid');
        $field2 = new XMLDBField('path');
        $field2->setAttributes(XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null, 'groupid');
        $field3 = new XMLDBField('label');
        $field3->setAttributes(XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null, 'path');
        $field4 = new XMLDBField('message');
        $field4->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'label');

    /// Launch change of nullability for field groupid
        $result = $result && change_field_notnull($table, $field) && change_field_notnull($table, $field2) && change_field_notnull($table, $field3) && change_field_notnull($table, $field4);
    }
    if ($result && $oldversion < 2011082802) {

    /// Changing nullability of field correctionnotes on table languagelab_student_eval to null
        $table = new XMLDBTable('languagelab_student_eval');
        $field = new XMLDBField('correctionnotes');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'userid');

    /// Launch change of nullability for field correctionnotes
        $result = $result && change_field_notnull($table, $field);
    }
    if ($result && $oldversion < 2011082801) {

    /// Changing nullability of field groupid on table languagelab_submissions to null
        $table = new XMLDBTable('languagelab_submissions');
        $field = new XMLDBField('groupid');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', null, null, null, null, null, null, 'userid');
        $field2 = new XMLDBField('path');
        $field2->setAttributes(XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null, 'groupid');
        $field3 = new XMLDBField('label');
        $field3->setAttributes(XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null, 'path');
        $field4 = new XMLDBField('message');
        $field4->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'label');

    /// Launch change of nullability for field groupid
        $result = $result && change_field_notnull($table, $field) && change_field_notnull($table, $field2) && change_field_notnull($table, $field3) && change_field_notnull($table, $field4);
    }
    if ($result && $oldversion < 2011082803) {
        //All features are now implemented
        $result;
    }

   

 return $result;

}

?>
