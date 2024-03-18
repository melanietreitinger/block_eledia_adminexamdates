# Adminexamdates & Checklist Installation Guide

## Overview

This tool can help with the administration of e-exams, providing a booking system for exam rooms and automaticly handling the moodle exam courses.

After the requested **exam date is confirmed, a course for this exam will be created in the examination system**. The course will
appear in the coursecategory of the current semester, and if there is none then the respective category will be created.

Current version: 3.11

## Installation

### 0. Install plugins on booking system
Both `block_eledia_adminexamdates` and `mod_elediachecklist` need to be installed for full functionality. They should both be **installed on the booking system**, because they use a webservice to communicate with the examination system.
(They could be installed on the very same system, for example for testing purposes.)

The configuration for `block_eledia_adminexamdates` can be found on the **setting page of the block plugins** (/admin/settings.php?
section=blocksettingeledia_adminexamdates).

The configuration for `mod_elediachecklist` can be found on the **setting page of the activity plugins** (/admin/settings.php?
section=modsettingelediachecklist).

### 1. Set up webservice (examination system)

**You can use the provided cli script to automatically perform the following steps!**

The following steps are based on the Moodle guide to create a webservice on `Site Administration -> Server -> Web services ->
Overview`
with some additional notes. Clicking the linked steps in moodle (in a new tab) will bring you directly to the respective setting pages.

**1. Enable web services**

**2. Enable protocols**: Only REST needs to be enabled.

**3. Create a specific user**

**4. Check user capability (create role)**: Create a role and assign it on system level to the webservice user.
<details>
  <summary>The role needs to be granted the following <b>capabilities:</b></summary>

    - moodle/category:manage
    - moodle/category:viewhiddencategories
    - moodle/course:create
    - moodle/backup:backupcourse
    - moodle/course:changecategory
    - moodle/course:changefullname
    - moodle/course:changeidnumber
    - moodle/course:changeshortname
    - moodle/course:changesummary
    - moodle/course:managegroups
    - moodle/course:update
    - moodle/course:view
    - moodle/course:viewhiddencourses
    - moodle/course:visibility
    - moodle/restore:restorecourse
    - webservice/rest:use
</details>

**5. Select/add a service**

**6. Add functions**
<details>
  <summary>Required functions:</summary>

    - core_course_create_categories
    - core_course_duplicate_course
    - core_course_get_categories
    - core_course_get_contents
    - core_course_get_courses_by_field
    - core_course_update_courses
    - core_group_create_groups
    - core_update_inplace_editable
</details>

**7. Select a specific user**

**8. Create a token for a user**: Copy token to use it in Step 2!

### 2. Enter webservice information in plugin settings (booking system)
Enter the **token and URL** of the webservice (e.g. 'https://moodle.university.de') in the **plugin settings** of the block "eLeDia e-exam dates administration" (`apidomain` and `apitoken`).

### 3. Create Course category for exams (examination system)
a) The coursecategory needs to have the **'Course category ID' from the plugin settings as idnumber** (`envcategoryidnumber`, the value is 'EXAMENV' by default).

b) Create **subcategories for the departments** in this category (at least one subcategory is needed).

c) Create another course category to be the exam archive, with the idnumber 'EXAMARCHIVE' (`archivecategoryidnumber`) or your customised name from the plugin settings.

d) Go to the `block_eledia_adminexamdates` settings page, mark the checkbox for "Update departments" (`reloaddepartments`) and
then save the settings.

e) **If the webservice works you will then have a new setting 'Selection of departments' (`departments`)**. Now you can select
the departments for which the booking service will be available.

### 4. Create exam course blueprint (examination system)
There is also a course idnumber (`examcoursetemplateidnumber`) in the plugin settings for a template course (default 'EXAMTEMPLATE').

Exam courses created by this plugin will be duplicated from this template course.

Create a template course anywhere in the examination system with the respective idnumber.

### 5. Define exam rooms (booking system)
Configure your exam rooms in the plugin settings (`examrooms`). Each line should contain the information for one course room, seperated by a pipe (|) like this:

<b>uniqueroomid | room name | capacity | room color in the calendar</br>
ER1|Examroom1|110|#3F51B5</b>

### 6. Create a global cohort for teachers (booking system)
Define one global cohort the teachers will belong to, so that they can interact with the plugin. Once the cohort is defined,
select it in the plugin settings (`examinercohorts`).

### 7. Create booking course (booking system)
E-exams can be booked in this course.
1. Enable completion tracking for this course.
2. Add the activities 'eLeDia Checklist' and 'Database' (for the "problem database") to the course (but hide them from students).
3. Add the block 'eLeDia e-exam dates administration'.
4. Go to the enrolment settings of the course and add 'Cohort sync' as enrolment method. Select the global cohort 'examiners'
   and assign them the role 'student'.


### 7. Configure and select checklist (booking system)
Go to the settings page of `mod_elediachecklist` and select the items which shall be used in the checklist before and after an
exam (`erinnerung_kvb_name` and `erinnerung_knb_name`).

You can add new items to the checklist if you are in the **course edit mode**. You will then see a green button "Add Topic" at the
bottom of the checklist.

Go to the `block_eledia_adminexamdates` settings page and select the created checklist in the plugin settings
(`instanceofmodelediachecklist`).

### 8. Problem database
In the problem database you can enter issues which happened during an exam.
A preset for the database configuration is available.

To integrate the problem database into the Checklist you need the **template id**. You can find this id in the url if you click
on the database activity in the booking course and then on "More / Templates".

Enter this id in the settings page of `mod_elediachecklist` as `data_instance_id_problems`.

Note: The setting `data_field_id_default` is only usable with a core hack.

