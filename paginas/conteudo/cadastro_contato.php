<?php
include_once('../config/conexao.php');

// Verificar se usuário está logado
if (!isset($_SESSION['loginUser']) || !isset($_SESSION['senhaUser'])) {
    header("Location: ../index.php?acao=negado");
    exit;
}

// CORREÇÃO: Usar o ID do usuário correto da sessão
// Se você tem um campo de ID do usuário na sessão, use-o
$id_user = $_SESSION['id_user'] ?? $_SESSION['senhaUser']; // Ajuste conforme sua estrutura

// Função para verificar se imagem existe
function getImagemCurso($imagem_curso) {
    $caminho_imagem = "../img/cursos/" . $imagem_curso;
    
    if (!empty($imagem_curso) && file_exists($caminho_imagem) && $imagem_curso != 'curso-padrao.jpg') {
        return $imagem_curso;
    } else {
        return 'curso-padrao.jpg';
    }
}

// Processar cadastro do curso
if (isset($_POST['botao'])) {
    $nome_curso = $_POST['nome'];
    $carga_horaria = $_POST['carga_horaria'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $nivel = $_POST['nivel'];
    $preco = $_POST['preco'];

    // Upload da imagem
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $formatosPermitidos = array("png", "jpg", "jpeg", "gif");
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        if (in_array(strtolower($extensao), $formatosPermitidos)) {
            $pasta = "../img/cursos/";
            $temporario = $_FILES['foto']['tmp_name'];
            $novoNome = uniqid() . ".$extensao";

            if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                $foto_curso = $novoNome;
            } else {
                echo '<script>mostrarMensagem("Erro no upload da imagem! Usando imagem padrão.", "warning");</script>';
                $foto_curso = 'curso-padrao.jpg';
            }
        } else {
            echo '<script>mostrarMensagem("Formato de imagem não permitido! Usando imagem padrão.", "warning");</script>';
            $foto_curso = 'curso-padrao.jpg';
        }
    } else {
        $foto_curso = 'curso-padrao.jpg';
    }

    // Inserir no banco de dados
    $cadastro = "INSERT INTO tb_cursos (nome_curso, carga_horaria, categoria, descricao, nivel, preco, imagem_curso, id_user) 
                VALUES (:nome, :carga, :categoria, :descricao, :nivel, :preco, :foto, :id_user)";

    try {
        $result = $conect->prepare($cadastro);
        $result->bindParam(':nome', $nome_curso, PDO::PARAM_STR);
        $result->bindParam(':carga', $carga_horaria, PDO::PARAM_STR);
        $result->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $result->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $result->bindParam(':nivel', $nivel, PDO::PARAM_STR);
        $result->bindParam(':preco', $preco, PDO::PARAM_STR);
        $result->bindParam(':foto', $foto_curso, PDO::PARAM_STR);
        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $result->execute();
        
        if ($result->rowCount() > 0) {
            echo '<script>mostrarMensagem("Curso cadastrado com sucesso!", "success");</script>';
        }
    } catch (PDOException $e) {
        echo '<script>mostrarMensagem("Erro ao cadastrar curso: ' . $e->getMessage() . '", "error");</script>';
    }
}

// Buscar cursos que o usuário criou
$meus_cursos_criados = [];
try {
    $select = "SELECT * FROM tb_cursos WHERE id_user = :id_user ORDER BY id_curso DESC";
    $result = $conect->prepare($select);
    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $result->execute();
    $meus_cursos_criados = $result->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo '<script>mostrarMensagem("Erro ao buscar cursos criados: ' . $e->getMessage() . '", "error");</script>';
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
    $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
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

// Buscar os cursos disponíveis do banco
$cursos_disponiveis = [];
try {
    $select = "SELECT * FROM tb_cursos ORDER BY id_curso DESC";
    $result = $conect->prepare($select);
    $result->execute();
    $cursos_disponiveis = $result->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo '<script>mostrarMensagem("Erro ao buscar cursos disponíveis: ' . $e->getMessage() . '", "error");</script>';
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
                    <form class="p-8 space-y-6" action="" method="post" enctype="multipart/form-data">
                        
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

                <!-- Seção: Meus Cursos (Matriculados) -->
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-graduation-cap mr-3"></i>
                            Meus Cursos
                        </h2>
                        <p class="text-green-100 mt-2">Cursos que você está matriculado</p>
                    </div>

                    <div class="p-6">
                        <?php if (count($meus_cursos_matriculados) > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($meus_cursos_matriculados as $curso): ?>
                                <div class="bg-white rounded-lg border border-gray-200 p-4">
                                    <div class="flex items-start space-x-4">
                                        <img src="../img/cursos/<?php echo getImagemCurso($curso->imagem_curso); ?>" 
                                             alt="<?php echo $curso->nome_curso; ?>" 
                                             class="w-16 h-16 rounded-lg object-cover"
                                             onerror="this.src='../img/cursos/curso-padrao.jpg'">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800"><?php echo $curso->nome_curso; ?></h3>
                                            <p class="text-sm text-gray-600 mt-1"><?php echo $curso->categoria; ?></p>
                                            <div class="flex items-center justify-between mt-2">
                                                <span class="text-xs text-gray-500">Progresso: <?php echo $curso->progresso ?? 0; ?>%</span>
                                                <a href="ver-curso.php?id=<?php echo $curso->id_curso; ?>" 
                                                   class="bg-[#4A5D73] text-white px-3 py-1 rounded text-sm hover:bg-[#3A4A5C] transition-colors">
                                                    Continuar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Nenhum curso matriculado</h3>
                                <p class="text-gray-500">Explore os cursos disponíveis e comece sua jornada de aprendizado!</p>
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
                                
                            ?>
                                <div class="course-card bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 h-full flex flex-col">
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
</body>
</html>