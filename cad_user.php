<?php
session_start(); 

// Verifica se o usuário está autenticado
if (isset($_SESSION['loginUser']) && isset($_SESSION['senhaUser'])) {
    header("Location: paginas/home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cadastro de Cursos | Escolha Já</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
        theme: {
            extend: {
                boxShadow: {
                    '3xl': '0 35px 60px -15px rgba(0, 0, 0, 0.3)',
                }
            }
        }
    }
  </script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Source Sans Pro', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md">
  <!-- Logo -->
  <div class="text-center mb-8">
    <h1 class="text-4xl font-bold text-white">
      <a href="#" class="hover:text-blue-200 transition-colors">
        <span class="text-blue-200">Cursos</span> <span class="text-white">Online</span> <span class="text-blue-200">1.0</span>
      </a>
    </h1>
    <p class="text-blue-100 mt-2">Seu Planejamento de Carreira Virtual</p>
  </div>

  <!-- Card de Cadastro -->
  <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all hover:shadow-3xl">
    <div class="p-8">
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Criar Nova Conta</h2>
        <p class="text-gray-600 mt-2">Cadastre todos os dados para ter acesso aos cursos</p>
      </div>

      <form action="" method="post" enctype="multipart/form-data">
        <!-- Foto Input -->
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-medium mb-2" for="foto">
            Foto do usuário
          </label>
          <div class="relative">
            <input type="file" name="foto" id="foto"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                   accept="image/png, image/jpg, image/jpeg, image/gif">
          </div>
          <p class="text-xs text-gray-500 mt-1">Formatos: PNG, JPG, JPEG, GIF</p>
        </div>

        <!-- Nome Input -->
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-medium mb-2" for="nome">
            Nome Completo
          </label>
          <div class="relative">
            <input type="text" name="nome" id="nome"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all pl-12"
                   placeholder="Seu nome completo" required>
            <div class="absolute left-4 top-3 text-gray-400">
              <i class="fas fa-user"></i>
            </div>
          </div>
        </div>

        <!-- Email Input -->
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-medium mb-2" for="email">
            E-mail
          </label>
          <div class="relative">
            <input type="email" name="email" id="email"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all pl-12"
                   placeholder="seu@email.com" required>
            <div class="absolute left-4 top-3 text-gray-400">
              <i class="fas fa-envelope"></i>
            </div>
          </div>
        </div>

        <!-- Password Input -->
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-medium mb-2" for="senha">
            Senha
          </label>
          <div class="relative">
            <input type="password" name="senha" id="senha"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all pl-12"
                   placeholder="Sua senha" required>
            <div class="absolute left-4 top-3 text-gray-400">
              <i class="fas fa-lock"></i>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="botao" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg">
          <i class="fas fa-user-plus mr-2"></i>
          Finalizar Cadastro
        </button>
      </form>

      <!-- Alertas PHP -->
      <div class="mt-6 space-y-3">
        <?php
        include_once('config/conexao.php');

        // Verifica se o formulário foi enviado
        if (isset($_POST['botao'])) {
            // Recebe os dados do formulário
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            // Verifica se foi enviado algum arquivo de foto
            if (!empty($_FILES['foto']['name'])) {
                $formatosPermitidos = array("png", "jpg", "jpeg", "gif");
                $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

                if (in_array(strtolower($extensao), $formatosPermitidos)) {
                    $pasta = "img/user/";
                    $temporario = $_FILES['foto']['tmp_name'];
                    $novoNome = uniqid() . ".$extensao";

                    if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                        // Sucesso no upload da imagem
                    } else {
                        echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                                <div class="flex">
                                  <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                  </div>
                                  <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                      <strong>Erro!</strong> Não foi possível fazer o upload do arquivo.
                                    </p>
                                  </div>
                                </div>
                              </div>';
                        exit();
                    }
                } else {
                    echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                            <div class="flex">
                              <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                              </div>
                              <div class="ml-3">
                                <p class="text-sm text-red-700">
                                  <strong>Formato Inválido!</strong> Formato de arquivo não permitido.
                                </p>
                              </div>
                            </div>
                          </div>';
                    exit();
                }
            } else {
                $novoNome = 'avatar-padrao.png';
            }

            // Prepara a consulta SQL
            $cadastro = "INSERT INTO tb_user (foto_user, nome_user, email_user, senha_user) VALUES (:foto, :nome, :email, :senha)";

            try {
                $result = $conect->prepare($cadastro);
                $result->bindParam(':nome', $nome, PDO::PARAM_STR);
                $result->bindParam(':email', $email, PDO::PARAM_STR);
                $result->bindParam(':senha', $senha, PDO::PARAM_STR);
                $result->bindParam(':foto', $novoNome, PDO::PARAM_STR);
                $result->execute();
                $contar = $result->rowCount();

                if ($contar > 0) {
                    echo '<div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                            <div class="flex">
                              <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                              </div>
                              <div class="ml-3">
                                <p class="text-sm text-green-700">
                                  <strong>Sucesso!</strong> Dados inseridos com sucesso!
                                </p>
                              </div>
                            </div>
                          </div>';
                } else {
                    echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                            <div class="flex">
                              <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                              </div>
                              <div class="ml-3">
                                <p class="text-sm text-red-700">
                                  <strong>Erro!</strong> Dados não inseridos!
                                </p>
                              </div>
                            </div>
                          </div>';
                }
            } catch (PDOException $e) {
                error_log("ERRO DE PDO: " . $e->getMessage());
                echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                        <div class="flex">
                          <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                          </div>
                          <div class="ml-3">
                            <p class="text-sm text-red-700">
                              <strong>Erro!</strong> Ocorreu um erro ao tentar inserir os dados.
                            </p>
                          </div>
                        </div>
                      </div>';
            }
        }
        ?>
      </div>

      <!-- Link para Login -->
      <div class="text-center mt-8 pt-6 border-t border-gray-200">
        <p class="text-gray-600">
          Já tem uma conta?
          <a href="index.php" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors ml-1">
            Faça login aqui!
          </a>
        </p>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="text-center mt-8">
    <p class="text-blue-100 text-sm">© 2025 Cadastro de Cursos - Todos os direitos reservados para Luiz Gustavo</p>
  </div>
</div>

<!-- Script para melhorar a experiência -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Foco automático no campo nome
    document.getElementById('nome')?.focus();
    
    // Animação suave para os alertas
    const alerts = document.querySelectorAll('[class*="bg-"]');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            alert.style.transition = 'all 0.3s ease';
            alert.style.opacity = '1';
            alert.style.transform = 'translateY(0)';
        }, 100);
    });
});
</script>
</body>
</html>