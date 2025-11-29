<?php
include_once('../config/conexao.php');
error_log("📁 CAMINHO DO LOG: " . ini_get('error_log'));

// 🔒 INICIAR SESSÃO SE NÃO ESTIVER INICIADA
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 🎯 DEBUG MENSAGENS - REMOVA DEPOIS
error_log("=== 🔍 VERIFICANDO MENSAGENS ===");
error_log("SESSION mensagem: " . ($_SESSION['mensagem'] ?? 'NÃO EXISTE'));
error_log("SESSION tipo_mensagem: " . ($_SESSION['tipo_mensagem'] ?? 'NÃO EXISTE'));

$_SESSION['mensagem'] = '';
$_SESSION['tipo_mensagem'] = '';


// DEBUG - VERIFICAR USUÁRIO DA SESSÃO
error_log("=== 🔍 DEBUG SESSÃO USUÁRIO ===");
error_log("SESSION: " . print_r($_SESSION, true));

// Verificar se existe algum usuário na tabela tb_user
try {
    $verifica_user = $conect->query("SELECT id_user FROM tb_user LIMIT 1");
    if ($verifica_user->rowCount() > 0) {
        $user_example = $verifica_user->fetch(PDO::FETCH_OBJ);
        error_log("✅ Existe usuário na tabela. Exemplo ID: " . $user_example->id_user);
    } else {
        error_log("❌ Nenhum usuário na tabela tb_user");
    }
} catch (PDOException $e) {
    error_log("❌ Erro ao verificar tb_user: " . $e->getMessage());
}

// DEBUG - VERIFICAR ESTRUTURA DO BANCO
error_log("=== VERIFICANDO BANCO DE DADOS ===");
try {
    // Verificar se tabela de cursos existe
    $tabela_existe = $conect->query("SHOW TABLES LIKE 'tb_cursos'");
    if ($tabela_existe->rowCount() == 0) {
        error_log("❌ TABELA tb_cursos NÃO EXISTE!");
        // Criar tabela se não existir
       $criar_tabela = "
CREATE TABLE IF NOT EXISTS tb_cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nome_curso VARCHAR(255) NOT NULL,
    carga_horaria VARCHAR(50) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    descricao TEXT,
    nivel VARCHAR(50) NOT NULL,
    preco DECIMAL(10,2) DEFAULT 0.00,
    imagem_curso VARCHAR(255) DEFAULT 'curso-padrao.jpg',
    id_user INT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
        $conect->exec($criar_tabela);
        error_log("✅ TABELA tb_cursos CRIADA COM SUCESSO!");
    } else {
        error_log("✅ TABELA tb_cursos EXISTE");
    }
    
    // Verificar estrutura da tabela
$estrutura = $conect->query("DESCRIBE tb_cursos");
error_log("ESTRUTURA: " . print_r($estrutura->fetchAll(PDO::FETCH_ASSOC), true));

} catch (PDOException $e) {
    error_log("ERRO BANCO: " . $e->getMessage());
}

// === 🖼️ VERIFICAR PASTA DE IMAGENS - ADICIONE ESTE BLOCO ===
$pasta_img = "../img/cursos/";
if (!is_dir($pasta_img)) {
    mkdir($pasta_img, 0777, true);
    error_log("✅ PASTA DE IMAGENS CRIADA: " . $pasta_img);
}

$imagem_padrao = $pasta_img . "curso-padrao.jpg";
if (!file_exists($imagem_padrao)) {
    // Verificar se GD está realmente disponível
    if (extension_loaded('gd') && function_exists('imagecreate')) {
        try {
            // Criar uma imagem padrão simples
            $imagem = imagecreate(100, 100);
            $cor_fundo = imagecolorallocate($imagem, 74, 93, 115);
            $cor_texto = imagecolorallocate($imagem, 255, 255, 255);
            imagestring($imagem, 2, 10, 45, "CURSO", $cor_texto);
            imagejpeg($imagem, $imagem_padrao);
            imagedestroy($imagem);
            error_log("✅ IMAGEM PADRÃO CRIADA COM GD");
        } catch (Exception $e) {
            error_log("⚠️ Erro ao criar imagem com GD: " . $e->getMessage());
        }
    } else {
        // GD não disponível - pular criação
        error_log("⚠️ GD não disponível - pulando criação de imagem");
    }
}

// 🎯 SOLUÇÃO COMPLETA: RESOLVER PROBLEMA DO USUÁRIO
error_log("=== 🔍 DEBUG SESSÃO USUÁRIO ===");
error_log("SESSION: " . print_r($_SESSION, true));

// 1. Primeiro tentar pegar da sessão
$id_user = null;
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
} elseif (isset($_SESSION['id'])) {
    $id_user = $_SESSION['id'];
} elseif (isset($_SESSION['user_id'])) {
    $id_user = $_SESSION['user_id'];
} elseif (isset($_SESSION['idusuario'])) {
    $id_user = $_SESSION['idusuario'];
}

// 2. Verificar se o usuário da sessão EXISTE na tabela tb_user
if ($id_user) {
    try {
        $verifica_user_especifico = $conect->prepare("SELECT id_user FROM tb_user WHERE id_user = ?");
        $verifica_user_especifico->execute([$id_user]);
        
        if ($verifica_user_especifico->rowCount() == 0) {
            error_log("❌ Usuário da sessão ID {$id_user} NÃO EXISTE na tb_user");
            $id_user = null; // Invalidar o ID
        } else {
            error_log("✅ Usuário da sessão ID {$id_user} EXISTE na tb_user");
        }
    } catch (PDOException $e) {
        error_log("❌ Erro ao verificar usuário específico: " . $e->getMessage());
        $id_user = null;
    }
}

