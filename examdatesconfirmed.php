<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block_eledia_adminexamdates
 * @copyright  2021 René Hansen <support@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

global $USER, $CFG, $PAGE, $OUTPUT, $DB;

require_login();

// Get course.
$courseid = $DB->get_field('course_modules',
        'course',
        ['id' => get_config('block_eledia_adminexamdates', 'instanceofmodelediachecklist')]);
$course = $DB->get_record('course', ['id' => $courseid]);
if (!$course) {
    throw new \moodle_exception('invalidcourseid', 'block_eledia_adminexamdates');
}
require_login($course);

$context = context_course::instance($course->id);

$displaydatefrom = optional_param_array('displaydatefrom', null, PARAM_INT);
$displaydateto = optional_param_array('displaydateto', null, PARAM_INT);

if (is_array($displaydatefrom)) {
    $displaydatefrom = mktime(0, 0, 0, $displaydatefrom['month'], $displaydatefrom['day'], $displaydatefrom['year']);
}
if (is_array($displaydateto)) {
    $displaydateto = mktime(23, 59, 59, $displaydateto['month'], $displaydateto['day'], $displaydateto['year']);
}

$myurl = new \moodle_url($FULLME);

$PAGE->set_url($myurl);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('examdaterequest', 'block_eledia_adminexamdates'));
$PAGE->set_pagelayout('course');

$mform = new \block_eledia_adminexamdates\forms\examdatesconfirmed_form();

echo $OUTPUT->header();
echo $OUTPUT->container_start();

// url = new moodle_url('/blocks/eledia_adminexamdates/editexamdate.php', ['newexamdate' => 1, 'url' => rawurlencode($myurl) ]);
// newexamdatebutton = new single_button($url, get_string('newexamdate', 'block_eledia_adminexamdates'));
$urlcalendar = new moodle_url('/blocks/eledia_adminexamdates/calendar.php');
$unconfirmed = new moodle_url('/blocks/eledia_adminexamdates/examdatesunconfirmed.php');

echo \html_writer::start_tag('div', ['class' => 'container-fluid px-4']);
echo \html_writer::start_tag('div', ['class' => 'row']);
echo \html_writer::start_tag('div', ['class' => 'col-xs-12']);

echo $OUTPUT->single_button($urlcalendar, get_string('calendar_btn', 'block_eledia_adminexamdates'));
echo $OUTPUT->single_button($unconfirmed, get_string('unconfirmed_btn', 'block_eledia_adminexamdates'));
echo \html_writer::start_tag('div', ['class' => 'singlebutton mb-3']);
echo \html_writer::tag('button',
        get_string('confirmed_btn', 'block_eledia_adminexamdates'),
        ['disabled' => true, 'class' => 'btn ']);
echo \html_writer::end_tag('div');
// cho $OUTPUT->single_button($url, get_string('newexamdate', 'block_eledia_adminexamdates'));
echo \html_writer::end_tag('div');
echo \html_writer::end_tag('div');
echo \html_writer::start_tag('div', ['class' => 'row mt-3']);
echo \html_writer::start_tag('div', ['class' => 'col-xs-12']);
echo \html_writer::start_tag('div', ['class' => 'card-deck']);
echo \html_writer::start_tag('div', ['class' => 'card']);
echo \html_writer::start_tag('div', ['class' => 'card-body']);
echo \html_writer::start_tag('p', ['class' => 'card-text']);

$mform->display();
echo block_eledia_adminexamdates\util::getexamdateitems(true, $displaydatefrom, $displaydateto);

echo \html_writer::end_tag('p');
echo \html_writer::end_tag('div');
echo \html_writer::end_tag('div');
echo \html_writer::end_tag('div');

echo \html_writer::end_tag('div');
echo \html_writer::end_tag('div');
echo \html_writer::end_tag('div');

echo $OUTPUT->container_end();

echo $OUTPUT->footer();



