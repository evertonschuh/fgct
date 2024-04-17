<?php
defined('_JEXEC') or die('Restricted access');
$this->_app     = JFactory::getApplication();
$this->_siteOffset = $this->_app->getCfg('offset');

?>



<div class="misc-wrapper">
    <h2 class="mb-2 mx-2">Sistema em desenvolvimento</h2>
    <p class="mb-4 mx-2">Olá. Estamos trabalhando para ajustar tudo por aqui.</p>
    <div class="mt-3">
      <img src="/assets/img/manutencao.png" alt="page-misc-error-light" width="500" class="img-fluid" data-app-dark-img="../../assets/img/manutencao.png" data-app-light-img="../../assets/img/manutencao.png">
    </div>
  </div>



<?php /*

<!-- Content Row -->
<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="index.php?view=pfs" class="a-dashboard">
            <div class="card border-left-primary shadow h-auto py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Corredores Cadastrados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->corredors + 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="index.php?view=pjs" class="a-dashboard">
            <div class="card border-left-success shadow h-auto py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pessoas Jurídicas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->corredors + 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Pending Requests Card Example -->

    <div class="col-xl-3 col-md-6 mb-4">
        <a href="index.php?view=armas" class="a-dashboard">
            <div class="card border-left-info shadow h-auto py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">

                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cadastros Dia</div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->corredors + 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-crosshairs fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="index.php?view=protocolos" class="a-dashboard">
            <div class="card border-left-danger shadow h-auto py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cadastros Mês
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->corredors + 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </a>
</div>

<div class="row">
    <?php /*
    <div class="col-md-12 col-xl-8 col-lg-8">
        <!-- Area Chart -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aniversariantes</h6>
            </div>
            <div class="card-body">
                <div class="chart-area chart-aniversario">
                    <?php foreach ($this->aniversariantes as $i => $item) : ?>
    <a href="<?php echo JRoute::_('index.php?view=pf&cid=' . $item->id_pf); ?>" class="list-group-item">
        <i class="fa fa-user fa-fw"></i> <?php echo $item->name_pf; ?>

        <span
            class="pull-right text-muted small"><em><?php echo JHtml::date(JFactory::getDate($item->data_nascimento_pf, $this->_siteOffset)->toISO8601(), 'DATE_FORMAT'); ?></em>
        </span>
        <span class="pull-right text-muted small hidden-xs"><em><?php echo $item->email_pf; ?></em>
        </span>
    </a>
    <?php endforeach; ?>
</div>
</div>
</div>
</div>

<div class="col-xl-4 col-lg-4">
    <!-- Bar Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Matrículas</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 small">
                    <div class="chart-bar">
                        <canvas id="myPieChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4 small">
                    <span class="mr-1">
                        <i class="fas fa-circle text-info"></i> Não Confirmada
                    </span><br />
                    <span class="mr-1">
                        <i class="fas fa-circle text-success"></i> Ativa
                    </span><br />
                    <span class="mr-1">
                        <i class="fas fa-circle text-primary"></i> Trancada
                    </span><br />
                    <span class="mr-1">
                        <i class="fas fa-circle text-light"></i> Concluida
                    </span><br />
                    <span class="mr-1">
                        <i class="fas fa-circle text-secondary"></i> Cancelada
                    </span><br />
                    <span class="mr-1">
                        <i class="fas fa-circle text-danger"></i> Inadimplente
                    </span><br />
                    <span class="mr-1">
                        <i class="fas fa-circle text-warning"></i> Travada
                    </span>
                </div>
            </div>
            <hr>
            Gráfico de <code>Status de Matrículas</code>.
        </div>
    </div>
</div>

</div>
*/ ?>