<?php
namespace App\sistema\acesso;
use App\modelo\{mConexao};

class sSenha {
    private string $email;
    private string $senhaCriptografada;
    private string $senha;
    private bool $validador;
    public sNotificacao $sNotificacao;
    public sConfiguracao $sConfiguracao;
    public mConexao $mConexao;
    public sTratamentoDados $sTratamentoDados;
        
    public function __construct(bool $validador) {
        $this->validador = $validador;
    }    
    
    public function gerar() {
        //declara uma variável com todas as letras e outra com todos os números possíveis
        $letras = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeros = '0123456789';
        
        //declara variável vazia para ser incrementada posteriormente
        $letrasSorteadas = '';
        $numerosSorteados = '';
        
        //seleciona randomicamente 5 letras
        for ($i = 0; $i < 5; $i++) {
            $indice = rand(0, strlen($letras) - 1);
            $letrasSorteadas .= $letras[$indice];
        }
        
        //seleciona randomicamente 5 números
        for ($i = 0; $i < 5; $i++) {
            $indice = rand(0, strlen($numeros) - 1);
            $numerosSorteados .= $numeros[$indice];
        }
        
        //concatena e atribui as letras e números
        $this->setSenha($letrasSorteadas.$numerosSorteados);
    }
    
    public function criptografar($senha) {
        $this->setSenha($senha);
        $this->setSenhaCriptografada(password_hash(hash_hmac("sha256", $senha, "sspmi"), PASSWORD_ARGON2ID));
    }
    
    public function verificar($pagina) {        
        //verifica os requisitos da senha
        if($pagina == 'tAcessar.php'){
            //verifica se a senha informada é a mesma constante no BD
            if(password_verify(hash_hmac("sha256", $this->getSenha(), "sspmi"), $this->getSenhaCriptografada())){
                //permita o acesso ao sistema
                $this->setValidador(true);
            }else{
                $this->setValidador(false);
            }
        }
                
        if($pagina == 'tAlterarSenha.php'){
            //verifica os requisitos da senha
            $this->sTratamentoDados->setDados($this->getSenha());
            if($this->sTratamentoDados->tratarSenha()){
                //pega a senha do BD
                $this->mConexao = new mConexao();

                $dados = [
                    'comando' => 'SELECT',
                    'busca' => 'senha',
                    'tabelas' => 'email',
                    'camposCondicionados' => 'nomenclatura',
                    'valoresCondicionados' => $this->getEmail(),
                    'camposOrdenados' => null,//caso não tenha, colocar como null
                    'ordem' => 'ASC'
                ];

                //busca a senha do e-mail correspondente
                $this->mConexao->CRUD($dados);            

                //verifica se a senha informada é a mesma constante no BD
                if(password_verify(hash_hmac("sha256", $this->getSenha(), "sspmi"), $this->mConexao->getRetorno())){
                    //permita o acesso ao sistema
                    $this->setValidador(true);
                }else{
                    $this->setValidador(false);
                    $this->setSNotificacao(new sNotificacao('A6'));
                }
            }
        }
    }
    

    public function getEmail(): string {
        return $this->email;
    }

    public function getSenhaCriptografada(): string {
        return $this->senhaCriptografada;
    }

    public function getSenha(): string {
        return $this->senha;
    }

    public function getValidador(): bool {
        return $this->validador;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function getSConfiguracao(): sConfiguracao {
        return $this->sConfiguracao;
    }

    public function getMConexao(): mConexao {
        return $this->mConexao;
    }

    public function getSTratamentoDados(): sTratamentoDados {
        return $this->sTratamentoDados;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setSenhaCriptografada(string $senhaCriptografada): void {
        $this->senhaCriptografada = $senhaCriptografada;
    }

    public function setSenha(string $senha): void {
        $this->senha = $senha;
    }

    public function setValidador(bool $validador): void {
        $this->validador = $validador;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }

    public function setSConfiguracao(sConfiguracao $sConfiguracao): void {
        $this->sConfiguracao = $sConfiguracao;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }

    public function setSTratamentoDados(sTratamentoDados $sTratamentoDados): void {
        $this->sTratamentoDados = $sTratamentoDados;
    }

}
