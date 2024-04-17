<?php
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');

$listOrder    = $this->state->get('list.ordering');
$listDirn    = $this->state->get('list.direction');

$saveOrder    = $listOrder == 'ordering';
?>
<style>
    /**===== bars3 =====*/
    
#bars3 {
  display: block;
  position: relative;

}

#bars3 span {
 display: inline-flex;
 width: 15px;
 height: 25px;
 margin: 1px;
  background: rgba(127, 188, 73, 0.25);
  -webkit-animation: bars3 1s  infinite ease-in;
          animation: bars3 1s  infinite ease-in;
}

#bars3 span:nth-child(2) {
  left: 11px;
  -webkit-animation-delay: 0.1s;
          animation-delay: 0.1s;
}

#bars3 span:nth-child(3) {
  left: 22px;
  -webkit-animation-delay: 0.2s;
          animation-delay: 0.2s;
}

#bars3 span:nth-child(4) {
  left: 33px;
  -webkit-animation-delay: 0.3s;
          animation-delay: 0.3s;
}

#bars3 span:nth-child(5) {
  left: 44px;
  -webkit-animation-delay: 0.4s;
          animation-delay: 0.4s;
}

@keyframes bars3 {
  0% {
    background: rgba(127, 188, 73, 0.25);
  }
  25% {
    background: rgba(127, 188, 73, 1);
  }
  50% {
    background: rgba(127, 188, 73, 0.25);
  }
  100% {
    background: rgba(127, 188, 73, 0.25);
  }
}
@-webkit-keyframes bars3 {
  0% {
    background: rgba(127, 188, 73, 0.25);
  }
  25% {
    background: rgba(127, 188, 73, 1);
  }
  50% {
    background: rgba(127, 188, 73, 0.25);
  }
  100% {
    background: rgba(127, 188, 73, 0.25);
  }
}
/** END of bars3 */


.custom-radio-squad input:disabled + .radio-btn-option {
    border: 2px solid red;
    cursor: default;
    background-color: #e6e6e6;
}
.descriptiom p{
    margin-bottom: 0.1rem;
}

