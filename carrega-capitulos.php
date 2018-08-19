<?php
//Instancia as configurações
require_once('app/config.php');

//Armazena o nome do livro abreviado em $livro
$livro = filter_input(INPUT_GET, 'livro');

//Instancia a classe Biblia
require_once('classes/Biblia.php');
$biblia = new Biblia();

//Carrega os capítulos para uma combo box (select)
$biblia->capitulos($livro);