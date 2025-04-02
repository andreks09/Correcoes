<?php
if($_POST['pagina'] == '3_2_1'){
    header('Location: ../../telas/acesso/tPainel.php?menu=0&notificacao=S1');
}else if($_POST['pagina'] == '2_2_1'){
    header('Location: ../../telas/acesso/tPainel.php?menu=3_2_1&id=2');
}
