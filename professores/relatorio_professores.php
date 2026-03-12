<?php
require_once '../vendor/autoload.php';
require_once '../config/conexao.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Buscar professores
$sql = "SELECT id_professor, nome, email, telefone, foto_professor FROM professores";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$professores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$options = new Options();
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);

// HTML
$html = '
<h2 style="text-align:center;">Relatório de Professores</h2>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color:#eeeeee;">
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
        </tr>
    </thead>
    <tbody>';
    
foreach ($professores as $p) {
    $html .= "
        <tr>
            <td>{$p['nome']}</td>
            <td>{$p['email']}</td>
            <td>{$p['telefone']}</td>
        </tr>";
}

$html .= "</tbody></table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_professores.pdf", ["Attachment" => true]);
exit;
