<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cursos</title>
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
    </style>
</head>
<body class="min-h-screen">

  <!-- Header -->
<div class="bg-gradient-to-r from-[#4A5D73] to-[#324151] shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center items-center py-8">
            <h1 class="text-3xl font-bold text-white text-center">Cadastro de Cursos</h1>
        </div>
    </div>
</div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Coluna Esquerda - Formulário Atualizado -->
                <div class="lg:col-span-1">
                    <div class="glass-card rounded-2xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                        
                        <!-- Cabeçalho do Formulário -->
                        <div class="bg-gradient-to-r from-[#4A5D73] to-[#324151] px-8 py-6">
                            <h2 class="text-2xl font-bold text-white">Cadastrar curso</h2>
                        </div>

                        <!-- Formulário Atualizado -->
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

                            <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                        </form>

                        <!-- PHP Code (MANTENHA SEU PHP ORIGINAL AQUI) -->
                        <?php
                        include('../config/conexao.php');
                        if (isset($_POST['botao'])) {
                            // SEU CÓDIGO PHP ORIGINAL AQUI
                        }
                        ?>
                    </div>
                </div>

                <!-- Coluna Direita - Tabela -->
                <div class="lg:col-span-2">
                    <div class="glass-card rounded-2xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                        <!-- Cabeçalho da Tabela -->
                        <div class="bg-gradient-to-r from-[#4A5D73] to-[#324151] px-8 py-6">
                            <h2 class="text-2xl font-bold text-white">Cursos Recentes</h2>
                        </div>

                        <!-- Tabela -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Imagem</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Categoria</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Carga Horária</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php
                                    $select = "SELECT * FROM tb_contatos WHERE id_user = :id_user ORDER BY id_contatos DESC LIMIT 6";
                                    try {
                                        $result = $conect->prepare($select);
                                        $cont = 1; 
                                        $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                                        $result->execute();
                                        $contar = $result->rowCount();
                                        if ($contar > 0) {
                                            while ($show = $result->FETCH(PDO::FETCH_OBJ)) {
                                    ?>
                                    <tr class="hover:bg-[#E8ECF1] transition-colors duration-150 border-b border-gray-100">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $cont++; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="../img/cont/<?php echo $show->foto_contatos; ?>" alt="Imagem do curso" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            <?php echo $show->nome_contatos; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                            <?php 
                                            $dados = explode(" | ", $show->email_contatos);
                                            echo str_replace("Categoria: ", "", $dados[0]);
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#4A5D73]">
                                            <?php echo $show->fone_contatos; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="home.php?acao=editar&id=<?php echo $show->id_contatos; ?>" class="text-gray-600 hover:text-[#4A5D73] transition-colors duration-200 p-2 rounded hover:bg-[#E8ECF1]">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="conteudo/del-contato.php?idDel=<?php echo $show->id_contatos; ?>" onclick="return confirm('Deseja remover o curso?')" class="text-gray-600 hover:text-red-600 transition-colors duration-200 p-2 rounded hover:bg-red-50">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500"><i class="fas fa-book-open mr-2"></i>Nenhum curso cadastrado</td></tr>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-red-500"><i class="fas fa-exclamation-triangle mr-2"></i>Erro: ' . $e->getMessage() . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>