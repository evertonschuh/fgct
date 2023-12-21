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

<form  method="post" name="adminForm">
	<div class="card shadow mb-4 border-left-primary">
		<div class="card-body d-sm-inline-block form-inline">
        	<div class="row">
                <div class="col-lg-4 py-3">
                    <div class="input-group">
                		<input type="text" name="search" id="search" class="form-control small" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"  placeholder="<?php echo JText::_('JGLOBAL_FILTER_SEARCH_TITLE') ?>" />
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" title="Pesquisar"><i class="fas fa-search fa-fw"></i></button>
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('search').value='';document.getElementById('datei').value='';document.getElementById('datef').value='';this.form.submit();" title="Limpar" ><i class="fas fa-eraser"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 py-3 card-filter">
                    <select name="status" class="form-control float-right ml-2" onchange="this.form.submit()" >
                        <?php 
                            $status[] = JHTML::_('select.option', '', JText::_( '- Status -' ), 'value', 'text' );
                            $status[] = JHTML::_('select.option', '1', JText::_( 'Publicado' ), 'value', 'text' );
                            $status[] = JHTML::_('select.option', '0', JText::_( 'Despublicado' ), 'value', 'text' );
                            echo JHtml::_('select.options',  $status, 'value', 'text', $this->state->get('filter.status') );
                        ?>
                    </select>

                    <select name="situacao" class="form-control float-right ml-2" onchange="this.form.submit()" >
                        <?php 
                            $situacao[] = JHTML::_('select.option', '', JText::_( '- Situação -' ), 'value', 'text' );
                            $situacao[] = JHTML::_('select.option', '1', JText::_( 'A Vencer' ), 'value', 'text' );
                            $situacao[] = JHTML::_('select.option', '2', JText::_( 'Em Aberto' ), 'value', 'text' );
                            $situacao[] = JHTML::_('select.option', '3', JText::_( 'Vencido' ), 'value', 'text' );
                            $situacao[] = JHTML::_('select.option', '4', JText::_( 'Pago' ), 'value', 'text' );
                            echo JHtml::_('select.options',  $situacao, 'value', 'text', $this->state->get('filter.situacao') );
                        ?>
                    </select>

                    <div class="input-group date float-right ml-2" id="dataFim">
                        <input type="text" autocomplete="off" name="datef" id="datef"  class="form-control" value="<?php echo $this->state->get('filter.datef'); ?>" size="8" placeholder="Data Máxima" onchange="this.form.submit()" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                    <div class="input-group date float-right ml-2" id="dataInicio">
                        <input type="text" autocomplete="off" name="datei" id="datei" class="form-control" value="<?php echo $this->state->get('filter.datei'); ?>" size="8" placeholder="Data Inicial" onchange="this.form.submit()" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                    <fieldset class="radio new-line-radio float-right ml-2" id="datatypes" for="datetype">
                        <?php 
                        $dateType[] = JHTML::_('select.option', '1', JText::_( 'P' ), 'value', 'text' );
                        $dateType[] = JHTML::_('select.option', '0', JText::_( 'V' ), 'value', 'text' );
                        
                        echo JHTML::_('select.radiolist',  $dateType, 'datetype','class="group-radio"onchange="this.form.submit()"' , 'value', 'text',  $this->state->get('filter.datetype') ); 
                        ?>
                    </fieldset>
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
                <div class="col-12" style="margin-top:-10px; margin-bottom:10px">
                    <a class="float-right" data-toggle="collapse" href="#advanceSearch" role="button" aria-expanded="false" aria-controls="advanceSearch">
                        <span id="advanceCod"><?php echo $advanceCod . $this->state->get('filter.recorrente')?></span>&nbsp;Pesquisa Avançada
                    </a>
                </div>
            </div>


            <div class="form-inline collapse <?php echo $inOnOff ?>" id="advanceSearch">
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
                                $typesocio[] = JHTML::_('select.option', '', JText::_( '- Tipo de Cadastro -' ), 'value', 'text' );
                                $typesocio[] = JHTML::_('select.option', '1', JText::_( 'Pessoa Física' ), 'value', 'text' );
                                $typesocio[] = JHTML::_('select.option', '2', JText::_( 'Pessoa Jurídica' ), 'value', 'text' );
                                
                                echo JHtml::_('select.options',  $typesocio, 'value', 'text', $this->state->get('filter.typesocio') );
                            ?>
                        </select> 
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <!-- /.row -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        	<h6 class="m-0 font-weight-bold text-primary">Documentos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th width="1%" ><input type="checkbox" name="checkall-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll(this)" /></th>
                            <th><?php echo JHtml::_('grid.sort',  'Número', 'id_pagamento', $listDirn, $listOrder); ?> </th>
                            <th class="text-center"><?php echo JHtml::_('grid.sort', 'Status', 'status_pagamento', $listDirn, $listOrder); ?></th>
                            <th class="hidden-xs hidden-sm" ><?php echo JHtml::_('grid.sort',  'Sacado', 'sacado', $listDirn, $listOrder); ?></th>
                            <th class="hidden-xs hidden-sm hidden-md" ><?php echo JHtml::_('grid.sort',  'Produto', 'produto', $listDirn, $listOrder); ?></th>
                            <th class="hidden-xs" style="text-align:center;"><?php echo JHtml::_('grid.sort',  'Vencimento', 'vencimento_pagamento', $listDirn, $listOrder); ?></th>
                            <th class="text-center"><?php echo JHtml::_('grid.sort',  'Valor', 'valor_pagamento', $listDirn, $listOrder); ?></th>
                            <th class="hidden-xs" style="text-align:center;"><?php echo JHtml::_('grid.sort',  'Pagamento', 'confirmado_pagamento', $listDirn, $listOrder); ?></th>
                            <th class="text-center"><?php echo JHtml::_('grid.sort',  'Pago', 'valorpago_pagamento', $listDirn, $listOrder); ?></th>
                            <th class="hidden-xs hidden-sm hidden-md" ><?php echo JHtml::_('grid.sort',  'Método', 'name_pagamento_metodo', $listDirn, $listOrder); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php  echo $this->loadTemplate('items'); ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center buttons-pagination">
                <div class="pagination-div">
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
            </div>             
        </div>
    </div>
    <input type="hidden" name="controller" value="pagamentos" />
    <input type="hidden" name="view" value="pagamentos" />
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