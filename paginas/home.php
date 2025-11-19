<?php
include_once('../includes/header.php');

// Sanitização de entrada CORRIGIDA
$acao = isset($_GET['acao']) ? htmlspecialchars($_GET['acao']) : 'bemvindo';

// Definir caminhos em variáveis
$paginas = [
    'bemvindo' => 'conteudo/cadastro_contato.php',
    'editar' => 'conteudo/update_contato.php',
    'perfil' => 'conteudo/perfil.php',
    'relatorio' => 'conteudo/relatorio.php'
];

// Verificar se a ação existe no array, caso contrário, usar a página padrão
$pagina_incluir = isset($paginas[$acao]) ? $paginas[$acao] : $paginas['bemvindo'];

// Incluir a página correspondente
include_once($pagina_incluir);

include_once('../includes/footer.php');
?>