// 3. Se não tem ID válido, buscar ou criar um usuário
if (!$id_user) {
    try {
        // Verificar se existe ALGUM usuário na tabela
        $verifica_qualquer_user = $conect->query("SELECT id_user FROM tb_user LIMIT 1");
        
        if ($verifica_qualquer_user->rowCount() > 0) {
            // Usar o primeiro usuário existente
            $user_existente = $verifica_qualquer_user->fetch(PDO::FETCH_OBJ);
            $id_user = $user_existente->id_user;
            error_log("✅ Usando usuário existente ID: " . $id_user);
            
        } else {
            // Criar usuário padrão
            error_log("❌ Nenhum usuário na tabela tb_user. Criando usuário padrão...");
            
            $criar_user = "INSERT INTO tb_user (nome, email, senha, data_cadastro) 
                          VALUES ('Usuário Padrão', 'default@email.com', '123456', NOW())";
            $conect->exec($criar_user);
            $id_user = $conect->lastInsertId();
            
            error_log("✅ USUÁRIO PADRÃO CRIADO COM ID: " . $id_user);
        }
        
    } catch (PDOException $e) {
        error_log("❌ Erro crítico ao verificar/criar usuário: " . $e->getMessage());
        // Último recurso - tentar usar ID 1
        $id_user = 1;
        error_log("⚠️ Usando ID fallback: " . $id_user);
    }
}

// 4. Garantir que o ID é inteiro
$id_user = (int)$id_user;
error_log("🎯 ID USER FINAL: " . $id_user);

// Função para detectar automaticamente a extensão real da imagem
function getImagemCurso($imagem_curso, $nome_curso = '', $id_curso = 0) {
    error_log("🔍 Buscando imagem para Curso ID: {$id_curso}, Nome: {$nome_curso}");
    
    // Lista oficial dos nomes base (sem extensão)
    $nomes_base_por_id = [
        2 => 'desenvolvimento-web',
        3 => 'python-data-science',
        4 => 'javascript-moderno',
        5 => 'react-nextjs',
        6 => 'marketing-digital',
        7 => 'gestao-projetos',
        8 => 'design-uxui',
        9 => 'oratoria'
    ];

    // Lista de todas as extensões possíveis
    $extensoes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Se o ID está no array, tenta achar o arquivo
    if (isset($nomes_base_por_id[$id_curso])) {
        $nome_base = trim($nomes_base_por_id[$id_curso]);

        foreach ($extensoes as $ext) {
            $arquivo = "{$nome_base}.{$ext}";
            $caminho = "../img/cursos/" . $arquivo;

            if (file_exists($caminho)) {
                error_log("✅ Encontrado automaticamente: {$arquivo}");
                return $arquivo;
            }
        }

        // Nenhuma imagem encontrada para esse ID
        error_log("❌ Nenhuma imagem encontrada para {$nome_base} em qualquer extensão.");
    }

    // Se existe imagem salva no banco, usa ela
    if (!empty($imagem_curso) && $imagem_curso !== 'curso-padrao.jpg') {
        $caminho = "../img/cursos/" . $imagem_curso;

        if (file_exists($caminho)) {
            error_log("✅ Usando imagem do banco: {$imagem_curso}");
            return $imagem_curso;
        } else {
            error_log("⚠️ Imagem no banco não encontrada: {$imagem_curso}");
        }
    }

    // Última opção
    error_log("⚠️ Usando imagem padrão");
    return 'curso-padrao.jpg';
}

// 🔍 SOLUÇÃO 3: DEBUG UPLOAD - COLE AQUI
function debugUpload() {
    $pasta = "../img/cursos/";
    
    error_log("=== 🔍 DEBUG UPLOAD ===");
    error_log("Pasta: " . $pasta);
    error_log("Existe: " . (is_dir($pasta) ? 'SIM' : 'NÃO'));
    error_log("É gravável: " . (is_writable($pasta) ? 'SIM' : 'NÃO'));
    
    if (is_dir($pasta)) {
        error_log("Permissões: " . substr(sprintf('%o', fileperms($pasta)), -4));
        
        // Listar arquivos na pasta
        $arquivos = scandir($pasta);
        $arquivos_validos = array_filter($arquivos, function($arquivo) {
            return $arquivo != '.' && $arquivo != '..';
        });
        
        error_log("Arquivos na pasta cursos:");
        foreach ($arquivos_validos as $arquivo) {
            $caminho = $pasta . $arquivo;
            $tamanho = filesize($caminho);
            error_log("  📄 {$arquivo} ({$tamanho} bytes)");
        }
    }
}


// DEBUG CRÍTICO: Verificar o que está acontecendo com cada curso (VERSÃO SEGURA)
function debugImagensCursos() {
    global $cursos_disponiveis;
    
    error_log("=== 🎯 DEBUG CRÍTICO - VERIFICANDO IMAGENS ===");
    
    // Verifica se a variável existe e é um array
    if (!isset($cursos_disponiveis) || !is_array($cursos_disponiveis)) {
        error_log("❌ ERRO: \$cursos_disponiveis não está definida ou não é um array");
        error_log("Tipo: " . gettype($cursos_disponiveis));
        return;
    }
    
    if (empty($cursos_disponiveis)) {
        error_log("⚠️ AVISO: \$cursos_disponiveis está vazia");
        return;
    }
    
    error_log("Total de cursos encontrados: " . count($cursos_disponiveis));
    
    foreach ($cursos_disponiveis as $index => $curso) {
        // Verifica se o curso é um objeto válido
        if (!is_object($curso)) {
            error_log("❌ Curso no índice {$index} não é um objeto: " . gettype($curso));
            continue;
        }
        
        // Verifica se as propriedades existem
        if (!property_exists($curso, 'id_curso') || !property_exists($curso, 'nome_curso') || !property_exists($curso, 'imagem_curso')) {
            error_log("❌ Curso no índice {$index} não tem propriedades necessárias");
            continue;
        }
        
        $imagem_encontrada = getImagemCurso($curso->imagem_curso, $curso->nome_curso, $curso->id_curso);
        $caminho_encontrado = "../img/cursos/" . $imagem_encontrada;
        $existe = file_exists($caminho_encontrado) ? "✅ EXISTE" : "❌ NÃO EXISTE";
        
        error_log("Curso ID {$curso->id_curso}: {$curso->nome_curso}");
        error_log("  - Imagem no banco: {$curso->imagem_curso}");
        error_log("  - Imagem encontrada: {$imagem_encontrada}");
        error_log("  - Status: {$existe}");
        error_log("  ---");
    }
}

