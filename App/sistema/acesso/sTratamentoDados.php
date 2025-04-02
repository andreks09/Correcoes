<?php

namespace App\sistema\acesso;

class sTratamentoDados {

    private mixed $dados;

    public function __construct(string $dados) {
        $this->dados = $dados;
    }

    public function tratarNomenclatura() {
        //converte tudo para minúscula
        $minuscula = mb_strtolower($this->getDados());
        $nomenclatura = explode(" ", $minuscula);

        $nomenclaturaTratada = '';
        $j = count($nomenclatura) - 1;
        for ($i = 0; $i < count($nomenclatura); $i++) {
            if (strlen($nomenclatura[$i]) < 3) {
                if ($j == 0) {
                    $nomenclaturaTratada .= $nomenclatura[$i];
                } else {
                    $nomenclaturaTratada .= $nomenclatura[$i] . ' ';
                }
            } else {
                if ($j == $i) {
                    $nomenclaturaTratada .= ucfirst($nomenclatura[$i]);
                } else {
                    $nomenclaturaTratada .= ucfirst($nomenclatura[$i]) . ' ';
                }
            }
        }
        return $nomenclaturaTratada;
    }

    public function tratarPatrimonio() {
        //converte todas para maiúsculas
        $maiuscula = mb_strtoupper($this->getDados());
        if (strlen($maiuscula) < 1) {
            $patrimonioTratado = 'Não Consta';
        } else {
            $patrimonioTratado = $maiuscula;
        }
        return $patrimonioTratado;
    }

    public function tratarData() {
        $dataTratada = date("d/m/Y H:i:s", strtotime(str_replace('-', '/', $this->getDados())));

        return $dataTratada;
    }

    public function tratarTelefone() {
        if (!ctype_alnum($this->getDados())) {
            $telefoneTratado = str_replace(['(', ')', '-', '_', ' '], '', $this->getDados());
        } else {
            $telefoneTratado = str_replace(" ", "", $this->getDados());
            //se for número de telefone fixo
            if (strlen($telefoneTratado) == 10) {
                $mascara = "(##) ####-####";
            } else {
                //se for número de telefone celular
                $mascara = "(##) # ####-####";
            }
            for ($i = 0; $i < strlen($telefoneTratado); $i++) {
                $mascara[strpos($mascara, "#")] = $telefoneTratado[$i];
            }
            $telefoneTratado = $mascara;
        }
        return $telefoneTratado;
    }

    public function tratarEtiquetaDeServico() {
        if (empty($this->getDados())) {
            $etiquetaDeServico = 'Indefinida';
        } else {
            $etiquetaDeServico = $this->getDados();
        }
        return $etiquetaDeServico;
    }

    public function tratarNumeroDeSerie() {
        if (empty($this->getDados())) {
            $numeroDeSerie = 'Indefinida';
        } else {
            $numeroDeSerie = $this->getDados();
        }
        return $numeroDeSerie;
    }

    public function tratarEmail() {
        if (filter_var($this->getDados(), FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    public function corPrioridade() {
        //escolhe a cor de acordo com a prioridade
        switch ($this->getDados()) {
            case 'Normal':
                $cor = 'blue';
                $posicao = 1;
                return $dadosPrioridade = [
                    0 => $posicao,
                    1 => $cor
                ];
                break;
            case 'Alta':
                $cor = 'green';
                $posicao = 2;
                return $dadosPrioridade = [
                    0 => $posicao,
                    1 => $cor
                ];
                break;
            case 'Urgente':
                $cor = 'yellow';
                $posicao = 3;
                return $dadosPrioridade = [
                    0 => $posicao,
                    1 => $cor
                ];
                break;
            case 'Muito Urgente':
                $cor = 'orange';
                $posicao = 4;
                return $dadosPrioridade = [
                    0 => $posicao,
                    1 => $cor
                ];
                break;
            case 'Emergente':
                $cor = 'red';
                $posicao = 5;
                return $dadosPrioridade = [
                    0 => $posicao,
                    1 => $cor
                ];
                break;
            default:
                break;
        }
    }

    public function tratarSenha() {
        /*
         * [RF011] Ao registrar um novo usuário o sistema deverá gerar uma senha
         * aleatória com os seguintes requisitos, de 7 a 14 caracteres, devendo
         * conter ao menos uma letra e ao menos um número, não sendo aceito
         * caracteres especiais.
         */
        if (strlen($this->getDados()) < 7 || strlen($this->getDados()) > 14) {
            //caso a senha tenha menos que 7 caracteres ou mais que 14
            return false;
        } else {
            //verifica se tem somente caracteres alfanuméricos
            if (ctype_alnum($this->getDados())) {
                if (preg_match("/[a-zA-Z]/", $this->getDados())) {
                    if (preg_match("/[0-9]/", $this->getDados())) {
                        return $this->getDados();
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function tratarNavegador() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent) || preg_match('/Trident/i', $u_agent)) {
            $bname = 'IE';
            $ub = "MSIE";
        } elseif (preg_match('/Edge/i', $u_agent)) {
            $bname = 'EDGE';
            $ub = "Edge";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'FIREFOX';
            $ub = "FIREFOX";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'CHROME';
            $ub = "CHROME";
        } elseif (preg_match('/AppleWebKit/i', $u_agent)) {
            $bname = 'OPERA';
            $ub = "OPERA";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'SAFARI';
            $ub = "SAFARI";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'NETSCAPE';
            $ub = "NETSCAPE";
        }else{
            $bname = 'Indefinido';
        }

        return $bname;
    }

    public function getDados(): mixed {
        return $this->dados;
    }

    public function setDados(mixed $dados): void {
        $this->dados = $dados;
    }

}
