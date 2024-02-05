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
                            <th class="sorting_disabled" style="width: 145px;">Ações</th>
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
          <div class="bs-stepper-header border-0 p-1">
            <div class="step" data-target="#details2">
              <button type="button" class="step-trigger" aria-selected="false">
                <span class="bs-stepper-circle"><i class="bx bx-file fs-5"></i></span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title text-uppercase">Equipe</span>
                  <span class="bs-stepper-subtitle">Escolha da Equipe</span>
                </span>
              </button>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#local">
              <button type="button" class="step-trigger" aria-selected="true">
                <span class="bs-stepper-circle"><i class="bx bx-box fs-5"></i></span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title text-uppercase">Local</span>
                  <span class="bs-stepper-subtitle">Escolha o Clube</span>
                </span>
              </button>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#database">
              <button type="button" class="step-trigger" aria-selected="false">
                <span class="bs-stepper-circle"><i class="bx bx-data fs-5"></i></span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title text-uppercase">Database</span>
                  <span class="bs-stepper-subtitle">Select Database</span>
                </span>
              </button>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#billing">
              <button type="button" class="step-trigger" aria-selected="false">
                <span class="bs-stepper-circle"><i class="bx bx-credit-card fs-5"></i></span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title text-uppercase">Reserva</span>
                  <span class="bs-stepper-subtitle">Reserve seu Horário</span>
                </span>
              </button>
            </div>
            <div class="line"></div>
            <div class="step" data-target="#submit">
              <button type="button" class="step-trigger" aria-selected="false">
                <span class="bs-stepper-circle"><i class="bx bx-check fs-5"></i></span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title text-uppercase">Finalizar</span>
                  <span class="bs-stepper-subtitle">Finalizar</span>
                </span>
              </button>
            </div>
          </div>
          <div class="bs-stepper-content p-1">
            <form  onsubmit="return false">
              <!-- Details -->
              <div id="details2" class="content pt-3 pt-lg-0 dstepper-block">
                <div class="mb-3">
                  <input type="text" class="form-control form-control-lg" name="firstname" placeholder="Application Name">

                </div>
                <h5>Category</h5>
                <ul class="p-0 m-0">
                  <li class="d-flex align-items-start mb-3">
                    <div class="badge bg-label-info p-2 me-3 rounded"><i class="bx bx-file bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">CRM Application</h6>
                        <small class="text-muted">Scales with any business</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="details-radio" class="form-check-input" type="radio" value="">
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex align-items-start mb-3">
                    <div class="badge bg-label-success p-2 me-3 rounded"><i class="bx bx-cart bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">eCommerce Platforms</h6>
                        <small class="text-muted">Grow Your Business With App</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="details-radio" class="form-check-input" type="radio" value="" checked="">
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex align-items-start">
                    <div class="badge bg-label-danger p-2 me-3 rounded"><i class="bx bx-laptop bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">Online Learning platform</h6>
                        <small class="text-muted">Start learning today</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="details-radio" class="form-check-input" type="radio" value="">
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="col-12 d-flex justify-content-between mt-4">
                  <button class="btn btn-label-secondary btn-prev" disabled=""> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i>
                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                  </button>
                  <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>
                </div>
              </div>

              <!-- Frameworks -->
              <div id="local" class="content pt-3 pt-lg-0 active dstepper-block">
                <h5>Category</h5>

                <div class="row">
                                <?php if(!isset($this->item->id_tipo) || $this->item->id_tipo=='0'): ?> 
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon <?php echo !isset($this->item->id_tipo) || $this->item->id_tipo=='0' ? 'checked' : ''; ?>">
                                        <label class="form-check-label custom-option-content" for="tipo0">
                                            <input class="form-check-input" <?php echo isset($this->item->id_tipo) ? 'style="display:none"' : 'style="margin: 7px 7px 0 0 !important;"' ?> <?php echo !isset($this->item->tipo) ||  $this->item->tipo=='0' ? 'checked' : ''; ?> type="radio" value="0" name="tipo" id="tipo0">
                                            <span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2">
                                                <i class="bx bx-user bx-xs"></i>
                                            </span>
                                            Física                                        
                                        </label>
                                    </div>
                                </div>
                                <?php  endif; ?>
                                <?php  if(!isset($this->item->id_tipo) || $this->item->id_tipo=='1'): ?> 
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon <?php echo isset($this->item->id_tipo) && $this->item->id_tipo=='1' ? 'checked' : ''; ?>">
                                        <label class="form-check-label custom-option-content" for="tipo1">
                                            <input  class="form-check-input" <?php echo isset($this->item->id_tipo) ? 'style="display:none"' : 'style="margin: 7px 7px 0 0 !important;"' ?> <?php echo isset($this->item->tipo) && $this->item->tipo=='1' ? 'checked' : ''; ?> type="radio" value="1" name="tipo" id="tipo1">
                                            <span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2">
                                                <i class="bx bxs-business bx-xs"></i>
                                            </span>
                                            Jurídica
                                        </label>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>








                <div class="row">
          <div class="col-md mb-md-0 mb-2">
            <div class="form-check custom-option custom-option-icon">
              <label class="form-check-label custom-option-content" for="customRadioSvg1">
                <span class="custom-option-body">
                  <img src="../../assets/img/icons/unicons/paypal.png" class="w-px-40 mb-2" alt="paypal">
                  <span class="custom-option-title"> Design </span>
                  <small>Cake sugar plum fruitcake I love sweet roll jelly-o.</small>
                </span>
                <input name="customRadioSvg" class="form-check-input" type="radio" value="" id="customRadioSvg1" checked="">
              </label>
            </div>
          </div>
          <div class="col-md mb-md-0 mb-2">
            <div class="form-check custom-option custom-option-icon">
              <label class="form-check-label custom-option-content" for="customRadioSvg2">
                <span class="custom-option-body">
                  <img src="../../assets/img/icons/unicons/wallet.png" class="w-px-40 mb-2" alt="wallet">
                  <span class="custom-option-title"> Development </span>
                  <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small>
                </span>
                <input name="customRadioSvg" class="form-check-input" type="radio" value="" id="customRadioSvg2">
              </label>
            </div>
          </div>
          <div class="col-md">
            <div class="form-check custom-option custom-option-icon checked">
              <label class="form-check-label custom-option-content" for="customRadioSvg3">
                <span class="custom-option-body">
                  <img src="../../assets/img/icons/unicons/cc-success.png" class="w-px-40 mb-2" alt="cc-success">
                  <span class="custom-option-title"> Native App </span>
                  <small>Cake sugar plum fruitcake I love sweet roll jelly-o.</small>
                </span>
                <input name="customRadioSvg" class="form-check-input" type="radio" value="" id="customRadioSvg3">
              </label>
            </div>
          </div>
        </div>
              </div>



               <?php /*
              <!-- Frameworks -->
              <div id="frameworks2" class="content pt-3 pt-lg-0 active dstepper-block">
                <h5>Category</h5>
                <ul class="p-0 m-0">
                  <li class="d-flex align-items-start mb-3">
                    <div class="badge bg-label-info p-2 me-3 rounded"><i class="bx bxl-react bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">React Native</h6>
                        <small class="text-muted">Create truly native apps</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="frameworks-radio" class="form-check-input" type="radio" value="">
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex align-items-start mb-3">
                    <div class="badge bg-label-danger p-2 me-3 rounded"><i class="bx bxl-angular bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">Angular</h6>
                        <small class="text-muted">Most suited for your application</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="frameworks-radio" class="form-check-input" type="radio" value="" checked="">
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex align-items-start mb-3">
                    <div class="badge bg-label-warning p-2 me-3 rounded"><i class="bx bxl-html5 bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">HTML</h6>
                        <small class="text-muted">Progressive Framework</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="frameworks-radio" class="form-check-input" type="radio" value="" checked="">
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex align-items-start">
                    <div class="badge bg-label-success p-2 me-3 rounded"><i class="bx bxl-vuejs bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">VueJs</h6>
                        <small class="text-muted">JS web frameworks</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="frameworks-radio" class="form-check-input" type="radio" value="">
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>

                <div class="col-12 d-flex justify-content-between mt-4">
                  <button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>
                  <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>
                </div>
              </div>
              */ ?>

              <!-- Database -->
              <div id="database" class="content pt-3 pt-lg-0">
                <div class="mb-3">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail2" placeholder="Database Name">
                </div>
                <h5>Select Database Engine</h5>
                <ul class="p-0 m-0">
                  <li class="d-flex align-items-start mb-3">
                    <div class="badge bg-label-danger p-2 me-3 rounded"><i class="bx bxl-firebase bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">Firebase</h6>
                        <small class="text-muted">Cloud Firestone</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="database-radio" class="form-check-input" type="radio" value="">
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex align-items-start mb-3">
                    <div class="badge bg-label-warning p-2 me-3 rounded"><i class="bx bxl-amazon bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">AWS</h6>
                        <small class="text-muted">Amazon Fast NoSQL Database</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="database-radio" class="form-check-input" type="radio" value="" checked="">
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex align-items-start">
                    <div class="badge bg-label-info p-2 me-3 rounded"><i class="bx bx-data bx-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                      <div class="me-2">
                        <h6 class="mb-0">MySQL</h6>
                        <small class="text-muted">Basic MySQL database</small>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                          <input name="database-radio" class="form-check-input" type="radio" value="">
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
                <div class="col-12 d-flex justify-content-between mt-4">
                  <button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>
                  <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>
                </div>
              </div>

              <!-- billing -->
              <div id="billing" class="content">
                <div id="AppNewCCForm" class="row g-3 pt-3 pt-lg-0 mb-5" onsubmit="return false">
                  <div class="col-12">
                    <div class="input-group input-group-merge">
                      <input class="form-control app-credit-card-mask" type="text" placeholder="1356 3215 6548 7898" aria-describedby="modalAppAddCard">
                      <span class="input-group-text cursor-pointer p-1" id="modalAppAddCard"><span class="app-card-type"></span></span>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <input type="text" class="form-control" placeholder="John Doe">
                  </div>
                  <div class="col-6 col-md-3">
                    <input type="text" class="form-control app-expiry-date-mask" placeholder="MM/YY">
                  </div>
                  <div class="col-6 col-md-3">
                    <div class="input-group input-group-merge">
                      <input type="text" id="modalAppAddCardCvv" class="form-control app-cvv-code-mask" maxlength="3" placeholder="654">
                      <span class="input-group-text cursor-pointer" id="modalAppAddCardCvv2"><i class="text-muted bx bx-help-circle" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Card Verification Value" data-bs-original-title="Card Verification Value"></i></span>
                    </div>
                  </div>
                  <div class="col-12">
                    <label class="switch">
                      <input type="checkbox" class="switch-input" checked="">
                      <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                      </span>
                      <span class="switch-label">Save card for future billing?</span>
                    </label>
                  </div>
                </div>
                <div class="col-12 d-flex justify-content-between mt-4">
                  <button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>
                  <button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>
                </div>
              </div>

              <!-- submit -->
              <div id="submit" class="content text-center pt-3 pt-lg-0">
                <h5 class="mb-2 mt-3">Submit</h5>
                <p>Submit to kick start your project.</p>
                <!-- image -->
                <img src="../../assets/img/illustrations/man-with-laptop-light.png" alt="Create App img" width="200" class="img-fluid" data-app-light-img="illustrations/man-with-laptop-light.png" data-app-dark-img="illustrations/man-with-laptop-dark.png">
                <div class="col-12 d-flex justify-content-between mt-4 pt-2">
                  <button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>
                  <button class="btn btn-success btn-submit"> <span class="align-middle d-sm-inline-block d-none">Submit</span> <i class="bx bx-check bx-xs ms-sm-1 ms-0"></i> </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!--/ App Wizard -->
    </div>
  </div>
</div>


