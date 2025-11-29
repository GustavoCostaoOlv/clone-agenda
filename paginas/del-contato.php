<?php
// 🎯 DELEÇÃO DE CURSOS - del-contato.php
error_log("=== 🚨 del-contato.php INICIADO ===");

include_once('../config/conexao.php');

session_start();

// Verificar se é CURSO
if(isset($_GET['idDel']) && isset($_GET['tipo']) && $_GET['tipo'] == 'curso'){
    $id_curso = $_GET['idDel'];
    error_log("🔧 DELETANDO CURSO ID: " . $id_curso);

    try {
        // 1. Buscar dados do curso
        $select = "SELECT nome_curso, imagem_curso FROM tb_cursos WHERE id_curso = :id";
        $result = $conect->prepare($select);
        $result->bindValue(':id', $id_curso, PDO::PARAM_INT);
        $result->execute();

        if ($result->rowCount() > 0) {
            $curso = $result->fetch(PDO::FETCH_ASSOC);
            $nome_curso = $curso['nome_curso'];
            $imagem_curso = $curso['imagem_curso'];
            
            error_log("✅ Curso encontrado: " . $nome_curso);

            // 2. Deletar imagem se não for padrão
            if ($imagem_curso != 'curso-padrao.jpg' && !empty($imagem_curso)) {
                $filePath = "../../img/cursos/" . $imagem_curso;
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        error_log("✅ Imagem deletada: " . $imagem_curso);
                    } else {
                        error_log("⚠️ Não conseguiu deletar imagem: " . $imagem_curso);
                    }
                } else {
                    error_log("⚠️ Arquivo de imagem não encontrado: " . $filePath);
                }
            }

            // 3. Deletar matrículas primeiro
            try {
                $delete_matriculas = "DELETE FROM tb_matriculas WHERE id_curso = :id";
                $result_matriculas = $conect->prepare($delete_matriculas);
                $result_matriculas->bindValue(':id', $id_curso, PDO::PARAM_INT);
                $result_matriculas->execute();
                error_log("✅ Matrículas deletadas");
            } catch (PDOException $e) {
                error_log("⚠️ Aviso matrículas: " . $e->getMessage());
            }

            // 4. Deletar curso
            $delete = "DELETE FROM tb_cursos WHERE id_curso = :id";
            $result = $conect->prepare($delete);
            $result->bindValue(':id', $id_curso, PDO::PARAM_INT);
            
            if ($result->execute()) {
                $_SESSION['mensagem'] = "Curso '" . $nome_curso . "' deletado com sucesso!";
                $_SESSION['tipo_mensagem'] = "success";
                error_log("✅ CURSO DELETADO: " . $id_curso);
            } else {
                $_SESSION['mensagem'] = "Erro ao deletar curso.";
                $_SESSION['tipo_mensagem'] = "error";
                error_log("❌ Erro ao executar DELETE");
            }
            
        } else {
            $_SESSION['mensagem'] = "Curso não encontrado.";
            $_SESSION['tipo_mensagem'] = "warning";
            error_log("❌ Curso não encontrado ID: " . $id_curso);
        }
        
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Erro ao deletar curso: " . $e->getMessage();
        $_SESSION['tipo_mensagem'] = "error";
        error_log("❌ ERRO PDO: " . $e->getMessage());
    }

       // Redirecionar para home.php COM mensagem
    $_SESSION['debug_time'] = time(); // Forçar recarregamento
    header("Location: home.php?deleted=" . $id_curso);
    exit();
    
} else {
    // Se não é curso, redirecionar normalmente (comportamento original)
    header("Location: home.php");
    exit();
}