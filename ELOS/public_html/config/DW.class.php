<?php
class DW
{
    const CONTAS = array(6, 47, 44, 9, 21, 19, 46, 48, 42, 43);
    const SLA = 7;
    //const CONTAS = array(6, 9, 21, 19, 46, 42);

    /**
     * Pega os cursos do Canvas 
     * 
     */
    public static function pegaCursos($CURSO, $forcarSincronismoDeTudoBool, $logger = true)
    {
        global $api;
        global $log;
        ($logger) ? $log->LogInfo('Pegando lista de cursos validos... ') : false;

        $courses = DB::query("SELECT DISTINCT id FROM `cursos` where dt_importacao = '" . date("dmY") . "'");
        foreach ($courses as $curso) {
            $cursos_done[] = $curso['id'];
        }

        $return = [];
        foreach (self::CONTAS as $conta) {

            $cursos = $api->getlist("/api/v1/accounts/$conta/courses?enrollment_type[]=student&published=true&blueprint=false&", 'id', '', -1);
            // $cursos = $api->getlist("/api/v1/accounts/$conta/courses?", 'id', '', -1);
            foreach ($cursos as $curso) {

                $curso_procurado = ($CURSO == $curso->id);
                if ($CURSO && !$curso_procurado) {
                    continue;
                }
                ($CURSO && $curso_procurado) ? $log->LogInfo('Processando manualmente o curso: "' . $curso->id . '"') : false;

                $curso_disponivel = ($curso->workflow_state == 'available');
                $end_at_null = (is_nan($curso->end_at));
                $curso_end_dt_ok = (strtotime(date('Y-m-d', strtotime($curso->end_at))) >= strtotime(date('Y-m-d')));
                $curso_feito = (in_array($curso->id, $cursos_done));

                if ($forcarSincronismoDeTudoBool) {
                    $curso_valido = ($curso_disponivel and !$end_at_null);
                } else {
                    $curso_valido = ($curso_disponivel and !$end_at_null and $curso_end_dt_ok and !$curso_feito);
                }

                if ($curso_valido) {
                    $return[] = $curso;
                } else {
                    ($curso_feito && $logger) ? $log->LogInfo('Curso ja sincronizado hoje: "' . $curso->id . '"') : false;
                }
            }
        }
        return $return;
    }

    public static function syncCurso($curso)
    {
        global $log;
        $log->LogInfo('[CURSO ' . $curso->id . '] Sincronizando "' . $curso->name . '"');

        DB::insertUpdate('cursos', array(
            'id' => $curso->id,
            'name' => convert_accent_characters($curso->name),
            'course_code' => convert_accent_characters($curso->course_code),
            'created_at' => $curso->created_at,
            'start_at' => $curso->start_at,
            'end_at' => $curso->end_at,
            'workflow_state' => $curso->workflow_state,
            // 'dt_importacao' => 'SYNC',
            // 'dt_importacao' => date('dmY'),
        ));
    }

