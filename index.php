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
  <title>Cadastro de Cursos | EducaPro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4A5D73',
                    primarydark: '#324151',
                    primarylight: '#E8ECF1',
                    glass: 'rgba(255, 255, 255, 0.1)',
                    glassdark: 'rgba(255, 255, 255, 0.05)'
                },
                backgroundImage: {
                    'premium-gradient': 'linear-gradient(135deg, #667eea 0%, #764ba2 25%, #4A5D73 50%, #2c3e50 100%)',
                    'glass-gradient': 'linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%)'
                },
                backdropBlur: {
                    'xl': '20px',
                }
            }
        }
    }
  </script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #4A5D73 50%, #2c3e50 100%);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      min-height: 100vh;
    }
    
    @keyframes gradientShift {
      0% { background-position: 0% 50% }
      50% { background-position: 100% 50% }
      100% { background-position: 0% 50% }
    }
    
    .glass-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .glass-form {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(40px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
    }
    
    .abstract-shape {
      position: fixed;
      opacity: 0.1;
      background: linear-gradient(45deg, #667eea, #764ba2);
      border-radius: 50%;
      filter: blur(40px);
      animation: float 8s ease-in-out infinite;
      z-index: 0;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .feature-glow {
      background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.1);
      transition: all 0.3s ease;
    }
    
    .feature-glow:hover {
      transform: translateY(-5px);
      border-color: rgba(255,255,255,0.3);
      box-shadow: 0 15px 30px -10px rgba(0,0,0,0.2);
    }
    
    .btn-glass {
      background: linear-gradient(135deg, rgba(74, 93, 115, 0.9) 0%, rgba(50, 65, 81, 0.9) 100%);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.2);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn-glass::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn-glass:hover::before {
      left: 100%;
    }
    
    .btn-glass:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 30px -10px rgba(74, 93, 115, 0.4);
    }
    
    .input-glass {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border: 1.5px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
    }
    
    .input-glass:focus {
      background: rgba(255, 255, 255, 0.95);
      border-color: #4A5D73;
      box-shadow: 0 0 0 3px rgba(74, 93, 115, 0.1);
      transform: translateY(-1px);
    }
    
    .mega-icon {
      font-size: 12rem;
      opacity: 0.08;
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      filter: blur(0.5px);
      animation: iconGlow 6s ease-in-out infinite;
    }
    
    @keyframes iconGlow {
      0%, 100% { opacity: 0.08; transform: scale(1); }
      50% { opacity: 0.12; transform: scale(1.05); }
    }

    .content-wrapper {
      position: relative;
      z-index: 10;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      width: 100%;
    }
  </style>
</head>

<body class="relative">
  
  <!-- Shapes Abstratos de Fundo -->
  <div class="abstract-shape w-96 h-96 -top-48 -left-48"></div>
  <div class="abstract-shape w-80 h-80 -bottom-32 -right-32" style="animation-delay: 2s;"></div>
  <div class="abstract-shape w-64 h-64 top-1/4 right-1/4" style="animation-delay: 4s;"></div>
  <div class="abstract-shape w-72 h-72 bottom-1/4 left-1/4" style="animation-delay: 6s;"></div>

  <div class="content-wrapper">
    <div class="w-full max-w-6xl">
      <div class="glass-card rounded-3xl overflow-hidden">
        <div class="flex flex-col md:flex-row min-h-[600px]">
          
          <!-- Coluna Esquerda - Conteúdo Premium -->
          <div class="md:w-1/2 text-white p-8 md:p-12 flex flex-col justify-center relative 
            border-2 border-white border-opacity-30 rounded-3xl m-4">
            
            <!-- Ícone Gigante Abstrato -->
            <div class="absolute right-8 md:right-12 top-1/2 transform -translate-y-1/2">
              <i class="fas fa-graduation-cap mega-icon"></i>
            </div>
            
            <div class="relative z-10">
              <div class="mb-8 md:mb-12">
                <h1 class="text-4xl md:text-5xl font-black mb-6 md:mb-8 leading-tight tracking-tight">
                  Seu futuro<br>
                  <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-purple-200">começa aqui</span>
                </h1>
                
                <!-- Features com Glass Effect -->
                <div class="space-y-4 mb-8 md:mb-12">
                  <div class="feature-glow rounded-2xl p-4 md:p-6">
                    <div class="flex items-center">
                      <div class="w-10 h-10 md:w-12 md:h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3 md:mr-4">
                        <i class="fas fa-globe-americas text-lg md:text-xl"></i>
                      </div>
                      <div>
                        <h3 class="font-bold text-base md:text-lg mb-1">Aprenda com o mundo</h3>
                        <p class="text-white text-opacity-80 text-xs md:text-sm">Crie e gerencie seu conhecimento</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="feature-glow rounded-2xl p-4 md:p-6">
                    <div class="flex items-center">
                      <div class="w-10 h-10 md:w-12 md:h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3 md:mr-4">
                        <i class="fas fa-shield-alt text-lg md:text-xl"></i>
                      </div>
                      <div>
                        <h3 class="font-bold text-base md:text-lg mb-1">Ecossistema completo</h3>
                        <p class="text-white text-opacity-80 text-xs md:text-sm">Tudo que você precisa em um só lugar</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Card de Avaliação Premium -->
                <div class="feature-glow rounded-2xl p-4 md:p-6">
                  <div class="flex items-center justify-between mb-3 md:mb-4">
                    <span class="font-bold text-lg md:text-xl">Seguro e confiável</span>
                    <div class="flex items-center">
                      <div class="flex text-yellow-300 text-lg md:text-xl">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                      </div>
                      <span class="ml-2 md:ml-3 font-black text-lg md:text-xl">4.6</span>
                    </div>
                  </div>
                  <p class="text-white text-opacity-90 text-sm md:text-lg font-medium">Baseado em +200.000 avaliações</p>
                </div>
              </div>

              <div class="mt-auto">
                <p class="text-xl md:text-2xl font-black mb-2 md:mb-3">Junte-se a nós agora</p>
                <p class="text-green-300 font-bold text-lg md:text-xl">É gratuito e sempre será!</p>
              </div>
            </div>
          </div>

          <!-- Coluna Direita - Formulário Glass -->
          <div class="md:w-1/2 p-6 md:p-8 lg:p-12 flex items-center justify-center">
            <div class="w-full max-w-md glass-form rounded-3xl p-6 md:p-8 lg:p-10">
              
              <!-- Header do Form -->
              <div class="text-center mb-8 md:mb-10">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-primary to-primarydark rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 shadow-2xl">
                  <i class="fas fa-graduation-cap text-white text-xl md:text-2xl"></i>
                </div>
                
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-2 tracking-tight">EducaPro</h2>
                <p class="text-gray-600 text-xs md:text-sm font-semibold uppercase tracking-wider mb-3 md:mb-4">Portal Premium</p>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Acesse seus cursos</h3>
                <p class="text-gray-500 text-xs md:text-sm">Entre e continue sua jornada</p>
              </div>

              <!-- Divisor Elegante -->
              <div class="flex items-center my-6 md:my-8">
                <div class="flex-1 border-t border-gray-300"></div>
                <div class="px-3 md:px-4 text-gray-500 text-xs md:text-sm font-medium">Ou</div>
                <div class="flex-1 border-t border-gray-300"></div>
              </div>

              <form action="" method="post">
                <!-- Email Input -->
                <div class="mb-4 md:mb-6">
                  <label class="block text-gray-700 text-xs md:text-sm font-bold mb-2 md:mb-3 tracking-wide" for="email">
                    Seu e-mail
                  </label>
                  <div class="relative">
                    <input type="email" name="email" id="email"
                           class="w-full px-4 py-3 md:py-4 input-glass rounded-xl focus:outline-none pl-10 md:pl-12 text-gray-800 placeholder-gray-500 text-sm font-medium"
                           placeholder="seu@email.com" required>
                    <div class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                      <i class="fas fa-envelope text-sm md:text-lg"></i>
                    </div>
                  </div>
                </div>

                <!-- Password Input -->
                <div class="mb-4 md:mb-6">
                  <label class="block text-gray-700 text-xs md:text-sm font-bold mb-2 md:mb-3 tracking-wide" for="senha">
                    Sua senha
                  </label>
                  <div class="relative">
                    <input type="password" name="senha" id="senha"
                           class="w-full px-4 py-3 md:py-4 input-glass rounded-xl focus:outline-none pl-10 md:pl-12 text-gray-800 placeholder-gray-500 text-sm font-medium"
                           placeholder="Sua senha" required>
                    <div class="absolute left-3 md:left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                      <i class="fas fa-lock text-sm md:text-lg"></i>
                    </div>
                  </div>
                </div>

                <!-- Termos -->
                <div class="mb-6 md:mb-8">
                  <label class="flex items-start space-x-2 md:space-x-3 text-gray-600 text-xs">
                    <input type="checkbox" class="mt-0.5 rounded border-gray-300 text-primary focus:ring-2 focus:ring-primary focus:ring-opacity-50" required>
                    <span class="leading-relaxed">
                      Concordo com os <a href="#" class="text-primary hover:text-primarydark font-bold">Termos</a> e 
                      <a href="#" class="text-primary hover:text-primarydark font-bold">Políticas</a>
                    </span>
                  </label>
                </div>

                <!-- Botão Glass -->
                <button type="submit" name="login" 
                        class="w-full btn-glass text-white font-black py-3 md:py-4 px-4 rounded-xl text-base md:text-lg mb-4">
                  <i class="fas fa-sign-in-alt mr-2 md:mr-3"></i>
                  ACESSAR PLATAFORMA
                </button>

                <!-- Segurança -->
                <div class="text-center mb-4 md:mb-6">
                  <p class="text-xs text-gray-500 flex items-center justify-center font-semibold">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                    Criptografia de ponta a ponta
                  </p>
                </div>
              </form>

              <!-- Alertas Glass -->
              <div class="mt-4 md:mt-6 space-y-3">
                <?php
                include_once('config/conexao.php');

                if (isset($_GET['acao'])) {
                    $acao = $_GET['acao'];
                    if ($acao == 'negado') {
                        echo '<div class="bg-red-50 border border-red-200 rounded-xl p-3 md:p-4 backdrop-blur-sm">
                                <div class="flex items-center">
                                  <i class="fas fa-exclamation-circle text-red-500 mr-3 text-sm"></i>
                                  <p class="text-xs md:text-sm text-red-700 font-medium">Erro ao acessar! Efetue o login.</p>
                                </div>
                              </div>';
                    } elseif ($acao == 'sair') {
                        echo '<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 md:p-4 backdrop-blur-sm">
                                <div class="flex items-center">
                                  <i class="fas fa-info-circle text-yellow-500 mr-3 text-sm"></i>
                                  <p class="text-xs md:text-sm text-yellow-700 font-medium">Saiu dos seus cursos! Volte sempre :)</p>
                                </div>
                              </div>';
                    }
                }

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

                                if (password_verify($senha, $user['senha_user'])) {
                                    $_SESSION['loginUser'] = $login;
                                    $_SESSION['senhaUser'] = $user['id_user'];

                                    echo '<div class="bg-green-50 border border-green-200 rounded-xl p-3 md:p-4 backdrop-blur-sm">
                                            <div class="flex items-center">
                                              <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                              <p class="text-xs md:text-sm text-green-700 font-medium">Logado com sucesso! Redirecionando...</p>
                                            </div>
                                          </div>';

                                    echo '<script>setTimeout(() => window.location.href = "paginas/home.php?acao=bemvindo", 2000);</script>';
                                } else {
                                    echo '<div class="bg-red-50 border border-red-200 rounded-xl p-3 md:p-4 backdrop-blur-sm">
                                            <div class="flex items-center">
                                              <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                              <p class="text-xs md:text-sm text-red-700 font-medium">Senha incorreta! Tente novamente.</p>
                                            </div>
                                          </div>';
                                }
                            } else {
                                echo '<div class="bg-red-50 border border-red-200 rounded-xl p-3 md:p-4 backdrop-blur-sm">
                                        <div class="flex items-center">
                                          <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                          <p class="text-xs md:text-sm text-red-700 font-medium">E-mail não encontrado! Verifique ou cadastre-se.</p>
                                        </div>
                                      </div>';
                            }
                        } catch (PDOException $e) {
                            echo '<div class="bg-red-50 border border-red-200 rounded-xl p-3 md:p-4 backdrop-blur-sm">
                                    <div class="flex items-center">
                                      <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                                      <p class="text-xs md:text-sm text-red-700 font-medium">Erro no sistema! Tente novamente.</p>
                                    </div>
                                  </div>';
                        }
                    } else {
                        echo '<div class="bg-red-50 border border-red-200 rounded-xl p-3 md:p-4 backdrop-blur-sm">
                                <div class="flex items-center">
                                  <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                  <p class="text-xs md:text-sm text-red-700 font-medium">Preencha e-mail e senha.</p>
                                </div>
                              </div>';
                    }
                }
                ?>
              </div>

              <!-- Link de Cadastro - AGORA VISÍVEL -->
              <div class="text-center mt-6 md:mt-8 pt-4 md:pt-6 border-t border-gray-200">
                <p class="text-gray-600 text-xs md:text-sm font-semibold">
                  Novo por aqui?
                  <a href="cad_user.php" class="text-primary hover:text-primarydark font-black transition-colors ml-1">
                    Criar conta gratuita
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('email')?.focus();
        
        // Garantir que a página tenha scroll se necessário
        document.body.style.overflow = 'auto';
        document.body.style.height = 'auto';
    });
  </script>
</body>
</html>