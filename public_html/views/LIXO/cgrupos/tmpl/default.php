<?php
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');

$listOrder    = $this->state->get('list.ordering');
$listDirn    = $this->state->get('list.direction');

$saveOrder    = $listOrder == 'ordering';
?>

<form method="post" name="adminForm">
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title">Filtros para Pesquisa</h5>
            <div class="d-flex justify-content-between align-items-center row py-2 gap-3 gap-md-0">
                <div class="col-md-4 user_status">
                    <select id="status" name="status" class="form-select text-capitalize" onchange="this.form.submit()">
                        <?php
                        $status[] = JHTML::_('select.option', '', JText::_('- Status -'), 'value', 'text');
                        $status[] = JHTML::_('select.option', '1', JText::_('Ativo'), 'value', 'text');
                        $status[] = JHTML::_('select.option', '0', JText::_('Inativo'), 'value', 'text');
                        $status[] = JHTML::_('select.option', '-1', JText::_('Lixeira'), 'value', 'text');
                        echo JHtml::_('select.options',  $status, 'value', 'text', $this->state->get('filter.status'));
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <div class="dataTables_wrapper dt-bootstrap5 no-footer ">
                <div class="row mx-2 py-3">
                    <div class="col-md-2">
                        <div class="me-3">
                            <div class="dataTables_length" id="DataTables_Table_0_length">
                                <label>
                                    <?php echo $this->pagination->getLimitBox(); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                            <div class="dataTables_filter">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" id="search" class="form-control small" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" placeholder="<?php echo JText::_('JGLOBAL_FILTER_SEARCH_TITLE') ?>">
                                    <button class="btn btn-outline-primary" type="submit"><i class="bx bx-search-alt-2" ></i></button>
                                    <button class="btn btn-outline-primary" type="button" onclick="document.getElementById('search').value='';this.form.submit();" title="Limpar"><i class="bx bxs-eraser"></i></button>
                                </div>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <div class="btn-group">
                                    <button class="btn btn-secondary buttons-collection dropdown-toggle btn-outline-secondary mx-3" tabindex="0" aria-controls="DataTables_Table_0" type="button" aria-haspopup="dialog" aria-expanded="false"><span><i class="bx bx-export me-1"></i>Export</span><span class="dt-down-arrow"></span></button>
                                </div> 
                                <a class="btn add-new btn-primary" href="<?php echo JRoute::_('index.php?view=cgrupo&cid=');?>">
                                    <span>
                                        <i class="bx bx-plus me-0 me-sm-1"></i>
                                        <span class="d-none d-sm-inline-block">Adicionar Novo Grupo</span>
                                    </span>
                                </a> 
                            </div>
                        </div>
                    </div>
                </div>
                <table class="datatables-users table border-top dataTable no-footer dtr-column">
                    <thead>
                        <tr>
                            <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label=""></th>
                            <th class="sorting sorting_desc"><?php echo JHtml::_('grid.sort',  'Nome', 'name_corredor_grupo', $listDirn, $listOrder); ?></th>
                            <th class="sorting text-center"><?php echo JHtml::_('grid.sort', 'Atletas Vinculados', 'total_corredor_grupo', $listDirn, $listOrder); ?></th>
                            <th class="sorting text-center"><?php echo JHtml::_('grid.sort', 'Status', 'status_corredor_grupo', $listDirn, $listOrder); ?></th>
                              <th class="sorting_disabled" style="width: 145px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $this->loadTemplate('items'); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" align="right">
                                <strong>Total de Itens <?php echo $this->total; ?></strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row mx-2">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_info mt-4">
                            <?php echo $this->pagination->getPagesCounter(); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers">
                            <div class="pagination-div float-end">
                                <?php echo $this->pagination->getPagesLinks(); ?>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="controller" value="cgrupos" />
    <input type="hidden" name="view" value="cgrupos" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
