<?php
require_once "/home2/elosed78/public_html/config/config.dw.php";
//DB::debugMode();

$log->LogInfo('-----------------------------------------------');
$log->LogInfo('Início sincronização');

$CURSO = (!$CURSO) ? $_GET['cid'] : false;
$forcar_aluno = (!$forcar_aluno) ? $_GET['aid'] : false;

switch (true) {
    case (!$_GET['syncCurso'] && !$_GET['syncConteudo'] && !$_GET['syncGrupos'] && !$_GET['syncTurmas'] && !$_GET['syncAluno'] && !$_GET['syncTarefas'] && !$_GET['syncAlunosRemovidos']):
        $syncCurso = $syncConteudo = $syncGrupos = $syncTurmas = $syncAluno = $syncTarefas = $syncAlunosRemovidos = true;
        break;
    case ($_GET['syncCurso']):
        $syncConteudo = $syncGrupos = $syncTurmas = $syncAluno = $syncTarefas = false;
        $syncCurso = $_GET['syncCurso'];
        break;
    case ($_GET['syncConteudo']):
        $syncCurso = $syncGrupos = $syncTurmas = $syncAluno = $syncTarefas = false;
        $syncConteudo = $_GET['syncConteudo'];
        break;
    case ($_GET['syncGrupos']):
        $syncCurso = $syncConteudo = $syncTurmas = $syncAluno = $syncTarefas = false;
        $syncGrupos = $_GET['syncGrupos'];
        break;
    case ($_GET['syncTurmas']):
        $syncCurso = $syncConteudo = $syncGrupos = $syncAluno = $syncTarefas = false;
        $syncTurmas = $_GET['syncTurmas'];
        break;
    case ($_GET['syncAluno']):
        $syncCurso = $syncConteudo = $syncGrupos = $syncTurmas = $syncTarefas = false;
        $syncAluno = $_GET['syncAluno'];
        break;
    case ($_GET['syncTarefas']):
        $syncCurso = $syncConteudo = $syncGrupos = $syncTurmas = $syncAluno = false;
        $syncTarefas = $_GET['syncTarefas'];
        break;
    default:
}

$forcarSincronismoDeTudoBool = (!$forcarSincronismoDeTudoBool) ? $_GET['force'] : false;
$cursos = $dw->pegaCursos($CURSO, $forcarSincronismoDeTudoBool);

//(count($cursos) > 0) ? $pg = new PHPTerminalProgressBar(count($cursos)) : false;

foreach ($cursos as $curso) {

    ($syncCurso) ? $dw->syncCurso($curso) : false;
    ($syncConteudo) ? $dw->syncConteudo($curso) : false;
    ($syncTurmas) ? $dw->syncTurmas($curso) : false;

    $dadosCursoBanco = DB::queryFirstRow("SELECT * FROM `cursos` WHERE id = '" . $curso->id . "'");

    if ($syncAluno or $syncTarefas) {

        $alunos = $dw->pegaAlunos($curso);

        foreach ($alunos as $aluno) {

            if ($dw->checkDone($aluno, $curso)) {
                // die;
                continue;
            }

            if (isset($forcar_aluno) && $forcar_aluno != $aluno->user_id) {
                continue;
            } elseif ($forcar_aluno) {
                $log->LogInfo("Forcando sincronizacao do aluno: [$aluno->user_id] " . $aluno->user->name);
            }

            ($syncAluno) ? $dw->syncAluno($aluno, $curso) : false;
            ($syncTarefas) ? $dw->syncTarefas($aluno, $curso, $dadosCursoBanco, $forcarSincronismoDeTudoBool) : false;
        }

        ($syncAlunosRemovidos or isset($forcar_aluno)) ? $dw->syncAlunosRemovidos($alunos, $curso) : false;
    }

    ($syncGrupos) ? $dw->syncGrupos($curso) : false;

    // $pg->tick();
    $dw->setDone($curso);
}

(count($cursos) == 0) ? " concluido!" . count($cursos) : false;
//(count($cursos) > 0) ? $pg->update(count($cursos)) : false;
$log->LogInfo('Sincronizacao finalizada!');
$log->LogInfo('-----------------------------------------------');