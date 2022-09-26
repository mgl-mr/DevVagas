<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <?php
            include_once('conecta.php');
            include_once('funcoes.php');
            session_start();   
            
            if(!isset($_SESSION['logado']) || $_SESSION['logado'] == FALSE){
                header('location:login.php');
            }
        ?>
        <title>Index</title>
    </head>
    <body>
        <Form action="logica.php" method="post">
            <input type="text" name="busca" placeholder="Pesquisar Produtos">
            <button type="submit" name="buscar" id="buscar">Pesquisar</button>
        </Form>
        <?php
            if(!empty($_SESSION['busca'])){
                echo "<h1>Resultados para: ". $_SESSION['busca']."</h1>";
                $produtos = buscar($conexao);

                if(empty($produtos)){
                    echo "<p>Não há produtos cadastrados</p1>";
                }else{
                    foreach($produtos as $produto){
        ?>              <section>
                            <p>Codigo: <?php echo $produto['idProduto']?></p>
                            <p>Nome: <?php echo $produto['nome']?></p>
                            <p>Descrição: <?php echo $produto['descricao']?></p>
                            <p>Quantidade: <?php echo $produto['quantidade']?></p>
                            <p>Categoria: <?php echo $produto['idCategoria']?></p><br><br>
                        </section>
        <?php                
                    }
                }
            }
            $_SESSION['busca'] = "";
        ?>
        <div id="msg">
        <?php 
            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
        ?>
    </div>
        <nav>
            <ul>
                <li><a href="cadastrarCategoria.php">Cadastrar Categoria</a></li>
                <li><a href="cadastrarProduto.php">Cadastrar produto</a></li>
                <li><a href="alterarSenha.php">Alterar senha</a></li>
            </ul>
        </nav>

        <form action="logica.php" method="post">
        <input type="submit" name="sair" value="sair">
    </form>
    </body>
</html>