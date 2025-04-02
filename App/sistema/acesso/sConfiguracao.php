<?php
namespace App\sistema\acesso;

class sConfiguracao{
    //atributos de diretórios
    private $diretorioPrincipal;
    private $diretorioDoSistema;
    private $diretorioModelo;
    private $diretorioControleAcesso;
    private $diretorioControleSuporte;
    private $diretorioVisualizacaoAcesso;
    private $diretorioVisualizacaoAcessoImagem;
    private $diretorioVisualizacaoSuporte;
    private $diretorioVisualizacaoSuporteImagem;
    
    //atributos do BD
    private $hostname;
    private $username;
    private $password;
    private $database;
    private $port;
    private $socket;
    private $charsetDB;
    
    //atributos das páginas
    private $lang;
    private $charset;
    private $title;
    private $loginLogo;
    
    //atributos de segurança
    private $caracterMinimo;
    private $caracterMaximo;
    private $letra;
    private $numero;
    private $caracterEspecial;
    
    //atributos do sistema
    private $versao;
    private $empresa;
    private $siteDaEmpresa;
    private bool $manutencao;
    private string $prazoManutencao;
    private string $timeZone;
    
    //atributos da empresa
    private string $whatsApp;

    public function __construct() {
        //Diretórios
        $this->diretorioPrincipal = 'https://itapoa.app.br/';
        $this->diretorioDoSistema = 'App/';
        $this->diretorioModelo = 'modelos/';        
        $this->diretorioControleAcesso = $this->diretorioPrincipal.$this->diretorioDoSistema.'sistema/acesso/';
        $this->diretorioControleSuporte = $this->diretorioPrincipal.$this->diretorioDoSistema.'sistema/suporte/';
        $this->diretorioVisualizacaoAcesso = $this->diretorioPrincipal.$this->diretorioDoSistema.'telas/acesso/';
        $this->diretorioVisualizacaoAcessoImagem = $this->diretorioPrincipal.$this->diretorioDoSistema.'telas/acesso/img';
        $this->diretorioVisualizacaoSuporte = $this->diretorioPrincipal.$this->diretorioDoSistema.'telas/suporte/';
        $this->diretorioVisualizacaoSuporteImagem = $this->diretorioPrincipal.$this->diretorioDoSistema.'telas/suporte/img/';
        //AdminLte
        $this->lang = 'pt-BR';
        $this->charset = 'utf-8';
        $this->title = 'SSPMI';
        $this->loginLogo = '<b>SS</b>PMI';
        //BD
        $this->hostname = 'localhost';
        $this->username = 'itapoaap_admin';
        $this->password = 'pr3f31tur4@2024';
        $this->database = 'itapoaap_sspmi';
        $this->port = 3306;
        $this->socket = '';
        $this->charsetDB = 'utf8';
        //Segurança
        $this->caracterMinimo = '7';
        $this->caracterMaximo = '14';
        $this->caracterEspecial = false;
        $this->letra = true;
        $this->numero = true;
        //sistema
        $this->versao = '2.8.2-beta';//2 módulos(perfil, suporte) - 8.0 (versão do BD) - beta (ambiente de execução)
        $this->empresa = 'Prefeitura de Itapoá';
        $this->siteDaEmpresa = 'https://www.itapoa.sc.gov.br';
        $this->manutencao = false;     
        $this->prazoManutencao = '08/11/2024 às 13:15';
        $this->timeZone = date_default_timezone_set('America/Sao_Paulo');
        //empresa
        $this->whatsApp = '5547988272029';
    }
    public function getDiretorioPrincipal() {
        return $this->diretorioPrincipal;
    }

    public function getDiretorioDoSistema() {
        return $this->diretorioDoSistema;
    }

    public function getDiretorioModelo() {
        return $this->diretorioModelo;
    }

    public function getDiretorioControleAcesso() {
        return $this->diretorioControleAcesso;
    }

    public function getDiretorioControleSuporte() {
        return $this->diretorioControleSuporte;
    }

    public function getDiretorioVisualizacaoAcesso() {
        return $this->diretorioVisualizacaoAcesso;
    }

    public function getDiretorioVisualizacaoAcessoImagem() {
        return $this->diretorioVisualizacaoAcessoImagem;
    }

    public function getDiretorioVisualizacaoSuporte() {
        return $this->diretorioVisualizacaoSuporte;
    }

