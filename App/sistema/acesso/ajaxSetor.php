<?php

session_start();
require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSetor
};

if(isset($_POST['idSecretaria'])){
    $sSetor = new sSetor($_POST['idSecretaria']);
    $sSetor->consultar('ajaxSetor.php');

    if($sSetor->mConexao->getValidador()){
        echo '<option value="0" selected="">--</option>';
        foreach ($sSetor->mConexao->getRetorno() as $value) {
            $_SESSION['credencial']['idSecretaria'] == $value['coordenacao_departamento_secretaria_idsecretaria'] ? $atributo = ' selected' : $atributo = '';
            echo '<option value="' . $value['idsetor'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
        }        
    }else{
        echo '<option value="0" selected="">--</option>';
    }    
}
?>