</style>

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
<div id="wizard-validation" class="bs-stepper mt-2 linear">
      <div class="bs-stepper-header">
        <div class="step active" data-target="#account-details-validation">
          <button type="button" class="step-trigger" aria-selected="true">
            <span class="bs-stepper-circle">1</span>
            <span class="bs-stepper-label mt-1">
              <span class="bs-stepper-title">Account Details</span>
              <span class="bs-stepper-subtitle">Setup Account Details</span>
            </span>
          </button>
        </div>
        <div class="line">
          <i class="bx bx-chevron-right"></i>
        </div>
        <div class="step" data-target="#personal-info-validation">
          <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
            <span class="bs-stepper-circle">2</span>
            <span class="bs-stepper-label mt-1">
              <span class="bs-stepper-title">Personal Info</span>
              <span class="bs-stepper-subtitle">Add personal info</span>
            </span>
          </button>
        </div>
        <div class="line">
          <i class="bx bx-chevron-right"></i>
        </div>
        <div class="step" data-target="#social-links-validation">
          <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
            <span class="bs-stepper-circle">3</span>
            <span class="bs-stepper-label mt-1">
              <span class="bs-stepper-title">Social Links</span>
              <span class="bs-stepper-subtitle">Add social links</span>
            </span>
          </button>
        </div>
      </div>
      <div class="bs-stepper-content">
        <form id="wizard-validation-form" onsubmit="return false">
          <!-- Account Details -->
          <div id="account-details-validation" class="content active dstepper-block fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="content-header mb-3">
              <h6 class="mb-0">Account Details</h6>
              <small>Enter Your Account Details.</small>
            </div>
            <div class="row g-3">
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationUsername">Username</label>
                <input type="text" name="formValidationUsername" id="formValidationUsername" class="form-control" placeholder="johndoe">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationEmail">Email</label>
                <input type="email" name="formValidationEmail" id="formValidationEmail" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 form-password-toggle fv-plugins-icon-container">
                <label class="form-label" for="formValidationPass">Password</label>
                <div class="input-group input-group-merge has-validation">
                  <input type="password" id="formValidationPass" name="formValidationPass" class="form-control" placeholder="············" aria-describedby="formValidationPass2">
                  <span class="input-group-text cursor-pointer" id="formValidationPass2"><i class="bx bx-hide"></i></span>
                </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              </div>
              <div class="col-sm-6 form-password-toggle fv-plugins-icon-container">
                <label class="form-label" for="formValidationConfirmPass">Confirm Password</label>
                <div class="input-group input-group-merge has-validation">
                  <input type="password" id="formValidationConfirmPass" name="formValidationConfirmPass" class="form-control" placeholder="············" aria-describedby="formValidationConfirmPass2">
                  <span class="input-group-text cursor-pointer" id="formValidationConfirmPass2"><i class="bx bx-hide"></i></span>
                </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              </div>
              <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-label-secondary btn-prev" disabled="">
                  <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                  <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-primary btn-next">
                  <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                  <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- Personal Info -->
          <div id="personal-info-validation" class="content fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="content-header mb-3">
              <h6 class="mb-0">Personal Info</h6>
              <small>Enter Your Personal Info.</small>
            </div>
            <div class="row g-3">
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationFirstName">First Name</label>
                <input type="text" id="formValidationFirstName" name="formValidationFirstName" class="form-control" placeholder="John">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationLastName">Last Name</label>
                <input type="text" id="formValidationLastName" name="formValidationLastName" class="form-control" placeholder="Doe">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationCountry">Country</label>
                <div class="position-relative"><div class="position-relative"><select class="select2 select2-hidden-accessible" id="formValidationCountry" name="formValidationCountry" tabindex="-1" aria-hidden="true" data-select2-id="formValidationCountry">
                  <option label=" " data-select2-id="27"></option>
                  <option>UK</option>
                  <option>USA</option>
                  <option>Spain</option>
                  <option>France</option>
                  <option>Italy</option>
                  <option>Australia</option>
                </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="26" style="width: auto;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-formValidationCountry-container"><span class="select2-selection__rendered" id="select2-formValidationCountry-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Select value</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></div></div>
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationLanguage">Language</label>
                <div class="dropdown bootstrap-select show-tick w-auto"><select class="selectpicker w-auto" id="formValidationLanguage" data-style="btn-transparent" data-icon-base="bx" data-tick-icon="bx-check text-white" name="formValidationLanguage" multiple="">
                  <option>English</option>
                  <option>French</option>
                  <option>Spanish</option>
                </select><button type="button" tabindex="-1" class="btn dropdown-toggle bs-placeholder btn-transparent" data-bs-toggle="dropdown" role="combobox" aria-owns="bs-select-2" aria-haspopup="listbox" aria-expanded="false" title="Nothing selected" data-id="formValidationLanguage"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">Nothing selected</div></div> </div></button><div class="dropdown-menu "><div class="inner show" role="listbox" id="bs-select-2" tabindex="-1" aria-multiselectable="true"><ul class="dropdown-menu inner show" role="presentation"></ul></div></div></div>
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                  <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                  <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-primary btn-next">
                  <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                  <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- Social Links -->
          <div id="social-links-validation" class="content fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="content-header mb-3">
              <h6 class="mb-0">Social Links</h6>
              <small>Enter Your Social Links.</small>
            </div>
            <div class="row g-3">
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationTwitter">Twitter</label>
                <input type="text" name="formValidationTwitter" id="formValidationTwitter" class="form-control" placeholder="https://twitter.com/abc">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationFacebook">Facebook</label>
                <input type="text" name="formValidationFacebook" id="formValidationFacebook" class="form-control" placeholder="https://facebook.com/abc">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationGoogle">Google+</label>
                <input type="text" name="formValidationGoogle" id="formValidationGoogle" class="form-control" placeholder="https://plus.google.com/abc">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-sm-6 fv-plugins-icon-container">
                <label class="form-label" for="formValidationLinkedIn">LinkedIn</label>
                <input type="text" name="formValidationLinkedIn" id="formValidationLinkedIn" class="form-control" placeholder="https://linkedin.com/abc">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-primary btn-prev">
                  <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                  <span class="align-middle d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-next btn-submit">Submit</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
        </div>
      </div>
      <!--/ App Wizard -->
    </div>
  </div>
</div>


