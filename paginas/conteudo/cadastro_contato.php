<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cursos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">

  <!-- Header Mais Destacado -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg">
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

                <!-- Coluna Esquerda - Formulário -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <!-- Cabeçalho do Card -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-lg">
                            <h2 class="text-lg font-semibold text-white">Cadastrar curso</h2>
                        </div>

                        <!-- Formulário -->
                        <form class="p-6 space-y-4" action="" method="post" enctype="multipart/form-data">
                            
                            <!-- Nome do Curso -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nome do Curso
                                </label>
                                <input 
                                    type="text" 
                                    name="nome" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Digite o nome do curso"
                                >
                            </div>

                            <!-- Carga Horária -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Carga Horária
                                </label>
                                <input 
                                    type="text" 
                                    name="carga_horaria" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Ex: 40 horas"
                                >
                            </div>

                            <!-- Categoria -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Categoria
                                </label>
                                <select 
                                    name="categoria" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                >
                                    <option value="">Selecione uma categoria</option>
                                    <option value="Tecnologia">Tecnologia</option>
                                    <option value="Negócios">Negócios</option>
                                    <option value="Saúde">Saúde</option>
                                    <option value="Artes">Artes</option>
                                    <option value="Idiomas">Idiomas</option>
                                </select>
                            </div>

                            <!-- Descrição -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Descrição do Curso
                                </label>
                                <textarea 
                                    name="descricao" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="Descreva o conteúdo do curso"
                                ></textarea>
                            </div>

                            <!-- Nível -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nível do Curso
                                </label>
                                <select 
                                    name="nivel" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                >
                                    <option value="">Selecione o nível</option>
                                    <option value="Iniciante">Iniciante</option>
                                    <option value="Intermediário">Intermediário</option>
                                    <option value="Avançado">Avançado</option>
                                </select>
                            </div>

                            <!-- Preço -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Preço (R$)
                                </label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="preco"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="0.00"
                                >
                            </div>

                            <!-- Imagem -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Imagem do curso
                                </label>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="file" 
                                        name="foto"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    >
                                </div>
                            </div>

                            <!-- Checkbox -->
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    required
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label class="ml-2 block text-sm text-gray-700">
                                    Confirmo que as informações estão corretas
                                </label>
                            </div>

                            <!-- Botão -->
                            <div class="pt-4">
                                <button 
                                    type="submit" 
                                    name="botao"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:ring-4 focus:ring-blue-300"
                                >
                                    Cadastrar Curso
                                </button>
                            </div>

                            <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                        </form>

                        <!-- PHP Code (mantenha o mesmo) -->
                        <?php
                        include('../config/conexao.php');
                        if (isset($_POST['botao'])) {
                            // ... seu código PHP atual aqui ...
                        }
                        ?>
                    </div>
                </div>

                <!-- Coluna Direita - Tabela -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <!-- Cabeçalho da Tabela -->
                        <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4 rounded-t-lg">
                            <h2 class="text-lg font-semibold text-white">Cursos Recentes</h2>
                        </div>

                        <!-- Tabela -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagem</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carga Horária</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
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
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $cont++; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="../img/cont/<?php echo $show->foto_contatos; ?>" alt="Imagem do curso" class="w-10 h-10 rounded-lg object-cover">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?php echo $show->nome_contatos; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php 
                                            $dados = explode(" | ", $show->email_contatos);
                                            echo str_replace("Categoria: ", "", $dados[0]);
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo $show->fone_contatos; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="home.php?acao=editar&id=<?php echo $show->id_contatos; ?>" class="text-green-600 hover:text-green-900 transition-colors duration-200">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="conteudo/del-contato.php?idDel=<?php echo $show->id_contatos; ?>" onclick="return confirm('Deseja remover o curso?')" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum curso cadastrado</td></tr>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">Erro: ' . $e->getMessage() . '</td></tr>';
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