debugImagensCursos();

// DEBUG: Verificar estado do banco de dados
function debugEstadoBanco() {
    global $conect;
    
    error_log("=== 🗄️ DEBUG ESTADO DO BANCO ===");
    
    try {
        $result = $conect->query("SELECT COUNT(*) as total FROM tb_cursos");
        $total = $result->fetch(PDO::FETCH_OBJ)->total;
        error_log("Total de cursos no banco: {$total}");
        
        // Ver cursos específicos
        $cursos = $conect->query("SELECT id_curso, nome_curso, imagem_curso FROM tb_cursos WHERE id_curso BETWEEN 2 AND 9 ORDER BY id_curso");
        $cursos_data = $cursos->fetchAll(PDO::FETCH_OBJ);
        
        error_log("Cursos ID 2-9 no banco:");
        foreach ($cursos_data as $curso) {
            error_log("  ID {$curso->id_curso}: {$curso->nome_curso} -> {$curso->imagem_curso}");
        }
        
    } catch (PDOException $e) {
        error_log("❌ ERRO AO ACESSAR BANCO: " . $e->getMessage());
    }
}

debugEstadoBanco();

// DEBUG: Verificar arquivos reais na pasta
function debugArquivosPasta() {
    $pasta = "../img/cursos/";
    
    error_log("=== 📁 DEBUG ARQUIVOS NA PASTA ===");
    
    if (!is_dir($pasta)) {
        error_log("❌ PASTA NÃO EXISTE: {$pasta}");
        return;
    }
    
    $arquivos = scandir($pasta);
    $arquivos_validos = array_filter($arquivos, function($arquivo) {
        return $arquivo != '.' && $arquivo != '..';
    });
    
    error_log("Arquivos encontrados na pasta:");
    foreach ($arquivos_validos as $arquivo) {
        $caminho = $pasta . $arquivo;
        $tamanho = filesize($caminho);
        error_log("  📄 {$arquivo} ({$tamanho} bytes)");
    }
}

debugArquivosPasta();

// DEBUG - VERIFICAR O QUE ESTÁ CHEGANDO NO POST
error_log("=== DEBUG FORMULÁRIO ===");
error_log("POST: " . print_r($_POST, true));
error_log("FILES: " . print_r($_FILES, true));

