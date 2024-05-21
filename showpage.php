<?php
use mod_collaborate\output\showpage;
require_once('../../config.php');

// The user page id and the collaborate instance id.
$page = required_param('page', PARAM_TEXT);
$cid = required_param('cid', PARAM_INT);

// Get the information required to check the user can access this page.
$collaborate = $DB->get_record('collaborate', ['id' => $cid], '*', MUST_EXIST);
$courseid = $collaborate->course;
$cm = get_coursemodule_from_instance('collaborate', $cid, $courseid, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_module::instance($cm->id);

// Set the page URL.
$PAGE->set_url('/mod/collaborate/showpage.php', ['cid' => $cid, 'page' => $page]);

// Check the user is logged on.
require_login($course, true, $cm);

// Set the page information.
$PAGE->set_title(format_string($collaborate->name));
$PAGE->set_heading(format_string($course->fullname));

// Start output to browser.
echo $OUTPUT->header();

// Create output object and render it using the template.
echo $OUTPUT->render(new showpage($collaborate, $cm, $page));

// End output to browser.
echo $OUTPUT->footer();