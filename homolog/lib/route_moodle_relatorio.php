<?php

/**
 * Undocumented function
 *
 * @param array $post
 * @return void
 */
function route_moodle_progress_csv()
{
    wh_log('=======[ API request - route: route_moodle_relatorio ]=========');
    global $API;
    global $cache;
    
    if (is_null($API->requestId)) {
         return $API->return_error('Moodle Progress Report', 'Course ID missing (/moodle/progress/csv/{ID})!');
    }

    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="courseProgress_'.$API->requestId.'.csv";');
    $output = fopen('php://output', 'w');
        
    $cache_name = $_SERVER['REQUEST_URI'];
    $isCached = $cache->get_cache($cache_name);
    if ($isCached) {
        fputcsv($output, $isCached);
        return json_decode($isCached);
    }
        
    ini_set('max_execution_time', 300);
    set_time_limit(300);

    // get enrollments
    $enrollments = $API->moodle_request("core_enrol_get_enrolled_users", "&courseid=".$API->requestId);
    // print_r($enrollments);
    
    $progressSum = 0;
    $gradeSum = 0;
    $studentCount = 0;

    // get course name
    foreach ($enrollments[0]->enrolledcourses as $enroll) {
        ($enroll->id == $API->requestId) ? $coursename = $enroll->shortname .' - '.$enroll->fullname : 'undefined course name' ;
    }

    $return = "*;$coursename;;;\n";
    $return .= "id;fullname;email;progress (%);points (grade)\n";

    foreach ($enrollments as $user) {
        
        $isStudent = ($user->roles[0]->roleid == 5);
        if (!$isStudent) {
            continue;
        }
        $userId = $user->id;
        $studentCount++;

        // get completition
        $activities = $API->moodle_request("core_completion_get_activities_completion_status", "&courseid=".$API->requestId."&userid=".$userId );
        // print_r($activities);
        
        list($doneCount, $progress, $total) = getProgress($activities, $userId);
        
        // get user grades
        $grades = $API->moodle_request("gradereport_overview_get_course_grades", "&userid=".$userId );
        // print_r($grades);

        list($grade) = getGrade($grades, $userId, $API->requestId);

        $return .= "$user->id;$user->fullname;$user->email;$progress;$grade\n";
        
        @$progressSum = $progressSum + $progress;
        @$gradeSum = $gradeSum + $grade;
    }

    $progressAverage = round(($progressSum / $studentCount));
    $gradeAverage = round(($gradeSum / $studentCount), 2);

    $return .= "Total: $studentCount;;;$progressAverage;$gradeAverage\n";
    $return .= "* This report is updated daily\n";

    $cache->set_cache($cache_name, json_encode($return));
    fputcsv($output, $return);
    echo $return;
}

function getProgress($activities, $userId) {

    $total = count($activities->statuses);

    $doneCount = 0;
    foreach ($activities->statuses as $page) {
        $done = ($page->state == 1 && $page->timecompleted !== 0);
        if ($done) {
            $doneCount++;
        } else {
        }
        $doneCertificate = ($page->modname == 'simplecertificate' && $page->state == 1) ? true : false ;
        // print_r($page);
    }
    $progress = round(($doneCount / $total) * 100);
    $progress = ($doneCertificate) ? '100' : $progress ;

    return array($doneCount, $progress, $total);
}

function getGrade($grades, $userId, $requestId) {
    $grade = 0;
    foreach ($grades->grades as $grade) {
        $matchCourse = ($grade->courseid == $requestId);
        if ($matchCourse) {
            $grade = (float)$grade->grade;
            $return = (is_numeric($grade)) ? $grade : 0 ;
            return array($grade);
        } 
        
    }
    return array(0);
}