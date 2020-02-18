<?php

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $name = test_input($_POST["name"]);
//     $email = test_input($_POST["email"]);
//     $website = test_input($_POST["website"]);
//     $comment = test_input($_POST["comment"]);
//     $gender = test_input($_POST["gender"]);
// }

// function test_input($data)
// {
//     $data = trim($data);
//     $data = stripslashes($data);
//     $data = htmlspecialchars($data);
//     return $data;
// }

class CanvasLMS
{
    private $token;
    private $domain;

    private $CONTAS = array(6, 9, 21, 19, 46, 42);

    public function CanvasLMS($t, $d)
    {
        $this->token = $t;
        $this->domain = $d;
    }

    public function pegaCursos()
    {
        //$contas = array(6, 47, 44, 9, 21, 19, 46, 48, 42, 43);
        //$cursos = array();
        foreach ($this->CONTAS as $conta) {
            $pegacursos = $this->getlist("/api/v1/accounts/" . $conta . "/courses?", 'id', '', -1);
            mostra($pegacursos);
            // foreach ($pegacursos as $key => $curso) {
            //     $cursos[$curso->id] = $curso->name;
            //     echo ".";
            // }
        }
        // asort($cursos);
        return $pegacursos;
    }

    //These functions return a list of items as an associative array: id=>name
    public function getEnrollList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/enrollments", 'id', $max);
    }

    public function getCourseList($max = -1)
    {
        return $this->getlist("/api/v1/courses", 'id', 'name', $max);
    }

    public function getAssignmentList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/assignments", 'id', 'name', $max);
    }

    public function getFileList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/files", 'id', 'display_name', $max);
    }

    public function getQuizList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/quizzes", 'id', 'title', $max);
    }

    public function getPageList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/pages", 'url', 'title', $max);
    }

    public function getDiscussionTopicList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/discussion_topics", 'id', 'title', $max);
    }

    public function getItemList($courseid, $type, $max = -1)
    {
        switch ($type) {
            case 'assignments':
                return $this->getAssignmentList($courseid, $max);
                break;
            case 'files':
                return $this->getFileList($courseid, $max);
                break;
            case 'quizzes':
                return $this->getQuizList($courseid, $max);
                break;
            case 'pages':
                return $this->getPageList($courseid, $max);
                break;
            case 'discuss':
                return $this->getDiscussionTopicList($courseid, $max);
                break;
            default:
                echo 'error in item type';
                exit;
        }
    }

    //These functions return the full list results of the API list call
    // as an associative array:  id=>dataObject
    public function getFullCourseList($max = -1)
    {
        return $this->getlist("/api/v1/courses", 'id', '', $max);
    }

    public function getFullAssignmentList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/assignments", 'id', '', $max);
    }

    public function getFullFileList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/files", 'id', '', $max);
    }

    public function getFullQuizList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/quizzes", 'id', '', $max);
    }

    public function getFullPageList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/pages", 'url', '', $max);
    }

    public function getFullDiscussionTopicList($courseid, $max = -1)
    {
        return $this->getlist("/api/v1/courses/$courseid/discussion_topics", 'id', '', $max);
    }

    //These functions return the detailed data on one specific item
    public function getCourseData($courseid, $item = '')
    {
        return $this->getdata("/api/v1/courses/$courseid", $item);
    }

    public function getAssignmentData($courseid, $assignmentid, $item = '')
    {
        return $this->getdata("/api/v1/courses/$courseid/assignments/$assignmentid", $item);
    }

    public function getFileData($fileid, $item = '')
    {
        return $this->getdata("/api/v1/files/$fileid", $item);
    }

    public function getQuizData($courseid, $quizid, $item = '')
    {
        return $this->getdata("/api/v1/courses/$courseid/quizzes/$quizid", $item);
    }

    public function getPageData($courseid, $pageid, $item = '')
    {
        return $this->getdata("/api/v1/courses/$courseid/pages/" . urlencode($pageid), $item);
    }

    public function getDiscussionTopicData($courseid, $discid, $item = '')
    {
        return $this->getdata("/api/v1/courses/$courseid/discussion_topics/$discid", $item);
    }

    public function getItemData($courseid, $type, $typeid, $item = '')
    {
        switch ($type) {
            case 'assignments':
                return $this->getAssignmentData($courseid, $typeid, $item = '');
                break;
            case 'files':
                return $this->getFileData($courseid, $typeid, $item = '');
                break;
            case 'quizzes':
                return $this->getQuizData($courseid, $typeid, $item = '');
                break;
            case 'pages':
                return $this->getPageData($courseid, $typeid, $item = '');
                break;
            case 'discuss':
                return $this->getDiscussionTopicData($courseid, $typeid, $item = '');
                break;
            default:
                echo 'error in item type';
                exit;
        }
    }

    //These functions update an item.  The val array should be an associative
    // array of the form key=>value.  Consult the Canvas API for valid keys.
    // For items like Wiki Pages, use the keys that would be reported in the
    // item details, not the update parameters.  For example, use "body" for
    // the key, not "wiki_page[body]".
    public function updateAssignment($courseid, $assignmentid, $valarray)
    {
        $paramarray = array();
        foreach ($valarray as $p => $v) {
            $paramarray[] = "assignment[$p]=" . urlencode($v);
        }
        return $this->update("/api/v1/courses/$courseid/assignments/$assignmentid", implode('&', $paramarray));
    }

    public function updateEnrollment($courseid, $valarray)
    {
        $paramarray = array();
        foreach ($valarray as $p => $v) {
            $paramarray[] = "enrollment[$p]=" . urlencode($v);
        }
        //  echo "/api/v1/courses/$courseid/enrollments". implode('&', $paramarray)."<br>";
        return $this->update("/api/v1/courses/$courseid/enrollments", implode('&', $paramarray));
    }

    public function updateFile($courseid, $fileid, $valarray)
    {
        $paramarray = array();
        foreach ($valarray as $p => $v) {
            $paramarray[] = "$p=" . urlencode($v);
        }
        return $this->update("/api/v1/files/$fileid", implode('&', $paramarray));
    }

    public function updateQuiz($courseid, $quizid, $valarray)
    {
        $paramarray = array();
        foreach ($valarray as $p => $v) {
            $paramarray[] = "quiz[$p]=" . urlencode($v);
        }

        return $this->update("/api/v1/courses/$courseid/quizzes/$quizid", implode('&', $paramarray));
    }

    public function updatePage($courseid, $pageid, $valarray)
    {
        $paramarray = array();
        foreach ($valarray as $p => $v) {
            $paramarray[] = "wiki_page[$p]=" . urlencode($v);
        }
        return $this->update("/api/v1/courses/$courseid/pages/" . urlencode($pageid), implode('&', $paramarray));
    }

    public function updateDiscussionTopic($courseid, $discid, $valarray)
    {
        $paramarray = array();
        foreach ($valarray as $p => $v) {
            $paramarray[] = "$p=" . urlencode($v);
        }
        return $this->update("/api/v1/courses/$courseid/discussion_topics/$discid", implode('&', $paramarray));
    }

    public function updateItem($courseid, $type, $typeid, $valarray)
    {
        switch ($type) {
            case 'assignments':
                return $this->updateAssignment($courseid, $typeid, $valarray);
                break;
            case 'files':
                return $this->updateFile($courseid, $typeid, $valarray);
                break;
            case 'quizzes':
                return $this->updateQuiz($courseid, $typeid, $valarray);
                break;
            case 'pages':
                return $this->updatePage($courseid, $typeid, $valarray);
                break;
            case 'discuss':
                return $this->updateDiscussionTopic($courseid, $typeid, $valarray);
                break;
            default:
                echo 'error in item type';
                exit;
        }
    }

    //These are the internal functions that do the calls.
    //	private function getlist($base,$itemident,$nameident,$max=-1) {
    public function getlist($base, $itemident, $nameident, $max = -1)
    {

        $pagecnt = 1;
        $itemcnt = 0;
        $itemassoc = array();
        while (1) {
            $f = @file_get_contents('https://' . $this->domain . $base . 'per_page=50&page=' . $pagecnt . '&access_token=' . $this->token);
            $pagecnt++;
            if (trim($f) == '[]' || $pagecnt > 30 || $f === false) {
                break; //stop if we run out, or if something went wrong and $pagecnt is over 30
            } else {
                $itemlist = json_decode($f);
                for ($i = 0; $i < count($itemlist); $i++) {
                    if ($nameident != '') {
                        $itemassoc[$itemlist[$i]->$itemident] = $itemlist[$i]->$nameident;
                        //	$itemassoc[$i] = $itemlist[$i]->$nameident;
                    } else {
                        $itemassoc[$itemlist[$i]->$itemident] = $itemlist[$i];
                        //	$itemassoc[$i] = $itemlist[$i];
                    }
                    $itemcnt++;
                    if ($max != -1 && $itemcnt >= $max) {
                        break;
                    }
                }
                if (count($itemlist) < 50) { //not going to be another page
                    break;
                }
            }
        }
        return $itemassoc;
    }

    public function getdata($base, $item = '')
    {
        $page = json_decode(file_get_contents('https://' . $this->domain . $base . '?access_token=' . $this->token));
        if ($item == '') {
            return $page;
        } else {
            return $page->$item;
        }
    }

    private function update($item, $vals)
    {
        $ch = curl_init('https://' . $this->domain . $item . '?access_token=' . $this->token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vals);
        $response = curl_exec($ch);
        return ($response !== false);
    }
}

