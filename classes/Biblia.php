<?php
require_once('BancoDados.php');

class Biblia extends BancoDados
{
    /**
     * Exibe as versoes da biblia para uma combo box (select)
     */
    public function versoes()
    {
        //Define os campos de busca
        $busca = [
            'table' => 'versoes'
        ];

        $versoes = $this->select($busca); //Traz as versoes

        //Verifica se já existe parâmetro de busca, se sim acrescenta o atributo "selected" para deixar selecionado
        $paramVersao = filter_input(INPUT_GET, 'versao');
        foreach ($versoes as $versao) {
            $vrs_id = $versao['vrs_id'];
            echo "<option value='{$vrs_id}'" . ($paramVersao === $vrs_id ? 'selected' : null) . ">"
                . utf8_encode($versao['vrs_nome']) . "</option>";
        }
    }

    /**
     * Exibe os livros da bíblia para uma combo box (select)
     */
    public function livro()
    {
        //Define os campos de busca
        $busca = [
            'table' => 'livros',
            'orderBy' => [
                'liv_tes_id', 'liv_posicao'
            ]
        ];

        $livros = $this->select($busca); //Armazena os livros dentro da variável $livros

        //Verifica se já existe parâmetro de busca, se sim acrescenta o atributo "selected" para deixar selecionado
        $paramLivro = filter_input(INPUT_GET, 'livro');

        foreach ($livros as $livro) {
            echo "<option value='{$livro['liv_abreviado']}' " . ($paramLivro === $livro['liv_abreviado'] ? 'selected' : null) . ">"
                . utf8_encode($livro['liv_nome']) . "</option>";
        }
    }

    /**
     * @param string/integer $livroId
     * Exibe os capítulos de um livro numa combo box (select)
     */
    public function capitulos($livroId)
    {
        /* Define os valores padrões para inner join e id do livro caso já receba como parâmetro o id do livro */
        $join = "";
        $livId = ['v.ver_liv_id', '=', $livroId];

        //Caso receba como parâmetro a abreviação do livro, faz o inner join
        if(!is_numeric($livroId)) {
            $join = [
                "table" => "livros l",
                "on" => "l.liv_id = v.ver_liv_id"
            ];
            $livId = ['l.liv_abreviado', '=', $livroId];
        }

        //Define os campos de busca
        $busca = [
            'table' => 'versiculos v',
            'where' => [
                ['v.ver_vrs_id', '=', 6],
                $livId
            ],
            'join' => $join,
            'groupBy' => 'v.ver_capitulo'
        ];

        //Armazena os capitulos dentro da variável $capitulos
        $capitulos = $this->select($busca);

        //Verifica se já existe parâmetro de busca, se sim acrescenta o atributo "selected" para deixar selecionado
        $paramCap = filter_input(INPUT_GET, 'cap');
        for ($i = 1; $i <= count($capitulos); $i++) {
            echo "<option value='{$i}' " . ((int) $paramCap === $i ? 'selected' : '') . ">{$i}</option>";
        }
    }

    /**
     * @param array $param
     * Traz os versículos para uma tabela
     */
    public function versiculo($param)
    {
        //Traz para variáveis os filtros de livro, versão e capítulo
        $livro = $param['livro'];
        $versao = $param['versao'];
        $cap = $param['cap'];

        //Define a refinação de busca
        $busca = [
            'table' => 'versiculos v',
            'exp' => 'v.*',
            'where' => [
                ['v.ver_vrs_id', '=', $versao],
                ['l.liv_abreviado', '=', $livro],
                ['v.ver_capitulo', '=', $cap]
            ],
            'join' => [
                [
                    'table' => 'livros l',
                    'on' => 'l.liv_id = v.ver_liv_id'
                ]
            ],
            'orderBy' => 'v.ver_versiculo'
        ];

        //Coloca os versículos dentro da variável $versiculos
        $versiculos = $this->select($busca);

        foreach ($versiculos as $versiculo) {
            echo "<tr><td>" . utf8_encode($versiculo['ver_texto']) . "</td></tr>";
        }
    }

    /**
     * @param string $livroAbrev
     */
    public function getNomeLivro($livroAbrev)
    {
        //Define o refinamento da busca
        $busca = [
            'table' => 'livros',
            'exp' => 'liv_nome',
            'where' => [
                ['liv_abreviado', '=', $livroAbrev]
            ]
        ];

        //Armazena o nome do livro na variável $livro
        $livro = $this->select($busca)[0]['liv_nome'];

        //Exibe o nome do livro
        echo utf8_encode($livro);
    }
}