<?php
session_start();
include_once('../config/conexao.php');

// 🐛 DEBUG - ADICIONE ESTAS 5 LINHAS
error_log("=== DEBUG UPDATE CURSO ===");
error_log("GET: " . print_r($_GET, true));
error_log("POST: " . print_r($_POST, true));
error_log("FILES: " . print_r($_FILES, true));
error_log("SESSION id_user: " . ($_SESSION['senhaUser'] ?? 'NÃO EXISTE'));

// Verificar se o usuário está logado
if (!isset($_SESSION['loginUser'])) {
    header("Location: ../index.php?acao=negado");
    exit;
}

// Verificar se o ID do curso foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id_curso = (int)$_GET['id'];
$id_user = $_SESSION['senhaUser']; // ID do usuário logado

// Buscar informações do curso
$curso = null;
try {
    $select = "SELECT * FROM tb_cursos WHERE id_curso = :id_curso AND id_user = :id_user";
    $result = $conect->prepare($select);
    $result->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $result->execute();
    
    if ($result->rowCount() > 0) {
        $curso = $result->fetch(PDO::FETCH_OBJ);
    } else {
        // Curso não encontrado ou não pertence ao usuário
        header("Location: ../index.php?erro=curso_nao_encontrado");
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro ao buscar curso: " . $e->getMessage());
    header("Location: ../index.php?erro=busca_curso");
    exit;
}

// Processar atualização do curso
if (isset($_POST['botao'])) {
    $nome_curso = trim($_POST['nome'] ?? '');
    $carga_horaria = trim($_POST['carga_horaria'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $nivel = trim($_POST['nivel'] ?? '');
    $preco = floatval($_POST['preco'] ?? 0);
    
    // Validação
    $campos_faltantes = [];
    if (empty($nome_curso)) $campos_faltantes[] = "Nome do Curso";
    if (empty($carga_horaria)) $campos_faltantes[] = "Carga Horária";
    if (empty($categoria)) $campos_faltantes[] = "Categoria";
    if (empty($nivel)) $campos_faltantes[] = "Nível";

    if (!empty($campos_faltantes)) {
        $mensagem_erro = "Preencha os campos: " . implode(", ", $campos_faltantes);
        $_SESSION['mensagem'] = $mensagem_erro;
        $_SESSION['tipo_mensagem'] = "error";
    } else {
        // Upload da imagem (se houver nova)
        $foto_curso = $curso->imagem_curso; // Manter a imagem atual por padrão
        
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $formatosPermitidos = array("png", "jpg", "jpeg", "gif", "webp");
            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if (in_array($extensao, $formatosPermitidos)) {
                $pasta = "../img/cursos/";
                
                if (!is_dir($pasta)) {
                    mkdir($pasta, 0777, true);
                }
                
                $temporario = $_FILES['foto']['tmp_name'];
                $nome_sem_espacos = preg_replace('/[^a-zA-Z0-9]/', '-', $nome_curso);
                $nome_sem_espacos = strtolower($nome_sem_espacos);
                $novoNome = $nome_sem_espacos . '-' . uniqid() . ".$extensao";

                if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                    $foto_curso = $novoNome;
                    
                    // Se tinha imagem antiga e não é a padrão, pode deletar
                    if ($curso->imagem_curso !== 'curso-padrao.jpg' && $curso->imagem_curso !== $novoNome) {
                        $imagem_antiga = $pasta . $curso->imagem_curso;
                        if (file_exists($imagem_antiga)) {
                            unlink($imagem_antiga);
                        }
                    }
                }
            }
        }

        // Atualizar no banco
        try {
            $update = "UPDATE tb_cursos SET 
                      nome_curso = :nome, 
                      carga_horaria = :carga, 
                      categoria = :categoria, 
                      descricao = :descricao, 
                      nivel = :nivel, 
                      preco = :preco, 
                      imagem_curso = :foto 
                      WHERE id_curso = :id_curso AND id_user = :id_user";

            $result = $conect->prepare($update);
            $result->bindParam(':nome', $nome_curso, PDO::PARAM_STR);
            $result->bindParam(':carga', $carga_horaria, PDO::PARAM_STR);
            $result->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $result->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $result->bindParam(':nivel', $nivel, PDO::PARAM_STR);
            $result->bindParam(':preco', $preco);
            $result->bindParam(':foto', $foto_curso, PDO::PARAM_STR);
            $result->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
            $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            
            if ($result->execute()) {
                $_SESSION['mensagem'] = "Curso atualizado com sucesso!";
                $_SESSION['tipo_mensagem'] = "success";
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['mensagem'] = "Erro ao atualizar curso. Tente novamente.";
                $_SESSION['tipo_mensagem'] = "error";
            }
            
        } catch (PDOException $e) {
            error_log("❌ ERRO PDO ao atualizar: " . $e->getMessage());
            $_SESSION['mensagem'] = "Erro no banco de dados: " . $e->getMessage();
            $_SESSION['tipo_mensagem'] = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Curso</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  
  <style>
    body {
      background: linear-gradient(135deg, #4A5D73 0%, #324151 100%);
      min-height: 100vh;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-6xl">
  <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <i class="fas fa-edit text-white text-2xl mr-3"></i>
          <h2 class="text-2xl font-bold text-white">Editar Curso</h2>
        </div>
        <a href="../index.php" class="text-white hover:text-gray-200 transition-colors">
          <i class="fas fa-times text-xl"></i>
        </a>
      </div>
      <p class="text-blue-100 mt-2">Atualize as informações do curso</p>
    </div>

    <form action="" method="post" enctype="multipart/form-data" class="p-8 space-y-6">
      <!-- Nome do Curso -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Nome do Curso</label>
        <input type="text" name="nome" required
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
               value="<?php echo htmlspecialchars($curso->nome_curso); ?>">
      </div>

      <!-- Carga Horária -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Carga Horária</label>
        <input type="text" name="carga_horaria" required
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
               value="<?php echo htmlspecialchars($curso->carga_horaria); ?>">
      </div>

      <!-- Categoria -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Categoria</label>
        <select name="categoria" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
          <option value="">Selecione uma categoria</option>
          <option value="Tecnologia" <?php echo $curso->categoria == 'Tecnologia' ? 'selected' : ''; ?>>Tecnologia</option>
          <option value="Negócios" <?php echo $curso->categoria == 'Negócios' ? 'selected' : ''; ?>>Negócios</option>
          <option value="Marketing" <?php echo $curso->categoria == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
          <option value="Artes" <?php echo $curso->categoria == 'Artes' ? 'selected' : ''; ?>>Artes</option>
          <option value="Desenvolvimento Pessoal" <?php echo $curso->categoria == 'Desenvolvimento Pessoal' ? 'selected' : ''; ?>>Desenvolvimento Pessoal</option>
        </select>
      </div>

      <!-- Descrição -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Descrição</label>
        <textarea name="descricao" rows="4" required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"><?php echo htmlspecialchars($curso->descricao); ?></textarea>
      </div>

      <!-- Nível -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Nível</label>
        <select name="nivel" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
          <option value="">Selecione o nível</option>
          <option value="Iniciante" <?php echo $curso->nivel == 'Iniciante' ? 'selected' : ''; ?>>Iniciante</option>
          <option value="Intermediário" <?php echo $curso->nivel == 'Intermediário' ? 'selected' : ''; ?>>Intermediário</option>
          <option value="Avançado" <?php echo $curso->nivel == 'Avançado' ? 'selected' : ''; ?>>Avançado</option>
        </select>
      </div>

      <!-- Preço -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Preço (R$)</label>
        <input type="number" step="0.01" name="preco"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
               value="<?php echo number_format($curso->preco, 2, '.', ''); ?>">
      </div>

      <!-- Imagem -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">Imagem do curso</label>
        <input type="file" name="foto" accept="image/*"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700">
        <p class="text-xs text-gray-500 mt-2">Atual: <?php echo $curso->imagem_curso; ?></p>
      </div>

      <!-- Botão -->
      <div>
        <button type="submit" name="botao"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
          <i class="fas fa-save mr-2"></i>Salvar Alterações
        </button>
      </div>
    </form>
  </div>
</div>
</body>
</html>