function verAprovacao($tarefasAluno)
{
    $notaTotal = $notaAluno = 0;
    foreach ($tarefasAluno as $tarefa) {
        $isQuizz = $tarefa->assignment_id !== null;
        if ($isQuizz) {
            $notaAluno = $notaAluno + $tarefa->submission->score;
            $notaTotal = $notaTotal + $tarefa->points_possible;
        }
    }
    $notaGeral = @round($notaAluno / $notaTotal * 10);
    $exigencia = 0.7;
    $notaCorte = $notaTotal * $exigencia;
    if ($notaGeral >= $notaCorte and $notaGeral > 0) {
        return array(true, $notaGeral, $notaAluno, $notaTotal);
    } else {
        return array(false, $notaGeral, $notaAluno, $notaTotal);
    }
}

function verAvanco($modules)
{
    $feito = $total = 0;
    foreach ($modules as $module) {
        foreach ($module->items as $aula) {
            $total = $total + 1;
            if ($aula->completion_requirement->completed == "1") {
                $feito = $feito + 1;
            }
        }
    } //foreach ($module
    $progresso = @round($feito / $total * 100);
    //echo 'Status:'.$feito.' '.$total.' '.@round($feito / $total *100).'%<br>';
    return array($progresso, $feito, $total);
}

function build_table($array)
{

    $html = '<table id="example" class="display compact nowrap" cellspacing="0" style="width: max-content;margin-bottom: 20px;">';
    // header row
    // $html .= '<tr>';
    // foreach($array[TOPO] as $key=>$value){
    //         $html .= '<th>' . $value . '</th>';
    //     }
    // $html .= '</tr>';

    // data rows
    foreach ($array as $key => $value) {
        //echo $key . ' ' .$value;
        //if (is_numeric($key)) {
        $html .= '<tr>';
        $html .= '<td>' . $key . '</td>';
        foreach ($value as $key2 => $value2) {
            $html .= '<td>' . $value2 . '</td>';
        }
        $html .= '</tr>';
        //}
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

function startsWith($haystack, $needle)
{
    // search backwards starting from haystack length characters from the end
    return $needle === ''
        || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    if ($needle === '') {
        return true;
    }
    $diff = \strlen($haystack) - \strlen($needle);
    return $diff >= 0 && strpos($haystack, $needle, $diff) !== false;
}

function formatPeriod($endtime, $starttime)
{

    $duration = $endtime - $starttime;

    $hours = (int) ($duration / 60 / 60);

    $minutes = (int) ($duration / 60) - $hours * 60;

    $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;

    return ($hours == 0 ? "00" : $hours) . ":" . ($minutes == 0 ? "00" : ($minutes < 10 ? "0" . $minutes : $minutes)) . ":" . ($seconds == 0 ? "00" : ($seconds < 10 ? "0" . $seconds : $seconds));
}

function console_log($data)
{
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }
    if (@$_GET['debug']) {
        echo "<script>console.log( '" . $output . "' );</script>";
    }
}

function mostra($data)
{
    echo "<pre>";
    echo print_r($data);
    echo "</pre>";
}

function feedback($data)
{
    echo '<script>document.getElementById("processando").innerHTML = "' . $data . '";</script>';
}

function valida_sla($inicio1, $inicio2, $fim1, $fim2)
{
    $start_date1 = strtotime($inicio1);
    $start_date2 = strtotime($inicio2);
    $end_date1 = strtotime($fim1);
    $end_date2 = strtotime($fim2);
    $result['SLA_OK'] = 1;

    console_log('valida_sla: $inicio1: ' . $inicio1 . ' $inicio2: ' . $inicio2 . ' $fim1: ' . $fim1 . ' $fim2: ' . $fim2);
    //onsole_log("START: 1 " . $start_date1 . " 2 " . $start_date2 . "END: 1 " . $end_date1 . " 2 " . $end_date2);

    if (!isset($inicio1)) {
        $start_date = strtotime($inicio2);
    } elseif (!isset($inicio2)) {
        $start_date = strtotime($inicio1);
    } else {
        $start_date = min($start_date1, $start_date2);
    }

    $valida_dt_resposta_formador = ($end_date1 < $end_date2 and $end_date1 > $start_date and isset($fim1));
    if ($valida_dt_resposta_formador) {
        $end_date = strtotime($fim1);
    } else {
        $end_date = strtotime($fim2);
    }

    $result['intervalo'] = (round(($end_date - $start_date) / 60 / 60 / 24));

    if ($result['intervalo'] > SLA_FORMADOR) {
        $result['SLA_OK'] = 0;
    }

    $result['dt_envio'] = date('d/m/Y', $start_date);
    $result['dt_resposta'] = date('d/m/Y', $end_date);

    ($result['dt_resposta'] == "01/01/1970") ? $result['dt_resposta'] = "N/A" : false;
    ($result['dt_resposta'] == "31/12/1969") ? $result['dt_resposta'] = "N/A" : false;

    console_log($result);

    return $result;
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @since 1.2.1
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function convert_accent_characters($string, $locale = null)
{
    if (!preg_match('/[\x80-\xff]/', $string)) {
        return $string;
    }
    $chars = [
        // Decompositions for Latin-1 Supplement
        'ª' => 'a',
        'º' => 'o',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'AE',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ð' => 'D',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ý' => 'Y',
        'Þ' => 'TH',
        'ß' => 's',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'ae',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'd',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ý' => 'y',
        'þ' => 'th',
        'ÿ' => 'y',
        'Ø' => 'O',
        // Decompositions for Latin Extended-A
        'Ā' => 'A',
        'ā' => 'a',
        'Ă' => 'A',
        'ă' => 'a',
        'Ą' => 'A',
        'ą' => 'a',
        'Ć' => 'C',
        'ć' => 'c',
        'Ĉ' => 'C',
        'ĉ' => 'c',
        'Ċ' => 'C',
        'ċ' => 'c',
        'Č' => 'C',
        'č' => 'c',
        'Ď' => 'D',
        'ď' => 'd',
        'Đ' => 'D',
        'đ' => 'd',
        'Ē' => 'E',
        'ē' => 'e',
        'Ĕ' => 'E',
        'ĕ' => 'e',
        'Ė' => 'E',
        'ė' => 'e',
        'Ę' => 'E',
        'ę' => 'e',
        'Ě' => 'E',
        'ě' => 'e',
        'Ĝ' => 'G',
        'ĝ' => 'g',
        'Ğ' => 'G',
        'ğ' => 'g',
        'Ġ' => 'G',
        'ġ' => 'g',
        'Ģ' => 'G',
        'ģ' => 'g',
        'Ĥ' => 'H',
        'ĥ' => 'h',
        'Ħ' => 'H',
        'ħ' => 'h',
        'Ĩ' => 'I',
        'ĩ' => 'i',
        'Ī' => 'I',
        'ī' => 'i',
        'Ĭ' => 'I',
        'ĭ' => 'i',
        'Į' => 'I',
        'į' => 'i',
        'İ' => 'I',
        'ı' => 'i',
        'Ĳ' => 'IJ',
        'ĳ' => 'ij',
        'Ĵ' => 'J',
        'ĵ' => 'j',
        'Ķ' => 'K',
        'ķ' => 'k',
        'ĸ' => 'k',
        'Ĺ' => 'L',
        'ĺ' => 'l',
        'Ļ' => 'L',
        'ļ' => 'l',
        'Ľ' => 'L',
        'ľ' => 'l',
        'Ŀ' => 'L',
        'ŀ' => 'l',
        'Ł' => 'L',
        'ł' => 'l',
        'Ń' => 'N',
        'ń' => 'n',
        'Ņ' => 'N',
        'ņ' => 'n',
        'Ň' => 'N',
        'ň' => 'n',
        'ŉ' => 'n',
        'Ŋ' => 'N',
        'ŋ' => 'n',
        'Ō' => 'O',
        'ō' => 'o',
        'Ŏ' => 'O',
        'ŏ' => 'o',
        'Ő' => 'O',
        'ő' => 'o',
        'Œ' => 'OE',
        'œ' => 'oe',
        'Ŕ' => 'R',
        'ŕ' => 'r',
        'Ŗ' => 'R',
        'ŗ' => 'r',
        'Ř' => 'R',
        'ř' => 'r',
        'Ś' => 'S',
        'ś' => 's',
        'Ŝ' => 'S',
        'ŝ' => 's',
        'Ş' => 'S',
        'ş' => 's',
        'Š' => 'S',
        'š' => 's',
        'Ţ' => 'T',
        'ţ' => 't',
        'Ť' => 'T',
        'ť' => 't',
        'Ŧ' => 'T',
        'ŧ' => 't',
        'Ũ' => 'U',
        'ũ' => 'u',
        'Ū' => 'U',
        'ū' => 'u',
        'Ŭ' => 'U',
        'ŭ' => 'u',
        'Ů' => 'U',
        'ů' => 'u',
        'Ű' => 'U',
        'ű' => 'u',
        'Ų' => 'U',
        'ų' => 'u',
        'Ŵ' => 'W',
        'ŵ' => 'w',
        'Ŷ' => 'Y',
        'ŷ' => 'y',
        'Ÿ' => 'Y',
        'Ź' => 'Z',
        'ź' => 'z',
        'Ż' => 'Z',
        'ż' => 'z',
        'Ž' => 'Z',
        'ž' => 'z',
        'ſ' => 's',
        // Decompositions for Latin Extended-B
        'Ș' => 'S',
        'ș' => 's',
        'Ț' => 'T',
        'ț' => 't',
        // Euro Sign
        '€' => 'E',
        // GBP (Pound) Sign
        '£' => '',
        // Vowels with diacritic (Vietnamese)
        // unmarked
        'Ơ' => 'O',
        'ơ' => 'o',
        'Ư' => 'U',
        'ư' => 'u',
        // grave accent
        'Ầ' => 'A',
        'ầ' => 'a',
        'Ằ' => 'A',
        'ằ' => 'a',
        'Ề' => 'E',
        'ề' => 'e',
        'Ồ' => 'O',
        'ồ' => 'o',
        'Ờ' => 'O',
        'ờ' => 'o',
        'Ừ' => 'U',
        'ừ' => 'u',
        'Ỳ' => 'Y',
        'ỳ' => 'y',
        // hook
        'Ả' => 'A',
        'ả' => 'a',
        'Ẩ' => 'A',
        'ẩ' => 'a',
        'Ẳ' => 'A',
        'ẳ' => 'a',
        'Ẻ' => 'E',
        'ẻ' => 'e',
        'Ể' => 'E',
        'ể' => 'e',
        'Ỉ' => 'I',
        'ỉ' => 'i',
        'Ỏ' => 'O',
        'ỏ' => 'o',
        'Ổ' => 'O',
        'ổ' => 'o',
        'Ở' => 'O',
        'ở' => 'o',
        'Ủ' => 'U',
        'ủ' => 'u',
        'Ử' => 'U',
        'ử' => 'u',
        'Ỷ' => 'Y',
        'ỷ' => 'y',
        // tilde
        'Ẫ' => 'A',
        'ẫ' => 'a',
        'Ẵ' => 'A',
        'ẵ' => 'a',
        'Ẽ' => 'E',
        'ẽ' => 'e',
        'Ễ' => 'E',
        'ễ' => 'e',
        'Ỗ' => 'O',
        'ỗ' => 'o',
        'Ỡ' => 'O',
        'ỡ' => 'o',
        'Ữ' => 'U',
        'ữ' => 'u',
        'Ỹ' => 'Y',
        'ỹ' => 'y',
        // acute accent
        'Ấ' => 'A',
        'ấ' => 'a',
        'Ắ' => 'A',
        'ắ' => 'a',
        'Ế' => 'E',
        'ế' => 'e',
        'Ố' => 'O',
        'ố' => 'o',
        'Ớ' => 'O',
        'ớ' => 'o',
        'Ứ' => 'U',
        'ứ' => 'u',
        // dot below
        'Ạ' => 'A',
        'ạ' => 'a',
        'Ậ' => 'A',
        'ậ' => 'a',
        'Ặ' => 'A',
        'ặ' => 'a',
        'Ẹ' => 'E',
        'ẹ' => 'e',
        'Ệ' => 'E',
        'ệ' => 'e',
        'Ị' => 'I',
        'ị' => 'i',
        'Ọ' => 'O',
        'ọ' => 'o',
        'Ộ' => 'O',
        'ộ' => 'o',
        'Ợ' => 'O',
        'ợ' => 'o',
        'Ụ' => 'U',
        'ụ' => 'u',
        'Ự' => 'U',
        'ự' => 'u',
        'Ỵ' => 'Y',
        'ỵ' => 'y',
        // Vowels with diacritic (Chinese, Hanyu Pinyin)
        'ɑ' => 'a',
        // macron
        'Ǖ' => 'U',
        'ǖ' => 'u',
        // acute accent
        'Ǘ' => 'U',
        'ǘ' => 'u',
        // caron
        'Ǎ' => 'A',
        'ǎ' => 'a',
        'Ǐ' => 'I',
        'ǐ' => 'i',
        'Ǒ' => 'O',
        'ǒ' => 'o',
        'Ǔ' => 'U',
        'ǔ' => 'u',
        'Ǚ' => 'U',
        'ǚ' => 'u',
        // grave accent
        'Ǜ' => 'U',
        'ǜ' => 'u',
    ];
    // Used for locale-specific rules
    if ('de_DE' == $locale || 'de_DE_formal' == $locale || 'de_CH' == $locale || 'de_CH_informal' == $locale) {
        $chars['Ä'] = 'Ae';
        $chars['ä'] = 'ae';
        $chars['Ö'] = 'Oe';
        $chars['ö'] = 'oe';
        $chars['Ü'] = 'Ue';
        $chars['ü'] = 'ue';
        $chars['ß'] = 'ss';
    } elseif ('da_DK' === $locale) {
        $chars['Æ'] = 'Ae';
        $chars['æ'] = 'ae';
        $chars['Ø'] = 'Oe';
        $chars['ø'] = 'oe';
        $chars['Å'] = 'Aa';
        $chars['å'] = 'aa';
    } elseif ('ca' === $locale) {
        $chars['l·l'] = 'll';
    } elseif ('sr_RS' === $locale || 'bs_BA' === $locale) {
        $chars['Đ'] = 'DJ';
        $chars['đ'] = 'dj';
    }
    $string = strtr($string, $chars);
    return $string;
}