    public static function syncConteudo($curso)
    {
        global $api;
        global $log;
        $DTagora = new DateTime();
        $carga_horaria_parcial_total = null;
        $carga_horaria_total = null;

        $log->LogInfo('[CURSO ' . $curso->id . '] Sincronizando conteudo do curso...');

        $conteudo = $api->getlist("/api/v1/courses/" . $curso->id . "/modules?include[]=items&include[]=content_details&", 'id', '', -1);

        DB::delete('conteudo', "course_id=%s", $curso->id);

        foreach ($conteudo as $modulo) {
            foreach ($modulo->items as $item) {
                $atividade_esta_vencida = 0;
                $despublicado = ($item->published != "1");
                $e_titulo = ($item->type == "SubHeader");
                $e_pagina = ($item->type == "Page");
                $e_tarefa = ($item->type == "Assignment");
                $e_teste = ($item->type == "Quiz");
                $coluna_nao_valida = ($despublicado or $e_titulo or $e_pagina);
                if ($coluna_nao_valida) {
                    continue;
                }

                if ($e_tarefa) {
                    $submission_types = $api->getdata("/api/v1/courses/" . $curso->id . "/assignments/" . $item->content_id, 'submission_types', '', -1);
                    $item->submission_types = $submission_types['0'];
                }

                $DT_vencimento = new DateTime(date('Y-m-d', strtotime($item->content_details->due_at)));
                $diff_DT_vencimento_hoje = $DT_vencimento->diff($DTagora)->format('%r%a');
                (!isset($item->content_details->due_at)) ? $diff_DT_vencimento_hoje = 0 : false;

                $atividade_vencida = ($diff_DT_vencimento_hoje >= 0);
                if ($atividade_vencida and $item->content_details->due_at) {
                    $carga_horaria_parcial_total = $carga_horaria_parcial_total + $item->content_details->points_possible;
                    $atividade_esta_vencida = 1;
                }

                DB::insertUpdate('conteudo', array(
                    'id' => $item->id,
                    'course_id' => $curso->id,
                    'title' => convert_accent_characters($item->title),
                    'type' => $item->type,
                    'position' => $item->position,
                    'module_id' => $item->module_id,
                    'content_id' => $item->content_id,
                    'published' => $item->published,
                    'due_at' => $item->content_details->due_at,
                    'unlock_at' => $item->content_details->unlock_at,
                    'lock_at' => $item->content_details->lock_at,
                    'points_possible' => $item->content_details->points_possible,
                    'html_url' => $item->html_url,
                    'submission_types' => $item->submission_types,
                    'atividade_vencida' => $atividade_esta_vencida,
                ));

                $carga_horaria_total = $carga_horaria_total + $item->content_details->points_possible;
            }
        }

        DB::update('cursos', array(
            'carga_horaria_total' => $carga_horaria_total,
            'carga_horaria_parcial_total' => $carga_horaria_parcial_total
        ), "id=%s", $curso->id);

        $log->LogInfo('[CURSO ' . $curso->id . '] Conteudo sincronizado!');

        return true;
    }

    public static function syncGrupos($curso)
    {
        global $api;
        global $log;

        $log->LogInfo('[CURSO ' . $curso->id . '] Sincronizando Grupos ...');

        $grupos = $api->getlist("/api/v1/courses/" . $curso->id . "/groups?", 'id', '', -1);

        foreach ($grupos as $grupo) {
            $group_status = '';
            $max_carga_h_parcial = 0;
            $max_carga_h_cumprida = 0;

            $cursistas = DB::query("SELECT * FROM `matricula` WHERE `grupo` = '$grupo->id' ORDER BY `enrollment_state` DESC");
            foreach ($cursistas as $key => $cursista) {

                switch ($cursista['enrollment_state']) {
                    case 'active':
                        $group_status = 'active';
                        break;
                    case 'inactive':
                        ($group_status != 'active') ? $group_status = 'inactive' : false;
                        break;
                    case 'invited':
                        ($group_status != 'active') ? $group_status = 'inactive' : false;
                        break;
                    case 'desistente':
                        ($group_status != 'active') ? $group_status = 'desistente' : false;
                        break;
                    default:
                        $group_status = $cursista['enrollment_state'];
                        mostra($cursista);
                        break;
                }

                $max_carga_h_parcial = ($cursista['carga_h_parcial_p'] > $max_carga_h_parcial) ? $cursista['carga_h_parcial_p'] : $max_carga_h_parcial;

                $max_carga_h_cumprida = ($cursista['carga_h_cumprida_p'] > $max_carga_h_cumprida) ? $cursista['carga_h_cumprida_p'] : $max_carga_h_cumprida;
            }

            DB::insertUpdate('grupos', array(
                'id' => $grupo->id,
                'name' => convert_accent_characters($grupo->name),
                'group_category_id' => $grupo->group_category_id,
                'group_status' => $group_status,
                'group_carga_h_parcial' => $max_carga_h_parcial,
                'group_carga_h' => $max_carga_h_cumprida,
                'members_count' => $grupo->members_count,
                'course_id' => $grupo->course_id
            ));
        }

        return true;
    }

    public static function syncTurmas($curso)
    {
        global $api;
        global $log;
        $log->LogInfo('[CURSO ' . $curso->id . '] Sincronizando Turmas ...');

        $pegaturma = $api->getlist("/api/v1/courses/" . $curso->id . "/sections?include[]=students&", 'id', '', -1);
        foreach ($pegaturma as $turma) {
            foreach ($turma->students as $students) {
                DB::update('matricula', array(
                    'turma' => convert_accent_characters($turma->name)
                ), "id_cursista=%s", $students->id);
            }
        }
        return true;
    }

