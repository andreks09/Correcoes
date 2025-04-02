<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Etapa 2 - Responsável</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Secretaria</label>
                                <select class="form-control" name="secretaria">
                                    <option value="" selected="" >--</option>
                                    <option value="administracao">Administração</option>
                                    <option value="infraestrutura">Infraestrutura</option>
                                    <option value="ouvidoria">Ouvidoria</option>
                                    <option value="turismo">Turismo</option>
                                </select>
                            </div>
                        </div>                            
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Departamento/ Unidade</label>
                                <select class="form-control" name="departamento">
                                    <option value="" selected="" >--</option>
                                    <option value="recursosHumanos">Recursos Humanos</option>
                                    <option value="tecnologiaDaInformacao">Tecnologia da Informação</option>
                                    <option value="frotas">Frotas</option>
                                    <option value="patrimonio">Patrimônio</option>
                                    <option value="licitacoesECompras">Licitações e Compras</option>
                                </select>
                            </div>
                        </div>        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Coordenação</label>
                                <select class="form-control" name="modelo">
                                    <option value="" selected="" >--</option>
                                    <option value="informaticaESistemas">Informática e Sistemas</option>
                                    <option value="pessoalERecursosHumanos">Pessoal e Recursos Humanos</option>
                                    <option value="comprasEAlmoxarifado">Compras e Almoxarifado</option>
                                    <option value="contratosELicitacoes">Contratos e Licitações</option>
                                    <option value="patrimonioPublico">Patrimônio Público</option>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Setor</label>
                                <select class="form-control" name="modelo">
                                    <option value="" selected="" >--</option>
                                    <option value="informatica">Informática</option>
                                    <option value="captacaoERecursos">Captação de Recursos</option>
                                    <option value="gestaoDeConvenios">Gestão de Convênios</option>
                                    <option value="pessoal">Pessoal</option>
                                    <option value="recursosHumanos">Recursos Humanos</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" value="responsavel" name="opcao" form="responsavel">
                        <input type="hidden" value="menu3_1_1" name="pagina" form="responsavel">
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="responsavel" method="post" enctype="multipart/form-data">
                    <!-- /.card-body-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div class="row">
        <!-- left column -->
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Secretaria</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="categoria">Nomenclatura</label>
                            <input type="text" class="form-control" name="secretaria" placeholder="Ex.: Administração">
                            <input type="hidden" value="secretaria" name="opcao" form="secretaria">
                            <input type="hidden" value="menu3_1_1" name="pagina" form="secretaria">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" method="post" id="secretaria" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Departamento/ Unidade</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="marca">Nomenclatura</label>
                            <input type="text" class="form-control" name="departamentoUnidade" placeholder="Ex.: Tecnologia da Informação" form="departamentoUnidade">
                            <input type="hidden" value="departamentoUnidade" name="opcao" form="departamentoUnidade">
                            <input type="hidden" value="menu3_1_1" name="pagina" form="departamentoUnidade">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="departamentoUnidade" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Coordenação</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="modelo">Nomenclatura</label>
                            <input type="text" class="form-control" name="coordenacao" placeholder="Ex.: Informática e Sistemas" form="coordenacao">
                            <input type="hidden" value="coordenacao" name="opcao" form="coordenacao">
                            <input type="hidden" value="menu3_1_1" name="pagina" form="coordenacao">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="coordenacao" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Setor</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="tensao">Nomenclatura</label>
                            <input type="text" class="form-control" name="setor" placeholder="Ex.: Informática">
                            <input type="hidden" value="setor" name="opcao" form="setor">
                            <input type="hidden" value="menu3_1_1" name="pagina" form="setor">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="setor" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div class="row">
        <div class="col-lg-12 col-12">
            <?php
            require_once '../../sistema/acesso/sNotificacao.php';

            if (isset($codigo)) {
                $mensagem = explode('|', $codigo);
                echo <<<HTML
                <div class="col-mb-3">
                    <div class="card card-outline card-{$mensagem[0]}">
                        <div class="card-header">
                            <h3 class="card-title">{$mensagem[1]}</h3>
                        </div>
                        <div class="card-body">
                            {$mensagem[2]}
                        </div>
                    </div>
                </div>
HTML;
            }
            ?>
        </div>
    </div>
</div>