// CORREÇÃO: PROCESSAR CADASTRO DO CURSO - VERSÃO SIMPLIFICADA
if (isset($_POST['botao'])) {
    error_log("=== TENTATIVA DE CADASTRO INICIADA ===");
    
    // Sanitizar dados com valores padrão
    $nome_curso = trim($_POST['nome'] ?? '');
    $carga_horaria = trim($_POST['carga_horaria'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $nivel = trim($_POST['nivel'] ?? '');
    $preco = floatval($_POST['preco'] ?? 0);

    error_log("Dados recebidos:");
    error_log("Nome: '$nome_curso'");
    error_log("Carga: '$carga_horaria'");
    error_log("Categoria: '$categoria'");
    error_log("Nível: '$nivel'");
    error_log("Preço: '$preco'");

    // VALIDAÇÃO SIMPLIFICADA - Vamos ver qual campo está falhando
    $campos_faltantes = [];
    if (empty($nome_curso)) $campos_faltantes[] = "Nome do Curso";
    if (empty($carga_horaria)) $campos_faltantes[] = "Carga Horária";
    if (empty($categoria)) $campos_faltantes[] = "Categoria";
    if (empty($nivel)) $campos_faltantes[] = "Nível";

    if (!empty($campos_faltantes)) {
        $mensagem_erro = "Preencha os campos: " . implode(", ", $campos_faltantes);
        // NOVO:
        $_SESSION['mensagem'] = $mensagem_erro;
        $_SESSION['tipo_mensagem'] = "error";
        error_log("CAMPOS FALTANTES: " . $mensagem_erro);
    } else {
        error_log("✅ TODOS OS CAMPOS OBRIGATÓRIOS PREENCHIDOS");
        
        // Upload da imagem - VERSÃO CORRIGIDA
$foto_curso = 'curso-padrao.jpg';

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $formatosPermitidos = array("png", "jpg", "jpeg", "gif", "webp");
    $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (in_array($extensao, $formatosPermitidos)) {
        $pasta = "../img/cursos/";
        
        // Garantir que a pasta existe
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }
        
        $temporario = $_FILES['foto']['tmp_name'];
        
        // Nome mais descritivo para a imagem
        $nome_sem_espacos = preg_replace('/[^a-zA-Z0-9]/', '-', $nome_curso);
        $nome_sem_espacos = strtolower($nome_sem_espacos);
        $novoNome = $nome_sem_espacos . '-' . uniqid() . ".$extensao";

        if (move_uploaded_file($temporario, $pasta . $novoNome)) {
            $foto_curso = $novoNome;
            error_log("✅ IMAGEM SALVA COM SUCESSO: " . $foto_curso);
            
            // Verificar se o arquivo realmente foi criado
            if (file_exists($pasta . $novoNome)) {
                error_log("✅ ARQUIVO CONFIRMADO NA PASTA: " . $novoNome);
            } else {
                error_log("❌ ARQUIVO NÃO ENCONTRADO APÓS UPLOAD");
            }
        } else {
            error_log("❌ FALHA NO move_uploaded_file");
            error_log("Temporário: " . $temporario);
            error_log("Destino: " . $pasta . $novoNome);
            error_log("Permissões: " . substr(sprintf('%o', fileperms($pasta)), -4));
        }
    } else {
        error_log("❌ FORMATO NÃO PERMITIDO: " . $extensao);
    }
} else {
    $erro_upload = $_FILES['foto']['error'] ?? 'Nenhum arquivo';
    error_log("❌ ERRO NO UPLOAD: " . $erro_upload);
}

        // TENTAR CADASTRAR NO BANCO
        try {
            error_log("🎯 TENTANDO INSERIR NO BANCO...");
            
            $cadastro = "INSERT INTO tb_cursos (nome_curso, carga_horaria, categoria, descricao, nivel, preco, imagem_curso, id_user) 
                        VALUES (:nome, :carga, :categoria, :descricao, :nivel, :preco, :foto, :id_user)";

            $result = $conect->prepare($cadastro);
            $result->bindParam(':nome', $nome_curso, PDO::PARAM_STR);
            $result->bindParam(':carga', $carga_horaria, PDO::PARAM_STR);
            $result->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $result->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $result->bindParam(':nivel', $nivel, PDO::PARAM_STR);
            $result->bindParam(':preco', $preco);
            $result->bindParam(':foto', $foto_curso, PDO::PARAM_STR);
            $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            
            if ($result->execute()) {
                $ultimo_id = $conect->lastInsertId();
                error_log("✅ CURSO CADASTRADO COM SUCESSO! ID: " . $ultimo_id);

                   // 🎯 SOLUÇÃO 4: MATRICULAR AUTOMATICAMENTE O CRIADOR - COLE AQUI
                try {
                    $matricula = "INSERT INTO tb_matriculas (id_curso, id_user, data_matricula, progresso) 
                                VALUES (:id_curso, :id_user, NOW(), 0)";
                    $result_matricula = $conect->prepare($matricula);
                    $result_matricula->bindParam(':id_curso', $ultimo_id, PDO::PARAM_INT);
                    $result_matricula->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                    
                    if ($result_matricula->execute()) {
                        error_log("✅ CRIADOR AUTOMATICAMENTE MATRICULADO NO CURSO");
                    }
                } catch (PDOException $e) {
                    error_log("⚠️ AVISO: Não foi possível matricular o criador: " . $e->getMessage());
                    // Não mostrar erro para o usuário, pois o curso foi criado com sucesso
                }

               // NOVO:
$_SESSION['mensagem'] = "Curso cadastrado com sucesso!";
$_SESSION['tipo_mensagem'] = "success";

// ADICIONE O REDIRECT E EXIT
echo '<script>window.location.href = window.location.href;</script>';
exit();
                
                // Limpar formulário
                echo '<script>
                    setTimeout(() => {
                        document.querySelector("form").reset();
                        window.location.reload();
                    }, 2000);
                </script>';
            } else {
                error_log("❌ ERRO NO EXECUTE()");
                // NOVO:
$_SESSION['mensagem'] = "Erro ao cadastrar curso. Tente novamente.";
$_SESSION['tipo_mensagem'] = "error";
            }
            
        } catch (PDOException $e) {
            error_log("❌ ERRO PDO: " . $e->getMessage());
            // NOVO:
$_SESSION['mensagem'] = "Erro no banco de dados: " . $e->getMessage();
$_SESSION['tipo_mensagem'] = "error";
        }
    }
}

// Buscar cursos que o usuário criou
$meus_cursos_criados = [];
try {
    $select = "SELECT * FROM tb_cursos WHERE id_user = :id_user ORDER BY id_curso DESC";
    $result = $conect->prepare($select);
    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT); // ADICIONE ESTA LINHA
    $result->execute();
    $meus_cursos_criados = $result->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    error_log("Erro ao buscar cursos criados: " . $e->getMessage());
}

// CORREÇÃO: Buscar cursos que o usuário está matriculado com verificação mais robusta
$meus_cursos_matriculados = [];
try {
    $select = "SELECT c.*, mc.data_matricula, mc.progresso 
               FROM tb_cursos c 
               INNER JOIN tb_matriculas mc ON c.id_curso = mc.id_curso 
               WHERE mc.id_user = :id_user 
               ORDER BY mc.data_matricula DESC";
    $result = $conect->prepare($select);
    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT); // ADICIONE ESTA LINHA
    $result->execute();
    $meus_cursos_matriculados = $result->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    // Se der erro, definir como array vazio
    $meus_cursos_matriculados = [];
    error_log("Erro ao buscar cursos matriculados: " . $e->getMessage());
}

