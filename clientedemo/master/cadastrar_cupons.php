<?php
require_once "topo.php";

// Conexão com o banco de dados
try {
    $connect = new PDO("mysql:host=localhost;dbname=sys_delivery", "root", "");
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Busca os cupons de desconto
$cupons = $connect->query("SELECT * FROM cupom_desconto")->fetchAll(PDO::FETCH_OBJ);
?>

<div class="slim-mainpanel">
    <div class="container">
      
        <?php if(isset($_GET["erro"])): ?>
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-asterisk" aria-hidden="true"></i> Erro.
        </div>
        <?php endif; ?>
        
        <?php if(isset($_GET["ok"])): ?>
        <div class="alert alert-success" role="alert">
            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Sucesso.
        </div>
        <?php endif; ?>
        
        <div class="section-wrapper mg-b-20">
            <label class="section-title"><i class="fa fa-check-square-o" aria-hidden="true"></i> Cadastrar Cupom de Desconto</label>
            <hr>
            <h3>Cupom de Desconto</h3>
            <p>Ofereça descontos para conseguir mais clientes.</p>
            <br />
            
            <form id="formcupom" method="post" action="processasubmitcupom.php">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="ativacao">Código de Ativação</label>
                            <input required type="text" maxlength="20" class="form-control" name="ativacao" placeholder="EX: CUPOM10" />
                            <small class="form-text text-muted">Para enviar para seus clientes. (max. 20 caracteres)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="porcentagem">Desconto %</label>
                            <input required type="number" class="form-control descontoporcentagem" value="1" name="porcentagem" min="1" max="100" />
                            <small class="form-text text-muted">Porcentagem de desconto.</small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="data_validade">Data de Validade</label>
                            <input required type="text" class="form-control" name="data_validade" id="datepicker" data-mask="00/00/0000" placeholder="00/00/0000" />
                            <small class="form-text text-muted">Data de expiração do cupom</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="total_vezes">Quantidade</label>
                            <input required type="number" class="form-control numero" name="total_vezes" value="1" min="1" max="100000" />
                            <small class="form-text text-muted">Número de vezes que o cupom pode ser usado!</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-layout-footer" align="center">
                    <button class="btn btn-primary bd-0">Salvar <i class="fa fa-arrow-right"></i></button>
                </div>
            </form>
            
            <br />
            <div id="sucsesscupom"></div>
            <br /><br />
            
            <div class="section-wrapper">
                <label class="section-title"><i class="fa fa-check-square-o" aria-hidden="true"></i> Lista de Cupons de Desconto</label>
                <hr>
                <div class="table-wrapper">
                    <table id="datatable1" class="table display responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">Ativação</th>
                                <th scope="col">Desconto</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Expira em</th>
                                <th scope="col">Situação</th>
                                <th scope="col">Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cupons as $cupom): ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($cupom->ativacao) ?></th>
                                <td><?= htmlspecialchars($cupom->porcentagem) ?> %</td>
                                <td><?= htmlspecialchars($cupom->total_vezes) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($cupom->data_validade))) ?></td>
                                <td>
                                    <?php
                                    if (strtotime($cupom->data_validade) < time()):
                                        echo "<strong style='color: red;'>EXPIROU!</strong>";
                                    elseif ($cupom->total_vezes <= 0):
                                        echo "<strong style='color: red;'>ACABOU!</strong>";
                                    else:
                                        echo "<strong style='color: #82C152;'>ATIVO</strong>";
                                    endif;
                                    ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger excluircupom" data-idcupom="<?= $cupom->id_cupom ?>">Excluir</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../lib/jquery/js/jquery.js"></script>
<script src="../lib/datatables/js/jquery.dataTables.js"></script>
<script src="../lib/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="../lib/select2/js/select2.min.js"></script>
<script src="../js/slim.js"></script>
<script>
$(document).ready(function(){
    $('.excluircupom').click(function(){
        var idcupom = $(this).data('idcupom');
        x0p({
            title: 'Atenção!',
            text: 'Tem certeza de que deseja excluir esse cupom?',
            animationType: 'slideUp',
            buttons: [
                {
                    type: 'error',
                    key: 49,
                    text: 'Cancelar',
                },
                {
                    type: 'info',
                    key: 50,
                    text: 'Excluir'
                }
            ]
        }).then(function(data) {
            if(data.button === 'info'){
                $.ajax({
                    url: 'processasubmitcupom.php',
                    method: 'post',
                    data: {
                        'action': 'delete',
                        'iddocupom': idcupom
                    },
                    success: function(data){
                        if(data === 'success'){
                            window.location.reload(); // Recarrega a página após a exclusão bem-sucedida
                        } else {
                            x0p('Opss...', 'Ocorreu um erro!', 'error', false);
                        }
                    }
                });
            }
        });
    });
});
</script>