    public function getDiretorioVisualizacaoSuporteImagem() {
        return $this->diretorioVisualizacaoSuporteImagem;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function getPort() {
        return $this->port;
    }

    public function getSocket() {
        return $this->socket;
    }

    public function getCharsetDB() {
        return $this->charsetDB;
    }

    public function getLang() {
        return $this->lang;
    }

    public function getCharset() {
        return $this->charset;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getLoginLogo() {
        return $this->loginLogo;
    }

    public function getCaracterMinimo() {
        return $this->caracterMinimo;
    }

    public function getCaracterMaximo() {
        return $this->caracterMaximo;
    }

    public function getLetra() {
        return $this->letra;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getCaracterEspecial() {
        return $this->caracterEspecial;
    }

    public function getVersao() {
        return $this->versao;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function getSiteDaEmpresa() {
        return $this->siteDaEmpresa;
    }

    public function getManutencao(): bool {
        return $this->manutencao;
    }

    public function getPrazoManutencao(): string {
        return $this->prazoManutencao;
    }

    public function getTimeZone(): string {
        return $this->timeZone;
    }

    public function getWhatsApp(): string {
        return $this->whatsApp;
    }

    public function setDiretorioPrincipal($diretorioPrincipal): void {
        $this->diretorioPrincipal = $diretorioPrincipal;
    }

    public function setDiretorioDoSistema($diretorioDoSistema): void {
        $this->diretorioDoSistema = $diretorioDoSistema;
    }

    public function setDiretorioModelo($diretorioModelo): void {
        $this->diretorioModelo = $diretorioModelo;
    }

    public function setDiretorioControleAcesso($diretorioControleAcesso): void {
        $this->diretorioControleAcesso = $diretorioControleAcesso;
    }

    public function setDiretorioControleSuporte($diretorioControleSuporte): void {
        $this->diretorioControleSuporte = $diretorioControleSuporte;
    }

    public function setDiretorioVisualizacaoAcesso($diretorioVisualizacaoAcesso): void {
        $this->diretorioVisualizacaoAcesso = $diretorioVisualizacaoAcesso;
    }

    public function setDiretorioVisualizacaoAcessoImagem($diretorioVisualizacaoAcessoImagem): void {
        $this->diretorioVisualizacaoAcessoImagem = $diretorioVisualizacaoAcessoImagem;
    }

    public function setDiretorioVisualizacaoSuporte($diretorioVisualizacaoSuporte): void {
        $this->diretorioVisualizacaoSuporte = $diretorioVisualizacaoSuporte;
    }

    public function setDiretorioVisualizacaoSuporteImagem($diretorioVisualizacaoSuporteImagem): void {
        $this->diretorioVisualizacaoSuporteImagem = $diretorioVisualizacaoSuporteImagem;
    }

    public function setHostname($hostname): void {
        $this->hostname = $hostname;
    }

    public function setUsername($username): void {
        $this->username = $username;
    }

    public function setPassword($password): void {
        $this->password = $password;
    }

    public function setDatabase($database): void {
        $this->database = $database;
    }

    public function setPort($port): void {
        $this->port = $port;
    }

    public function setSocket($socket): void {
        $this->socket = $socket;
    }

    public function setCharsetDB($charsetDB): void {
        $this->charsetDB = $charsetDB;
    }

    public function setLang($lang): void {
        $this->lang = $lang;
    }

    public function setCharset($charset): void {
        $this->charset = $charset;
    }

    public function setTitle($title): void {
        $this->title = $title;
    }

    public function setLoginLogo($loginLogo): void {
        $this->loginLogo = $loginLogo;
    }

    public function setCaracterMinimo($caracterMinimo): void {
        $this->caracterMinimo = $caracterMinimo;
    }

    public function setCaracterMaximo($caracterMaximo): void {
        $this->caracterMaximo = $caracterMaximo;
    }

    public function setLetra($letra): void {
        $this->letra = $letra;
    }

    public function setNumero($numero): void {
        $this->numero = $numero;
    }

    public function setCaracterEspecial($caracterEspecial): void {
        $this->caracterEspecial = $caracterEspecial;
    }

    public function setVersao($versao): void {
        $this->versao = $versao;
    }

    public function setEmpresa($empresa): void {
        $this->empresa = $empresa;
    }

    public function setSiteDaEmpresa($siteDaEmpresa): void {
        $this->siteDaEmpresa = $siteDaEmpresa;
    }

    public function setManutencao(bool $manutencao): void {
        $this->manutencao = $manutencao;
    }

    public function setPrazoManutencao(string $prazoManutencao): void {
        $this->prazoManutencao = $prazoManutencao;
    }

    public function setTimeZone(string $timeZone): void {
        $this->timeZone = $timeZone;
    }

    public function setWhatsApp(string $whatsApp): void {
        $this->whatsApp = $whatsApp;
    }
}
?>