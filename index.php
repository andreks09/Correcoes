<?php

$manutenção = false;

if(!$manutenção){
    header('Location: App/telas/acesso/tAcessar.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SSPMI</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css">
        <!-- pace-progress -->
        <link rel="stylesheet" href="vendor/almasaeed2010/adminlte/plugins/pace-progress/themes/black/pace-theme-flat-top.css">
        <!-- adminlte-->
        <link rel="stylesheet" href="vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition register-page">
        <div class="register-box">                
            <a class="btn btn-app bg-green" href="App/telas/acesso/tAcessar.php">
                <span class="badge bg-success"></span>
                <i class="fas fa-user-check"></i> Possuo Acesso
            </a>
            <a class="btn btn-app bg-warning" href="App/telas/acesso/tSolicitarAcesso.php">
                <span class="badge bg-success"></span>
                <i class="fas fa-user-clock"></i> Solicitar Acesso
            </a>
                
            
            
            
            <!--
            <div class="register-logo">
                Quase lá!
            </div>
            <div class="card">
                <div class="card-body register-card-body">
                </div>
                <div class="card-body">
                    <p>
                        Você deve estar ansioso(a) para fazer uso de nosso sistema, porém,
                        ainda temos alguns ajustes para realizar antes da liberação da versão beta.
                    </p>
                    <p>
                        <b>Documentação</b><br />
                        <s>Análise da Metodologia Atual</s><br />
                        <s>Requisitos do Sistema</s><br />
                        <s>Protótipo de Alta Fidelidade (Mockup)</s><br />
                        <s>Modelagem do BD (MER e Físico)</s><br />
                        <s>Contratação de Domínio (itapoa.app.br)</s><br />
                        <s>Contratação de Hospedagem</s><br />
                    </p>
                    <p>
                        <b>Desenvolvimento</b><br />
                        <s>Configuração do Servidor</s><br />
                        Módulo de Acesso - Lógica de Acesso<br />
                        Módulo de Acesso - Lógica de Registros de Usuários<br />
                        Módulo de Suporte - Lógica de Registros de Locais<br />
                        Módulo de Suporte - Lógica de Registros de Equipamentos<br />
                        Módulo de Suporte - Lógica de Tickets<br />
                    </p>
                    <p>
                        <b>Liberação (release)</b><br />
                        Versão Beta<br />
                        Acompanhamento da Versão Beta<br />
                        Correções da Versão Beta<br />
                        Versão Estável<br />
                        Acompanhamento da Versão Estável<br />
                        Correções da Versão Estável<br />
                    </p>
                    <p>
                        Itapoá, 29 de maio de 2024.
                    </p>
                    <p>
                        <a href="http://www.itapoa.app.br/App/telas/acesso/tAcessar.php">Testar</a>
                    </p>
                </div>
            </div>
            -->
        </div>
        <!-- jQuery -->
        <script src="vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- pace-progress -->
        <script src="vendor/almasaeed2010/adminlte/plugins/pace-progress/pace.min.js"></script>
        <!-- AdminLTE App -->
        <script src="vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js"></script>
    </body>
</html>