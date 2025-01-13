<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');

jimport('joomla.image.resize');
$resize = new JResize();

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

/*
if ( !empty( $this->item->image_arma )):
    $imageUser = $resize->resize(JPATH_CDN .DS. 'images' .DS. 'armas' .DS. $this->item->image_arma, 100, 100, 'cache/' . $this->item->image_arma, 'manterProporcao');
else:
    $imageUser = $resize->resize(JPATH_IMAGES .DS. 'noimageweapon.png' , 100, 100, 'cache/noimageweapon.png', 'manterProporcao'); 
endif;  
*/ 
?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-12">
            <div class="card app-calendar-wrapper">
                <div class="row g-0">
                    <!-- Calendar Sidebar -->
                    <div class="col-12 col-md-2 app-calendar-sidebar" id="app-calendar-sidebar">

                        <div class="p-4">
                            <!-- inline calendar (flatpicker) -->
                            <!-- Filter -->
                            <div class="mb-4">
                                <small class="text-small text-muted text-uppercase align-middle">Modalidades</small>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked>
                                <label class="form-check-label" for="selectAll">Ver Todas</label>
                            </div>
                            <div class="app-calendar-events-filter">
                                <?php foreach($this->modalidades as $i => $modalidade): ?>
                                <div class="form-check form-check-<?php echo $modalidade->id_modalidade; ?> mb-2">
                                    <input class="form-check-input input-filter" type="checkbox" id="select-<?php echo $modalidade->id_modalidade; ?>" data-value="<?php echo $modalidade->id_modalidade; ?>" checked>
                                    <label class="form-check-label" for="select-<?php echo $modalidade->id_modalidade; ?>"><?php echo $modalidade->smallname_modalidade; ?></label>
                                </div>
                                
                                <?php endforeach ?>

                            </div>
                        </div>
                    </div>
                    <!-- /Calendar Sidebar -->

                    <!-- Calendar & Modal -->
                    <div class="col-12 col-md-10">
                        <div class="col-12 app-calendar-content">
                            <div class="card shadow-none border-0">
                                <div class="card-body pb-0">
                                    <!-- FullCalendar -->
                                    <div id="calendar" class="m-3"></div>
                                </div>
                            </div>
                            <div class="app-overlay"></div>
                            <!-- FullCalendar Offcanvas -->
                            <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel">
                                <div class="offcanvas-header border-bottom">
                                    <h5 class="offcanvas-title mb-2" id="addEventSidebarLabel">Informações da Etapa</h5>
                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <div class="user-avatar-section">
                                        <div class=" d-flex align-items-center flex-column">
                                            <h5 id="nameEtapa"></h5>
                                            <img class="img-fluid rounded mb-4" id="logoEtapa" src="teste" height="120" width="120" />
                                            <div class="user-info text-center">
                                                <span id="clubeEtapa" class="badge bg-label-secondary"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-around flex-wrap my-3 gap-0 gap-md-3 gap-lg-4">
                                        <div>
                                            <h6 class="mb-2">Etapa</h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar">
                                                    
                                                    <div class="avatar-initial bg-label-primary rounded w-px-35 h-px-35">
                                                        <i class="icon-base bx bx-calendar icon-lg"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <small class="text-nowrap text-heading" style="font-size:0.7125rem" id="etapaDateBeg"></small><br/>
                                                    <small class="text-nowrap text-heading" style="font-size:0.7125rem" id="etapaDateEnd"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-2">Inscrições</h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar">
                                                    <div class="avatar-initial bg-label-primary rounded w-px-35 h-px-35">
                                                        <i class="icon-base bx bx-customize icon-lg"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <small class="text-nowrap text-heading" style="font-size:0.7125rem" id="etapaInscriBeg"></small><br/>
                                                    <small class="text-nowrap text-heading" style="font-size:0.7125rem" id="etapaInscriEnd"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h6 class="pb-1 border-bottom mb-2 mt-4">Detalhes</h6>
                                    <div class="d-flex flex-wrap row-gap-2">
                                        <div class="me-12">
                                            <p class="text-nowrap mb-2" style="font-size: 0.75rem;"><i class="icon-base bx bx-check me-2 align-bottom"></i><span  id="nameCampeonato"></span></p>
                                            <p class="text-nowrap mb-2" style="font-size: 0.75rem;"><i class="icon-base bx bx-check me-2 align-top"></i><span id="anoCampeonato"></span></p>
                                            <p class="text-nowrap mb-2" style="font-size: 0.75rem;"><i class="icon-base bx bx-check me-2 align-bottom"></i><span id="nameModalidade"></span></p>
                                        </div>
                                    </div>

                                    <h6 class="pb-1 border-bottom mb-2 mt-4">Prova(s)</h6>
                                    <div class="d-flex flex-wrap row-gap-2">
                                        <div class="me-12">
                                            <p class="text-nowrap mb-2" style="font-size: 0.75rem;" id="nameProvas"></p>                                        
                                        </div>
                                    </div>

        
                                





                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Calendar & Modal -->
                </div>
            </div>
        </div>
    </div>   
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="calendar" />
    <input type="hidden" name="view" value="calendar" />
    <?php echo JHTML::_('form.token'); ?>
</form>