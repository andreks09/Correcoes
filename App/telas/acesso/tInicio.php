<div class="card card-default">
    <div class="card-header">         
        <h3 class="card-title">
            <i class="fas fa-bullhorn"></i>
            Informativo
        </h3>
    </div>
    <div class="card-body">
        <div class="callout callout-info">
            <div class="row">
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <h5>Implementação - Botão para alterar o equipamento de um ticket já aberto.</h5>
                    <p>
                        Versão: 2.8.2-beta (pós teste);<br />
                        Locais: 2_2_1;<br />
                        Campos: Botão Alterar;<br />
                        Data: 13/06/2025;<br />                
                        Reclamação: Se marcar equipamento como indefinido por equívoco e ele já estiver registrado no sistema, o pessoal do Departamento pede para encerrar o ticket e abrir um novo informando o patrimônio.<br />  
                        Solução: Implementado na tela de visualização do ticket, no quadro das especificações do equipamento, um botão que permite ao usuário
                        de permissão nível 1 alterar o equipamento enquanto estiver na etapa 1. Para usuário de níveis de permissão mais elevados, o botão estará 
                        disponível até o encerramento do ticket.<br />   

                    </p>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/qPev0Wk2b-s?si=1Qq7ptdJuHXBheXk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>                
            </div>            
        </div>
        <div class="callout callout-info">
            <h5>Alteração - Tempo de expiração da sessão</h5>
            <p>
                Versão: 2.8.2-beta (pós teste);<br />
                Locais: tPainel;<br />
                Campos: --;<br />
                Data: 03/06/2025;<br />                
                Reclamação: Pouco tempo para acompanhar os tickets<br />  
                Solução: Alterado tempo do Garbage Collector e parâmetros dos Cookies<br />   
            </p>
        </div>
        <div class="callout callout-warning">
            <h5>Correção - Senha e telefone</h5>
            <p>
                Versão: 2.8.2-beta (pós teste);<br />
                Locais: telas 1_1_1 e 2_1_1;<br />
                Campos: telefone, senha;<br />
                Data: 26/05/2025;<br />                
                Reclamação: Não consigo alterar minha senha<br />  
                Solução: Alterado lógica do método de tratamento e verificação do número do telefone<br />   
            </p>
        </div>
    </div>
</div>
<!--
<div class="callout callout-info">
    <h5>Implementado menu Relatórios->Tickets Encerrados</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: telas 6_1 e 6_1_2;<br />
        Campos: tabela;<br />
        Data: 17/12/2024;<br />                
        Descrição: Implementado menu Relatórios->Tickets Encerrados para privilégios de nível de permissão < 2 e > 2<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Alterado algoritmo para imprimir dados dos locais</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: tela 2_2_1;<br />
        Campos: telefone, e-mail;<br />
        Data: 11/12/2024;<br />                
        Descrição: Implementado lógica para buscar e imprimir os dados de telefone e e-mail da Secretaria, Departamento, Coordenação e Setor.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Implementado algoritmo para alterar dados dos locais</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: telas 4_2_1, 4_2_1_1, 4_2_2, 4_2_2_1, 4_2_3, 4_2_3_1, 4_2_4, 4_2_4_1;<br />
        Campo: --;<br />
        Data: 11/12/2024;<br />                
        Descrição: Implementado lógica de alteração dos dados da Secretaria, Departamento, Coordenação e Setor.<br />                
    </p>
</div>
<div class="callout callout-warning">
    <h5>Implementado Declarações Preparadas para exclusão do BD</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: modelo mConexao;<br />
        Campo: --;<br />
        Data: 11/12/2024;<br />                
        Descrição: Alterado lógica do modelo, atendendo a versão 8.2 da linguagem PHP.<br />                
    </p>
</div>
<div class="callout callout-warning">
    <h5>Implementado Declarações Preparadas para alterações do BD</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: modelo mConexao;<br />
        Campo: --;<br />
        Data: 28/11/2024;<br />                
        Descrição: Alterado lógica do modelo, atendendo a versão 8.2 da linguagem PHP.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Implementação de ícone de copiar</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: tela 2_1_1;<br />
        Campo: e-mail;<br />
        Data: 12/11/2024;<br />                
        Descrição: Implementado ícone e alterado algorítmo para copiar e-mail.<br />                
    </p>
</div>
<div class="callout callout-warning">
    <h5>Resolução de bug</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: tela 2_1_1;<br />
        Campo: descricao;<br />
        Data: 11/11/2024;<br />                
        Descrição: Limitado a quantidade de caracteres do campo para 240.<br />                
    </p>
