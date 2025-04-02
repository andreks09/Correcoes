<?php
namespace App\sistema\acesso;

use App\sistema\acesso\{sConfiguracao};

class sNotificacao{
    private $letra;
    private $numero;
    private $codigo;
    private $tipo;
    private $titulo;
    private $mensagem;
    public sConfiguracao $sConfiguracao;


    public function __construct($codigo) {
        $this->codigo = $codigo;
        $this->gerarNotificacao();
    }
    
    protected function gerarNotificacao() {
        //separa o tipo de notificação e seu índice
        $this->letra = substr($this->codigo, 0, 1);
        $this->numero = substr($this->codigo, 1);
        
        //se a for uma notificação de Alerta, execute o seguinte método
        if($this->letra == 'A'){
            $this->tipoAtencao();
        }else if($this->letra == 'E'){
            $this->tipoErro();
        }else if($this->letra == 'S'){
            $this->tipoSucesso();
        }else if($this->letra == 'I'){
            $this->tipoInformacao();
        }else{
            
        }        
    }
    
    private function tipoAtencao() {
        $this->setTipo('warning');
        $this->setTitulo('Atenção!');
        $this->setSConfiguracao(new sConfiguracao());
        
        switch ($this->getNumero()) {
            case '1':               
                $this->setMensagem('O e-mail informado não está registrado em nosso sistema.');
                break;
            case '2':
                $this->setMensagem('O e-mail informado não é um endereço de e-mail válido.');
                break;
            case '3':
                $this->setMensagem('E-mail já registrado, favor clique na opção <a href="tRecuperarAcesso.php">esqueci minha senha</a>.');
                break;
            case '4':
                $this->setMensagem('A senha deve ter de '.$this->sConfiguracao->getCaracterMinimo().' à '.$this->sConfiguracao->getCaracterMaximo().' caracteres e possuir somente letras e números.');
                break;
            case '5':
                $this->setMensagem('A senha deve possuir somente letras e números');
                break;
            case '6':
                $this->setMensagem('Senha incorreta, tente novamente.');
                break;
            case '7':
                $this->setMensagem('Esta conta está inativa, contate o administrador e informe o código ('.$this->getCodigo().').');
                break;
            case '8':
                $this->setMensagem('O campo deve conter de 2 a 20 caracteres');
                break;
            case '9':
                $this->setMensagem('Só pode ser inserido letras no campo');
                break;
            case '10':
                $this->setMensagem('O campo deve conter de 2 a 100 caracteres');
                break;
            case '11':
                $this->setMensagem('O telefone deve conter o seguinte formato (99) 9 9999-9999 ou (99) 9999-9999.');
                break;
            case '12':
                $this->setMensagem('Esse e-mail já está registrado em nosso sistema, contate o administrador e informe o código ('.$this->getCodigo().').');
                break;
            case '13':
                $this->setMensagem('Esse número de telefone já está registrado');
                break;
            case '14':
                $this->setMensagem('Identificamos que já existe uma solicitação, favor verifique sua caixa de e-mail. Caso já tenha decorrido 24 horas úteis, favor contatar o administrador do sistema e informe o código ('.$this->getCodigo().').');
                break;
            case '15':
                $this->setMensagem('Identificamos que já existe um registro com essa nomenclatura');
                break;
            case '16':
                $this->setMensagem('O campo deve conter de 5 a 100 caracteres');
                break;
            case '17':
                $this->setMensagem('Você deve escolher uma das opções do campo');
                break;
            case '18':
                $this->setMensagem('Se você não localizou o "Patrimônio" na lista abaixo, então você deve escolher uma das opções do campo "Categoria"');
                break;
            case '19':
                $this->setMensagem('Se você localizou o Patrimônio na lista abaixo, então deve "Escolher" e clicar em "Próximo".');
                break;
            case '20':
                $this->setMensagem('Essa solicitação já foi aprovada, caso esteja com problemas de acesso contate o administrador');
                break;
            case '21':
                $this->setMensagem('Esse equipamento já foi registrado.');
                break;
            case '22':
                $this->setMensagem('Esse suporte está atribuído a outro responsável.');
                break;
            case '23':
                $this->setMensagem('Para reatribuir o chamado você deve escolher um responsável diferente do atual.');
                break;
            case '24':
                $this->setMensagem('O nome do usuário não atende os requisitos do sistema. O campo deve conter de 2 a 20 caracteres');
                break;
            case '25':
                $this->setMensagem('O nome do usuário não atende os requisitos do sistema. Só pode conter letras');
                break;
            case '26':
                $this->setMensagem('O sobrenome do usuário não atende os requisitos do sistema. O campo deve conter de 2 a 100 caracteres');
                break;
            case '27':
                $this->setMensagem('O sobrenome do usuário não atende os requisitos do sistema. Só pode conter letras');
                break;
            case '28':
                $this->setMensagem('Por alguma razão não foi enviado o e-mail para o usuário. Contate o administrador do sistema informando o erro e contate o usuário e oriente-o para recuperar a senha.');
                break;
            case '29':
                $this->setMensagem('Por alguma razão não foi enviado o e-mail de recuperação de senha, contate o administrador e informe o código ('.$this->getCodigo().').');
                break;
            case '30':
                $this->setMensagem('A senha e a confirmação de senha não coincidem.');
                break;
            case '31':
                //não localizou a chave no bd
                $this->setMensagem('A chave informada não atende os requisitos de segurança do sistema, contate o administrador e informe o código ('.$this->getCodigo().').');
                break;
            case '32':
                //não localizou a chave no bd
                $this->setMensagem('O prazo da chave de recuperação de senha expirou, solicite uma recuperação de senha novamente.');
                break;
            case '33':
                //não localizou a chave no bd
                $this->setMensagem('Esse equipamento não pode ter seus atributos alterados devido ao [RF014] O sistema deve conter um registro de equipamento indefinido e o mesmo não poderá ter seus atributos alterados.');
                break;          
            default:
                $this->setTipo('danger');
                $this->setTitulo('Erro!');
                $this->setMensagem('Código: '.$this->getCodigo().' não localizado, favor contatar o desenvolvedor.');
                break;
        }
    }
    
