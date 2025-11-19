<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Cadastro de Cursos</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-4">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Cadastrar curso</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="nome">Nome do Curso</label>
                    <input type="text" class="form-control" name="nome" id="nome" required placeholder="Digite o nome do curso">
                  </div>
                  <div class="form-group">
                    <label for="carga_horaria">Carga Horária</label>
                    <input type="text" class="form-control" name="carga_horaria" id="carga_horaria" required placeholder="Ex: 40 horas">
                  </div>
                  <div class="form-group">
                    <label for="categoria">Categoria</label>
                    <select class="form-control" name="categoria" id="categoria" required>
                      <option value="">Selecione uma categoria</option>
                      <option value="Tecnologia">Tecnologia</option>
                      <option value="Negócios">Negócios</option>
                      <option value="Saúde">Saúde</option>
                      <option value="Artes">Artes</option>
                      <option value="Idiomas">Idiomas</option>
                    </select>
                  </div>
                  
                <div class="form-group">
                  <label for="descricao">Descrição do Curso</label>
                  <textarea class="form-control" name="descricao" id="descricao" rows="3" placeholder="Descreva o conteúdo do curso"></textarea>
                </div>

                <div class="form-group">
                  <label for="nivel">Nível do Curso</label>
                  <select class="form-control" name="nivel" id="nivel" required>
                    <option value="">Selecione o nível</option>
                    <option value="Iniciante">Iniciante</option>
                    <option value="Intermediário">Intermediário</option>
                    <option value="Avançado">Avançado</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="preco">Preço (R$)</label>
                  <input type="number" step="0.01" class="form-control" name="preco" id="preco" placeholder="0.00">
                </div>

                  <div class="form-group">
                    <label for="foto">Imagem do curso</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto" id="foto">
                        <label class="custom-file-label" for="foto">Arquivo de imagem</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="hidden" class="custom-file-input" name="id_user" id="id_user" value="<?php echo $id_user ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                    <label class="form-check-label" for="exampleCheck1">Confirmo que as informações estão corretas</label>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" name="botao" class="btn btn-primary">Cadastrar Curso</button>
                </div>
              </form>
              <?php
                // Inclui o arquivo de conexão com o banco de dados
                include('../config/conexao.php');

                // Verifica se o formulário foi submetido
                if (isset($_POST['botao'])) {
                    // Recupera os valores do formulário
                    $nome = $_POST['nome'];
                    $carga_horaria = $_POST['carga_horaria'];
                    $categoria = $_POST['categoria'];
                    $id_usuario = $_POST['id_user'];
                    $descricao = $_POST['descricao'];
                    $nivel = $_POST['nivel'];
                    $preco = $_POST['preco'];

                    // Combine as informações extras em um campo (já que não temos campos extras no banco)
                    $info_completa = "Categoria: $categoria | Nível: $nivel | Preço: R$ $preco | Descrição: $descricao";
                    // Define os formatos de imagem permitidos
                    $formatP = array("png", "jpg", "jpeg", "JPG", "gif");
                
                    // Verifica se a imagem foi enviada e se é válida
                    if (isset($_FILES['foto'])) {
                        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    
                        // Verifica se o formato da imagem é permitido
                        if (in_array($extensao, $formatP)) {
                            // Define o diretório para upload da imagem
                            $pasta = "../img/cont/";
                        
                            // Move o arquivo temporário para o diretório de upload
                            $temporario = $_FILES['foto']['tmp_name'];
                            $novoNome = uniqid() . ".$extensao";
                        
                            if (move_uploaded_file($temporario, $pasta . $novoNome)) {
                                // Se o upload for bem-sucedido, define o nome do arquivo como o nome da imagem
                                $foto = $novoNome;
                            } else {
                                // Se o upload falhar, exibe mensagem de erro e define o avatar padrão
                                echo "Erro, não foi possível fazer o upload do arquivo!";
                                $foto = 'avatar_padrao.png';
                            }
                        } else {
                            // Se o formato da imagem não for permitido, exibe mensagem de erro e define o avatar padrão
                            echo "Formato Inválido";
                            $foto = 'avatar_padrao.png';
                        }
                    } else {
                        // Se não houver imagem enviada, define o avatar padrão
                        $foto = 'avatar_padrao.png';
                    }
                  
                    // Prepara a consulta SQL para inserir os dados no banco de dados EXISTENTE
                    // Mapeamento: nome_contatos = nome do curso, fone_contatos = carga horária, email_contatos = categoria
                    $cadastro = "INSERT INTO tb_contatos (nome_contatos, fone_contatos, email_contatos, foto_contatos, id_user) 
                                VALUES (:nome, :carga_horaria, :categoria, :foto, :id_user)";
                  
                    try {
                        // Prepara a consulta SQL com os parâmetros
                        $result = $conect->prepare($cadastro);
                        $result->bindParam(':nome', $nome, PDO::PARAM_STR);
                        $result->bindParam(':carga_horaria', $carga_horaria, PDO::PARAM_STR);
                        $result->bindParam(':categoria', $info_completa, PDO::PARAM_STR);
                        $result->bindParam(':foto', $foto, PDO::PARAM_STR);
                        $result->bindParam(':id_user', $id_usuario, PDO::PARAM_INT);
                    
                        // Executa a consulta SQL
                        $result->execute();
                    
                        // Verifica se a inserção foi bem-sucedida
                        $contar = $result->rowCount();
                        if ($contar > 0) {
                            // Se a inserção for bem-sucedida, exibe mensagem de sucesso
                            echo '<div class="container">
                                    <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> OK!</h5>
                                    Curso cadastrado com sucesso !!!
                                  </div>
                                </div>';
                            header("Refresh: 5, home.php");
                        } else {
                            // Se a inserção falhar, exibe mensagem de erro
                            echo '<div class="container">
                                  <div class="alert alert-danger alert-dismissible">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                  <h5><i class="icon fas fa-check"></i> Erro!</h5>
                                  Curso não cadastrado !!!
                                </div>
                              </div>';
                            header("Refresh: 5, home.php");
                        }
                    } catch (PDOException $e) {
                        // Exibe mensagem de erro se ocorrer um erro de PDO
                        echo "<strong>ERRO DE PDO= </strong>" . $e->getMessage();
                    }
                  }
              ?>
            </div>
          </div>
            
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Cursos Recentes</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Imagem</th>
                      <th>Nome</th>
                      <th>Categoria</th>
                      <th>Carga Horária</th>
                      <th style="width: 40px">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  // Consulta SQL para selecionar os cursos do usuário atual
                  // Usando a tabela tb_contatos existente
                  $select = "SELECT * FROM tb_contatos WHERE id_user = :id_user ORDER BY id_contatos DESC LIMIT 6";
                  
                  try {
                      // Prepara a consulta SQL com o parâmetro :id_user
                      $result = $conect->prepare($select);
                      // Inicializa o contador de linhas
                      $cont = 1; 
                      // Vincula o ID do usuário ao parâmetro :id_user
                      $result->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                      // Executa a consulta SQL
                      $result->execute();
                      // Verifica se a consulta retornou algum resultado
                      $contar = $result->rowCount();
                      if ($contar > 0) {
                          // Itera sobre cada linha de resultado da consulta
                          while ($show = $result->FETCH(PDO::FETCH_OBJ)) {
                  ?>  
                                      
                     <tr>
                    <td><?php echo $cont++; ?></td>
                    <td> <img src="../img/cont/<?php echo $show->foto_contatos; ?>" alt="Imagem do curso" style="width:40px; height:40px; object-fit:cover; border-radius:5px;"></td>
                    <td><?php echo $show->nome_contatos; ?></td>
                    <td>
                      <?php 
                      // Extrai apenas a categoria principal
                      $dados = explode(" | ", $show->email_contatos);
                      echo str_replace("Categoria: ", "", $dados[0]); // Mostra "Tecnologia"
                      ?>
                  </td>
                    <td><?php echo $show->fone_contatos; ?></td> <!-- Carga horária está no campo fone_contatos -->
                    <td>
                        <div class="btn-group">
                            <!-- Botão para editar o curso -->
                            <a href="home.php?acao=editar&id=<?php echo $show->id_contatos; ?>" class="btn btn-success" title="Editar Curso"><i class="fas fa-edit"></i></a>

                            <!-- Botão para remover o curso -->
                            <a href="conteudo/del-contato.php?idDel=<?php echo $show->id_contatos; ?>" onclick="return confirm('Deseja remover o curso?')" class="btn btn-danger" title="Remover Curso"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                    <?php
                            }
                        } else {
                            // Se a consulta não retornar resultados, exibe uma mensagem
                            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Não há cursos cadastrados!</strong></div>';
                        }
                    } catch (PDOException $e) {
                        // Exibe a mensagem de erro de PDO
                        echo '<strong>ERRO DE PDO= </strong>' . $e->getMessage();
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->