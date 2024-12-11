<?php

defined ('_JEXEC') or die ('resticted aceess');

jimport('joomla.image.resize');
$resize = new JResize();

$uri = JFactory::getURI();
$user = JFactory::getUser();
$view = JRequest::getCmd('view');
$this->config   = JFactory::getConfig();
$this->siteOffset = $this->config->getValue('offset');

if ( !$user->get('guest') ):
		
	$user_id = $user->get('id'); 
	$NameUser = trim($user->get('name')); 
	$avatar = trim($user->get('avatar')); 
	$lastvisitDate = $user->get('lastvisitDate'); 
	
  $this->db		= JFactory::getDBO();
  $query = $this->db->getQuery(true);			
  $query->select('IF(ISNULL(id_pj),#__intranet_pf.image_pf, #__intranet_pj.logo_pj) AS id_estado');		
  $query->from('#__intranet_associado');	
  $query->leftJoin($this->db->quoteName('#__intranet_pf') . ' USING( ' . $this->db->quoteName('id_user') . ')');
  $query->leftJoin($this->db->quoteName('#__intranet_pj') . ' USING( ' . $this->db->quoteName('id_user') . ')');				
  $query->where( $this->db->quoteName('id_user') . '=' . $this->db->escape( $user_id ) );
  $this->db->setQuery($query);
	$avatar = $this->db->loadResult();



	$NameUser = explode(" ", $NameUser);
	if ( count($NameUser)>1 ) 
		$Nome = $NameUser[0] . ' ' . end($NameUser);
	else
		$Nome =  end($NameUser);


	if ( !empty( $avatar )):
		$avatarUser = $resize->resize(JPATH_CDN.DS. 'images' . DS . 'avatar' .DS. $avatar, 266, 266, 'cache/tmp_' . $avatar, 'tirarProporcao');
	else:
		$avatarUser = $resize->resize(JPATH_IMAGES . DS . 'noimageuser.png', 266, 266, 'cache/tmp_noimageuser.png', 'tirarProporcao');
	endif;
