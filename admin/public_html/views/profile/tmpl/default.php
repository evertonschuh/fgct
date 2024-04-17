<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation'); 
JHtml::_('behavior.keepalive');
$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

?>
<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="card shadow mb-4">
		<div class="card-header py-3">
        	<h6 class="m-0 font-weight-bold text-primary"><?php echo $this->item->name; ?></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-12">
            		<div class="row">
                        <div class="col-lg-3">
                            <div class="form-group" style="text-align:center">
                                <label>Foto do Perfil:</label>
                                <input type="hidden" name="image_pf" value="<?php echo $this->item->image_pf; ?>" />
                                <div class="row">
                                    <div class="col-xl-12 text-center">
                                    <?php if ( !empty( $this->item->image_pf )): ?>
                                        <img class="img-fluid img-thumbnail" style="width:100%; max-width:280px;" src="<?php echo $this->trataimagem->resize(JPATH_MEDIA .DS. 'images' .DS. 'avatar'  .DS. $this->item->image_pf, 250, 250, 'cache/' . $this->item->image_pf, 'manterProporcao'); ?>" />
                                    <?php else: ?>
                                        <img class="img-fluid img-thumbnail" style="width:100%; max-width:280px;" src="<?php echo $this->trataimagem->resize(JPATH_THEMES_NATIVE .DS. 'img' .DS. 'noimageuser.png' , 250, 250, 'cache/noimageuser.png', 'manterProporcao') ;  ?>" />
                                    <?php endif; ?>  
                                    </div>
                                </div>
                                <div class="clearfix"></div>
								<?php if ( !empty( $this->item->image_pf )): ?>
                                <fieldset class="checkbox-img-remove">
                                    <label>
                                        <input type="checkbox" name="remove_image_pf" value="1" />
                                        <?php echo JText::_('Marque para apenas remover a imagem!'); ?>
                                    </label>  
                                </fieldset>
                                <div class="clearfix"></div>
                                <?php endif; ?>    
								<?php if($this->item->id_pf):?>
                             	<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#avatar-modal" ><b>Atualizar Foto</b></button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group"  >
                                                <label><?php echo JText::_('Nome:*'); ?></label>
                                                <input type="text"  class="form-control required" name="name" id="name" value="<?php echo $this->item->name; ?>" placeholder="<?php echo JText::_('Nome') ?>">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6 col-xl-3">
                                            <div class="form-group">
                                                <label id="label_cpf"><?php echo JText::_('CPF:'); ?></label>
                                                <input type="text"  class="form-control required validate-cpf" name="cpf_pf" id="cpf_pf" value="<?php echo $this->item->cpf_pf; ?>" <?php echo $this->item->brasileiro_pf ?: 'pattern="\d{3}.\d{3}.\d{3}-\d{2}"'; ?> placeholder="<?php echo $this->item->brasileiro_pf ? JText::_('Identificação') : JText::_('CPF'); ?>">
                                            </div>
                                        </div> 
                                        <div class="col-md-12 col-lg-6 col-xl-3">
                                            <div class="form-group" >
                                                <label><?php echo JText::_('PIS/PASEP:'); ?></label>
                                                <input type="text" class="form-control validate-pis" name="pis_pf" id="pis_pf" value="<?php echo $this->item->pis_pf; ?>" placeholder="<?php echo JText::_('PIS/PASEP') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-6 col-xl-3">
                                            <div class="form-group">
                                                <label><?php echo JText::_('Data de Nacimento:*') ?></label>
                                                <div class="input-group date">
                                                    <input type="text"  class="form-control required validate-date" name="data_nascimento_pf" value="<?php if( $this->item->data_nascimento_pf ) echo JHtml::date(JFactory::getDate($this->item->data_nascimento_pf, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="<?php echo JText::_('Data de Nacimento') ?>" />
                                                    <span class="input-group-append input-group-addon">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-12 col-lg-6 col-xl-3">
                                            <div class="form-group">
                                                <label><?php echo JText::_('Sexo:') ?></label>
                                                <fieldset>
                                                    <?php echo JHTML::_('select.booleanlist', 'sexo_pf', '', $this->item->sexo_pf, 'Feminino', 'Masculino'); ?>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>        
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <fieldset class="group-border">
                                                <legend class="group-border">RG</legend>
                                                <div class="row">
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Número:'); ?></label>
                                                            <input type="text" class="form-control" name="rg_pf" id="rg_pf" value="<?php echo $this->item->rg_pf; ?>" placeholder="<?php echo JText::_('RG') ?>">
                                                        </div>
                                                    </div>
                                                    <div class=" col-md-6 col-lg-3">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Expedidor:'); ?></label>
                                                            <input type="text" class="form-control" name="orgao_expeditor_pf" id="orgao_expeditor_pf" value="<?php echo $this->item->orgao_expeditor_pf; ?>" placeholder="<?php echo JText::_('Orgão Expeditor RG') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('UF:'); ?></label>
                                                            <select name="uf_orga_expeditor_pf"  id="uf_orga_expeditor_pf"class="form-control select2">
                                                                <option selected class="default" value=""><?php echo JText::_('- UF -'); ?></option>
                                                                <?php echo JHTML::_('select.options',  $this->ufs, 'value', 'text', $this->item->uf_orga_expeditor_pf ); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Data da Expedição:'); ?></label>
                                                            <div class="input-group date">
                                                            <input type="text" class="form-control" name="data_expedicao_pf" value="<?php if( $this->item->data_expedicao_pf ) echo JHtml::date(JFactory::getDate($this->item->data_expedicao_pf, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="<?php echo JText::_('Data Expedição RG') ?>" />
                                                                <span class="input-group-append input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>                             
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>    
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <fieldset class="group-border">
                                                <legend class="group-border">Contato</legend>
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-6">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Email:'); ?></label>
                                                            <p class="form-text text-muted" ><?php echo $this->item->email; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Telefone Móvel:'); ?></label>
                                                            <input type="tel" autocomplete="off" class="form-control required" name="tel_celular_pf" id="tel_celular_pf" value="<?php echo $this->item->tel_celular_pf; ?>" placeholder="<?php echo JText::_('Telefone Móvel') ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="form-group">
                                                        	<label><?php echo JText::_('Telefone Fixo:'); ?></label>
                                                        	<input type="tel" autocomplete="off" class="form-control" name="tel_residencial_pf" id="tel_residencial_pf" value="<?php echo $this->item->tel_residencial_pf; ?>" placeholder="<?php echo JText::_('Telefone Fixo') ?>" />
                                               			</div>                             
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>    
                      				<div id="loading"></div>                           
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <fieldset class="group-border">
                                                <legend class="group-border">Endereço</legend>
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-4">
                                                        <div class="form-group">
                                                            <label id="label_cep"><?php echo JText::_('CEP:'); ?></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="cep_pf" name="cep_pf" autocomplete="off" value="<?php echo $this->item->cep_pf; ?>"  placeholder="<?php echo JText::_('CEP') ?>" />
                                                                <div class="input-group-append ">
                                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buscacep"  title="Pesquisar CEP"><i class="fa fa-search fa-fw"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>   
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label><?php echo JText::_('Logradouro:'); ?></label>
                                                            <input type="text" class="form-control" id="logradouro_pf" name="logradouro_pf" value="<?php echo $this->item->logradouro_pf; ?>"  placeholder="<?php echo JText::_('Logradouro') ?>" />
                                            			</div>
													</div>
                                                </div>   
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                        	<label><?php echo JText::_('Número:'); ?></label>
                                                        	<input type="text" class="form-control" id="numero_pf" name="numero_pf" value="<?php echo $this->item->numero_pf; ?>" placeholder="<?php echo JText::_('Número') ?>" />
                                            			</div>
													</div>
                                                    <div class="col-lg-3">
                                                    	<div class="form-group">
                                                        	<label><?php echo JText::_('Complemento:'); ?></label>
                                                        	<input type="text" class="form-control" id="complemento_pf" name="complemento_pf" value="<?php echo $this->item->complemento_pf; ?>" placeholder="<?php echo JText::_('Complemento') ?>" />
                                                    	</div>
                                           			</div>   
                                                    <div class="col-lg-6">
                                                    	<div class="form-group">
                                                        	<label id="label_bairro"><?php echo JText::_('Bairro:'); ?></label>
                                                        	<input type="text" class="form-control" id="bairro_pf" name="bairro_pf" value="<?php echo $this->item->bairro_pf; ?>" placeholder="<?php echo JText::_('Bairro:'); ?>" />
                                                    	</div>
                                                	</div>
                                            	</div>   
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                    	<div class="form-group">
                                                            <label><?php echo JText::_('Estado:'); ?></label>
                                                            <select id="id_estado" name="id_estado" class="form-control estado select2">
                                                                <?php if ( empty($this->item->id_estado) ) { ?>
                                                                <option disabled selected class="default" value=""><?php echo JText::_('EASISTEMAS_GLOBAL_ESTADO'); ?></option>
                                                                <?php } ?>
                                                                <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text', $this->item->id_estado ); ?>
                                                            </select>
                                                    	</div>
                                           			</div>   
                                                    <div class="col-lg-6">
                                                    	<div class="form-group">
                                                            <label><?php echo JText::_('Cidade:'); ?></label>
                                                            <select id="id_cidade" name="id_cidade" class="form-control select2">
                                                                <?php if ( empty($this->item->id_cidade) ) { ?>
                                                               <option disabled selected class="default" value=""><?php echo JText::_('EASISTEMAS_GLOBAL_CIDADE'); ?></option>
                                                                <?php }                                                    
                                                                if ($this->item->id_estado > 0)
                                                                echo JHTML::_('select.options',  $this->cidades, 'value', 'text', $this->item->id_cidade );
                                                                ?>
                                                            </select> 
                                                  		</div>        
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
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_pf; ?>" />
    <input type="hidden" name="id_pf" value="<?php echo $this->item->id_pf; ?>" /> 
    <input type="hidden" name="controller" id="controller" value="perfil" />			
    <input type="hidden" name="view" id="view" value="perfil" />
    <?php echo JHTML::_('form.token'); ?>	
