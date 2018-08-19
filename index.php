<?php
//Instancia bíblia e configurações
require_once('app/config.php');
require_once('classes/Biblia.php');

$biblia = new Biblia();
?>
<!DOCTYPE html>
<html>
<header>
    <meta charset="<?php echo CHARSET; ?>"> <!-- Charset definido em config.php -->
    <title>Busca bíblia</title>

    <!-- Instancia da versão web mesmo do Fontawesome e Bootstrap apenas para ficar mais bem visível -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</header>
<body>

<header style="margin-bottom: 30px;">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo INCIAL_PAGE; ?>">Bruno Souza</a><!-- INICIAL_PAGE definido em config.php -->
        </div>
    </nav>
</header>

<div class="container">
    <section id="formulario">

        <!-- Formulário de busca -->
        <form action="" method="get" class="row" id="formSearch">
            <div class="col-sm-5 form-group">
                <label for="versao">Versão</label>
                <select name="versao" id="versao" class="form-control">
                    <!-- Traz as versoes da biblia da classe Biblia -->
                    <?php $biblia->versoes(); ?>
                </select>
            </div>
            <div class="col-sm-4 form-group">
                <label for="livro">Livro</label>
                <select name="livro" id="livro" class="form-control">
                    <!-- Traz os livros da biblia da classe Biblia -->
                    <?php $biblia->livro(); ?>
                </select>
            </div>
            <div class="col-sm-2 form-group">
                <label for="cap">Capítulo</label>
                <select name="cap" id="cap" class="form-control">
                    <?php
                    //Verifica se tem parâmetro de busca, caso nao possua coloca livro de Gênesis como default
                    $livro = filter_input(INPUT_GET, 'livro');
                    $liv = $livro !== null ? $livro : 1;
                    //Traz os capítulos do livro definido por busca ou GN por default
                    $biblia->capitulos($liv);
                    ?>
                </select>
            </div>
            <div class="col-sm-1 form-group">
                <label>&nbsp;</label>
                <button class="btn btn-primary form-control">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>

    </section>

    <section id="versiculos">
        <!-- Caso possua os campos de busca traz a tabela com os versículos -->
        <?php
        $get = filter_input_array(INPUT_GET);

        if ($get !== null && isset($get['cap']) && isset($get['livro']) && isset($get['versao'])) {
            require_once('versiculos-table.php');
        }
        ?>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>