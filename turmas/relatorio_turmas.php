<?php
require_once '../vendor/autoload.php';
require_once '../config/conexao.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Buscar turmas + total de alunos
$sql = "SELECT 
            t.id_turma,
            t.nome_turma,
            t.descricao,
            COUNT(m.id_aluno_fk) AS total_alunos
        FROM turmas t
        LEFT JOIN matriculas m ON t.id_turma = m.id_turma_fk
        GROUP BY t.id_turma
        ORDER BY t.nome_turma ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DomPDF
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

// HTML
$html = '
<h2 style="text-align:center;">Relatório de Turmas</h2>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color:#eeeeee;">
            <th>Nome da Turma</th>
            <th>Descrição</th>
            <th>Total de Alunos</th>
        </tr>
    </thead>
    <tbody>';

foreach ($turmas as $t) {
    $html .= "
        <tr>
            <td>{$t['nome_turma']}</td>
            <td>{$t['descricao']}</td>
            <td>{$t['total_alunos']}</td>
        </tr>
    ";
}

$html .= "</tbody></table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("relatorio_turmas.pdf", ["Attachment" => true]);
exit;
?>
