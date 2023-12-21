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
                            <h5 class="card-title mb-sm-0 me-2">Cadastro de Categoria de Corredores</h5>
                            <?php if($this->item->status_corredor_categoria < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_corredor_categoria" value="<?php echo $this->item->status_corredor_categoria;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_corredor_categoria" <?php echo $this->item->status_corredor_categoria == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_corredor_categoria == 1 ? 'Ativo' : 'Inativo';?></label>
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
                            <label for="name_corredor" class="form-label">Nome da Categoria</label>
                            <input
                                class="form-control required"
                                type="text"
                                id="name_corredor_categoria"
                                name="name_corredor_categoria"
                                value="<?php echo $this->item->name_corredor_categoria; ?>"
                                autofocus
                            />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="cpf_corredor" class="form-label">Observações</label>
                            <?php
                            $editor = JFactory::getEditor('tinymce');                            echo $editor->display('observacao_corredor_categoria', $this->item->observacao_corredor_categoria, '100%', '400', '60', '20', array('pagebreak', 'article', 'image', 'readmore'));
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
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_corredor_categoria; ?>" />
    <input type="hidden" name="id_corredor_categoria" value="<?php echo $this->item->id_corredor_categoria; ?>" />
    <input type="hidden" name="controller" value="ccategoria" />
    <input type="hidden" name="view" value="ccategoria" />
    <?php echo JHTML::_('form.token'); ?>
</form>