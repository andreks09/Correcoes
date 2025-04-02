<?php

namespace App\modelo;

use App\sistema\acesso\{
    sNotificacao,
    sConfiguracao
};

class mConexao {

    private int $registro;
    private mixed $retorno;
    private mixed $conexao;
    private bool $validador;
    public sConfiguracao $sConfiguracao;
    public sNotificacao $sNotificacao;

    public function __construct() {
        $this->sConfiguracao = new sConfiguracao();
        $this->setConexao(new \mysqli(
                        $this->sConfiguracao->getHostname(),
                        $this->sConfiguracao->getUsername(),
                        $this->sConfiguracao->getPassword(),
                        $this->sConfiguracao->getDatabase(),
                        $this->sConfiguracao->getPort(),
                        $this->sConfiguracao->getSocket()
        ));
        mysqli_set_charset($this->conexao, $this->sConfiguracao->getCharsetDB());
        $this->validador = false;
    }

    public function CRUD($dados) {
        //verifica qual comando foi passado para solicitar o método apropriado
        switch ($dados['comando']) {
            case 'SELECT':
                $this->consultar($dados);
                break;
            case 'INSERT INTO':
                $this->inserir($dados);
                break;
            case 'UPDATE':
                $this->atualizar($dados);
                break;
            case 'DELETE':
                $this->deletar($dados);
                break;
            default:
                $this->setSNotificacao(new sNotificacao('E3'));
                $this->setValidador(false);
                break;
        }
    }

