<?php
defined('_JEXEC') or die('Restricted access'); 

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

?>
<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="card shadow mb-4">
		<div class="card-header py-3">
        	<h6 class="m-0 font-weight-bold text-primary"><?php echo empty($this->item->id_event) ? 'Novo Cadastro' : 'Editar Cadastro' ?></h6>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-8 col-xl-offset-2">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav  nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><b>Dados do Evento</b></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" id="reserve-tab" data-toggle="tab" href="#reserve" role="tab" aria-controls="reserve" aria-selected="true"><b>Configurar Reservas</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="results-tab" data-toggle="tab" href="#results" role="tab" aria-controls="results" aria-selected="true"><b>Configurar Lançamentos</b></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>
                                            <div class="form-group">
                                                <fieldset class="group-border">
                                                    <legend class="group-border">Dados do Evento</legend>
                                                    <div class="row">
                                                        
                                                    <div class="col-12 ">
                                                        <?php if( $this->util->_user->authorize('core.admin', 'com_easistemas') ): ?>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Proprietário do Evento:*'); ?></label>
                                                            <select name="id_client" class="form-control required w-100 select2" >
                                                                <option value="" disabled selected>- Selecione o Cliente Proprietário do Evento-</option>
                                                                <?php  echo JHtml::_('select.options',  $this->clients, 'value', 'text', $this->item->id_client); ?>
                                                            </select>
                                                        </div>                                    
                                                        <?php endif;?>

                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Reservas Ativadas:') ?></label>
                                                            <fieldset>
                                                                <?php echo JHTML::_('select.booleanlist', 'status_event', 'required', $this->item->status_event); ?>
                                                            </fieldset>
                                                        </div>           
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Nome do Evento:*') ?></label>
                                                            <input type="text" autocomplete="off" class="form-control required" name="name_event" id="name_event" value="<?php echo $this->item->name_event; ?>" placeholder="<?php echo JText::_('Informe o nome do Evento') ?>"  >
                                                        </div> 
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Link de Inscrição:') ?></label>
                                                            <div class="input-group">                               
                                                                <input type="text" autocomplete="off" class="form-control" readonly id="slug_event" value="<?php echo !empty($this->item->slug_event) ? $this->util->_app->getCfg('urlbase') .'/'. $this->item->slug_event: ''; ?>" placeholder="<?php echo JText::_('O cadastramento do link ocorre de forma automática.') ?>"  >
                                                                <?php if(!empty($this->item->slug_event)):?>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-light copy-slug" type="button" title="Copiar link"><i class="fas fa-copy"></i></button>
                                                                    <button class="btn btn-light" onclick=" window.open('<?php echo $this->util->_app->getCfg('urlbase') .'/'. $this->item->slug_event; ?>','_blank')" title="Abrir em uma nova aba"><i class="fas fa-external-link-alt"></i></button>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Link dos Resultados:') ?></label>
                                                            <div class="input-group">                               
                                                                <input type="text" autocomplete="off" class="form-control" readonly value="<?php echo !empty($this->item->slug_event) ? $this->util->_app->getCfg('urlbase') .'/resultados-do-evento/'. $this->item->slug_event: ''; ?>" placeholder="<?php echo JText::_('O cadastramento do link ocorre de forma automática.') ?>"  >
                                                                <?php if(!empty($this->item->slug_event)):?>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-light copy-slug" type="button" title="Copiar link"><i class="fas fa-copy"></i></button>
                                                                    <button class="btn btn-light" onclick=" window.open('<?php echo $this->util->_app->getCfg('urlbase') .'/resultados-do-evento/'. $this->item->slug_event; ?>','_blank')" title="Abrir em uma nova aba"><i class="fas fa-external-link-alt"></i></button>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Link dos Show TV:') ?></label>
                                                            <div class="input-group">                               
                                                                <input type="text" autocomplete="off" class="form-control" readonly value="<?php echo !empty($this->item->slug_event) ? $this->util->_app->getCfg('urlbase') .'/show-de-resultados/'. $this->item->slug_event: ''; ?>" placeholder="<?php echo JText::_('O cadastramento do link ocorre de forma automática.') ?>"  >
                                                                <?php if(!empty($this->item->slug_event)):?>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-light copy-slug" type="button" title="Copiar link"><i class="fas fa-copy"></i></button>
                                                                    <button class="btn btn-light" onclick=" window.open('<?php echo $this->util->_app->getCfg('urlbase') .'/show-de-resultados/'. $this->item->slug_event; ?>','_blank')" title="Abrir em uma nova aba"><i class="fas fa-external-link-alt"></i></button>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Tipo do Evento:'); ?></label>
                                                            <select name="type_event" class="form-control required w-md-100 w-lg-auto" >
                                                                <?php 
                                                                    $type_event[] = JHTML::_('select.option', '1', JText::_( 'TRAP AMERICANO' ), 'value', 'text' );
                                                                    $type_event[] = JHTML::_('select.option', '2', JText::_( 'PERCURSO DE CAÇA' ), 'value', 'text' );
                                                                    echo JHtml::_('select.options',  $type_event, 'value', 'text', $this->item->type_event);
                                                                ?>
                                                            </select>
                                                        </div>    
                                                        <div class="row">
                                                            <div class="col-12 ">
                                                                <div class="form-group">
                                                                    <label><?php echo JText::_('Informações Adicionais do Evento:') ?></label>
                                                                    <?php 
                                                                        $editor = JFactory::getEditor('tinymce');
                                                                        $multipleEditor = array(
                                                                            array( 'class_editor'=>'basic_mode', 'mode' =>'0', 'html_height'=>'200px', ),
                                                                        );	

                                                                        $params = array( 'multiples_editor' => $multipleEditor, );												
                                                                
                                                                    // $editor = JFactory::getEditor('tinymce');

                                                                       // echo $editor->display('description_event', $this->item->description_event, '100%', '60', '20', '10', array('article','image','pagebreak', 'readmore'), 'description_event', null, null, $params,'basic_mode');
                                                                ?>
                                                                    <p class="form-text text-muted"><em>Este texto será exibido na tela de reservas, abaixo do título contendo o nome do evento.</em></p>
                                                                </div>
                                                            </div>
                                                        </div>	
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="reserve" role="tabpanel" aria-labelledby="reserve-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>
                           
                                            <div class="form-group">
                                                <fieldset class="group-border">
                                                    <legend class="group-border">Reservas</legend>
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Métodos de Reservas</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Cadastre métodos diferentes de reserva para este evento.<br/>Os métodos serão exibidos apenas quando existirem mais de 1.</em></p>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Nome do Método:*'); ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required" name="name_method[]" value="<?php echo $this->item->methods_event[0]['name_method']; ?>" placeholder="<?php echo JText::_('Nome do Método') ?>" />
                                                                        </div>   
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Reservas para o Método:*'); ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="amount_method[]" data-min="1" data-max="10" data-step="1" value="<?php echo $this->item->methods_event[0]['amount_method'] + 0; ?>" placeholder="<?php echo JText::_('Total de reservas para o método') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="w-md-100">&nbsp;</label>
                                                                        <button class="btn btn-primary add_method" type="button">Incluir novo Método</button>
                                                                    </div>
                                                                </div>
                                                                <div id="aditional_method">
                                                                <?php if(count($this->item->methods_event)>1):?>

                                                                    <?php foreach($this->item->methods_event as $i => $method_event):?>
                                                                            <?php if($i==0) continue;?>

                                                                    <div class="row">
                                                                        <div class="col-12 col-md-6 col-lg-4" >
                                                                            <div class="form-group">
                                                                                <label><?php echo JText::_('Nome do Método:*'); ?></label>
                                                                                <input type="text" autocomplete="off" class="form-control required" name="name_method[]"  value="<?php echo $method_event['name_method']; ?>" placeholder="<?php echo JText::_('Nome do Método') ?>" />
                                                                            </div>   
                                                                        </div>
                                                                        <div class="col-12 col-md-6 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label><?php echo JText::_('Reservas para o Método:*') ?></label>
                                                                                <input type="text" autocomplete="off" class="form-control required value-touchspin" name="amount_method[]" data-min="1" data-max="10" data-step="1" value="<?php echo $method_event['amount_method'] + 0; ?>" placeholder="<?php echo JText::_('Total de reservas para o método') ?>" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="w-md-100">&nbsp;</label>
                                                                            <button class="btn btn-danger remove" type="button">Remover</button>
                                                                        </div>
                                                                    </div>
                                                                    <?php endforeach; ?>


                                                                <?php endif;?>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>  

                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Cronograma do Evento</legend>

                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Abertura para Reservas:*'); ?></label>
                                                                            <div class="input-group date-time">
                                                                                <input type="text" autocomplete="off" class="form-control required" name="begin_reserve_event" value="<?php if ($this->item->begin_reserve_event) echo JHtml::date(JFactory::getDate($this->item->begin_reserve_event, $this->util->_siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME'); ?>" placeholder="<?php echo JText::_('Abertura para Reservas') ?>" />
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-light" type="button"><i class="fa fa-calendar"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>     
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Encerramento das Reservas:*'); ?></label>
                                                                            <div class="input-group date-time">
                                                                                <input type="text" autocomplete="off" class="form-control required" name="end_reserve_event" value="<?php if ($this->item->end_reserve_event) echo JHtml::date(JFactory::getDate($this->item->end_reserve_event, $this->util->_siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME'); ?>" placeholder="<?php echo JText::_('Encerramento das Reservas') ?>" />
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-light" type="button"><i class="fa fa-calendar"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>     
                                                                    </div> 
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Início do Evento:*'); ?></label>
                                                                            <div class="input-group date">
                                                                                <input type="text" autocomplete="off" class="form-control required" name="begin_event" value="<?php if ($this->item->begin_event) echo JHtml::date(JFactory::getDate($this->item->begin_event, $this->util->_siteOffset)->toISO8601(true), 'DATE_FORMAT'); ?>" placeholder="<?php echo JText::_('Início Evento') ?>" />
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-light" type="button"><i class="fa fa-calendar"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>     
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Término do Evento:*'); ?></label>
                                                                            <div class="input-group date">
                                                                                <input type="text" autocomplete="off" class="form-control required" name="end_event" value="<?php if ($this->item->end_event) echo JHtml::date(JFactory::getDate($this->item->end_event, $this->util->_siteOffset)->toISO8601(true), 'DATE_FORMAT'); ?>" placeholder="<?php echo JText::_('Término Evento') ?>" />
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-light" type="button"><i class="fa fa-calendar"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>     
                                                                    </div> 
                                                                </div>

                                                            </fieldset>
                                                        </div>
                                                    </div> 

                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Tipos de Intervalos</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Limitar em apenas uma reserva por dia:') ?></label>
                                                                            <fieldset>
                                                                                <?php echo JHTML::_('select.booleanlist', 'limit_day_event', '', $this->item->limit_day_event); ?>
                                                                            </fieldset>
                                                                            <p class="form-text text-muted"><em>Será aplicado apenas em casos que o evento tenha duração superior à um dia e que seja permitido multiplas reservas em algum método.</em></p>
                                                                        </div>  
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Permitir que os intervalos avancem de nível:') ?></label>
                                                                            <fieldset>
                                                                                <?php echo JHTML::_('select.booleanlist', 'limit_break_event', '', $this->item->limit_break_event); ?>
                                                                            </fieldset>
                                                                            <p class="form-text text-muted"><em>Ative caso deseje que o intervalo cumpra-se no total, mesmo iniciando um novo nível (Dia/Bateria/Squad).</em></p>
                                                                        </div>  
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>       
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Baterias por Dia</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Será exibido para escolha apenas em casos que o evento tenha mais de uma bateria.</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-xl-3" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Quantidade:*') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="drums_event" data-min="1" data-max="10" data-step="1" value="<?php echo $this->item->drums_event + 0; ?>" placeholder="<?php echo JText::_('Quantidade de Baterias') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Intervalo Mínimo:') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="break_drums_event" data-min="0" data-max="10" data-step="1" value="<?php echo $this->item->break_drums_event + 0; ?>" placeholder="<?php echo JText::_('Intervalo para 2ª Inscrição') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Horário de Inicio:'); ?></label>
                                                                            <div class="input-group time">
                                                                                <input type="text" autocomplete="off" class="form-control" name="time_drums_event" value="<?php if ($this->item->time_drums_event) echo JHtml::date(JFactory::getDate($this->item->time_drums_event, $this->util->_siteOffset)->toISO8601(true), 'DATE_FORMAT_TIME'); ?>" placeholder="<?php echo JText::_('Horário de Inicio') ?>" />
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-light" type="button"><i class="fa fa-clock"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Duração (min):') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control value-touchspin" name="duration_drums_event" data-min="" data-max="1000" data-step="1" value="<?php echo $this->item->duration_drums_event; ?>" placeholder="<?php echo JText::_('Duração (min)') ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>        
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Turmas/Squads por Bateria</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Será exibido para escolha apenas em casos que o evento tenha mais de uma Turma/Squad.</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Quantidade:*') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="squad_event" data-min="1" data-max="100" data-step="1" value="<?php echo $this->item->squad_event + 0; ?>" placeholder="<?php echo JText::_('Quantidade de Turmas/Squad') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Intervalo Mínimo:') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="break_squad_event" data-min="0" data-max="100" data-step="1" value="<?php echo $this->item->break_squad_event + 0; ?>" placeholder="<?php echo JText::_('Intervalo para 2ª Inscrição') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Horário de Inicio:'); ?></label>
                                                                            <div class="input-group time">
                                                                                <input type="text" autocomplete="off" class="form-control" name="time_squad_event" value="<?php if ($this->item->time_squad_event) echo JHtml::date(JFactory::getDate($this->item->time_squad_event, $this->util->_siteOffset)->toISO8601(true), 'DATE_FORMAT_TIME'); ?>" placeholder="<?php echo JText::_('Horário de Inicio') ?>" />
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-light" type="button"><i class="fa fa-clock"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Duração (min):') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control value-touchspin" name="duration_squad_event" data-min="" data-max="1000" data-step="1" value="<?php echo $this->item->duration_squad_event; ?>" placeholder="<?php echo JText::_('Duração (min)') ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>        
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Posto/Posição por Turma/Squad</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Será exibido para escolha apenas em casos que o evento tenha mais de um Posto/Posição.</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Quantidade:*') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="position_event" data-min="1" data-max="50" data-step="1" value="<?php echo $this->item->position_event + 0; ?>" placeholder="<?php echo JText::_('Quantidade de Postos/Posições') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Intervalo Mínimo:') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="break_position_event" data-min="0" data-max="50" data-step="1" value="<?php echo $this->item->break_position_event + 0; ?>" placeholder="<?php echo JText::_('Intervalo para 2ª Inscrição') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Horário de Inicio:'); ?></label>
                                                                            <div class="input-group time">
                                                                                <input type="text" autocomplete="off" class="form-control" name="time_position_event" value="<?php if ($this->item->time_position_event) echo JHtml::date(JFactory::getDate($this->item->time_position_event, $this->util->_siteOffset)->toISO8601(true), 'DATE_FORMAT_TIME'); ?>" placeholder="<?php echo JText::_('Horário de Inicio') ?>" />
                                                                                <div class="input-group-append">
                                                                                    <button class="btn btn-light" type="button"><i class="fa fa-clock"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>  
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-xl-3" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Duração (min):') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control value-touchspin" name="duration_position_event" data-min="" data-max="1000" data-step="1" value="<?php echo $this->item->duration_position_event; ?>" placeholder="<?php echo JText::_('Duração (min)') ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>        
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="results-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>
                                            <div class="form-group">
                                                <fieldset class="group-border">
                                                    <legend class="group-border">Configurar Lançamentos</legend>
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Informações de Lançamento</legend>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Resultados no Evento:') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="nr_event" data-min="1" data-max="10" data-step="1" value="<?php echo $this->item->nr_event + 0; ?>" placeholder="<?php echo JText::_('Resultados no Evento') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Séries no Resultado:') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="ns_event" data-min="1" data-max="10" data-step="1" value="<?php echo $this->item->ns_event + 0; ?>" placeholder="<?php echo JText::_('Séries no Resultado') ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-6 col-lg-4">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Casas Decimais na Pontuação:') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="decimal_event" data-min="0" data-max="5" data-step="1" value="<?php echo $this->item->decimal_event + 0; ?>" placeholder="<?php echo JText::_('Casas Decimais') ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Disciplinas</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Configure a formulação do resultado da disciplina deste evento. Se necessário, adicione novas disciplinas.</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Nome da Disciplina:*') ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required" name="name_result[]" value="<?php echo $this->item->results_event[0]['name_result']; ?>" placeholder="<?php echo JText::_('Informe o nome do Disciplina') ?>"  >
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                                <div class="form-group">
                                                                                    <label><?php echo JText::_('Cálculo do Resultado:'); ?></label>
                                                                                    <select  name="rf_result[]" class="form-control required" aria-required="true" required="required" >
                                                                                        <option disabled selected class="default" value=""><?php echo JText::_('- Cálculo do Resultado -'); ?></option>
                                                                                        <?php 
                                                                                            $calculate[] = JHTML::_('select.option', 2, JText::_('Calcular Média'), 'value', 'text' );
                                                                                            $calculate[] = JHTML::_('select.option', 4, JText::_('Considerar o Primeiro'), 'value', 'text' );
                                                                                            $calculate[] = JHTML::_('select.option', 1, JText::_('Considerar o Maior'), 'value', 'text' );
                                                                                            $calculate[] = JHTML::_('select.option', 5, JText::_('Considerar o Último'), 'value', 'text' );
                                                                                            $calculate[] = JHTML::_('select.option', 3, JText::_('Somar Resultados'), 'value', 'text' );
                                                                                            echo JHTML::_('select.options',  $calculate, 'value', 'text', $this->item->results_event[0]['rf_result'] );
                                                                                        ?>
                                                                                    </select>  
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                                <div class="form-group">
                                                                                    <label><?php echo JText::_('Cálculo da Série:'); ?></label>
                                                                                    <select name="rs_result[]" class="form-control required">
                                                                                        <option disabled selected class="default" value=""><?php echo JText::_('- Cálculo da Série -'); ?></option>
                                                                                        <?php echo JHTML::_('select.options',  $calculate, 'value', 'text', $this->item->results_event[0]['rs_result'] );?>
                                                                                    </select>  
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Métodos Participantes:') ?></label>
                                                                            <?php if(count($this->item->methods_event)>0):?>
                                                                                <?php foreach($this->item->methods_event as $i => $method_event):?>
                                                                                <div class="custom-control custom-switch">
                                                                                    <input type="checkbox" class="custom-control-input" <?php echo (in_array($i, $this->item->results_event[0]['method_result'] ) ? 'checked' : '') ?> id="method_result<?php echo $i;?>" name="method_result[0][]" value="<?php echo $i;?>">
                                                                                    <label class="custom-control-label" style="font-weight:normal;" for="method_result<?php echo $i;?>"><?php echo $method_event['name_method'];?></label>
                                                                                </div>
                                                                                <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="form-group">
                                                                            <button class="btn btn-primary add_result" type="button">Incluir nova Disciplina</button>
                                                                        </div>
                                                                    </div>
                                                                </div>        

                                                                <div id="aditional_result">
                                                                <?php if(count($this->item->results_event)>1):?>
                                                                    <?php foreach($this->item->results_event as $x => $result_event):?>
                                                                            <?php if($x==0) continue;?>


                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <hr>
                                                                            <div class="form-group">
                                                                                <label><?php echo JText::_('Nome da Disciplina:*') ?></label>
                                                                                <input type="text" autocomplete="off" class="form-control required" name="name_result[]" value="<?php echo $this->item->results_event[$x]['name_result']; ?>" placeholder="<?php echo JText::_('Informe o nome do Disciplina') ?>"  >
                                                                            </div> 
                                                                            <div class="row">
                                                                                <div class="col-12 col-md-6 col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label><?php echo JText::_('Cálculo do Resultado:'); ?></label>
                                                                                        <select  name="rf_result[]" class="form-control required" aria-required="true" required="required" >
                                                                                            <option disabled selected class="default" value=""><?php echo JText::_('- Cálculo do Resultado -'); ?></option>
                                                                                            <?php 
                                                                                                $calculate[] = JHTML::_('select.option', 1, JText::_('Considerar o Maior'), 'value', 'text' );
                                                                                                $calculate[] = JHTML::_('select.option', 2, JText::_('Calcular Média'), 'value', 'text' );
                                                                                                $calculate[] = JHTML::_('select.option', 3, JText::_('Somar Resultados'), 'value', 'text' );
                                                                                                echo JHTML::_('select.options',  $calculate, 'value', 'text', $this->item->results_event[$x]['rf_result'] );
                                                                                            ?>
                                                                                        </select>  
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-md-6 col-lg-4">
                                                                                    <div class="form-group">
                                                                                        <label><?php echo JText::_('Cálculo da Série:'); ?></label>
                                                                                        <select name="rs_result[]" class="form-control required">
                                                                                            <option disabled selected class="default" value=""><?php echo JText::_('- Cálculo da Série -'); ?></option>
                                                                                            <?php echo JHTML::_('select.options',  $calculate, 'value', 'text', $this->item->results_event[$x]['rs_result'] );?>
                                                                                        </select>  
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label><?php echo JText::_('Métodos Participantes:') ?></label>
                                                                                <?php if(count($this->item->methods_event)>0):?>
                                                                                    <?php foreach($this->item->methods_event as $i => $method_event):?>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input" <?php echo (in_array($i, $this->item->results_event[$x]['method_result'] ) ? 'checked' : '') ?> id="method_result_<?php echo $x.'_'.$i; ?>" name="method_result[<?php echo $x ?>][]" value="<?php echo $i;?>">
                                                                                        <label class="custom-control-label" style="font-weight:normal;" for="method_result_<?php echo $x.'_'.$i;?>"><?php echo $method_event['name_method'];?></label>
                                                                                    </div>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <button class="btn btn-danger remove_result" type="button">Remover esta Disciplina</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>  
                                                                    <?php endforeach; ?>
                                                                <?php endif;?>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Equipes</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Adicione Classificação por equipes em seu evento. Caso não deseje ativar as equipes no evento, escolha 0 na quantidade de participantes</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Participantes por Equipe:*'); ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required value-touchspin" name="team_event" data-min="0" data-max="10" data-step="1" value="<?php echo $this->item->team_event + 0; ?>" placeholder="<?php echo JText::_('Participantes por Equipe:') ?>" />
                                                                        </div>   
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>  

                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Gêneros</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Cadastre Gêneros para segmentar os resultados do Evento (Masculino, Feminino, Misto, etc).</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Nome do Gênero:*'); ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required" name="name_genre[]" value="<?php echo $this->item->genres_event[0]['name_genre']; ?>" placeholder="<?php echo JText::_('Nome do Gênero') ?>" />
                                                                        </div>   
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="w-md-100">&nbsp;</label>
                                                                        <button class="btn btn-primary add_genre" type="button">Incluir novo Gênero</button>
                                                                    </div>
                                                                </div>
                                                                <div id="aditional_genre">
                                                                <?php if(count($this->item->genres_event)>1):?>
                                                                    <?php foreach($this->item->genres_event as $i => $genre_event):?>
                                                                            <?php if($i==0) continue;?>
                                                                    <div class="row">
                                                                        <div class="col-12 col-md-6 col-lg-4" >
                                                                            <div class="form-group">
                                                                                <label><?php echo JText::_('Nome do Gênero:*'); ?></label>
                                                                                <input type="text" autocomplete="off" class="form-control required" name="name_genre[]"  value="<?php echo $genre_event['name_genre']; ?>" placeholder="<?php echo JText::_('Nome do Gênero') ?>" />
                                                                            </div>   
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="w-md-100">&nbsp;</label>
                                                                            <button class="btn btn-danger remove_genre" type="button">Remover</button>
                                                                        </div>
                                                                    </div>
                                                                    <?php endforeach; ?>
                                                                <?php endif;?>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>  
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Categorias</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Cadastre Categorias para segmentar os resultados do Evento (Master, Senior, Junior, Damas, etc).</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Nome da Categoria:*'); ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required" name="name_category[]" value="<?php echo $this->item->categorys_event[0]['name_category']; ?>" placeholder="<?php echo JText::_('Nome da Categoria') ?>" />
                                                                        </div>   
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="w-md-100">&nbsp;</label>
                                                                        <button class="btn btn-primary add_category" type="button">Incluir nova Categoria</button>
                                                                    </div>
                                                                </div>
                                                                <div id="aditional_category">
                                                                <?php if(count($this->item->categorys_event)>1):?>
                                                                    <?php foreach($this->item->categorys_event as $i => $category_event):?>
                                                                            <?php if($i==0) continue;?>
                                                                    <div class="row">
                                                                        <div class="col-12 col-md-6 col-lg-4" >
                                                                            <div class="form-group">
                                                                                <label><?php echo JText::_('Nome da Categoria:*'); ?></label>
                                                                                <input type="text" autocomplete="off" class="form-control required" name="name_category[]"  value="<?php echo $category_event['name_category']; ?>" placeholder="<?php echo JText::_('Nome da Categoria') ?>" />
                                                                            </div>   
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="w-md-100">&nbsp;</label>
                                                                            <button class="btn btn-danger remove_category" type="button">Remover</button>
                                                                        </div>
                                                                    </div>
                                                                    <?php endforeach; ?>
                                                                <?php endif;?>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>  
                                                    <div class="row">
                                                        <div class="col-12" >
                                                            <fieldset class="group-border">
                                                                <legend class="group-border">Classes</legend>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <p class="form-text text-muted"><em>Cadastre Classes para segmentar os resultados do Evento (AA, A, B, C, Inicianes, Premium, etc).</em></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6 col-lg-4" >
                                                                        <div class="form-group">
                                                                            <label><?php echo JText::_('Nome da Classe:*'); ?></label>
                                                                            <input type="text" autocomplete="off" class="form-control required" name="name_class[]" value="<?php echo $this->item->classs_event[0]['name_class']; ?>" placeholder="<?php echo JText::_('Nome da Classe') ?>" />
                                                                        </div>   
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="w-md-100">&nbsp;</label>
                                                                        <button class="btn btn-primary add_class" type="button">Incluir nova Classe</button>
                                                                    </div>
                                                                </div>
                                                                <div id="aditional_class">
                                                                <?php if(count($this->item->classs_event)>1):?>
                                                                    <?php foreach($this->item->classs_event as $i => $class_event):?>
                                                                            <?php if($i==0) continue;?>
                                                                    <div class="row">
                                                                        <div class="col-12 col-md-6 col-lg-4" >
                                                                            <div class="form-group">
                                                                                <label><?php echo JText::_('Nome da Classe:*'); ?></label>
                                                                                <input type="text" autocomplete="off" class="form-control required" name="name_class[]"  value="<?php echo $class_event['name_class']; ?>" placeholder="<?php echo JText::_('Nome da Classe') ?>" />
                                                                            </div>   
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="w-md-100">&nbsp;</label>
                                                                            <button class="btn btn-danger remove_class" type="button">Remover</button>
                                                                        </div>
                                                                    </div>
                                                                    <?php endforeach; ?>
                                                                <?php endif;?>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div> 

                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid" id="cid" value="<?php echo $this->item->id_event; ?>" />
    <input type="hidden" name="id_event" id="id_event" value="<?php echo $this->item->id_event; ?>" />
    <input type="hidden" name="ordering" value="<?php echo $this->item->ordering; ?>" />
    <input type="hidden" name="controller" value="event" />			
    <input type="hidden" name="view"  value="event" />
    <?php echo JHTML::_('form.token'); ?>	
</form>
  
