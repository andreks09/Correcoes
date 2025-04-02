<?php

session_start();
require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sCoordenacao
};

if(isset($_POST['idSecretaria'])){
    
    $sCoordenacao = new sCoordenacao($_POST['idSecretaria']);
    $sCoordenacao->consultar('ajaxCoordenacao.php');

    if($sCoordenacao->mConexao->getValidador()){
        echo '<option value="0" selected="">--</option>';
        foreach ($sCoordenacao->mConexao->getRetorno() as $value) {
            $_SESSION['credencial']['idSecretaria'] == $value['departamento_secretaria_idsecretaria'] ? $atributo = ' selected' : $atributo = '';
            echo '<option value="' . $value['idcoordenacao'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
        }        
    }else{
        echo '<option value="0" selected="">--</option>';
    }
    
}
?>