    private function tipoErro() {
        $this->setTipo('danger');
        $this->setTitulo('Erro!');
        
        switch ($this->getNumero()) {
            case '1':               
                $this->setMensagem('Você está tentando violar uma política de privacidade, verificaremos os logs de acordo com a notificação ('.$this->getCodigo().').');
                break;
            case '2':
                $this->setMensagem('A estrutura de menus apresentou uma inconsistência, favor contate o administrador do sistema e informe a notificação ('.$this->getCodigo().').');
                break;
            case '3':
                $this->setMensagem('Um parâmetro do método CRUD foi definido incorretamente, contate o administrador do sistema e informe este erro ('.$this->getCodigo().').');
                break;
            case '4':
                //deve existir um registro na tabela de equipamento com patrimônio de nomenclatura "Indefinido"
                $this->setMensagem('O sistema necessita ser configurado corretamente, contate o administrador do sistema e informe este erro ('.$this->getCodigo().').');
                break;
            case '5':
                //Não há ID do último e-mail registrado, verifique o método getRegistro da classe mConexao
                $this->setMensagem('Não foi possível registrar o e-mail, contate o administrador do sistema e informe este erro ('.$this->getCodigo().').');
                break;
            case '6':
                //Não há ID do último e-mail registrado, verifique o método getRegistro da classe mConexao
                $this->setMensagem('Não foi possível registrar o telefone, contate o administrador do sistema e informe este erro ('.$this->getCodigo().').');
                break;
            default:
                $this->setTipo('danger');
                $this->setTitulo('Erro!');
                $this->setMensagem('Código: '.$this->getCodigo().' não localizado, favor contatar o desenvolvedor.');
                break;
        }
    }
    
    private function tipoSucesso() {
        $this->setTipo('success');
        $this->setTitulo('Sucesso!');
        
        switch ($this->getNumero()) {
            case '1':
                $this->setMensagem('Alterações realizadas.');
                break;
            case '2':
                $this->setMensagem('Verifique seu e-mail para finalizar a recuperação de acesso.');
                break;
            case '3':
                $this->setMensagem('Registro realizado, você receberá um e-mail com a liberação de acesso do Departamento de Tecnologia da Informação em até 24 horas úteis.');
                break;
            case '4':
                $this->setMensagem('Registro realizado.');
                break;
            case '5':
                $this->setMensagem('Suporte finalizado.');
                break;
            case '6':
                $this->setMensagem('Ticket atribuído para '.$_SESSION['credencial']['nome'].' '.$_SESSION['credencial']['sobrenome']);
                break;
            case '7':
                $this->setMensagem('Ticket reatribuído.');
                break;
            default:
                $this->setTipo('danger');
                $this->setTitulo('Erro!');
                $this->setMensagem('Código: '.$this->getCodigo().' não localizado, favor contatar o desenvolvedor.');
                break;
        }
    } 
    
    private function tipoInformacao() {
        $this->setTipo('info');
        $this->setTitulo('Informativo!');        
        $this->setSConfiguracao(new sConfiguracao());
        
        switch ($this->getNumero()) {
            case '1':
                $this->setMensagem('O sistema está em manutenção. Previsão de retorno: '.$this->sConfiguracao->getPrazoManutencao());
                break;
            default:
                $this->setTipo('danger');
                $this->setTitulo('Erro!');
                $this->setMensagem('Código: '.$this->getCodigo().' não localizado, favor contatar o desenvolvedor.');
                break;
        }
    }
    
    public function getLetra() {
        return $this->letra;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getMensagem() {
        return $this->mensagem;
    }

    public function getSConfiguracao(): sConfiguracao {
        return $this->sConfiguracao;
    }

    public function setLetra($letra): void {
        $this->letra = $letra;
    }

    public function setNumero($numero): void {
        $this->numero = $numero;
    }

    public function setCodigo($codigo): void {
        $this->codigo = $codigo;
    }

    public function setTipo($tipo): void {
        $this->tipo = $tipo;
    }

    public function setTitulo($titulo): void {
        $this->titulo = $titulo;
    }

    public function setMensagem($mensagem): void {
        $this->mensagem = $mensagem;
    }

    public function setSConfiguracao(sConfiguracao $sConfiguracao): void {
        $this->sConfiguracao = $sConfiguracao;
    }


}
?>