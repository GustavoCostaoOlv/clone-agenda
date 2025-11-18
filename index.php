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

  <!-- Card de Login -->
  <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all hover:shadow-3xl">
    <div class="p-8">
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Bem-vindo de volta</h2>
        <p class="text-gray-600 mt-2">Para acessar entre com E-mail e Senha</p>
      </div>

      <form action="" method="post">
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
        <button type="submit" name="login" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg">
          <i class="fas fa-sign-in-alt mr-2"></i>
          Acessar Cursos
        </button>
      </form>

      <!-- Alertas PHP -->
      <div class="mt-6 space-y-3">
        <?php
        include_once('config/conexao.php');

        // Exibir mensagens com base na ação
        if (isset($_GET['acao'])) {
            $acao = $_GET['acao'];
            if ($acao == 'negado') {
                echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                        <div class="flex">
                          <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                          </div>
                          <div class="ml-3">
                            <p class="text-sm text-red-700">
                              <strong>Erro ao acessar o sistema!</strong> Efetue o login.
                            </p>
                          </div>
                        </div>
                      </div>';
            } elseif ($acao == 'sair') {
                echo '<div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm">
                        <div class="flex">
                          <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-500"></i>
                          </div>
                          <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                              <strong>Você saiu dos seus cursos catalogados!</strong> Volte sempre :)
                            </p>
                          </div>
                        </div>
                      </div>';
            }
        }

        // Processar o formulário de login
        if (isset($_POST['login'])) {
            $login = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

            if ($login && $senha) {
                $select = "SELECT * FROM tb_user WHERE email_user = :emailLogin";

                try {
                    $resultLogin = $conect->prepare($select);
                    $resultLogin->bindParam(':emailLogin', $login, PDO::PARAM_STR);
                    $resultLogin->execute();

                    $verificar = $resultLogin->rowCount();
                    if ($verificar > 0) {
                        $user = $resultLogin->fetch(PDO::FETCH_ASSOC);

                        // Verifica a senha
                        if (password_verify($senha, $user['senha_user'])) {
                            // Criar sessão
                            $_SESSION['loginUser'] = $login;
                            $_SESSION['senhaUser'] = $user['id_user'];

                            echo '<div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                                    <div class="flex">
                                      <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                      </div>
                                      <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                          <strong>Logado com sucesso!</strong> Redirecionando para os cursos...
                                        </p>
                                      </div>
                                    </div>
                                  </div>';

                            echo '<script>
                                setTimeout(function() {
                                window.location.href = "paginas/home.php?acao=bemvindo";
                                }, 3000);
                              </script>';
                        } else {
                            echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                                    <div class="flex">
                                      <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-500"></i>
                                      </div>
                                      <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                          <strong>Senha incorreta!</strong> Tente novamente.
                                        </p>
                                      </div>
                                    </div>
                                  </div>';
                            echo '<script>
                            setTimeout(function() {
                                window.location.href = "index.php";
                            }, 5000);
                        </script>';
                        }
                    } else {
                        echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                                <div class="flex">
                                  <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500"></i>
                                  </div>
                                  <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                      <strong>E-mail não encontrado!</strong> Verifique seu login ou faça o cadastro.
                                    </p>
                                  </div>
                                </div>
                              </div>';
                        echo '<script>
                          setTimeout(function() {
                              window.location.href = "index.php";
                          }, 5000);
                      </script>';
                    }
                } catch (PDOException $e) {
                    error_log("ERRO DE LOGIN DO PDO: " . $e->getMessage());
                    echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                            <div class="flex">
                              <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                              </div>
                              <div class="ml-3">
                                <p class="text-sm text-red-700">
                                  <strong>Erro no sistema!</strong> Tente novamente mais tarde.
                                </p>
                              </div>
                            </div>
                          </div>';
                }
            } else {
                echo '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                        <div class="flex">
                          <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                          </div>
                          <div class="ml-3">
                            <p class="text-sm text-red-700">
                              <strong>Campos obrigatórios!</strong> Preencha e-mail e senha.
                            </p>
                          </div>
                        </div>
                      </div>';
            }
        }
        ?>
      </div>

      <!-- Link de Cadastro -->
      <div class="text-center mt-8 pt-6 border-t border-gray-200">
        <p class="text-gray-600">
          Não tem uma conta?
          <a href="cad_user.php" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors ml-1">
            Cadastre-se aqui!
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
    // Foco automático no campo email
    document.getElementById('email')?.focus();
    
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