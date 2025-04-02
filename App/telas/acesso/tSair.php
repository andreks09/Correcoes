<?php
require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{sSair};

$sSair = new sSair();
$sSair->verificar('1');

?>