// Cursos pré-definidos (removemos as URLs externas pois estamos usando imagem padrão)
$cursos_pre_definidos = [
    [
        'nome' => 'Desenvolvimento Web Full Stack',
        'carga' => '120 horas',
        'categoria' => 'Tecnologia',
        'nivel' => 'Intermediário',
        'preco' => 897.00,
        'descricao' => 'Aprenda HTML, CSS, JavaScript, React, Node.js e MongoDB para se tornar um desenvolvedor completo.',
        'requisitos' => 'Conhecimento básico de informática'
    ],
    [
        'nome' => 'Python para Data Science',
        'carga' => '80 horas', 
        'categoria' => 'Tecnologia',
        'nivel' => 'Intermediário',
        'preco' => 745.00,
        'descricao' => 'Domine Python, Pandas, NumPy e Machine Learning para análise de dados.',
        'requisitos' => 'Lógica de programação básica'
    ],
    [
        'nome' => 'JavaScript Moderno',
        'carga' => '60 horas',
        'categoria' => 'Tecnologia', 
        'nivel' => 'Iniciante',
        'preco' => 498.00,
        'descricao' => 'Aprenda ES6+, async/await, APIs e conceitos modernos de JavaScript.',
        'requisitos' => 'Conhecimento básico de HTML e CSS'
    ],
    [
        'nome' => 'React.js & Next.js',
        'carga' => '100 horas',
        'categoria' => 'Tecnologia',
        'nivel' => 'Avançado',
        'preco' => 956.00,
        'descricao' => 'Desenvolvimento de aplicações modernas com React, Next.js e TypeScript.',
        'requisitos' => 'JavaScript intermediário'
    ],
    [
        'nome' => 'Marketing Digital',
        'carga' => '70 horas',
        'categoria' => 'Marketing',
        'nivel' => 'Iniciante',
        'preco' => 645.00,
        'descricao' => 'SEO, mídias sociais, Google Ads e estratégias digitais.',
        'requisitos' => 'Conhecimento básico de internet'
    ],
    [
        'nome' => 'Gestão de Projetos com Agile',
        'carga' => '50 horas',
        'categoria' => 'Negócios',
        'nivel' => 'Intermediário',
        'preco' => 598.00,
        'descricao' => 'Metodologias ágeis, Scrum, Kanban e gestão de equipes.',
        'requisitos' => 'Experiência em gestão ou TI'
    ],
    [
        'nome' => 'Design UX/UI',
        'carga' => '80 horas',
        'categoria' => 'Artes',
        'nivel' => 'Intermediário',
        'preco' => 789.00,
        'descricao' => 'Experiência do usuário, interfaces modernas e prototipagem.',
        'requisitos' => 'Conhecimento básico de design'
    ],
    [
        'nome' => 'Oratória e Apresentação',
        'carga' => '30 horas',
        'categoria' => 'Desenvolvimento Pessoal',
        'nivel' => 'Iniciante',
        'preco' => 356.00,
        'descricao' => 'Técnicas para falar em público com confiança e persuasão.',
        'requisitos' => 'Nenhum pré-requisito'
    ]
];

// CORREÇÃO: Cadastrar cursos pré-definidos apenas se não existirem
try {
    foreach ($cursos_pre_definidos as $curso) {
        $verifica_curso = "SELECT id_curso FROM tb_cursos WHERE nome_curso = :nome AND categoria = :categoria";
        $result = $conect->prepare($verifica_curso);
        $result->bindParam(':nome', $curso['nome'], PDO::PARAM_STR);
        $result->bindParam(':categoria', $curso['categoria'], PDO::PARAM_STR);
        $result->execute();
        
        if ($result->rowCount() == 0) {
            $cadastro_curso = "INSERT INTO tb_cursos (nome_curso, carga_horaria, categoria, descricao, nivel, preco, imagem_curso, id_user) 
                              VALUES (:nome, :carga, :categoria, :descricao, :nivel, :preco, 'curso-padrao.jpg', :id_user)";
            
            $result_insert = $conect->prepare($cadastro_curso);
            $result_insert->bindParam(':nome', $curso['nome'], PDO::PARAM_STR);
            $result_insert->bindParam(':carga', $curso['carga'], PDO::PARAM_STR);
            $result_insert->bindParam(':categoria', $curso['categoria'], PDO::PARAM_STR);
            $result_insert->bindParam(':descricao', $curso['descricao'], PDO::PARAM_STR);
            $result_insert->bindParam(':nivel', $curso['nivel'], PDO::PARAM_STR);
            $result_insert->bindParam(':preco', $curso['preco'], PDO::PARAM_STR);
            $result_insert->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $result_insert->execute();
        }
    }
} catch (PDOException $e) {
    error_log("Erro ao cadastrar cursos automáticos: " . $e->getMessage());
}

// CORREÇÃO 4: BUSCAR CURSOS DISPONÍVEIS (APENAS DE OUTROS USUÁRIOS)
$cursos_disponiveis = [];
$total_cursos = 0;

try {
    $select = "SELECT * FROM tb_cursos WHERE id_user != :id_user ORDER BY id_curso DESC";
    $result = $conect->prepare($select); // ✅ FALTAVA ESTA LINHA!
    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $result->execute();
    $cursos_disponiveis = $result->fetchAll(PDO::FETCH_OBJ);
    $total_cursos = count($cursos_disponiveis);
} catch (PDOException $e) {
   error_log("Erro ao buscar cursos disponíveis: " . $e->getMessage());
}

// === 🔍 DEBUG - VERIFICAÇÃO DE CURSOS - COLE AQUI ===
error_log("=== VERIFICANDO CURSOS ===");
error_log("Cursos criados: " . count($meus_cursos_criados));
error_log("Cursos disponíveis: " . count($cursos_disponiveis));
error_log("Cursos matriculados: " . count($meus_cursos_matriculados));

// Verificar conteúdo dos cursos
foreach ($cursos_disponiveis as $index => $curso) {
    error_log("Curso $index: " . $curso->nome_curso . " - Imagem: " . $curso->imagem_curso);
}

// Agrupar cursos por categoria
$cursos_por_categoria = [];
foreach ($cursos_disponiveis as $curso_db) {
    $categoria = $curso_db->categoria;
    if (!isset($cursos_por_categoria[$categoria])) {
        $cursos_por_categoria[$categoria] = [];
    }
    $cursos_por_categoria[$categoria][] = [
        'id' => $curso_db->id_curso,
        'nome' => $curso_db->nome_curso,
        'carga' => $curso_db->carga_horaria,
        'categoria' => $curso_db->categoria,
        'nivel' => $curso_db->nivel,
        'preco' => $curso_db->preco,
        'descricao' => $curso_db->descricao,
        'requisitos' => 'Conhecimento básico',
        'imagem_curso' => $curso_db->imagem_curso
    ];
}