endif;



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html lang="<?php echo $this->language; ?>" class="light-style layout-navbar-fixed layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="vertical-menu-template">
    <head>
      <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
      <link rel="shortcut icon" href="/assets/img/favicon.ico" >
      <jdoc:include type="head" />
      <link href="/assets/css/sweetalert.css" rel="stylesheet">   
        <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
        <!-- Icons. Uncomment required icon fonts -->
      <link rel="stylesheet" href="/assets/vendor/fonts/boxicons.css" />
        <!-- Core CSS -->
      <link rel="stylesheet" href="/assets/vendor/css/core.css" class="template-customizer-core-css" />
      <link rel="stylesheet" href="/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
      <link rel="stylesheet" href="/assets/css/main.css" />
        <!-- Vendors CSS -->
      <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
      <link rel="stylesheet" href="/assets/vendor/libs/apex-charts/apex-charts.css" />   
      <link rel="stylesheet" href="/assets/vendor/libs/toast-notification/toast-notification.css" />

      <script src="/assets/vendor/js/helpers.js"></script>
      <script src="/assets/vendor/js/template-customizer.js"></script>
      <script src="/assets/js/config.js"></script>
    </head>
    <body>
      <?php if( !$user->get('guest') && $view != 'autenticate'): ?>

      <div id="overlay" class="animate">
        <div class="preload-wrap"></div>
        <img src="/assets/img/favicon.ico" alt="" class="menu-logo" width="129" height="129">
      </div>
      <?php //if (!JRequest::getInt('hidemainmenu')): ?>
      <!-- Layout wrapper -->
      <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
          <!-- Menu -->
          <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" >
            <div class="app-brand main">
              <!-- Logo -->
              <div class="app-brand-logo main justify-content-center">
              </div>
              <!-- /Logo -->
              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
              </a>
            </div>
            <div class="menu-inner-shadow"></div>
            <ul class="menu-inner py-1 ps ps--active-y">
              <!-- Dashboard -->
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=dashboard');?>"  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-home-circle"></i>
                  <div data-i18n="Analytics">Dashboard</div>
                </a>
              </li>
              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Menu Esportivo</span>
              </li>
              <li class="menu-item" style="">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons bx bx-edit"></i>
                  <div data-i18n="Inscriçôes">Inscriçôes</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="<?php echo JRoute::_('index.php?view=myenrollments');?>" class="menu-link">
                      <div data-i18n="Minhas Inscriçôes">Minhas Inscriçôes</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="javascript:void(0);<?php //echo JRoute::_('index.php?view=enrollmentopens');?>" 
                    onclick="Swal.fire({title:'Atenção',html:'Este portal está em desenvolvimento.<br/>Em breve mais recursos estarão disponíveis.',icon:'info',confirmButtonColor: '#467119',confirmButtonText: 'Entendi'})"
                    class="menu-link">
                      <div data-i18n="Inscriçôes Abertas">Inscriçôes Abertas</div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="menu-item" style="">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons bx bx-crosshair"></i>
                  <div data-i18n="Resultados">Resultados</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="javascript:void(0);<?php //echo JRoute::_('index.php?view=results');?>" 
                    onclick="Swal.fire({title:'Atenção',html:'Este portal está em desenvolvimento.<br/>Em breve mais recursos estarão disponíveis.',icon:'info',confirmButtonColor: '#467119',confirmButtonText: 'Entendi'})"
                    class="menu-link">
                      <div data-i18n="Account">Meus Resultdos</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="javascript:void(0);<?php //echo JRoute::_('index.php?view=rankings');?>" 
                    onclick="Swal.fire({title:'Atenção',html:'Este portal está em desenvolvimento.<br/>Em breve mais recursos estarão disponíveis.',icon:'info',confirmButtonColor: '#467119',confirmButtonText: 'Entendi'})"
                    class="menu-link">
                      <div data-i18n="Rankings">Rankings</div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=calendar');?>" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-calendar"></i>  
                  <div data-i18n="Calendários">Calendários</div>
                </a>
              </li>

              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Cadastro</span>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=profile');?>" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-user"></i> 
                  <div data-i18n="Meu Perfil">Meu Perfil</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=weapons');?>" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-target-lock"></i>
                  <div data-i18n="Minhas Armas">Minhas Armas</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=membershipcard');?>" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-id-card" ></i>
                  <div data-i18n="Cartão de Sócio">Carteira Digital</div>
                </a>
              </li>
              
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=documents');?>" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-file" ></i>
                  <div data-i18n="Meus Documentos">Meus Documentos</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=payments');?>" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                  <div data-i18n="Cobranças">Minhas Cobranças</div>
                </a>
              </li>
              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">FGCT Digital</span>
              </li> 
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=requests');?>" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-archive-in"></i>
                  <div data-i18n="Pedidos">Serviços</div>
                </a>
              </li>
              <?php /*
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=access');?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                  <div data-i18n="Dados de Acesso">Dados de Acesso</div>
                </a>
              </li>
              */?>
              <?php /*
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=classifi');?>" class="menu-link">
                  <div data-i18n="Account">Equipes Adicionais</div>
                </a>
              </li>
              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Financeiro</span>
              </li>
              <li class="menu-item" style="">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons bx bx-dock-top"></i>
                  <div data-i18n="Account Settings">Receitas</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="index.php?view=cobrancas" class="menu-link">
                      <div data-i18n="Account">Cobranças</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="pages-account-settings-notifications.html" class="menu-link">
                      <div data-i18n="Notifications">Taxas de Inscrições</div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="menu-item" style="">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons bx bx-dock-top"></i>
                  <div data-i18n="Account Settings">Produtos</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="1.html" class="menu-link">
                      <div data-i18n="Account">Anuidades</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="2.html" class="menu-link">
                      <div data-i18n="Notifications">Outros</div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="menu-item" style="">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons bx bx-dock-top"></i>
                  <div data-i18n="Account Settings">Arquivos</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="3.html" class="menu-link">
                      <div data-i18n="Account">Retorno</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="4.html" class="menu-link">
                      <div data-i18n="Notifications">Remessa</div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="menu-item" style="">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon tf-icons bx bx-dock-top"></i>
                  <div data-i18n="Account Settings">Configurações</div>
                </a>
                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="5.html" class="menu-link">
                      <div data-i18n="Account">Métodos de Pagamento</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="6.html" class="menu-link">
                      <div data-i18n="Notifications">Certificado Digital</div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="menu-item">
                <a href="#<?php //echo JRoute::_('index.php?view=');?>" class="menu-link">
                  <div data-i18n="Account">Cobranças</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="#<?php //echo JRoute::_('index.php?view=);?>" class="menu-link">
                  <div data-i18n="Notifications">Taxas de Inscrição</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="#<?php //echo JRoute::_('index.php?view=);?>" class="menu-link">
                  <div data-i18n="Connections">Notas</div>
                </a>
              </li>
              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Cadastro</span>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=atividades');?>" class="menu-link">
                  <div data-i18n="Corredors">Atividades de Corrida</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=ccategorias');?>" class="menu-link">
                  <div data-i18n="ccategoria">Categorias de Corredores</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="<?php echo JRoute::_('index.php?view=cgrupos');?>" class="menu-link">
                  <div data-i18n="cgrupos">Grupos de Corrida</div>
                </a>
              </li>





              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Painel de Controle</span>
              </li>

              <li class="menu-item">
                <a href="#<?php //echo JRoute::_('index.php?view=);?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                  <div data-i18n="Notifications">Configurações</div>
                </a>
              </li>
            */  ?>
            </ul>
          </aside>
          <!-- / Menu -->
          <!-- Layout container -->
          <div class="layout-page">
            <!-- Navbar -->
            <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar" >
              <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                  <i class="bx bx-menu bx-sm"></i>
                </a>
              </div>
              <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                  <!-- Place this tag where you want the button to render. -->
                  <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                      <i class="bx bx-sm bx-moon"></i>
                    </a>
                  </li>
                  <!-- Notification -->
                  <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">   
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                      <i class="bx bx-bell bx-sm"></i>
                      <?php /*<span class="badge bg-danger rounded-pill badge-notifications">5</span> */ ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                      <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                          <h5 class="text-body mb-0 me-auto">Notificações</h5>
                          <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Marcar todas como lida" data-bs-original-title="Marcar todas como lida"><i class="bx fs-4 bx-envelope-open"></i></a>
                        </div>
                      </li>
                      <li class="dropdown-notifications-list scrollable-container ps">
                        <span class=" d-flex justify-content-center p-3">
                        <em> Nenhuma notificação </em>
                        </span>
                      </li>
                      <?php /*
                      <li class="dropdown-notifications-list scrollable-container ps">
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item list-group-item-action dropdown-notifications-item">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                  <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="mb-1">Charles Franklin</h6>
                                <p class="mb-0">Accepted your connection</p>
                                <small class="text-muted">12hr ago</small>
                              </div>
                              <div class="flex-shrink-0 dropdown-notifications-actions">
                                <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="bx bx-x"></span></a>
                              </div>
                            </div>
                          </li>
                          <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                            <div class="d-flex">
                              <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                  <img src="/assets/img/avatars/1.png" alt="" class="w-px-40 h-auto rounded-circle">
                                </div>
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="mb-1">New Message ✉️</h6>
                                <p class="mb-0">You have new message from Natalie</p>
                                <small class="text-muted">1h ago</small>
                              </div>
                              <div class="flex-shrink-0 dropdown-notifications-actions">
                                <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                                <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="bx bx-x"></span></a>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></li>
                      <li class="dropdown-menu-footer border-top">
                        <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3">
                          Ver todas notificações
                        </a>
                      </li>
                      */ ?>

                    </ul>
                  </li>
                  <!--/ Notification -->
                  <!-- User -->
                  <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                        <img src="<?php echo $avatarUser; ?>" alt="" class="w-px-40 h-auto rounded-circle" />
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="#">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">
                                <img src="<?php echo $avatarUser; ?>" alt="<?php echo $Nome; ?>" class="w-px-40 h-auto rounded-circle" />
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <span class="fw-semibold d-block"><?php echo $Nome; ?></span>
                              <small class="text-muted">Portal</small>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="<?php echo JRoute::_('index.php?view=profile');?>">
                          <i class="bx bx-user me-2"></i>
                          <span class="align-middle">Perfil</span>
                        </a>
                      </li>
                      <?php /*
                      <li>
                        <a class="dropdown-item" href="<?php echo JRoute::_('index.php?view=access');?>">
                          <i class="bx bx-lock me-2"></i>
                          <span class="align-middle">Dados de Acesso</span>
                        </a>
                      </li>
                      */?>
                      <?php /*
                      <li>
                        <a class="dropdown-item" href="#">
                          <span class="d-flex align-items-center align-middle">
                            <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                            <span class="flex-grow-1 align-middle">Billing</span>
                            <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                          </span>
                        </a>
                      </li>
                      */ ?>
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="#"  data-bs-toggle="modal" data-bs-target="#logoutModal">
                          <i class="bx bx-power-off me-2"></i>
                          <span class="align-middle">Sair</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <!--/ User -->
                </ul>
              </div>
            </nav>
            <!-- / Navbar -->
            <!-- Content wrapper -->
            <div class="content-wrapper">
              <!-- Content -->
              <div class="container-xxl flex-grow-1 container-p-y">
                <jdoc:include type="modules" name="title" />
                <jdoc:include type="modules" name="subinfo" />
                <jdoc:include type="message" />
                <jdoc:include type="component" />
              </div>
              <!-- / Content -->
              <!-- Footer -->
              <footer class="content-footer footer bg-footer-theme mt-5">
                <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>, All Reserved 
                    <img src="assets/img/eas_box_min.png" style="margin-top:-5px" />
                    <a href="https://www.easistemas.com.br" target="_blank" class="footer-link fw-bolder">EASistemas</a>
                  </div>
                </div>
              </footer>
              <!-- / Footer -->
              <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
          </div>
          <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
      </div>
      <!-- / Layout wrapper -->
      <!-- Core JS -->
      <!-- build:js assets/vendor/js/core.js -->
      <!-- Logout Modal-->
      <div class="modal fade" id="logoutModal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog " role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalCenterTitle">Pronto para partir?</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Selecione "Sair" abaixo se você estiver pronto para encerrar sua sessão atual.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
              <form role="form" method="post" name="formLogoff">
                  <input type="hidden" name="task" id="task" value="logoff" />
                  <input type="hidden" name="controller" id="controller" value="login" />			
                  <input type="hidden" name="view" id="view" value="login" />
                  <?php echo JHTML::_('form.token'); ?>
              </form>  
              <a class="btn btn-primary" href="javascript:void(0);" onclick="Joomla.submitform('logoff',formLogoff)">Sair</a>
            </div>
          </div>
        </div>
      </div>
      <?php else: ?>
      <jdoc:include type="message" />
      <jdoc:include type="component" />        
      <?php endif; ?>

    <script src="/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets/vendor/libs/popper/popper.js"></script>
    <script src="/assets/vendor/js/bootstrap.js"></script>
    <script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/assets/vendor/libs/hammer/hammer.js"></script>
    <!-- endbuild -->


    <script src="/assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    

    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script src="/assets/js/sweetalert.js"></script>
		<jdoc:include type="footer" />
    <?php if( !$user->get('guest')): ?>
    <script type="text/javascript">
      $(document).ready(function(){
        jQuery('a[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', jQuery(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if(activeTab)
            $('.nav-pills a[href="' + activeTab + '"]').tab('show');


       jQuery('#overlay').css('opacity', '0');
       jQuery('#overlay').css('visibility', 'hidden');
        
       jQuery('a[href^="/"]:not(a[target="_blank"]) ').on('click', function(){
            localStorage.clear();
            jQuery('#overlay').removeClass('animate');
            jQuery('#overlay').css('opacity', 1);
            jQuery('#overlay').css('visibility', 'visible');
        });

      });
    </script> 
    <?php endif; ?>
	</body>
</html>

