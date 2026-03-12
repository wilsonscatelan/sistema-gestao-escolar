<?php
require_once __DIR__ . '/../models/Turma.php';
require_once __DIR__ . '/../models/Matricula.php';
require_once __DIR__ . '/../vendor/autoload.php';


use Dompdf\Dompdf;


class ReportController {
public function alunos_por_turma_pdf($id_turma) {
$turma = Turma::find($id_turma);
$matriculas = Matricula::allByTurma($id_turma);


ob_start();
include __DIR__ . '/../views/reports/alunos_por_turma.php';
$html = ob_get_clean();


$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('alunos_turma_' . preg_replace('/[^A-Za-z0-9]/','_', $turma['nome_turma']) . '.pdf', ['Attachment' => 0]);
}
}