</div>
<div class="callout callout-warning">
    <h5>Método get para recuperação de senha</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: tela tAlterarSenha;<br />
        Campo: seguranca;<br />
        Data: 08/11/2024;<br />                
        Descrição: Implementado str_replace para substituição do "espaço" por "+".<br />                
    </p>
</div>
<div class="callout callout-warning">
    <h5>Implementado Declarações Preparadas para consultas do BD</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: modelo mConexao;<br />
        Campo: --;<br />
        Data: 07/11/2024;<br />                
        Descrição: Alterado lógica do modelo, atendendo a versão 8.2 da linguagem PHP.<br />                
    </p>
</div>
<div class="callout callout-warning">
    <h5>Credencial do usuário</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: controladores sCargo, sCoordenacao, sDepartamento, sEmail, sPermissao, sSecretaria, sSenha, sSetor, sTelefone, sUsuario;<br />
        Campo: --;<br />
        Data: 29/10/2024;<br />                
        Descrição: Alterado lógica dos controladores, removendo INNER_JOIN e implementando 2 consultas.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Alterar dados dos locais</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: telas 4_2_1, 4_2_2, 4_2_3, 4_2_4, 4_2_1_1, 4_2_2_1, 4_2_3_1, 4_2_4_1;<br />
        Campos: Secretaria, Departamento, Coordenação, Setor, Endereço, E-mail, Ambiente, Telefone, WhatsApp;<br />
        Data: 24/10/2024;<br />                
        Descrição: Implementado menus e telas até 4 níveis para alterar os dados dos locais.<br />                
    </p>
</div>
<div class="callout callout-warning">
    <h5>Correção de bug (Multibyte String)</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Locais: telas 2_2 e 2_2_1;<br />
        Campo: Solicitante;<br />
        Data: 17/10/2024;<br />                
        Descrição: Alterado lógica do algoritmo, ao invés de comparar nome do requerente com solicitante, comparar e-mail do requerente com solicitante.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5><s>Alterado tempo limite do Garbage Collector</s></h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Local: tela tAcessar.php;<br />
        Campo: --;<br />
        Data: 15/10/2024;<br />                
        Descrição: Alterado tempo limite do GC para 6h.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Implementado segurança de cookie</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Local: tela tAcessar.php;<br />
        Campo: --;<br />
        Data: 14/10/2024;<br />                
        Descrição: Habilitado HttpOnly.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Alterar dados dos outros usuários</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Local: Perfil->Outros Usuários->Editar;<br />
        Campos: Nome, Sobrenome, Sexo, Telefone, WhatsApp, E-mail, Permissão, Cargo, Secretaria, Departamento, Coordenação, Setor, Situação;<br />
        Data: 11/10/2024;<br />                
        Descrição: Implementado backend da tela 1_2_1.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Botão de contato</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Local: Barra de menus (top);<br />
        Campo: Conversa via WhatsApp;<br />
        Data: 08/10/2024;<br />                
        Descrição: Incrementado link de redirecionamento para conversa via WhatsApp.<br />                
    </p>
</div>
<div class="callout callout-info">
    <h5>Link de contato</h5>
    <p>
        Versão: 2.8.2-beta (teste);<br />
        Local: Barra de Menus (lateral) Suporte->Acompanhar->Visualizar;<br />
        Campo: Telefone;<br />
        Data: 08/10/2024;<br />                
        Descrição: Incrementado link de redirecionamento para conversa via WhatsApp.<br />                
    </p>
</div>  
<div class="container-fluid">
<div class="row">
<div class="col-lg-4 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <h3>1</h3>
            <p>Atendimentos Aguardando</p>
        </div>
        <div class="icon">
            <i class="ion ion-pie-graph"></i>
        </div>
        <a href="#" class="small-box-footer">Mais Informações <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-4 col-6">
    <div class="small-box bg-warning">
        <div class="inner">
            <h3>2</h3>
            <p>Atendimentos Em Andamento</p>
        </div>
        <div class="icon">
            <i class="ion ion-person-add"></i>
        </div>
        <a href="#" class="small-box-footer">Mais Informações <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-4 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            <h3>1</h3>
            <p>Atendimentos Finalizados</p>
        </div>
        <div class="icon">
            <i class="ion ion-stats-bars"></i>
        </div>
        <a href="#" class="small-box-footer">Mais Informações <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
</div>
-->