    public static function pegaAlunos($curso)
    {
        global $api;
        global $log;

        $log->LogInfo('[CURSO ' . $curso->id . '] Processando Alunos...');

        $return = $api->getlist("/api/v1/courses/" . $curso->id . "/enrollments?include[]=group_ids&include[]=enrollments&type[]=StudentEnrollment&", 'id', '', -1);

        $log->LogInfo('[CURSO ' . $curso->id . '] Alunos coletados. ');

        return $return;
    }

    public static function syncAluno($aluno, $curso)
    {
        global $log;

        DB::insertUpdate('cursista', array(
            'id' => $aluno->user_id,
            'nome' => convert_accent_characters($aluno->user->name),
            'email' => $aluno->user->login_id,
            'enrollment_state' => $aluno->enrollment_state,
            'dt_importacao' => date('dmY'),
        ));

        DB::insertUpdate('matricula', array(
            'id' => $aluno->user_id . $aluno->course_id,
            'id_cursista' => $aluno->user_id,
            'id_course' => $aluno->course_id,
            'type' => $aluno->type,
            'enrollment_state' => $aluno->enrollment_state,
            'etapa' => convert_accent_characters($curso->course_code),
            'grupo' => $aluno->user->group_ids['0'],
            'dt_importacao' => date('dmY'),
        ));

        $log->LogInfo('[CURSO ' . $curso->id . "] [ALUNO $aluno->user_id] " . convert_accent_characters($aluno->user->name) . " OK.");

        return true;
    }

    public static function syncAlunosRemovidos($alunos, $curso)
    {
        global $log;

        $dadosAlunosBanco = DB::query("SELECT * FROM `matricula` where `id_course` = $curso->id");
        foreach ($dadosAlunosBanco as $aluno) {
            $dadosAlunosBanco_array[] = $aluno['id_cursista'];
        }

        foreach ($alunos as $aluno) {
            $alunos_id_array[] = $aluno->user_id;
        }

        foreach ($dadosAlunosBanco_array as $aluno_id) {

            $aluno_n_matriculado = (!in_array($aluno_id, $alunos_id_array));

            if ($aluno_n_matriculado) {

                DB::delete('matricula', "id_cursista=%s and id_course=%s", $aluno_id, $curso->id);
                DB::delete('tarefas', "user_id=%s and course_id=%s", $aluno_id, $curso->id);

                $log->LogInfo("[CURSO $curso->id] [ALUNO $aluno_id] removido do curso. ");
            }
        }

        return true;
    }

