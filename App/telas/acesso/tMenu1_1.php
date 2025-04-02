<?php
use App\sistema\acesso\{
    sConfiguracao,
    sTelefone
};

$sConfiguracao = new sConfiguracao();
$sTelefone = new sTelefone(0, 0, '0');

//define a foto com base no sexo
if($_SESSION['credencial']['sexo'] == 'Masculino'){
    $imagem = 'user2-160x160.jpg';
}else{
    $imagem = 'user7-128x128.jpg';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/dist/img/<?php echo $imagem; ?>" alt="Imagem do Perfil">
                    </div>

                    <h3 class="profile-username text-center"><?php echo $_SESSION['credencial']['nome'] . ' ' . $_SESSION['credencial']['sobrenome']; ?></h3>
                    <ul class="list-group list-group-unbordered mb-4">
                        <li class="list-group-item">
                            <i class="fas fa-venus-mars mr-1"></i><b> Sexo</b><a class="float-right"><?php echo $_SESSION['credencial']['sexo']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-building mr-1"></i><b> Secretaria</b><a class="float-right"><?php echo $_SESSION['credencial']['secretaria']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Departamento</b> <a class="float-right"><?php echo $_SESSION['credencial']['departamento']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Coordenação</b> <a class="float-right"><?php echo $_SESSION['credencial']['coordenacao']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Setor</b> <a class="float-right"><?php echo $_SESSION['credencial']['setor']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-briefcase mr-1"></i><b> Cargo/ Função</b> <a class="float-right"><?php echo $_SESSION['credencial']['cargo']; ?></a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-phone mr-1"></i><b> Telefone Corporativo</b>
                            <a class="float-right">
                                <?php
                                echo '<b>Secretaria: </b>';
                                if ($_SESSION['credencial']['whatsAppSecretaria']) {
                                    echo 'Secretaria: <i class="fab fa-whatsapp mr-1"></i> ';
                                }
                                echo $sTelefone->tratarTelefone($_SESSION['credencial']['telefoneSecretaria']);
                                ?>
                            </a><br />
                            <a class="float-right">
                                <?php
                                echo '<b>Departamento: </b>';
                                if ($_SESSION['credencial']['whatsAppDepartamento']) {
                                    echo '<i class="fab fa-whatsapp mr-1"></i> ';
                                }
                                echo $sTelefone->tratarTelefone($_SESSION['credencial']['telefoneDepartamento']);
                                ?>
                            </a><br />
                            <a class="float-right">
                                <?php
                                echo '<b>Coordenação: </b>';
                                if ($_SESSION['credencial']['whatsAppCoordenacao']) {
                                    echo '<i class="fab fa-whatsapp mr-1"></i> ';
                                }
                                echo $sTelefone->tratarTelefone($_SESSION['credencial']['telefoneCoordenacao']);
                                ?>
                            </a><br />
                            <a class="float-right">
                                <?php
                                echo '<b>Setor: </b>';
                                if ($_SESSION['credencial']['whatsAppSetor']) {
                                    echo '<i class="fab fa-whatsapp mr-1"></i> ';
                                }
                                echo $sTelefone->tratarTelefone($_SESSION['credencial']['telefoneSetor']);
                                ?>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-phone mr-1"></i><b> Telefone Pessoal</b>
                            <a class="float-right">
                                <?php
                                if ($_SESSION['credencial']['whatsAppUsuario']) {
                                    echo '<b><i class="fab fa-whatsapp mr-1"></i> </b>';
                                }
                                echo $sTelefone->tratarTelefone($_SESSION['credencial']['telefoneUsuario']);
                                ?>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-envelope-open-text mr-1"></i><b> Email Corporativo</b>
                            <a class="float-right">
                                <?php
                                echo '<b>Secretaria: </b>';
                                echo $_SESSION['credencial']['emailSecretaria'];
                                ?>
                            </a><br />
                            <a class="float-right">
                                <?php
                                echo '<b>Departamento: </b>';
                                echo $_SESSION['credencial']['emailDepartamento'];
                                ?>
                            </a><br />
                            <a class="float-right">
                                <?php
                                echo '<b>Coordenação: </b>';
                                echo $_SESSION['credencial']['emailCoordenacao'];
                                ?>
                            </a><br />
                            <a class="float-right">
                                <?php
                                echo '<b>Setor: </b>';
                                echo $_SESSION['credencial']['emailSetor'];
                                ?>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-envelope-open-text mr-1"></i><b> Email Pessoal</b> 
                            <a class="float-right">
                                <?php
                                    echo $_SESSION['credencial']['emailUsuario'];
                                ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <form action="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso() ?>tPainel.php" method="get">
                    
                    <div class="card-footer">
                        <input type="hidden" name="menu" value="1_1_1">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->            
</div>
<!-- /.container-fluid -->