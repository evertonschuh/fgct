<?php
defined('_JEXEC') or die('Restricted access');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

?>
<form  method="post" name="adminForm">

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="form-inline">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"  placeholder="<?php echo JText::_('JGLOBAL_FILTER_SEARCH_TITLE') ?>" />
                            <div class="input-group-btn" >
                                <button class="btn btn-primary" style="height:34px" type="submit" title="Pesquisar"><i class="fa fa-search fa-fw"></i></button>
                                <button type="button" class="btn btn-primary" style="height:34px" onclick="document.getElementById('search').value='';this.form.submit();" title="Limpar" ><i class="fa fa-eraser"></i></button>
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
                       <?php /* <select name="situacao" class="form-control pull-right" onchange="this.form.submit()" >
                               <option selected class="default" value=""><?php echo JText::_('- Situação -'); ?></option>
                               <?php echo JHTML::_('select.options',  $this->situacao, 'value', 'text', $this->state->get('filter.situacao') );   ?>
                        </select> */ ?>
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
                                
                                <th><?php echo JHtml::_('grid.sort', 'Associado', 'name', $listDirn, $listOrder); ?></th>
                                <th width="1%" class="text-center"><?php echo JHtml::_('grid.sort', 'Número', 'numero_documento_numero', $listDirn, $listOrder); ?></th>
                                <th class="text-center hidden-xs" ><?php echo JHtml::_('grid.sort',  'Documento', 'name_documento', $listDirn, $listOrder); ?></th>
                                <th class="text-center hidden-xs" ><?php echo JHtml::_('grid.sort',  'Registro', 'register_documento_numero', $listDirn, $listOrder); ?></th>
                                <th width="1%" class="text-center" ><?php echo JHtml::_('grid.sort',  'Id', 'id_documento_numero', $listDirn, $listOrder); ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                        	<tr>
                            	<td colspan="4" align="right">
                                	<strong>Total de Itens <?php echo $this->total; ?></strong>
                                </td>
                      		</tr>
                		</tfoot>
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
    <input type="hidden" name="controller" value="docassociados" />
    <input type="hidden" name="view" value="docassociados" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />  
    <input type="hidden" name="boxchecked" value="0" />	
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
