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
                            <h5 class="card-title mb-sm-0 me-2">Cadastro de Cronograma de Treino</h5>
                            <?php if($this->item->status_treino < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_treino" value="<?php echo $this->item->status_treino;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_treino" <?php echo $this->item->status_treino == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_treino == 1 ? 'Ativo' : 'Inativo';?></label>
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

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="id_corredor_categoria">Categoria dos Corredores</label>
                            <select id="id_corredor_categoria" name="id_corredor_categoria" class="select2 form-select required">
                                <option disabled selected class="default" value=""><?php echo JText::_('- Categoria dos Corredores -'); ?></option>
                                <?php echo JHTML::_('select.options',  $this->categorias, 'value', 'text',  $this->item->id_corredor_categoria); ?>    
                            </select>    
                        </div> 
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="mes_treino">Mês do Treino</label>
                            <select id="mes_treino" name="mes_treino" class="select2 form-select required">
                                <option disabled selected class="default" value=""><?php echo JText::_('- Mês do Treino -'); ?></option>
                                <?php echo JHTML::_('select.options',  $this->meses, 'value', 'text',  $this->item->mes_treino); ?>    
                            </select>    
                        </div> 
                        <?php if(isset($this->grupos) && count($this->grupos)>0): ?>
                        
                        <div class="my-3 row">
                            <h5 class="text-center"> Pré-visualização dos Cronogramas</h5>
                            <?php foreach($this->grupos as $grupo):?>
                            <div class="my-3 col-md-6">
                                
                                <h6 class="text-center">Cronogramas <?php echo $grupo->text; ?></h6>
                                <div id="treino_grupo_<?php echo $grupo->value; ?>"></div>
                            </div>
                            <?php endforeach;?>
                        </div>
                        <?php else: ?>
                        <hr class="my-2" />
                        <div class="my-3 col-md-12">
                            <h5 class="text-center">Pré-visualização dos Cronogramas</h5>
                            <h6 class="text-center">Não há grupos de corrida cadastrados</h6>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_treino; ?>" />
    <input type="hidden" name="id_treino" value="<?php echo $this->item->id_treino; ?>" />
    <input type="hidden" name="controller" value="treino" />
    <input type="hidden" name="view" value="treino" />
    <?php echo JHTML::_('form.token'); ?>
</form>