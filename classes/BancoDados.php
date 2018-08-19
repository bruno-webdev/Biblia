<?php

class BancoDados
{
    //atributo tipo "object" que armazena a conexão
    protected $conexao;

    /**
     * Método que cria uma conexão com o banco de dados
     */
    public function conectar()
    {
        $this->conexao = new PDO('mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DB, USER, PASS);
    }

    /**
     * Método para desconectar o banco de dados, zerando o atributo
     */
    public function desconectar()
    {
        $this->conexao = null;
    }

    /**
     * @param array $busca
     * @return array com resultados da busca
     */
    public function select($busca)
    {
        $table = $busca['table']; //Nome da tabela
        $exp = isset($busca['exp']) ? $busca['exp'] : '*'; //Lista de campos ou * por default
        $where = isset($busca['where']) ? $this->where($busca['where']) : ''; //Where caso possua
        $orderBy = isset($busca['orderBy']) ? $this->orderBy($busca['orderBy']) : ''; //OrderBy caso possua
        $groupBy = isset($busca['groupBy']) ? 'GROUP BY ' . $busca['groupBy'] : ''; //groupBy caso possua
        $join = isset($busca['join']) ? $this->innerJoin($busca['join']) : ''; //inner join caso possua

        $sql = "SELECT " . $exp . " FROM {$table} {$join} {$where} {$orderBy} {$groupBy};"; //Comando SQL depois dos filtros

        //Abre conexão com o banco
        $this->conectar();

        //Faz a consulta
        $sth = $this->conexao->prepare($sql);
        $sth->execute();

        /* Atribui o resultado da consulta dentro de $result */
        $result = $sth->fetchAll();

        //Desconecta do banco
        $this->desconectar();

        //Retorna o resultado
        return $result;
    }

    /**
     * @param array/string $joinArray
     * @return string
     */
    public function innerJoin($joinArray)
    {
        $joins = []; //Cria array default
        if (is_array($joinArray)) { //Verifica se é um array (Aceita inner join em texto também)

            if (isset($joinArray['table'])) { //Verifica se existe apenas um inner join, se sim retorna o inner join
                return "INNER JOIN {$joinArray['table']} ON {$joinArray['on']}";
            } else { //Caso contrário faz o foreach para retornar os inner joins necessários
                foreach ($joinArray as $joinItem) {
                    $joins[] = "INNER JOIN {$joinItem['table']} ON {$joinItem['on']}";
                }
                return implode(' ', $joins);
            }

        } else {
            return (string) $joinArray;
        }
    }

    /**
     * @param array/string $orderByArray
     * @return string
     */
    public function orderBy($orderByArray)
    {
        if (is_array($orderByArray)) { //Verifica se é array ou texto com o order by
            $orderBy = [];
            foreach ($orderByArray as $orderByItem) {
                if (is_array($orderByItem)) { //Verifica se tem vários order by
                    $direc = isset($orderByItem[1]) ? $orderByItem[1] : 'ASC';
                    $orderBy[] = "{$orderByItem[0]} {$direc}";
                } else {
                    $orderBy[] = $orderByItem . " ASC";
                }
            }
            return count($orderBy) > 1 ? "order by " . implode(", ", $orderBy) : '';
        }

        return "order by " . $orderByArray;
    }

    /**
     * @param string $whereArray
     * @return string
     */
    public function where($whereArray)
    {
        $where = [];
        foreach ($whereArray as $whereItem) {
            /*
             * whereItem[0] é o campo da tabela
             * whereItem[1] é o é o operador de comparação (=, <>, LIKE, etc)
             * whereItem[2] é a string a ser comparada e filtrada
             */
            $where[] = "{$whereItem[0]} {$whereItem[1]} '{$whereItem[2]}'";
        }

        if (count($where) > 0) {
            return 'where ' . implode(' AND ', $where);
        }
        return '';
    }
}