</form>

<div class="modal fade" id="buscacep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
        	<form role="form" method="post" name="buscarCep" id="buscarCep" class="form-validate">
                <div class="modal-header modal-header-primary  modal-color">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo JText::_('JGLOBAL_BUTTON_CLOSE'); ?></span></button>
                    <h3 class="modal-title text-center"><?php echo JText::_('Pesquisar CEP'); ?></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 modal-correios">
                            <div class="row" id="cepbuscar">
                                <div class="col-sm-12">
                                	<div class="form-group">
                                    	<label><?php echo JText::_('Estado'); ?></label>
                                        <select id="estado_buscacep" name="estado_buscacep" class="form-control" required >
                                           <option disabled selected class="default" value=""><?php echo JText::_('EASISTEMAS_GLOBAL_ESTADO'); ?></option>
                                            <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text', '' ); ?>
                                        </select>
                                    </div>                 
                                </div>  
                                <div class="col-sm-12">
                                	<div class="form-group">
                                    	<label><?php echo JText::_('Cidade:*'); ?></label>
                                        <select id="cidade_buscacep" name="cidade_buscacep" class="form-control" required >
                                            <option disabled selected class="default" value=""><?php echo JText::_('EASISTEMAS_GLOBAL_CIDADE'); ?></option>
                                        </select> 
                                	</div>                   
                                </div>
                                <div class="col-sm-12">
                                	<div class="form-group">
                                    	<label><?php echo JText::_('Logradouro:'); ?></label>
                                    	<input type="text" class="form-control" required name="logradouro_buscacep" id="logradouro_buscacep"   autocomplete="off"  placeholder="<?php echo JText::_('Logradouro:'); ?>" />
                                	</div>
                                </div>  
                            </div>  
                            <div class="row" id="cepresult" style="display:none;"> 
                                <div class="form-group col-sm-12 col-sm-12 col-md-12">
                                	<label class="col-lg-12"><?php echo JText::_('Resltados da Pesquisa'); ?></label>
                                </div> 
                                <div id="cepresultmain" class="col-sm-12" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="loading-buscacep"></div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo JText::_('Fechar'); ?></button>
                    <button type="button" id="submithsearch" class="btn btn-primary validate" ><span class="glyphicon glyphicon-search"></span>&nbsp;<?php echo JText::_('Pesquisar'); ?></button>
                </div>
            </form>
		</div>
	</div>
