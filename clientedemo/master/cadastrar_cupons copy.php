<?php
require_once "topo.php";
?>
<div class="slim-mainpanel">
    <div class="container">
      
        <?php if(isset($_GET["erro"])){?>
        <div class="alert alert-warning" role="alert">
        <i class="fa fa-asterisk" aria-hidden="true"></i> Erro.
        </div>
        <?php }?>
        <?php if(isset($_GET["ok"])){?>
        <div class="alert alert-success" role="alert">
        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Sucesso.
        </div>
        <?php }?>
      
        <div id="contato_do_site">
            <div style="background-color:#ffffff;" class="container margin_60">     
                <div class="row"> 
                <div class="section-wrapper mg-b-20">
                <label class="section-title"><i class="fa fa-check-square-o" aria-hidden="true"></i> Cadastrar Cupom de Desconto</label>

                            <h3>Cupom de Desconto</h3>
                            <p>Ofereça descontos para conseguir mais clientes.</p>
                            <br />
                            <form id="formcupom" method="post">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Código de Ativação</label>
                                            <input required type="text" maxlength="20" class="form-control" name="ativacao" aria-describedby="emailHelp" placeholder="EX: CUPOM10" />
                                            <small id="emailHelp" class="form-text text-muted">Para enviar para seus clientes. (max. 20 caracteres)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Desconto %</label>
                                            <input required type="number" class="form-control descontoporcentagem" value="1" name="porcentagem" min="1" max="100" />
                                            <small class="form-text text-muted">Porcentagem de desconto.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Data de Válidade</label>
                                            <input required type="text" class="form-control" name='data_validade' id="datepicker" data-mask="00/00/0000" placeholder="00/00/0000" />
                                            <small id="emailHelp" class="form-text text-muted">Data de expiração do cupom</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Quantidade</label>
                                            <input required type="number" class="form-control numero" name="total_vezes" value="1" min="1" max="100000" />
                                            <small class="form-text text-muted">Número de vezes que o cupom pode ser usado!</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="<?=$userlogin['user_id'];?>">
                                <input type="hidden" name="lojaurl" value="<?=$Url[0];?>">
                                <input type="hidden" name="submitcupomconfirm" value="true">
                                <input type="hidden" name="mostrar_site" value="0">
                                <a id="submitbtncupom" class="btn btn-primary">Cadastrar Cupom</a>
                            </form>
                            <br />
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $('#submitbtncupom').click(function(){
                                        $.ajax({
                                            url: '<?=$site;?>includes/processasubmitcupom.php',
                                            method: 'post',
                                            data: $('#formcupom').serialize(),
                                            success: function(data){
                                                $('#sucsesscupom').html(data);
                                            }
                                        });
                                    });
                                });
                            </script>
                            <div id="sucsesscupom"></div>

                            <br />
                            <br />
                            <?php
                           // Busca os cupons de desconto
$cupons = $connect->query("SELECT * FROM cupom_desconto")->fetchAll(PDO::FETCH_OBJ);
?>
		<div class="section-wrapper">
          <label class="section-title"><i class="fa fa-check-square-o" aria-hidden="true"></i> Lista de Cupons </label>
		  <hr>
          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap" width="100%">
            <tr>
                <th scope="col">Ativação</th>
                <th scope="col">Desconto</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Expira em</th>
                <th scope="col">Situação</th>
                <th scope="col">Exibir no site</th>
                <th scope="col">Excluir</th>
            </tr>
        </thead>
        <tbody style="text-align: center;">
            <?php foreach ($cupons as $cupom): ?>
            <tr>
                <th scope="row"><?= htmlspecialchars($cupom->ativacao) ?></th>
                <td><?= htmlspecialchars($cupom->porcentagem) ?> %</td>
                <td><?= htmlspecialchars($cupom->total_vezes) ?></td>
                <td>
                    <?= htmlspecialchars(date('d/m/Y', strtotime($cupom->data_validade))) ?>
                </td>
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
                    	              <input type="hidden" name="delfun" value="<?php print $dadosmoto->id;?>"/>
                    <button type="button" class="btn btn-default exibirsite" data-idcupom="<?= $cupom->id_cupom ?>">
                        <?= $cupom->mostrar_site == 0 ? 'Não' : 'Sim' ?>
                    </button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger excluircupom" data-idcupom="<?= $cupom->id_cupom ?>">Excluir</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('.exibirsite').click(function(){
        var idcupom = $(this).data('idcupom');
        $(this).prop('disabled', true);

        $.ajax({
            url: '<?=$site;?>includes/processamostrarcupom.php',
            method: 'post',
            data: {
                'iddocupom': idcupom,
                'url': '<?=$Url[0];?>',
                'iduser': '<?=$userlogin['user_id'];?>'
            },
            success: function(data){
                $('.exibirsite').prop('disabled', false);
                if(data === 'erro1'){
                    x0p('Opss...', 'Ocorreu um erro!', 'error', false);
                } else if(data === 'erro0'){
                    window.location.replace('<?=$site.$Url[0].'/cupom-desconto';?>');
                }
            }
        });
    });

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
                    url: '<?=$site;?>includes/processadeletarcupom.php',
                    method: 'post',
                    data: {
                        'iddocupom': idcupom,
                        'url': '<?=$Url[0];?>',
                        'iduser': '<?=$userlogin['user_id'];?>'
                    },
                    success: function(data){
                        $('#sucsesscupom').html(data);
                        $('.excluircupom').prop('disabled', false);
                    }
                });
            }
        });
    });
});
</script>
<script src="../lib/jquery/js/jquery.js"></script>
<script src="../lib/datatables/js/jquery.dataTables.js"></script>
<script src="../lib/datatables-responsive/js/dataTables.responsive.js"></script>
<script src="../lib/select2/js/select2.min.js"></script>
<script>
$(function(){
    'use strict';

    $('#datatable1').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Buscar...',
            sSearch: '',
            lengthMenu: '_MENU_ ítens',
        }
    });

    $('#datatable2').DataTable({
        bLengthChange: false,
        searching: false,
        responsive: true
    });

    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
});
</script>
<script src="../js/slim.js"></script>
    </body>
</html>