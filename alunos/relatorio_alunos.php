<?php
require_once '../vendor/autoload.php';
require_once '../config/conexao.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Buscar alunos
$sql = "
    SELECT 
        a.id_aluno,
        a.nome_aluno,
        a.data_nascimento,
        a.cpf,
        a.telefone,
        COALESCE(u.email, 'Sem e-mail') AS email,
        string_agg(t.nome_turma, ', ') AS cursos
    FROM alunos a
    LEFT JOIN usuarios u 
        ON a.id_aluno = u.id_referencia 
       AND u.tipo_usuario = 'aluno'
    LEFT JOIN matriculas m 
        ON a.id_aluno = m.id_aluno_fk
    LEFT JOIN turmas t 
        ON m.id_turma_fk = t.id_turma
    GROUP BY a.id_aluno, u.email
    ORDER BY a.nome_aluno ASC
";


$stmt = $pdo->prepare($sql);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Configurações do DomPDF
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

// HTML do relatório
$html = '
<h2 style="text-align:center;">Relatório de Alunos</h2>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color:#eeeeee;">
            <th>Nome</th>
            <th>Email</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Data Nasc.</th>
            <th>Cursos</th>
        </tr>
    </thead>
    <tbody>';

foreach ($alunos as $a) {
    $html .= "
        <tr>
            <td>{$a['nome_aluno']}</td>
            <td>{$a['email']}</td>
            <td>{$a['cpf']}</td>
            <td>{$a['telefone']}</td>
            <td>{$a['data_nascimento']}</td>
            <td>{$a['cursos']}</td>
        </tr>
    ";
}

$html .= '
    </tbody>
</table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait'); // A4 em pé
$dompdf->render();

// Baixar o PDF
$dompdf->stream("relatorio_alunos.pdf", ["Attachment" => true]);
exit;
?>
