<?php
namespace App\sistema\acesso;

use App\sistema\acesso\{sConfiguracao};

class sSair{
    private string $validador;
    public sConfiguracao $sConfiguracao;
    public sNotificacao $sNotificacao;
    
    public function verificar(string $validador) {
        $this->setValidador($validador);
        $this->setSConfiguracao(new sConfiguracao());
        header("Location: {$this->sConfiguracao->getDiretorioVisualizacaoAcesso()}tAcessar.php?validador={$this->getValidador()}");   
        session_start();
        session_destroy();
    }
    
    public function notificar(string $validador) {
        $this->setValidador($validador);
        $this->setSNotificacao(new sNotificacao('E1'));
    }
    
    public function getValidador(): string {
        return $this->validador;
    }

    public function getSConfiguracao(): sConfiguracao {
        return $this->sConfiguracao;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setValidador(string $validador): void {
        $this->validador = $validador;
    }

    public function setSConfiguracao(sConfiguracao $sConfiguracao): void {
        $this->sConfiguracao = $sConfiguracao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }


}
?>