// CORREÇÃO: Processar matrícula em curso com verificação melhorada

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cursos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4A5D73 0%, #324151 100%);
        }
        .modern-input {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }
        .modern-input:focus {
            background: white;
            border-color: #4A5D73;
            box-shadow: 
                0 0 0 3px rgba(74, 93, 115, 0.2),
                0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        .progress-bar {
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-fill {
            background: linear-gradient(90deg, #4A5D73, #324151);
            height: 8px;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .course-card {
            transition: all 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="min-h-screen">

<!-- Header -->
<div class="bg-gradient-to-r from-[#4A5D73] to-[#324151] shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center items-center py-8">
            <h1 class="text-3xl font-bold text-white text-center">Seus Cursos Já!!</h1>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 ml-auto lg:ml-32 xl:ml-48" style="max-width: 90%;">

            <!-- Coluna Esquerda - Formulário de Cursos -->
            <div class="lg:col-span-1">
                <div class="glass-card rounded-2xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                    
                    <!-- Cabeçalho do Formulário -->
                    <div class="bg-gradient-to-r from-[#4A5D73] to-[#324151] px-8 py-6">
                        <h2 class="text-2xl font-bold text-white">Cadastrar curso</h2>
                    </div>

                    <!-- Formulário -->
                    <form class="p-8 space-y-6" action="" method="post" enctype="multipart/form-data" id="formCurso">
                        
                        <!-- Nome do Curso -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                                Nome do Curso
                            </label>
                            <input 
                                type="text" 
                                name="nome" 
                                required
                                class="modern-input w-full px-4 py-3 rounded-lg transition-all duration-200 text-gray-800 placeholder-gray-500"
                                placeholder="Digite o nome do curso"
                            >
                        </div>

                        <!-- Carga Horária -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                                Carga Horária
                            </label>
                            <input 
                                type="text" 
                                name="carga_horaria" 
                                required
                                class="modern-input w-full px-4 py-3 rounded-lg transition-all duration-200 text-gray-800 placeholder-gray-500"
                                placeholder="Ex: 40 horas"
                            >
                        </div>

                        <!-- Categoria -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                                Categoria
                            </label>
                            <select 
                                name="categoria" 
                                required
                                class="modern-input w-full px-4 py-3 rounded-lg transition-all duration-200 text-gray-800 appearance-none bg-white"
                            >
                                <option value="" class="text-gray-400">Selecione uma categoria</option>
                                <option value="Tecnologia" class="text-gray-700">Tecnologia</option>
                                <option value="Negócios" class="text-gray-700">Negócios</option>
                                <option value="Saúde" class="text-gray-700">Saúde</option>
                                <option value="Artes" class="text-gray-700">Artes</option>
                                <option value="Idiomas" class="text-gray-700">Idiomas</option>
                                <option value="Marketing" class="text-gray-700">Marketing</option>
                                <option value="Desenvolvimento Pessoal" class="text-gray-700">Desenvolvimento Pessoal</option>
                            </select>
                        </div>
                        <!-- Descrição do Curso -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                                Descrição do Curso
                            </label>
                            <textarea 
                                name="descricao" 
                                rows="4"
                                required
                                class="modern-input w-full px-4 py-3 rounded-lg transition-all duration-200 text-gray-800 placeholder-gray-500 resize-none"
                                placeholder="Descreva o conteúdo do curso"
                            ></textarea>
                        </div>

                        <!-- Nível do Curso -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                                Nível do Curso
                            </label>
                            <select 
                                name="nivel" 
                                required
                                class="modern-input w-full px-4 py-3 rounded-lg transition-all duration-200 text-gray-800 appearance-none bg-white"
                            >
                                <option value="" class="text-gray-400">Selecione o nível</option>
                                <option value="Iniciante" class="text-gray-700">Iniciante</option>
                                <option value="Intermediário" class="text-gray-700">Intermediário</option>
                                <option value="Avançado" class="text-gray-700">Avançado</option>
                            </select>
                        </div>

                        <!-- Preço -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                                Preço (R$)
                            </label>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="preco"
                                class="modern-input w-full px-4 py-3 rounded-lg transition-all duration-200 text-gray-800 placeholder-gray-500"
                                placeholder="0.00"
                                value="0.00"
                            >
                        </div>

                        <!-- Imagem do Curso -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                                Imagem do curso
                            </label>
                            <div class="flex items-center space-x-3">
                                <input 
                                    type="file" 
                                    name="foto"
                                    accept="image/png, image/jpg, image/jpeg, image/gif"
                                    class="modern-input w-full px-4 py-3 rounded-lg transition-all duration-200 text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-[#E8ECF1] file:text-[#4A5D73] hover:file:bg-[#dfe5ea]"
                                >
                            </div>
                        </div>

                        <!-- Checkbox de Confirmação -->
                        <div class="flex items-center p-4 bg-[#E8ECF1] rounded-lg border border-[#d0d7dd]">
                            <input 
                                type="checkbox" 
                                required
                                class="h-4 w-4 text-[#4A5D73] focus:ring-[#4A5D73] border-gray-300 rounded"
                            >
                            <label class="ml-3 block text-sm font-medium text-gray-700">
                                Confirmo que as informações estão corretas
                            </label>
                        </div>

                        <!-- Botão de Cadastro -->
                        <div class="pt-6">
                            <button 
                                type="submit" 
                                name="botao"
                                class="w-full bg-gradient-to-r from-[#4A5D73] to-[#324151] hover:from-[#576D86] hover:to-[#3A4A5C] text-white font-semibold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:ring-2 focus:ring-[#4A5D73] focus:ring-offset-2 shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-plus-circle mr-2"></i>
                                Cadastrar Curso
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Coluna Direita - Duas Seções -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Seção: Meus Cursos (Criados e Matriculados) -->


<div class="glass-card rounded-2xl overflow-hidden">
    <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-graduation-cap mr-3"></i>
            Meus Cursos
        </h2>
        <p class="text-green-100 mt-2">Cursos que você criou e está matriculado</p>
    </div>

    <div class="p-6">
        <?php 
        // Combinar cursos criados e matriculados
        $todos_meus_cursos = array_merge($meus_cursos_criados, $meus_cursos_matriculados);
        
        // Remover duplicatas (caso o usuário tenha criado e se matriculado no mesmo curso)
        $cursos_unicos = [];
        $ids_vistos = [];
        
        foreach ($todos_meus_cursos as $curso) {
            $curso_id = $curso->id_curso;
            if (!in_array($curso_id, $ids_vistos)) {
                $cursos_unicos[] = $curso;
                $ids_vistos[] = $curso_id;
            }
        }
        ?>
        
        <?php if (count($cursos_unicos) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($cursos_unicos as $curso): ?>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-start space-x-4">
                        <?php 
                        $imagem_curso = getImagemCurso($curso->imagem_curso, $curso->nome_curso, $curso->id_curso);
                        $caminho_completo = "../img/cursos/" . $imagem_curso;
                        ?>
                        <img src="<?php echo $caminho_completo; ?>" 
                             alt="<?php echo htmlspecialchars($curso->nome_curso); ?>" 
                             class="w-16 h-16 rounded-lg object-cover"
                             onerror="this.src='../img/cursos/curso-padrao.jpg'">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($curso->nome_curso); ?></h3>
                            <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($curso->categoria); ?></p>
                            
                            <!-- Indicador se é criador ou aluno -->
                            <div class="mt-2">
                                <?php 
                                $eh_criador = in_array($curso, $meus_cursos_criados);
                                $cor_status = $eh_criador ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800';
                                $texto_status = $eh_criador ? 'Criador' : 'Aluno';
                                ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo $cor_status; ?>">
                                    <i class="fas <?php echo $eh_criador ? 'fa-crown' : 'fa-user-graduate'; ?> mr-1"></i>
                                    <?php echo $texto_status; ?>
                                </span>
                            </div>
                            
                           <div class="flex items-center justify-between mt-2">
    <span class="text-xs text-gray-500">
        Progresso: <?php echo isset($curso->progresso) ? $curso->progresso . '%' : '0%'; ?>
    </span>
    <div class="flex space-x-2">
        <!-- BOTÃO DE DELETAR - APENAS PARA CRIADORES -->
        <?php if ($eh_criador): ?>
        <button onclick="confirmarDelecao(<?php echo $curso->id_curso; ?>, '<?php echo htmlspecialchars($curso->nome_curso); ?>')" 
                class="group relative bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
                title="Deletar curso">
            <i class="fas fa-trash-alt text-xs"></i>
            <span class="absolute -top-8 -left-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                Deletar Curso
            </span>
        </button>
        <?php endif; ?>
    </div>
</div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhum curso encontrado</h3>
                <p class="text-gray-500">Cadastre um novo curso ou matricule-se em um curso disponível!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

                <!-- Seção: Cursos Disponíveis -->
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-[#4A5D73] to-[#324151] px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-book mr-3"></i>
                                Cursos Disponíveis
                            </div>
                            <span class="text-lg bg-blue-500 text-white px-3 py-1 rounded-full">
                                <?php echo count($cursos_disponiveis); ?> cursos
                            </span>
                        </h2>
                        <p class="text-blue-100 mt-2">Organizados por área de conhecimento</p>
                    </div>

                    <div class="p-6">
                        <?php foreach ($cursos_por_categoria as $categoria => $cursos_da_categoria): ?>
                        <!-- Categoria -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <h3 class="text-xl font-bold text-gray-800 mr-4">
                                    <?php 
                                    $icones_categoria = [
                                        'Tecnologia' => '🚀',
                                        'Marketing' => '💼', 
                                        'Negócios' => '📊',
                                        'Artes' => '🎨',
                                        'Desenvolvimento Pessoal' => '🧠'
                                    ];
                                    echo ($icones_categoria[$categoria] ?? '📚') . ' ' . $categoria;
                                    ?>
                                </h3>
                                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    <?php echo count($cursos_da_categoria); ?> curso<?php echo count($cursos_da_categoria) > 1 ? 's' : ''; ?>
                                </span>
                            </div>

                            <!-- Grid de cursos da categoria -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-4">
    <?php foreach ($cursos_da_categoria as $curso): 
        $imagem_curso_disponivel = getImagemCurso($curso['imagem_curso']);
        $caminho_completo_disponivel = "../img/cursos/" . $imagem_curso_disponivel;
    ?>
    <div class="course-card bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 h-full flex flex-col">
        <!-- IMAGEM DO CURSO - ADICIONE ESTE BLOCO -->
        <div class="h-32 bg-gradient-to-r from-[#4A5D73] to-[#324151] relative">
            <img src="<?php echo $caminho_completo_disponivel; ?>" 
                 alt="<?php echo htmlspecialchars($curso['nome']); ?>"
                 class="w-full h-full object-cover"
                 onerror="this.src='../img/cursos/curso-padrao.jpg'">
        </div>
        
        <div class="p-6 flex-1">
                                        <!-- Cabeçalho do Curso -->
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2"><?php echo $curso['nome']; ?></h3>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                                    <?php echo $categoria == 'Tecnologia' ? 'bg-blue-100 text-blue-800' : 
                                                           ($categoria == 'Negócios' ? 'bg-purple-100 text-purple-800' :
                                                           ($categoria == 'Marketing' ? 'bg-pink-100 text-pink-800' :
                                                           ($categoria == 'Artes' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'))); ?>">
                                                    <?php echo $categoria; ?>
                                                </span>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php echo $curso['nivel'] == 'Iniciante' ? 'bg-green-100 text-green-800' : 
                                                       ($curso['nivel'] == 'Intermediário' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                                <?php echo $curso['nivel']; ?>
                                            </span>
                                        </div>

                                        <!-- Descrição -->
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo $curso['descricao']; ?></p>

                                        <!-- Informações Detalhadas -->
                                        <div class="space-y-2 mb-4">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">⏱️ Duração:</span>
                                                <span class="font-semibold text-gray-700"><?php echo $curso['carga']; ?></span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">📋 Requisitos:</span>
                                                <span class="font-semibold text-gray-700 text-right"><?php echo $curso['requisitos']; ?></span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">💰 Investimento:</span>
                                                <span class="font-bold text-[#4A5D73]">R$ <?php echo number_format($curso['preco'], 2, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                  <!-- Botão de Matrícula -->
                                <!-- Espaço reservado -->
                                <div class="px-6 pb-6 pt-4 border-t border-gray-100">
                                    <div class="text-center py-2">
                                        <span class="text-sm text-gray-500">Curso disponível</span>
                                    </div>
                                </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
    
    <script>
function mostrarMensagem(texto, tipo = 'success') {
    const mensagem = document.createElement('div');
    mensagem.textContent = texto;
    
    // Cores baseadas no tipo
    const cores = {
        success: '#4CAF50',
        error: '#f44336',
        warning: '#ff9800',
        info: '#2196F3'
    };
    
    mensagem.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${cores[tipo] || '#4CAF50'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 10000;
        animation: fadeInOut 3s ease-in-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-weight: 500;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    document.body.appendChild(mensagem);
    
    setTimeout(() => {
        if (mensagem.parentNode) {
            mensagem.remove();
        }
    }, 3000);
}

// Também pode usar para outros tipos de mensagem
function mostrarErro(texto) {
    mostrarMensagem(texto, 'error');
}

function mostrarAviso(texto) {
    mostrarMensagem(texto, 'warning');
}

// CORREÇÃO 6: VALIDAÇÃO DO FORMULÁRIO - ADICIONE ESTE CÓDIGO
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCurso');
    if (form) {
        form.addEventListener('submit', function(e) {
            const camposObrigatorios = form.querySelectorAll('[required]');
            let valido = true;
            
            camposObrigatorios.forEach(campo => {
                if (!campo.value.trim()) {
                    valido = false;
                    campo.style.borderColor = '#f44336';
                } else {
                    campo.style.borderColor = '';
                }
            });
            
            if (!valido) {
                e.preventDefault();
                mostrarMensagem('Preencha todos os campos obrigatórios!', 'error');
            }
        });
    }
});
</script>

<style>
@keyframes fadeInOut {
    0% { 
        opacity: 0; 
        transform: translateX(100px) scale(0.8); 
    }
    15% { 
        opacity: 1; 
        transform: translateX(0) scale(1); 
    }
    85% { 
        opacity: 1; 
        transform: translateX(0) scale(1); 
    }
    100% { 
        opacity: 0; 
        transform: translateX(100px) scale(0.8); 
    }
}
</style>
<script>
// 🎯 ADICIONE ESTE CÓDIGO - Mostrar mensagens da sessão PHP
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])): ?>
    setTimeout(() => {
        mostrarMensagem('<?php echo addslashes($_SESSION['mensagem']); ?>', '<?php echo $_SESSION['tipo_mensagem'] ?? 'success'; ?>');
    }, 500);
    
    <?php 
    // Limpar a mensagem após mostrar
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
    ?>
    <?php endif; ?>
});

// Abas dos meus cursos
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remover classe ativa de todas as abas
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'text-blue-600', 'border-blue-600');
            b.classList.add('text-gray-600');
        });
        
        // Adicionar classe ativa na aba clicada
        this.classList.add('active', 'text-blue-600', 'border-blue-600');
        this.classList.remove('text-gray-600');
        
        // Esconder todos os conteúdos
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Mostrar conteúdo da aba clicada
        const tabId = this.getAttribute('data-tab');
        document.getElementById(`tab-${tabId}`).classList.remove('hidden');
    });
});

