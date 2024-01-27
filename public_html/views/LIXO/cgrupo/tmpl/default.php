<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');
?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="card-title mb-sm-0 me-2">Cadastro do Grupo de Corrida</h5>
                            <?php if($this->item->status_corredor_grupo < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_corredor_grupo" value="<?php echo $this->item->status_corredor_grupo;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_corredor_grupo" <?php echo $this->item->status_corredor_grupo == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_corredor_grupo == 1 ? 'Ativo' : 'Inativo';?></label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="action-btns">
                        <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary">Salvar</button>
                        <button type="button" onclick="Joomla.submitbutton('cancel')" class="btn btn-outline-secondary me-1">Sair</button>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="name_corredor" class="form-label">Nome do Grupo</label>
                            <input
                                class="form-control required"
                                type="text"
                                id="name_corredor_grupo"
                                name="name_corredor_grupo"
                                value="<?php echo $this->item->name_corredor_grupo; ?>"
                                autofocus
                            />
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="border rounded p-3 mb-3">
                                <h6>Cronograma de Atividades</h6>
                                <div class="bg-lighter rounded p-3 mb-4">
                                    <p>Defina a rotina semanal para este grupo de corrida.</p>
                                    <p>Para dias de semana em que não há ativiade, selecione a opção <strong>Nenhuma Atividade</sstrong></p>
                                </div>
                                
                                <div class="row g-3 mb-3">
                                    <h6 class="mb-3 fw-semibold">Dias da Semana</h6>

                                    <label class="col-sm-3 col-lg-2 col-form-label">Segunda-feira</label>
                                    <div class="col-sm-4">
                                        <select name="id_atividade_segunda" class="form-select">
                                            <option value="">- Nenhuma Atividade -</option>
                                            <?php echo JHtml::_('select.options',  $this->atividades, 'value', 'text', $this->item->id_atividade_segunda);?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="local_atividade_segunda" class="form-control" placeholder="Local" value="<?php echo $this->item->local_atividade_segunda; ?>">
                                    </div>
                                    <hr class="mt-3 mb-0" />
                                    <label class="col-sm-3 col-lg-2 col-form-label">Terça-feira</label>
                                    <div class="col-sm-4">
                                        <select name="id_atividade_terca" class="form-select">
                                            <option value="">- Nenhuma Atividade -</option>
                                            <?php echo JHtml::_('select.options',  $this->atividades, 'value', 'text', $this->item->id_atividade_terca);?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="local_atividade_terca" class="form-control" placeholder="Local" value="<?php echo $this->item->local_atividade_terca; ?>">
                                    </div>
                                    <hr class="mt-3 mb-0" />
                                    <label class="col-sm-3 col-lg-2 col-form-label">Quarta-feira</label>
                                    <div class="col-sm-4">
                                        <select name="id_atividade_quarta" class="form-select">
                                            <option value="">- Nenhuma Atividade -</option>
                                            <?php echo JHtml::_('select.options',  $this->atividades, 'value', 'text', $this->item->id_atividade_quarta);?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="local_atividade_quarta" class="form-control" placeholder="Local" value="<?php echo $this->item->local_atividade_quarta; ?>">
                                    </div>
                                    <hr class="mt-3 mb-0" />
                                    <label class="col-sm-3 col-lg-2 col-form-label">Quinta-feira</label>
                                    <div class="col-sm-4">
                                        <select name="id_atividade_quinta" class="form-select">
                                            <option value="">- Nenhuma Atividade -</option>
                                            <?php echo JHtml::_('select.options',  $this->atividades, 'value', 'text', $this->item->id_atividade_quinta);?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="local_atividade_quinta" class="form-control" placeholder="Local" value="<?php echo $this->item->local_atividade_quinta; ?>">
                                    </div>
                                    <hr class="mt-3 mb-0" />
                                    <label class="col-sm-3 col-lg-2 col-form-label">Sexta-feira</label>
                                    <div class="col-sm-4">
                                        <select name="id_atividade_sexta" class="form-select">
                                            <option value="">- Nenhuma Atividade -</option>
                                            <?php echo JHtml::_('select.options',  $this->atividades, 'value', 'text', $this->item->id_atividade_sexta);?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="local_atividade_sexta" class="form-control" placeholder="Local" value="<?php echo $this->item->local_atividade_sexta; ?>">
                                    </div>
                                    <hr class="mt-3 mb-0" />
                                    <label class="col-sm-3 col-lg-2 col-form-label">Sábado</label>
                                    <div class="col-sm-4">
                                        <select name="id_atividade_sabado" class="form-select">
                                            <option value="">- Nenhuma Atividade -</option>
                                            <?php echo JHtml::_('select.options',  $this->atividades, 'value', 'text', $this->item->id_atividade_sabado);?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="local_atividade_sabado" class="form-control" placeholder="Local" value="<?php echo $this->item->local_atividade_sabado; ?>">
                                    </div>
                                    <hr class="mt-3 mb-0" />
                                    <label class="col-sm-3 col-lg-2 col-form-label">Domingo</label>
                                    <div class="col-sm-4">
                                        <select name="id_atividade_domingo" class="form-select">
                                            <option value="">- Nenhuma Atividade -</option>
                                            <?php echo JHtml::_('select.options',  $this->atividades, 'value', 'text', $this->item->id_atividade_domingo);?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="local_atividade_domingo" class="form-control" placeholder="Local" value="<?php echo $this->item->local_atividade_domingo; ?>">
                                    </div>




                                </div>
                            </div>
                        </div>



                        <div class="mb-3 col-md-12">
                            <label for="cpf_corredor" class="form-label">Observações</label>
                            <?php
                            $editor = JFactory::getEditor('tinymce');                            echo $editor->display('observacao_corredor_grupo', $this->item->observacao_corredor_grupo, '100%', '400', '60', '20', array('pagebreak', 'article', 'image', 'readmore'));
                            ?>
                        </div>
                        <div class="mt-2">
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_corredor_grupo; ?>" />
    <input type="hidden" name="id_corredor_grupo" value="<?php echo $this->item->id_corredor_grupo; ?>" />
    <input type="hidden" name="controller" value="cgrupo" />
    <input type="hidden" name="view" value="cgrupo" />
    <?php echo JHTML::_('form.token'); ?>
</form>