<?php
include('../../config/conexao.php');

if(isset($_GET['idDel'])){
    $id = $_GET['idDel'];

    $select = "SELECT foto_contatos FROM tb_contatos WHERE id_contatos=:id";
    try {
        $result = $conect->prepare($select);
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

         $foto = 'avatar-padrao.png';  // ← evita erro caso não tenha foto

        $contar = $result->rowCount();
        if ($contar > 0) {
            $dados = $result->fetch(PDO::FETCH_ASSOC);
            $foto = $dados['foto_contatos']; // ← Aqui você passa a ter a foto real

            if (empty($foto)) {
             $foto = 'avatar-padrao.png';
            }
        }
        if ($foto != 'avatar-padrao.png') { // fogo, terra, agua e vento
            $filePath = "../../img/cont/" . $foto;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $delete = "DELETE FROM tb_contatos WHERE id_contatos = :id";
        try {
            $result = $conect->prepare($delete);
            $result->bindValue(':id', $id, PDO::PARAM_INT);
            $result->execute();

            if ($result->rowCount() > 0) {
                header("Location: ../home.php");
            } else {
                header("location: ../home.php");
            }
            
        } catch (PDOException $e) {
            echo "<strong>ERRO DE DELETE: </strong>" . $e->getMessage();
        } 
    } catch (PDOException $e) {
        echo "<strong>ERRO DE DELETE: </strong>" . $e->getMessage();
       
    }
} else {header("Location: ../home.php");}