<?php
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');

$listOrder    = $this->state->get('list.ordering');
$listDirn    = $this->state->get('list.direction');

$saveOrder    = $listOrder == 'ordering';
?>

<form method="post" name="adminForm">



    <div class="card">
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
                            <div class="dataTables_filter mx-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" id="search" class="form-control small" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" placeholder="<?php echo JText::_('JGLOBAL_FILTER_SEARCH_TITLE') ?>">
                                    <button class="btn btn-outline-primary" type="submit"><i class="bx bx-search-alt-2" ></i></button>
                                    <button class="btn btn-outline-primary" type="button" onclick="document.getElementById('search').value='';this.form.submit();" title="Limpar"><i class="bx bxs-eraser"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="datatables-users table border-top dataTable no-footer dtr-column">
                    <thead>
                        <tr>
                            <th class="sorting sorting_desc"><?php echo JHtml::_('grid.sort',  'Campeonato', 'name_especie', $listDirn, $listOrder); ?></th>
                            <th class="sorting">Local</th>
                            <th class="sorting text-center">Período de Inscrição</th>
                            <th class="sorting text-center"><?php echo JHtml::_('grid.sort', 'Inscritos', 'total_inscritos', $listDirn, $listOrder); ?></th>
                            <th class="sorting_disabled" style="width: 155px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $this->loadTemplate('items'); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" align="right">
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
    <input type="hidden" name="controller" value="enrollmentopens" />
    <input type="hidden" name="view" value="enrollmentopens" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>





<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createApp"> Show </button>



<div class="modal fade" id="createApp" tabindex="-1" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-simple modal-upgrade-plan">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body p-2">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center">
          <h3 class="mb-2">Inscreva-se nesta prova</h3>
          <p>Realize as etapas para completar sua incrição</p>
        </div>
        <!-- App Wizard -->
        <div id="wizard-create-app" class="bs-stepper vertical mt-2 shadow-none border-0">

        </div>
      </div>
      <!--/ App Wizard -->
    </div>
  </div>
</div>


