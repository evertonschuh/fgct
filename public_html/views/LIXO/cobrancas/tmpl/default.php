<?php
defined('_JEXEC') or die('Restricted access');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$saveOrder	= $listOrder == 'ordering';

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

$_app = JFactory::getApplication();
//$_app->setUserState( 'newAcessPanel', '1' );
$openModal = $_app->getUserStateFromRequest( 'openModal' );
if($openModal)
{
	$_app->setUserState( 'openModal', NULL );
	//$_app->setUserState( 'id_pagamentos_cnab400', NULL );
	
//$this->_app->setUserState( 'id_pagamentos_cnab400', NULL );
	
?>

<div class="modal fade" id="message-print">
	<div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo JText::_('JGLOBAL_BUTTON_CLOSE'); ?></span></button>
                <h4 class="modal-title"><?php echo JText::_('Arquivo CNAB'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo JText::_('O arquivo CNAB está pronto para download.<br/>Clique em Baixar Aqruivo para iniciar o download'); ?></p>
            </div>
            <div class="modal-footer">
                <form method="post" name="downloadForm" id="downloadForm">  
                
                	<button type="submit" id="downloadcnab400" class="btn btn-primary btn-sm" >Baixar Arquivo</button>	
                    <input type="hidden" name="controller" id="controller" value="finpagamentos" />
                    <input type="hidden" name="view" id="view" value="finpagamentos" />
                    <input type="hidden" name="task" id="task" value="cnab" />
                    <?php echo JHtml::_('form.token'); ?>
                </form>                
			</div>
    	</div>
  	</div>
</div>

<script> 
	$(document).ready(function () {
		$('#message-print').modal('show');
	
	
		$('#downloadcnab400').click(function(e) {
			e.preventDefault();
			// Coding
			$('#message-print').modal('toggle'); //or  $('#IDModal').modal('hide');
			$('#downloadForm').submit();
		});
	});
</script>
<?php
}
?>
<form method="post" name="adminForm" class="form-validate">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="form-inline">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"  placeholder="<?php echo JText::_('JGLOBAL_FILTER_SEARCH_TITLE') ?>" />
                            <div class="input-group-btn" >
                                <button class="btn btn-primary" style="height:34px" type="submit" title="Pesquisar"><i class="fa fa-search fa-fw"></i></button>
                                <button type="button" class="btn btn-primary" style="height:34px" onclick="document.getElementById('search').value='';document.getElementById('datei').value='';document.getElementById('datef').value='';this.form.submit();" title="Limpar" ><i class="fa fa-eraser"></i></button>
                            </div>
                        </div> 
                        <select name="status" class="form-control pull-right" onchange="this.form.submit()" >
                            <?php 
                                $status[] = JHTML::_('select.option', '', JText::_( '- Status -' ), 'value', 'text' );
                                $status[] = JHTML::_('select.option', '1', JText::_( 'Publicado' ), 'value', 'text' );
                                $status[] = JHTML::_('select.option', '0', JText::_( 'Despublicado' ), 'value', 'text' );
                                echo JHtml::_('select.options',  $status, 'value', 'text', $this->state->get('filter.status') );
                            ?>
                        </select>

                        <select name="situacao" class="form-control pull-right" onchange="this.form.submit()" >
                            <?php 
                                $situacao[] = JHTML::_('select.option', '', JText::_( '- Situação -' ), 'value', 'text' );
								$situacao[] = JHTML::_('select.option', '1', JText::_( 'A Vencer' ), 'value', 'text' );
                                $situacao[] = JHTML::_('select.option', '2', JText::_( 'Em Aberto' ), 'value', 'text' );
                                $situacao[] = JHTML::_('select.option', '3', JText::_( 'Vencido' ), 'value', 'text' );
								$situacao[] = JHTML::_('select.option', '4', JText::_( 'Pago' ), 'value', 'text' );
                                echo JHtml::_('select.options',  $situacao, 'value', 'text', $this->state->get('filter.situacao') );
                            ?>
                        </select>
                        <div class="input-group date pull-right" id="dataFim">
                            <input type="text" name="datef" id="datef"  class="form-control" value="<?php echo $this->state->get('filter.datef'); ?>" size="8" placeholder="Data Máxima" onchange="this.form.submit()" />
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                        <div class="input-group date pull-right" id="dataInicio">
                            <input type="text" name="datei" id="datei" class="form-control" value="<?php echo $this->state->get('filter.datei'); ?>" size="8" placeholder="Data Inicial" onchange="this.form.submit()" />
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                        <fieldset class="radio new-line-radio pull-right" id="datatypes" for="datetype">
                            <?php 
							$dateType[] = JHTML::_('select.option', '1', JText::_( 'P' ), 'value', 'text' );
							$dateType[] = JHTML::_('select.option', '0', JText::_( 'V' ), 'value', 'text' );
							
							echo JHTML::_('select.radiolist',  $dateType, 'datetype','class="group-radio"onchange="this.form.submit()"' , 'value', 'text',  $this->state->get('filter.datetype') ); 
							?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $inOnOff = '';
    $advanceCod = '(+)';
    if($this->state->get('filter.polo') != ''
		|| $this->state->get('filter.registrado') != ''
		|| $this->state->get('filter.metodos') != ''
		|| $this->state->get('filter.anuidades') != ''
		|| $this->state->get('filter.produtos') != ''
		|| $this->state->get('filter.typesocio') != ''
		) {
			$inOnOff = 'in';
    		$advanceCod = '(-)';
		}
    
    ?>    
    
    <div class="row">
    	<div class="col-xs-12" style="margin-top:-10px; margin-bottom:10px">
            <a class="pull-right" data-toggle="collapse" href="#advanceSearch" role="button" aria-expanded="false" aria-controls="advanceSearch">
                <span id="advanceCod"><?php echo $advanceCod . $this->state->get('filter.recorrente')?></span>&nbsp;Pesquisa Avançada
            </a>
    	</div>
    </div>

    <div class="panel panel-default collapse <?php echo $inOnOff ?>" id="advanceSearch">
        <div class="panel-heading">
            <div class="form-inline">
                <div class="row">
                    <div class="col-md-12">

                        <select name="registrado" class="form-control" onchange="this.form.submit()" >
                            <?php 
                                $registrado[] = JHTML::_('select.option', '', JText::_( '- Registro -' ), 'value', 'text' );
                                $registrado[] = JHTML::_('select.option', '1', JText::_( 'Sim' ), 'value', 'text' );
                                $registrado[] = JHTML::_('select.option', '0', JText::_( 'Não' ), 'value', 'text' );
                                echo JHtml::_('select.options',  $registrado, 'value', 'text', $this->state->get('filter.registrado') );
                            ?>
                        </select>
                        <select id="metodos" name="metodos" class="form-control" onchange="this.form.submit()">
                            <option selected class="default" value=""><?php echo JText::_('- Métodos -'); ?></option>
                            <?php echo JHTML::_('select.options',  $this->metodos, 'value', 'text', $this->state->get('filter.metodos')); ?>
                        </select>
                        <select id="anuidades" name="anuidades" class="form-control" onchange="this.form.submit()">
                            <option selected class="default" value=""><?php echo JText::_('- Anuidades -'); ?></option>
                            <?php echo JHTML::_('select.options',  $this->anuidades, 'value', 'text', $this->state->get('filter.anuidades')); ?>
                        </select>
                        <select id="produtos" name="produtos" class="form-control" onchange="this.form.submit()">
                            <option selected class="default" value=""><?php echo JText::_('- Outros -'); ?></option>
                            <?php echo JHTML::_('select.options',  $this->produtos, 'value', 'text', $this->state->get('filter.produtos')); ?>
                        </select>
                        <select name="typesocio" class="form-control"  onchange="this.form.submit()">
                            <?php 
                                $typesocio[] = JHTML::_('select.option', '', JText::_( '- Tipo de Sócio -' ), 'value', 'text' );
								$typesocio[] = JHTML::_('select.option', '1', JText::_( 'Pessoa Física' ), 'value', 'text' );
                                $typesocio[] = JHTML::_('select.option', '2', JText::_( '|- Sócio Ar Comprimido' ), 'value', 'text' );
                                $typesocio[] = JHTML::_('select.option', '3', JText::_( '|- Sócio Full' ), 'value', 'text' );
                                $typesocio[] = JHTML::_('select.option', '4', JText::_( 'Pessoa Jurídica' ), 'value', 'text' );
								
                                echo JHtml::_('select.options',  $typesocio, 'value', 'text', $this->state->get('filter.typesocio') );
                            ?>
                        </select> 
                    </div>
                </div>
            </div>
        </div>
    </div> 



    <!-- /.row -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="1%" ><input type="checkbox" name="checkall-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll(this)" /></th>
                                <th><?php echo JHtml::_('grid.sort',  'Número', 'id_pagamento', $listDirn, $listOrder); ?> </th>
                                <th class="text-center"><?php echo JHtml::_('grid.sort', 'Status', 'status_pagamento', $listDirn, $listOrder); ?></th>
                                <th class="hidden-xs hidden-sm" ><?php echo JHtml::_('grid.sort',  'Sacado', 'sacado', $listDirn, $listOrder); ?></th>
                                <th class="hidden-xs hidden-sm hidden-md" ><?php echo JHtml::_('grid.sort',  'Produto', 'produto', $listDirn, $listOrder); ?></th>
                                <th class="hidden-xs" style="text-align:center;"><?php echo JHtml::_('grid.sort',  'Vencimento', 'vencimento_pagamento', $listDirn, $listOrder); ?></th>
                                <th class="text-center"><?php echo JHtml::_('grid.sort',  'Valor', 'valor_pagamento', $listDirn, $listOrder); ?></th>
                                <th class="hidden-xs text-center"><?php echo JHtml::_('grid.sort',  'Desconto', 'valor_desconto_pagamento', $listDirn, $listOrder); ?></th>
                                <th class="hidden-xs" style="text-align:center;"><?php echo JHtml::_('grid.sort',  'Pagamento', 'confirmado_pagamento', $listDirn, $listOrder); ?></th>
                                <th class="text-center"><?php echo JHtml::_('grid.sort',  'Pago', 'valorpago_pagamento', $listDirn, $listOrder); ?></th>
                                <th class="hidden-xs hidden-sm hidden-md" ><?php echo JHtml::_('grid.sort',  'Método', 'name_pagamento_metodo', $listDirn, $listOrder); ?></th>
                                <th class="hidden-xs hidden-sm hidden-md" style="text-align:center;"><?php echo JHtml::_('grid.sort',  'Registro', 'registrado_pagamento', $listDirn, $listOrder); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php  echo $this->loadTemplate('items'); ?>
                        </tbody>
                    </table>
        		</div>
        	</div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-xs-12">
                    <div class="text-center buttons-pagination">
                        <div class="pagination-div">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </div>
                    </div> 
                </div>
            </div> 
        </div>
    </div> 
    <?php if( $this->_user->authorize('core.financeiro.operacional', 'com_fbt') ): ?>  
	<div class="clearfix"></div> 
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <fieldset class="group-border">
                    <legend class="group-border">Processar em Lote</legend>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label"><?php echo JText::_('Processo'); ?></label>
                            <div class="row">
                           		<div class="col-md-4">
                                    <select name="lote" id="lote" class="form-control" >
                                        <?php 
                                            $lote[] = JHTML::_('select.option', '', JText::_( '- Selecione o Processo -' ), 'value', 'text' );
                                            $lote[] = JHTML::_('select.option', 'updateday', JText::_( 'Alterar Dia de Vencimento' ), 'value', 'text' );
                                            $lote[] = JHTML::_('select.option', 'updatemetodo', JText::_( 'Alterar Método de Pagamento' ), 'value', 'text' );
											$lote[] = JHTML::_('select.option', 'updatevalue', JText::_( 'Alterar Valor da Cobrança' ), 'value', 'text' );
											$lote[] = JHTML::_('select.option', 'updateperiod', JText::_( 'Adicionar Período no Vencimento' ), 'value', 'text' );
											$lote[] = JHTML::_('select.option', 'expotremessa', JText::_( 'Exportar Arquivo de Remessa' ), 'value', 'text' );
                                            echo JHtml::_('select.options',  $lote, 'value', 'text', '');
                                        ?>
                                    </select>  
                        		</div>
                        	</div>                        
                        </div>
                        <div id="loteparams">

                        </div>
                        <div id="loading"></div>
                        <div class="form-group">
                            
<a href="javascript:void(0);" onclick="if(document.adminForm.lote.value==''){alert('Selecione algum Processo');}else{ if(document.adminForm.boxchecked.value==0){alert('Selecione algum item da lista');}else{Joomla.submitbutton('process');jQuery(this).attr('disabled',true);}}" class="btn btn-primary btn-sm" data-original-title="" title="">
Processar</a>
                            
                        </div>
					</div>  
                </fieldset>
            </div> 
        </div>  
    </div> 
    <style>
	.div-result-calc  {
		position: fixed;
		top: 35%;
		right: 5%;
    	z-index: 5;
		}
	
    </style>
    <div class="div-result-calc" style="display:none;">
        <div class="modal-content login-modal panel-warning">
            <div class="modal-header modal-header-warning">
                <h5 class="modal-title text-center"><strong>Pré-Cálculo</strong></h5>
            </div>
            <div class="modal-body login-modal-body panel-heading">
                <div class="row">
                    <div class="form-group">
                        <label>Selecionados</label>
                        <p id="ttit"></p>
                    </div>
                    <div class="form-group">
                        <label>Média Dias</label>
                        <p id="mddia"></p>
                    </div>
                    <div class="form-group">
                        <label>Valor Total</label>
                        <p id="ttval"></p>
                    </div>
                    <div class="form-group">
                        <label>Total Deságio</label>
                        <p id="ttdes"></p>
                    </div>
                    <div class="form-group">
                        <label>Total Líquido</label>
                        <p id="ttliq"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?> 
    <input type="hidden" name="controller" value="finpagamentos" />
    <input type="hidden" name="view" value="finpagamentos" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />  
    <input type="hidden" name="boxchecked" value="0" />	
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<div class="modal fade" id="modal-print" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    	<div class="modal-content">   
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center"><?php echo JText::_('Exportar Resultados'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
               		<div class="col-md-12 text-center">
                    	<div class="form-group">
                            <p>Escolha o Tipo</p>
                    	</div>
                    	<div class="form-group">
                			<div class="row">
               					<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 text-center">
                                    <a class="btn btn-default btn-block" target="_blank" href="<?php echo JRoute::_('index.php?view=finpagamentos&layout=report&tmpl=print'); ?>" >Relatório</a>
                                    <a class="btn btn-default btn-block" target="_blank"  href="<?php echo JRoute::_('index.php?view=finpagamentos&layout=etiqueta&tmpl=print'); ?>" >Etiquetas</a> 
                                    <a class="btn btn-default btn-block" target="_blank"  href="<?php echo JRoute::_('index.php?view=finpagamentos&format=excel'); ?>" >Excel Lista</a> 
                                    <a class="btn btn-default btn-block" target="_blank"  href="<?php echo JRoute::_('index.php?view=finpagamentos&layout=email&format=excel'); ?>" >Excel Email Boletos</a> 
                                </div>
                            </div>   
                        </div>
                    </div>
                    <br/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
            </div>
        </div>
    </div>
</div>