function confirmarDelecao(id_curso, nome_curso) {
    if (confirm(`Tem certeza que deseja deletar o curso "${nome_curso}"?\n\nEsta ação não pode ser desfeita!`)) {
        // 🎯 TESTE ESTES CAMINHOS (um por vez):
        
        // Opção 1 - Se del-contatos.php está na pasta PAI de conteudo/
        window.location.href = `../del-contatos.php?idDel=${id_curso}&tipo=curso`;
        
        // Opção 2 - Se está na mesma pasta que config/
        // window.location.href = `../../paginas/del-contatos.php?idDel=${id_curso}&tipo=curso`;
        
        // Opção 3 - Caminho absoluto
        // window.location.href = `/index/clone-agenda/paginas/del-contatos.php?idDel=${id_curso}&tipo=curso`;
        
        // Opção 4 - URL completa
        // window.location.href = `http://localhost/index/clone-agenda/paginas/del-contatos.php?idDel=${id_curso}&tipo=curso`;
    }
}

function confirmarDelecao(id_curso, nome_curso) {
    if (confirm(`Tem certeza que deseja deletar o curso "${nome_curso}"?\n\nEsta ação não pode ser desfeita!`)) {
        // 🎯 NOME CORRETO: del-contato.php
        window.location.href = `del-contato.php?idDel=${id_curso}&tipo=curso`;
    }
}

// 🎯 MOSTRAR MENSAGENS DA SESSÃO
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])): ?>
    console.log('📢 Mensagem da sessão:', '<?php echo $_SESSION['mensagem']; ?>');
    setTimeout(() => {
        mostrarMensagem('<?php echo addslashes($_SESSION['mensagem']); ?>', '<?php echo $_SESSION['tipo_mensagem'] ?? 'success'; ?>');
    }, 500);
    
    <?php 
    // Limpar a mensagem após mostrar
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
    ?>
    <?php endif; ?>
});

// 🎯 DEBUG - VERIFICAR CAMINHOS (REMOVA DEPOIS)
console.log('📍 Caminho atual:', window.location.pathname);
console.log('📁 URL completa:', window.location.href);

// Teste manual no console
function testarCaminho() {
    const caminhos = [
        '../del-contatos.php',
        '../../paginas/del-contatos.php',
        '/index/clone-agenda/paginas/del-contatos.php'
    ];
    
    caminhos.forEach(caminho => {
        console.log('🔗 Testando:', caminho);
    });
}

console.log('✅ DEBUG: Função testarCaminho() carregada!');
console.log('💡 Execute no console: testarCaminho()');
</script>
</body>
</html>