    public static function checkDone($aluno, $curso)
    {

        global $api;
        global $log;

        $return = DB::query("SELECT DISTINCT dt_execucao FROM `matricula` where 
            `id_cursista` = $aluno->user_id and 
            `id_course` = $curso->id");
        $last_execution = $return[0]['dt_execucao'];

        if ($last_execution == date('dmY')) {
            return true;
            $log->LogInfo("[CURSO $curso->id] [ALUNO $aluno->user_id] Ja processado hoje... pulando!");
        } else {
            return false;
        }
    }

    public static function syncTarefas($aluno, $curso, $curso_db, $forcarSincronismoDeTudoBool)
    {
        global $api;
        global $log;
        $DTagora = new DateTime();
        $carga_h_cumprida_cursista = 0;
        $carga_h_cumprida_parcial = 0;
        $carga_h_cumprida_p = 0;
        $carga_h_curso = 0;
        unset($nomeProfessor);

        // if ($forcarSincronismoDeTudoBool) {
        //     $log->LogInfo('[CURSO ' . $curso->id . '] [ALUNO ' . $aluno->user_id . '] Processando Tarefas...');
        // }

        $tarefas_db = DB::query("SELECT DISTINCT assignment_id FROM `tarefas` where `dt_execucao` = '" . date("dmY") . "' and `course_id` = $curso->id and `user_id` = $aluno->user_id");

        foreach ($tarefas_db as $i) {
            //$log->LogInfo("[CURSO ' . $curso->id . '] [ALUNO ' . $aluno->user_id . '] [ TAREFA $tarefa->assignment_id] Pulando tarefa...");
            $tarefas_done[] = $i['assignment_id'];
        }

        $tarefas = $api->getlist("/api/v1/courses/" . $curso->id . "/analytics/users/" . $aluno->user_id . "/assignments?", 'assignment_id', '', -1);

        foreach ($tarefas as $tarefa) {

            // $tarefaJaSincronizada = (in_array($tarefa->assignment_id, $tarefas_done));
            // //if ($tarefaJaSincronizada && !$forcarSincronismoDeTudoBool) {
            // if ($tarefaJaSincronizada) {
            //     // $log->LogInfo("[CURSO $curso->id] [ALUNO $aluno->user_id] [ TAREFA $tarefa->assignment_id] Pulando tarefa...");
            //     continue;
            // }

            $id = $tarefa->assignment_id . $aluno->user_id;
            $nota = $api->getlist("/api/v1/courses/" . $curso->id . "/gradebook_history/feed?assignment_id=" . $tarefa->assignment_id . "&user_id=" . $aluno->user_id . "&", 'assignment_id', '', -1);

            $tem_formador = (isset($nota[$tarefa->assignment_id]->current_grader) and $nota[$tarefa->assignment_id]->current_grader != "Avaliado no envio" and $nota[$tarefa->assignment_id]->current_grader != "Bruno de Jesus");
            if ($tem_formador) {
                $nomeProfessor = $nota[$tarefa->assignment_id]->current_grader;
            }

            $DT_vencimento = new DateTime(date('Y-m-d', strtotime($tarefa->due_at)));
            $diff_DT_vencimento_hoje = $DT_vencimento->diff($DTagora)->format('%r%a');
            (!isset($tarefa->due_at)) ? $diff_DT_vencimento_hoje = 0 : false;

            $atividade_vencida = ($diff_DT_vencimento_hoje > 0);
            if ($atividade_vencida) {
                $carga_h_cumprida_parcial = $carga_h_cumprida_parcial + $tarefa->submission->score;
            }

            $carga_h_cumprida_cursista = $carga_h_cumprida_cursista + $tarefa->submission->score;
            $carga_h_curso = $carga_h_curso + $tarefa->max_score;


            unset($intervalo);
            $formador_atrasado = false;
            $envio_str_dt = strtotime($tarefa->submission->submitted_at);
            $resposta_str_dt = strtotime($nota[$tarefa->assignment_id]->current_graded_at);
            $intervalo = (round(($resposta_str_dt - $envio_str_dt) / 60 / 60 / 24));
            if ($intervalo > self::SLA) {
                $formador_atrasado = true;
            }

            DB::insertUpdate('tarefas', array(
                'id' => $id,
                'assignment_id' => $tarefa->assignment_id,
                'course_id' => $curso->id,
                'user_id' => $aluno->user_id,
                'title' => convert_accent_characters($tarefa->title),
                'points_possible' => $tarefa->points_possible,
                'due_at' => $tarefa->due_at,
                'status' => $tarefa->status,
                'max_score' => $tarefa->max_score,
                'min_score' => $tarefa->min_score,
                'score' => $tarefa->submission->score,
                'submitted_at' => $tarefa->submission->submitted_at,
                'current_grader' => convert_accent_characters($nota[$tarefa->assignment_id]->current_grader),
                'intervalo_resposta' => $intervalo,
                'formador_atrasado' => $formador_atrasado,
                'grader_id' => $nota[$tarefa->assignment_id]->grader_id,
                'nota_submitted_at' => $nota[$tarefa->assignment_id]->submitted_at,
                'graded_at' => $nota[$tarefa->assignment_id]->graded_at,
                'current_graded_at' => $nota[$tarefa->assignment_id]->current_graded_at,
                'dt_execucao' => date('dmY'),
            ));
        }

        DB::update('matricula', array(
            'formador' => $nomeProfessor,
        ), "id_cursista=%s", $aluno->user_id);

        @$_carga_h_parcial = round(($carga_h_cumprida_parcial / $curso_db['carga_horaria_parcial_total']) * 100);
        if (is_nan($_carga_h_parcial)) {
            $_carga_h_parcial = 0;
        }

        @$carga_h_cumprida_p = round(($carga_h_cumprida_cursista / $curso_db['carga_horaria_total']) * 100);
        if (is_nan($carga_h_cumprida_p)) {
            $carga_h_cumprida_p = 0;
        }

        DB::update('matricula', array(
            'carga_h_parcial' => $carga_h_cumprida_parcial,
            'carga_h_parcial_p' => $_carga_h_parcial,
            'carga_h_cumprida' => $carga_h_cumprida_cursista,
            'carga_h_cumprida_p' => $carga_h_cumprida_p,
            'dt_execucao' => date('dmY'),
        ), "id_cursista=%s", $aluno->user_id);

        DB::update('cursista', array(
            'tarefa_ok' => date('dmY'),
        ), "id=%s", $aluno->user_id);

        return true;
    }

    public static function setDone($curso)
    {

        DB::update('cursos', array(
            'dt_importacao' => date('dmY'),
        ), "id=%s", $curso->id);

        return true;
    }

    public static function getR3(string $formador, string $etp, int $start = null, int $end = null, string $group_status = null)
    {
        $return = [];
        if (!isset($formador) or !isset($etp)) {
            return false;
        }

        $find_in_range = (isset($start) and isset($end));
        $find_in_start = (isset($start) and !isset($end));
        $find_actives = (!isset($start) and !isset($end) and !isset($group_status));
        $find_by_group_status = (isset($group_status));
        // $find_by_carga_horaria = (isset($group_status));

        if ($find_in_range) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                formador = '$formador' and etapa = '$etp' and `group_status` = 'active' GROUP BY grupo 
                HAVING MAX(`group_carga_h`) BETWEEN $start AND $end order by max desc");
        } elseif ($find_in_start) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                formador = '$formador' and etapa = '$etp' and `group_status` = 'active'and
                (`group_carga_h` = 0 or `group_carga_h` IS NULL) GROUP BY grupo order by max desc");
        } elseif ($find_actives) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                formador = '$formador' and etapa = '$etp' and `group_status` = 'active' GROUP BY grupo order by max desc");
        } elseif ($find_by_group_status) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                formador = '$formador' and etapa = '$etp' and `group_status` = '$group_status' GROUP BY grupo order by max desc");
        }

        if (count($return) > 0) {
            $title = "Escolas:&#10;";
            foreach ($return as $i) {
                $title .= "(" . $i['MAX'] . "%) " . $i['grupo'] . "&#10;";
            }
        }

        return array(
            'count' => count($return),
            'title' => $title
        );
    }

    public static function getR4(string $escola, string $etp, int $start = null, int $end = null, string $group_status = null)
    {
        $return = [];
        if (!isset($escola) or !isset($etp)) {
            return false;
        }

        $find_in_range = (isset($start) and isset($end));
        $find_in_start = (isset($start) and !isset($end));
        $find_actives = (!isset($start) and !isset($end) and !isset($group_status));
        $find_by_group_status = (isset($group_status));
        // $find_by_carga_horaria = (isset($group_status));

        if ($find_in_range) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                grupo = '$escola' and etapa = '$etp' and `group_status` = 'active' GROUP BY grupo 
                HAVING MAX(`group_carga_h`) BETWEEN $start AND $end order by max desc");
        } elseif ($find_in_start) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                grupo = '$escola' and etapa = '$etp' and `group_status` = 'active'and
                (`group_carga_h` = 0 or `group_carga_h` IS NULL) GROUP BY grupo order by max desc");
        } elseif ($find_actives) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                grupo = '$escola' and etapa = '$etp' and `group_status` = 'active' GROUP BY grupo order by max desc");
        } elseif ($find_by_group_status) {
            $return = DB::query("SELECT grupo, max(`group_carga_h`) as MAX FROM `database` WHERE 
                grupo = '$escola' and etapa = '$etp' and `group_status` = '$group_status' GROUP BY grupo order by max desc");
        }

        if (count($return) > 0) {
            $title = "Escolas:&#10;";
            foreach ($return as $i) {
                $title .= "(" . $i['MAX'] . "%) " . $i['grupo'] . "&#10;";
            }
        }

        return array(
            'count' => count($return),
            'title' => $title
        );
    }
}

// function authenticate() {
//     try {
//         switch(true) {
//             case array_key_exists('HTTP_AUTHORIZATION', $_SERVER) :
//                 $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
//                 break;
//             case array_key_exists('Authorization', $_SERVER) :
//                 $authHeader = $_SERVER['Authorization'];
//                 break;
//             default :
//                 $authHeader = null;
//                 break;
//         }
//         preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
//         if(!isset($matches[1])) {
//             throw new \Exception('No Bearer Token');
//         }
//         $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
//             ->setIssuer(getenv('OKTAISSUER'))
//             ->setAudience('api://default')
//             ->setClientId(getenv('OKTACLIENTID'))
//             ->build();
//         return $jwtVerifier->verify($matches[1]);
//     } catch (\Exception $e) {
//         return false;
//     }
// }