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

if (!has_capability('block/eledia_adminexamdates:confirmexamdates', $context)) {
    throw new \moodle_exception(' only users with rights to confirm admin exam dates allowed', 'block_eledia_adminexamdates');
}

$returnurl = optional_param('url', '', PARAM_RAW);

$calendarurl = new \moodle_url('/blocks/eledia_adminexamdates/calendar.php');
$returnurldecoded = (!empty($returnurl)) ? rawurldecode($returnurl) : $calendarurl;

$myurl = new \moodle_url($FULLME);

$PAGE->set_url($myurl);
$PAGE->set_context(context_system::instance());
$title = get_string('statistics', 'block_eledia_adminexamdates');
$PAGE->set_title($title);
$PAGE->set_pagelayout('course');

$onlynumberstudents = false;

$mform = new \block_eledia_adminexamdates\forms\statistics_form(null, ['url' => $returnurl]);

// Execute the form.
if ($mform->is_cancelled()) {
    redirect($returnurldecoded);
} else {
    $formdata = $mform->get_data();
    echo $OUTPUT->header();
    echo $OUTPUT->container_start();
    $url = new moodle_url('/blocks/eledia_adminexamdates/editexamdate.php', ['newexamdate' => 1, 'url' => rawurlencode($myurl)]);
    $newexamdatebutton = new single_button($url, get_string('newexamdate', 'block_eledia_adminexamdates'));
    $urlcalendar = new moodle_url('/blocks/eledia_adminexamdates/calendar.php');
    $urllist = new moodle_url('/blocks/eledia_adminexamdates/examdateslist.php');
    $unconfirmed = new moodle_url('/blocks/eledia_adminexamdates/examdatesunconfirmed.php');

    echo \html_writer::start_tag('div', ['class' => 'container-fluid px-4']);
    echo \html_writer::start_tag('div', ['class' => 'row']);
    echo \html_writer::start_tag('div', ['class' => 'col-xs-12']);
    echo $OUTPUT->single_button($urlcalendar,
            get_string('calendar_btn', 'block_eledia_adminexamdates'),
            'get',
            ['class' => 'singlebutton mb-3']);
    echo $OUTPUT->single_button($urllist,
            get_string('examdateslist_btn', 'block_eledia_adminexamdates'),
            'get',
            ['class' => 'singlebutton mb-3']);
    echo $OUTPUT->single_button($unconfirmed,
            get_string('unconfirmed_btn', 'block_eledia_adminexamdates'),
            'get',
            ['class' => 'singlebutton mb-3']);
    echo $OUTPUT->single_button($url,
            get_string('newexamdate', 'block_eledia_adminexamdates'),
            'get',
            ['class' => 'singlebutton mb-3']);
    echo \html_writer::start_tag('div', ['class' => 'singlebutton mb-3']);
    echo \html_writer::tag('button',
            get_string('statistics', 'block_eledia_adminexamdates'),
            ['disabled' => true, 'class' => 'btn ']);
    echo \html_writer::end_tag('div');
    $urlreport = new moodle_url('/mod/elediachecklist/terminreport.php');
    echo $OUTPUT->single_button($urlreport,
            get_string('report_button', 'elediachecklist'),
            'get',
            ['class' => 'singlebutton mb-3']);

    echo \html_writer::end_tag('div');
    echo \html_writer::end_tag('div');

    echo \html_writer::start_tag('div', ['class' => 'row']);
    echo \html_writer::start_tag('div', ['class' => 'col-xs-12']);

    echo \html_writer::start_tag('div', ['class' => 'card-deck mt-3']);
    echo \html_writer::start_tag('div', ['class' => 'card']);
    echo \html_writer::start_tag('div', ['class' => 'card-body']);
    echo \html_writer::start_tag('p', ['class' => 'card-text']);

    $mform->display();

    echo \html_writer::end_tag('p');
    echo \html_writer::end_tag('div');
    echo \html_writer::end_tag('div');
    echo \html_writer::end_tag('div');

    if (!empty($formdata)) {
        echo \html_writer::start_tag('div', ['class' => 'col-xs-6']);
        echo block_eledia_adminexamdates\util::get_html_statisticstable($formdata);
        echo \html_writer::end_tag('div');
    }

    echo \html_writer::end_tag('div');
    echo \html_writer::end_tag('div');

    echo \html_writer::end_tag('div');
    echo $OUTPUT->container_end();
    echo $OUTPUT->footer();
}

