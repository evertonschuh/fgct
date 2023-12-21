<?php
defined('_JEXEC') or die('Restricted access');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

?>

<form  method="post" name="adminForm">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-inline">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control" value="<?php echo $this->escape($this->state->get('filter.searchm')); ?>"  placeholder="<?php echo JText::_('JGLOBAL_FILTER_SEARCH_TITLE') ?>" />
                                <div class="input-group-btn" >
                                    <button class="btn btn-primary" style="height:34px" type="submit" title="Pesquisar"><i class="fa fa-search fa-fw"></i></button>
                                    <button type="button" class="btn btn-primary" style="height:34px" onclick="document.getElementById('search').value='';this.form.submit();" title="Limpar" ><i class="fa fa-eraser"></i></button>
                                </div>
                            </div>
                            <select name="status" class="form-control pull-right" onchange="this.form.submit()" >
                                <?php 
                                    $status[] = JHTML::_('select.option', '', JText::_( '- Status -' ), 'value', 'text' );
                                    $status[] = JHTML::_('select.option', '1', JText::_( 'Ativo' ), 'value', 'text' );
                                    $status[] = JHTML::_('select.option', '0', JText::_( 'Cancelado' ), 'value', 'text' );
                                    echo JHtml::_('select.options',  $status, 'value', 'text', $this->state->get('filter.statusm') );
                                ?>
                            </select>
                            <select name="situacao" class="form-control pull-right" onchange="this.form.submit()" >
                                <?php 
                                    $situacao[] = JHTML::_('select.option', '', JText::_( '- Situação -' ), 'value', 'text' );
									$situacao[] = JHTML::_('select.option', '0', JText::_( 'Novo' ), 'value', 'text' );
                                    $situacao[] = JHTML::_('select.option', '1', JText::_( 'Em dia' ), 'value', 'text' );
                                    $situacao[] = JHTML::_('select.option', '2', JText::_( 'Vencido (1 ano)' ), 'value', 'text' );
                                    $situacao[] = JHTML::_('select.option', '3', JText::_( 'Vencido (Todos)' ), 'value', 'text' );
                                    echo JHtml::_('select.options',  $situacao, 'value', 'text', $this->state->get('filter.situacaom') );
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
                    <div class="col-md-4">
                    	<button type="button" onclick="if (document.adminForm.boxchecked.value==0){alert('Selecione algum item da lista');}else{ Joomla.submitform('addUsers')}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;Adicionar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="1%" ><input type="checkbox" name="checkall-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll(this)" /></th>
                                    <th><?php echo JHtml::_('grid.sort',  'Nome', 'name', $listDirn, $listOrder); ?></th>
                                    <th style="text-align:center;"><?php echo JHtml::_('grid.sort', 'Status', 'status_associado', $listDirn, $listOrder); ?></th>
                                    <th style="text-align:center;"><?php echo JHtml::_('grid.sort', 'Validade Anuidade', 'validate_associado', $listDirn, $listOrder); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  echo $this->loadTemplate('items'); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center buttons-pagination">
                <div class="pagination-div">
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
            </div> 
        </div>
    </div>
    <input type="hidden" name="controller" value="documento" />
    <input type="hidden" name="view" value="documento" />
    <input type="hidden" name="layout" value="modalm" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_documento; ?>" />
    <input type="hidden" name="id_documento" value="<?php echo $this->item->id_documento; ?>" /> 
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />  
    <input type="hidden" name="boxchecked" value="0" />	
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>