    private function consultar($dados) {
        //monta a query de consulta
        $query = '';
        $n1 = '';
        $n2 = '';
        $ordem = false;
        $valoresCondicionados = false;

        foreach ($dados as $key => $value) {
            if ($key == 'busca' && $value) {
                $query .= $value . ' FROM ';
            } else if ($key == 'camposCondicionados' && $value) {
                
                if ($dados['valoresCondicionados'] == "null") {
                    $query .= 'WHERE ' . $value . ' <=> ';
                } else if($dados['valoresCondicionados'] == "is not null"){
                    $query .= 'WHERE ' . $value.'!=';
                }else {
                    if($dados['camposCondicionados'] == 'dataHoraAbertura'){
                        $query .= 'WHERE YEAR(' . $dados['camposCondicionados'] . ')=';
                    }else{
                        $query .= 'WHERE ' . $value . '=';
                    }
                }
            } else if ($key == 'valoresCondicionados' && $value) {
                $valoresCondicionados = true;
                $n1 = '?';
                $n2 = '?';
            } else if ($key == 'camposOrdenados' && $value) {
                $ordem = true;
                $query .= $n1 . ' ORDER BY ' . $value . ' ';
            } else if ($key == 'ordem') {
                if ($ordem) {
                    $query .= $value;
                } else {
                    $query .= $n2;
                }
            } else {
                if ($valoresCondicionados) {
                    $query .= $value;
                } else {
                    $query .= $value . ' ';
                }
            }
        }
                
        $stmt = $this->conexao->prepare($query);
        
        if (empty($dados['valoresCondicionados'])) {   
            $stmt->execute([]);
        } else if ($dados['valoresCondicionados'] == 'null') {  
            $stmt->execute([null]);
        } else if ($dados['valoresCondicionados'] == 'is not null') {  
            $stmt->execute([$dados['valoresCondicionados']]);
        }else {           
            $stmt->execute([$dados['valoresCondicionados']]);
        }

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            foreach ($resultado as $linha) {
                $this->setRetorno($resultado);
            }
            $this->setValidador(true);
        } else {
            $this->setValidador(false);
        }
        
    }

    public function inserir($dados) {
        //monta a query para inserção dos dados
        $j = count($dados['camposInsercao']) - 1;
        $query = '';
        $query .= $dados['comando'] . ' ';
        $query .= $dados['tabela'] . '(';
        for ($i = 0; $i < count($dados['camposInsercao']); $i++) {
            if ($i == $j) {
                $query .= $dados['camposInsercao'][$i] . ') ';
            } else {
                $query .= $dados['camposInsercao'][$i] . ',';
            }
        }
        $query .= 'VALUES (';
        for ($i = 0; $i < count($dados['valoresInsercao']); $i++) {
            if ($i == $j) {
                $query .= '?)';
            } else {
                $query .= '?,';
            }
        }

        //insere os dados no bd com tratamento bind_param
        $resultado = $this->conexao->execute_query($query, $dados['valoresInsercao']);

        //pega o último registro inserido no bd
        $id = $this->conexao->insert_id;
        $this->setRegistro($id);

        if ($resultado) {
            $this->setValidador(true);
        } else {
            $this->setValidador(false);
        }
    }

    public function atualizar($dados) {
        $query = '';
        $query .= $dados['comando'] . ' ';
        $query .= $dados['tabela'] . ' ';
        $query .= 'SET ';
        $query .= $dados['camposAtualizar'];
        if ($dados['valoresAtualizar'] == "null") {
            $query .= ' <=> ';
            $query .= '?';
        } else {
            $query .= '=';
            $query .= '?';
        }
        $query .= ' WHERE ';

        $query .= $dados['camposCondicionados'] . '=';
        $query .= "'{$dados['valoresCondicionados']}'";

        //echo $query;
        //exit();

        $stmt = $this->conexao->prepare($query);
        if (empty($dados['valoresCondicionados'])) {
            $stmt->execute([]);
        } else if ($dados['valoresCondicionados'] == 'null') {
            $stmt->execute([null]);
        } else {
            $stmt->execute([$dados['valoresAtualizar']]);
        }

        $stmt->get_result();

        $this->setValidador(true);
        //UPDATE table_name SET column1=value, column2=value2 WHERE some_column=some_value
    }

    public function deletar($dados) {
        //monta a query para exclusão dos dados
        foreach ($dados as $key => $value) {
            if ($key == 'comando') {
                $query = $value . ' FROM ';
            }
            if ($key == 'tabela') {
                $query .= $value.' WHERE ';
                
                if(is_array($dados['camposCondicionados'])){
                    $parte = explode('_', $value);
                    
                    if(isset($parte)){
                        if($parte[0] == 'email'){
                            $email = [
                                'email_idemail' => ''
                            ];
                            $campo1 = 'email_idemail';
                            if($value == 'email_has_secretaria'){
                                $local = [
                                    'secretaria_idsecretaria' => ''
                                ];
                                $tabela = 'secretaria';                           
                                $campo2 = 'secretaria_idsecretaria';
                            }else if($value == 'email_has_departamento'){
                                $local = [
                                    'departamento_iddepartamento' => ''
                                ];
                                $tabela = 'departamento';
                                $campo2 = 'departamento_iddepartamento';
                            }else if($value == 'email_has_coordenacao'){
                                $local = [
                                    'coordenacao_idcoordenacao' => ''
                                ];
                                $tabela = 'coordenacao';
                                $campo2 = 'coordenacao_idcoordenacao';
                            }else if($value == 'email_has_setor'){
                                $local = [
                                    'setor_idsetor' => ''
                                ];
                                $tabela = 'setor';
                                $campo2 = 'setor_idsetor';
                            }
                        }
                    }
                }else{
                    $query .= $dados['camposCondicionados'].' = ';  
                    $query .= '?';
                }               
            }     
        }
        
        //caso seja um array, implemente mais campos
        if(isset($campo2)){
            $query .= $campo1.' = ';
            $query .= '?';
            $query .= ' AND ';
            $query .= $campo2.' = ';
            $query .= '?';
        }
        
        //echo $query;
        
        //deleta os dados no bd com tratamento bind_param
        if(is_array($dados['valoresCondicionados'])){
            $this->conexao->execute_query($query, $dados['valoresCondicionados']); 
        }else{
            $this->conexao->execute_query($query, [$dados['valoresCondicionados']]); 
        }
               
        $this->setValidador(true);
    }

    public function getRegistro(): int {
        return $this->registro;
    }

    public function getRetorno(): mixed {
        return $this->retorno;
    }

    public function getConexao(): mixed {
        return $this->conexao;
    }

    public function getValidador(): bool {
        return $this->validador;
    }

    public function getSConfiguracao(): sConfiguracao {
        return $this->sConfiguracao;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setRegistro(int $registro): void {
        $this->registro = $registro;
    }

    public function setRetorno(mixed $retorno): void {
        $this->retorno = $retorno;
    }

    public function setConexao(mixed $conexao): void {
        $this->conexao = $conexao;
    }

    public function setValidador(bool $validador): void {
        $this->validador = $validador;
    }

    public function setSConfiguracao(sConfiguracao $sConfiguracao): void {
        $this->sConfiguracao = $sConfiguracao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }
}