</div>




<style>


.preview {
	margin:auto;
    overflow: hidden;

    background-color: #f7f7f7;
    text-align: center;
    width: 100%;
}


.preview-lg {
    height: 171px;
    width: 171px;
    margin-top: 15px;
}

.preview-circle {
    border-radius: 50%;
}

@media (max-width: 768px) {
  .preview-lg  {
    height: 120px;
    width: 120px;
  }
}


</style>

<!-- Cropping modal -->
<div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" style="padding-right:0 !important">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo JText::_('Atualizar Foto'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="avatar-body">
                    <div class="row">    
                        <div class="col-sm-12">  
                            <!-- image-preview-filename input [CUT FROM HERE]-->
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
                                <span class="input-group-btn input-group-append ">
                                    <!-- image-preview-clear button -->
                                    <button type="button" class="btn btn-danger image-preview-clear" style="display:none;">
                                        <span class="glyphicon glyphicon-remove"></span> Cancelar
                                    </button>
                                    <!-- image-preview-clear button -->
                                    <button type="button" class="btn btn-primary image-upload-file" onclick="Joomla.submitform('upload')" style="display:none;">
                                        <i class="fa fa-upload"></i> Enviar
                                    </button>
                                    <!-- image-preview-input -->
                                    <div class="btn btn-primary image-preview-input">
                                        <span class="fas fa-folder-open"></span>
                                        <span class="image-preview-input-title">Carregar Arquivo</span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="image_slider_new"/> <!-- rename it -->
                                    </div>
        
                                </span>
                            </div><!-- /input-group image-preview [TO HERE]--> 
                        </div>
                    </div>
                    <!-- Crop and preview -->
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="avatar-wrapper">
                                <img id="image" src="<?php echo $this->trataimagem->resize(JPATH_THEMES_NATIVE.DS.'img'.DS.'no_image_avatar.jpg', 622, 366, 'cache/tmp_no_image_avatar.png', 'tirarProporcao'); ?>" alt="Nenhum Arquivo Carregado">
                            	 <div id="loading_img"></div> 
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-sm-6 col-md-12">
                                    <div class="preview preview-lg"></div>
                                </div>
                                <div class="col-sm-6 col-md-12">
                                    <div class="preview  preview-lg preview-circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
          		</div>
        	</div>
            <div class="modal-footer">
                <form name="adminFormImage" id="adminFormImage" method="post" enctype="multipart/form-data"  > 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo JText::_('Fechar'); ?></button>
                    <button type="submit" id="submithimage" disabled="disabled" class="btn btn-success" ><span class="glyphicon glyphicon-search"></span>&nbsp;<?php echo JText::_('Salvar Foto'); ?></button>
                    <input type="hidden" name="position_x1" value="" id="dataX" />
                    <input type="hidden" name="position_y1" value="" id="dataY" />
                    <input type="hidden" name="position_x2" value="" id="dataScaleX" />
                    <input type="hidden" name="position_y2" value="" id="dataScaleY" />
                    <input type="hidden" name="position_w" value="" id="dataWidth" />
                    <input type="hidden" name="position_h" value="" id="dataHeight" /> 
                    <input type="hidden" name="pathimage" value="" id="pathimage" /> 
                    <input type="hidden" name="image" value="<?php echo $this->item->image_pf; ?>" /> 
                       
                    <input type="hidden" name="controller" value="perfil" />
                    <input type="hidden" name="view" value="perfil" />
                    <input type="hidden" name="task" value="uloadimage" />
                    <?php echo JHtml::_('form.token'); ?>   
                </form>             
            </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
    </div>
  </div>
</div><!-- /.modal -->
