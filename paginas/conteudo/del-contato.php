<?php
// 🎯 DEBUG COMPLETO
error_log("=== 🚨 DEBUG DEL-CONTATOS.PHP ===");
error_log("📡 URL: " . ($_SERVER['REQUEST_URI'] ?? 'N/A'));
error_log("📋 GET: " . print_r($_GET, true));
error_log("📍 Script: " . __FILE__);

// Verificar se consegue escrever na tela
echo "🎯 del-contatos.php CARREGADO!<br>";
echo "📋 Parâmetros GET: " . print_r($_GET, true) . "<br>";

// Testar conexão com banco
include_once('../config/conexao.php');
echo "✅ Conexão com banco: OK<br>";

// Testar sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo "✅ Sessão: OK<br>";

// Verificar se é curso
if(isset($_GET['idDel']) && isset($_GET['tipo']) && $_GET['tipo'] == 'curso') {
    echo "🎯 É um CURSO! ID: " . $_GET['idDel'] . "<br>";
    
    // Testar consulta ao banco
    try {
        $select = "SELECT id_curso, nome_curso FROM tb_cursos WHERE id_curso = :id";
        $result = $conect->prepare($select);
        $result->bindValue(':id', $_GET['idDel'], PDO::PARAM_INT);
        $result->execute();
        
        if ($result->rowCount() > 0) {
            $curso = $result->fetch(PDO::FETCH_ASSOC);
            echo "✅ Curso encontrado: " . $curso['nome_curso'] . "<br>";
        } else {
            echo "❌ Curso não encontrado<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Erro banco: " . $e->getMessage() . "<br>";
    }
    
} else {
    echo "❌ Não é um curso ou parâmetros faltando<br>";
}

echo "--- FIM DEBUG ---";
exit();