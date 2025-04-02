<?php

session_start();
require_once '../../../vendor/autoload.php';

use App\sistema\suporte\{
    sModelo
};

$sModelo = new sModelo();
$sModelo->setNomeCampo('marca_idmarca');
$sModelo->setValorCampo($_POST['idMarca']);
$sModelo->consultar('ajaxModelo.php');

if ($sModelo->mConexao->getValidador()) {
    foreach ($sModelo->mConexao->getRetorno() as $value) {
        echo '<option value="' . $value['idmodelo'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
    }
} else {
    echo '<option value="0" selected>--</option>';
}
?>