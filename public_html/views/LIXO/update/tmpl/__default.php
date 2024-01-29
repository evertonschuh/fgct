<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');

jimport('joomla.image.resize');
$resize = new JResize();
$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
if ( !empty( $this->item->image_pf )):
    $imageUser = $resize->resize(JPATH_MEDIA .DS. 'images' .DS. 'avatar'  .DS. $this->item->image, 100, 100, 'cache/' . $this->item->image_pf, 'manterProporcao');
else:
    $imageUser = $resize->resize(JPATH_IMAGES .DS. 'noimageuser.png' , 100, 100, 'cache/noimageuser.png', 'manterProporcao'); 
endif;   
?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-md-12">
         <?php if(isset($this->item->tipo)): ?> 
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Cadastro</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages-account-settings-notifications.html"
                    ><i class="bx bx-bell me-1"></i> Serviços</a
                    >
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages-account-settings-connections.html"
                    ><i class="bx bx-link-alt me-1"></i> Finanças</a
                    >
                </li>
            </ul>
            <?php endif; ?>
            <div class="card mb-4">
                <h5 class="card-header">Cadastro do Associado</h5>
                <input type="hidden" name="tipo" value="<?php echo $this->item->tipo; ?>" />
                <!-- Account -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md mb-md-0 mb-2">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img
                                    src="<?php echo $imageUser; ?>"
                                    alt="user-avatar"
                                    class="d-block rounded user-associado"
                                    height="100"
                                    width="100"
                                />
                                <input type="hidden" name="image" value="<?php echo isset($this->item->tipo) ? $this->item->image : ''; ?>" />
                                <input type="hidden" id="imgSRC" value="<?php echo $imageUser; ?>" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3 upload-image" tabindex="0">
                                        <span class="d-none d-sm-block">Enviar Imagem
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input
                                            type="file"
                                            id="upload"
                                            class="account-file-input"
                                            hidden
                                            name="image_new"
                                            accept="image/png, image/jpeg, image/gif"
                                        />
                                    </label>
                                    <button type="button" class="btn btn-outline-danger me-2 mb-3 skip-upload" style="display:none;">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Desistir do Envio</span>
                                    </button>
                                    <div class="col mb-2">
                                        <?php if ( !empty( $this->item->image )): ?>
                                        <fieldset class="checkbox-img-remove">
                                            <label>
                                                <input type="checkbox" name="remove_image" value="1" />
                                                <?php echo JText::_('Marque e salve para remover a imagem!'); ?>
                                            </label>  
                                        </fieldset>
                                        <div class="clearfix"></div>
                                        <?php endif; ?> 
                                    </div>
                                    <p class="text-muted mb-0">Somente JPG, GIF ou PNG. Tamanho sugerido: 250x250 (px)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md float-rigth">
                            <div class="row">
                                <?php if(!isset($this->item->tipo) || $this->item->tipo=='0'): ?> 
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon <?php echo !isset($this->item->tipo) || $this->item->tipo=='0' ? 'checked' : ''; ?>">
                                        <label class="form-check-label custom-option-content" for="tipo0">
                                            <input class="form-check-input" <?php echo isset($this->item->tipo) ? 'style="display:none"' : 'style="margin: 7px 7px 0 0 !important;"' ?> <?php echo !isset($this->item->tipo) ||  $this->item->tipo=='0' ? 'checked' : ''; ?> type="radio" value="0" name="tipo" id="tipo0">
                                            <span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2">
                                                <i class="bx bx-user bx-xs"></i>
                                            </span>
                                            Física                                        
                                        </label>
                                    </div>
                                </div>
                                <?php  endif; ?>
                                <?php  if(!isset($this->item->tipo) || $this->item->tipo=='1'): ?> 
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon <?php echo isset($this->item->tipo) && $this->item->tipo=='1' ? 'checked' : ''; ?>">
                                        <label class="form-check-label custom-option-content" for="tipo1">
                                            <input  class="form-check-input" <?php echo isset($this->item->tipo) ? 'style="display:none"' : 'style="margin: 7px 7px 0 0 !important;"' ?> <?php echo isset($this->item->tipo) && $this->item->tipo=='1' ? 'checked' : ''; ?> type="radio" value="1" name="tipo" id="tipo1">
                                            <span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2">
                                                <i class="bx bxs-business bx-xs"></i>
                                            </span>
                                            Jurídica
                                        </label>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label for="name" class="form-label"><?php echo !isset($this->item->tipo) || $this->item->tipo == '0' ? 'Nome Completo' : 'Razão Social'; ?></label>
                            <input
                                class="form-control required"
                                type="text"
                                id="name"
                                name="name"
                                value="<?php echo isset($this->item->tipo) ? $this->item->name : ''; ?>"
                                autofocus
                            />
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                                class="form-control"
                                type="text"
                                id="email"
                                name="email"
                                value="<?php echo isset($this->item->tipo) ? $this->item->email : ''; ?>"
                                placeholder="email"
                            />
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="doc" class="form-label"><?php echo !isset($this->item->tipo) || $this->item->tipo == '0' ? 'CPF' : 'CNPJ'; ?></label>
                            <input class="form-control required validate-<?php echo !isset($this->item->tipo) || $this->item->tipo == '0' ? 'cpf' : 'cnpj'; ?>" type="text" name="doc" id="doc" value="<?php echo isset($this->item->tipo) ? $this->item->doc : ''; ?>" />
                        </div>
                        <div class="mb-3 col-md-3" class="date">
                            <label for="date" class="form-label"><?php echo !isset($this->item->tipo) || $this->item->tipo == '0' ? 'Data de Nascimento' : 'Data de Fundação'; ?></label>
                            <input class="form-control" 
                                   type="text" 
                                   value="<?php echo isset($this->item->tipo) ? $this->item->date : ''; ?>" 
                                   name="date" 
                                   id="date"
                            />
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="telefone">Telefone</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">BR (55)</span>
                                <input
                                    type="text"
                                    name="telefone"
                                    class="form-control"
                                    placeholder="Telefone"
                                    value="<?php echo isset($this->item->tipo) ? $this->item->telefone : ''; ?>" 
                                />
                            </div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="celular">Celular</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">BR (55)</span>
                                <input
                                    type="text"
                                    name="celular"
                                    class="form-control"
                                    placeholder="Telefone"
                                    value="<?php echo isset($this->item->tipo) ? $this->item->celular : ''; ?>" 
                                />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="celular">CEP</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="text"
                                    name="cep"
                                    id="cep"
                                    class="form-control"
                                    placeholder="CEP"
                                    value="<?php echo isset($this->item->tipo) ? $this->item->cep : ''; ?>" 
                                />
                                <button class="btn btn-outline-primary" type="button" id="complete"><i class='bx bx-search'></i></button>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="logradouro">Endereço</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="text"
                                    id="logradouro"
                                    name="logradouro"
                                    class="form-control"
                                    placeholder="Endereço"
                                    value="<?php echo isset($this->item->tipo) ? $this->item->logradouro : ''; ?>" 
                                />
                            </div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="numero">Número</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="text"
                                    name="numero"
                                    class="form-control"
                                    placeholder="Número"
                                    value="<?php echo isset($this->item->tipo) ? $this->item->numero : ''; ?>" 
                                />
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="complemento">Complemento</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="text"
                                    name="complemento"
                                    class="form-control"
                                    placeholder="Complemento"
                                    value="<?php echo isset($this->item->tipo) ? $this->item->complemento : ''; ?>" 
                                />
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="bairro">Bairro</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="text"
                                    id="bairro"
                                    name="bairro"
                                    class="form-control"
                                    placeholder="Bairro"
                                    value="<?php echo isset($this->item->tipo) ? $this->item->bairro : ''; ?>" 
                                />
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="id_estado">Estado</label>
                            <select id="id_estado" name="id_estado" class="select2 form-select">
                                <?php if (empty($this->item->id_estado)) { ?>
                                <option disabled selected class="default" value=""><?php echo JText::_('- Estados -'); ?></option>
                                <?php } ?>
                                <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text',  (isset($this->item->tipo) ? $this->item->id_estado : '')); ?>    
                            </select>    
                        </div>  
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="id_cidade">Estado</label>
                            <select id="id_cidade" name="id_cidade" class="select2 form-select">
                                <?php if (empty($this->item->id_cidade)) { ?>
                                <option disabled selected class="default" value=""><?php echo JText::_('- Cidades -'); ?></option>
                                <?php }
                                if ($this->item->id_estado > 0)
                                    echo JHTML::_('select.options',  $this->cidades, 'value', 'text',  (isset($this->item->tipo) ?$this->item->id_cidade : ''));
                                ?>   
                            </select>    
                        </div> 
                        <div class="mt-2">
                            <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary me-2">Salvar</button>
                            <button type="button" onclick="Joomla.submitbutton('cancel')" class="btn btn-outline-secondary">Sair</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>   
     <?php /*       <div class="card">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                <div class="mb-3 col-12 mb-0">
                <div class="alert alert-warning">
                    <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                    <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                </div>
                </div>
                <form id="formAccountDeactivation" onsubmit="return false">
                <div class="form-check mb-3">
                    <input
                    class="form-check-input"
                    type="checkbox"
                    name="accountActivation"
                    id="accountActivation"
                    />
                    <label class="form-check-label" for="accountActivation"
                    >I confirm my account deactivation</label
                    >
                </div>
                <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                </form>
            </div>
            </div>
        </div>
    </div>


    <div class="card shadow mb-4">
		<div class="card-header py-3">
        	<h6 class="m-0 font-weight-bold text-primary"><?php echo empty($this->item->id_pf) ? 'Novo Cadastro' : 'Editar Cadastro' ?></h6>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <input type="hidden" name="image_pf" value="<?php echo $this->item->image_pf; ?>" />
                                
                                <div class="text-center">
                                <?php if ( !empty( $this->item->image_pf )): ?>
                                    <img class="img-fluid img-avatar img-thumbnail" src="<?php echo $resize->resize(JPATH_MEDIA .DS. 'images' .DS. 'avatar'  .DS. $this->item->image_pf, 250, 250, 'cache/' . $this->item->image_pf, 'manterProporcao'); ?>" />
                                <?php else: ?>
                                    <img class="img-fluid img-avatar img-thumbnail" src="<?php echo $resize->resize(JPATH_THEMES_NATIVE .DS. 'img' .DS. 'noimageuser.png' , 250, 250, 'cache/noimageuser.png', 'manterProporcao') ;  ?>" />
                                <?php endif; ?>  
                                    <img class="img-fluid img-avatar-preview img-thumbnail" src="" style="display:none"/>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <?php if ( !empty( $this->item->image_pf )): ?>
                                <fieldset class="checkbox-img-remove">
                                    <label>
                                        <input type="checkbox" name="remove_image_pf" value="1" />
                                        <?php echo JText::_('Marque para apenas remover a imagem!'); ?>
                                    </label>  
                                </fieldset>
                                <div class="clearfix"></div>
                                <?php endif; ?> 
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label for="subject"><strong>Selecionar Imagem:</strong></label>
                                <!-- image-preview-filename input [CUT FROM HERE]-->
                                <div class="image-preview">
                                    <span class="input-group" style="width: auto;">
                                        <input type="text" class="form-control image-preview-filename" disabled="disabled" style="display:none;"> 
                                        <span class="input-group-append" style="width: auto;">
                                            <button type="button" class="btn btn-danger image-preview-clear " style="display:none;">
                                                <i class="fa fa-times"></i> Desistir 
                                            </button>
                                        </span>
                                    </span>
                                    <div class="btn btn-primary btn-block image-preview-input">
                                        <i class="fa fa-upload"></i> Enviar Arquivo
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="image_pf_new"/>
                                    </div>
                                </div>
                                <span class="form-text text-muted">Tamanho sugerido: 250x250 (px).</span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label><?php echo JText::_('Ativo') ?></label>
                                    <fieldset>
                                        <?php echo JHTML::_('select.booleanlist', 'status_pf', '', $this->item->status_pf); ?>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <?php if(!empty($this->item->cpf_pf)):  ?>
                            <div class="form-group">
                                <div class="form-group">
                                    <label><?php echo JText::_('Código QR') ?></label>
                                    <span class="form-text text-muted" style="font-size:0.9em;"><?php echo str_replace('=', '', strrev( base64_encode(base64_encode( $this->item->cpf_pf ) ) ) ); ?></span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php endif;?>
                        </div>

                        <div class="col-12 col-md-9">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="dados-tab" data-toggle="tab" href="#dados" role="tab" aria-controls="dados" aria-selected="true"><b>Dados Pessoais</b></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" id="endereco-tab" data-toggle="tab" href="#endereco" role="tab" aria-controls="endereco" aria-selected="true"><b>Endereço / Contato</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="arquivos-tab" data-toggle="tab" href="#arquivos" role="tab" aria-controls="arquivos" aria-selected="true"><b>Arquivo Digital</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="observacoes-tab" data-toggle="tab" href="#observacoes" role="tab" aria-controls="observacoes" aria-selected="true"><b>Observações</b></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="dados" role="tabpanel"
                                    aria-labelledby="dados-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Nome Completo: *'); ?></label>
                                                        <input type="text" class="form-control required" name="name_pf"id="name_pf" value="<?php echo $this->item->name_pf; ?>" placeholder="<?php echo JText::_('Nome Completo') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('CPF: *'); ?></label>
                                                        <input type="text" class="form-control validate-cpf required" name="cpf_pf" id="cpf_pf" value="<?php echo $this->item->cpf_pf; ?>" placeholder="<?php echo JText::_('CPF') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Email: *'); ?></label>
                                                        <input type="text" class="form-control required validate-email" name="email_pf" id="email_pf" value="<?php echo $this->item->email_pf; ?>" placeholder="<?php echo JText::_('Email') ?>" />
                                                    </div>
                                                </div>


                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Data da Nascimento:'); ?></label>
                                                        <div class="input-group date">
                                                            <input type="text" class="form-control" name="data_nascimento_pf" value="<?php if ($this->item->data_nascimento_pf) echo JHtml::date(JFactory::getDate($this->item->data_nascimento_pf, $siteOffset)->toISO8601(true), 'DATE_FORMAT'); ?>" placeholder="<?php echo JText::_('Data de Nascimento') ?>" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>     
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Nacionalidade:'); ?></label>
                                                        <input type="text" class="form-control" name="nacionalidade_pf" value="<?php echo $this->item->nacionalidade_pf; ?>" placeholder="<?php echo JText::_('Nacionalidade') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Sexo:') ?></label>
                                                        <fieldset>
                                                            <?php echo JHTML::_('select.booleanlist', 'sexo_pf', '', $this->item->sexo_pf, 'Feminino', 'Masculino'); ?>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Estado onde Nasceu:*'); ?></label>
                                                        <select name="naturalidade_uf_pf" id="naturalidade_uf_pf" class="form-control select2 required">
                                                            <option selected class="default" disabled="disabled" value=""><?php echo JText::_('Selecione o Estado'); ?></option>
                                                            <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text', $this->item->naturalidade_uf_pf); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="naturalidade_pf"><?php echo JText::_('Cidade onde Nasceu:*'); ?></label>
                                                        <select name="naturalidade_pf" id="naturalidade_pf" class="form-control select2 required">
                                                            <option selected class="default" disabled="disabled" value=""><?php echo JText::_('Selecione a Cidade'); ?></option>
                                                            <?php echo JHTML::_('select.options',  $this->cidadesNasceu, 'value', 'text', $this->item->naturalidade_pf); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Nome do Pai:'); ?></label>
                                                        <input type="text" class="form-control" name="npai_pf" id="npai_pf" value="<?php echo $this->item->npai_pf; ?>" placeholder="<?php echo JText::_('Nome do Pai') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Nome da Mãe: *'); ?></label>
                                                        <input type="text" class="form-control required" name="nmae_pf" id="nmae_pf" value="<?php echo $this->item->nmae_pf; ?>" placeholder="<?php echo JText::_('Nome da Mãe') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Estado Civil:'); ?></label>
                                                        <select name="id_estado_civil" class="form-control select2">
                                                        <option selected class="default" disabled="disabled" value=""><?php echo JText::_('Estado Civil'); ?></option>
                                                            <?php echo JHTML::_('select.options',  $this->estadoCivil, 'value', 'text', $this->item->id_estado_civil); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-8">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Profissão: *'); ?></label>
                                                        <input type="text" class="form-control required" name="profissao_pf" id="profissao_pf" value="<?php echo $this->item->profissao_pf; ?>" placeholder="<?php echo JText::_('Profissão') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <fieldset class="group-border">
                                                            <legend class="group-border">RG</legend>
                                                            <div class="row">
                                                                <div class="col-12 col-md-6 col-lg-3">
                                                                    <div class="form-group">
                                                                        <label><?php echo JText::_('Número:'); ?></label>
                                                                        <input type="text" class="form-control" name="rg_pf" id="rg_pf" value="<?php echo $this->item->rg_pf; ?>" placeholder="<?php echo JText::_('RG') ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-6 col-lg-3">
                                                                    <div class="form-group">
                                                                        <label><?php echo JText::_('Orgão Expedidor:'); ?></label>
                                                                        <input type="text" class="form-control" name="orgao_expeditor_pf" id="orgao_expeditor_pf" value="<?php echo $this->item->orgao_expeditor_pf; ?>" placeholder="<?php echo JText::_('Orgão Expeditor RG') ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-6 col-lg-2">
                                                                    <div class="form-group">
                                                                        <label><?php echo JText::_('UF:'); ?></label>
                                                                        <select name="uf_orga_expeditor_pf" id="uf_orga_expeditor_pf" class="form-control select2">
                                                                            <option selected class="default" disabled="disabled" value=""><?php echo JText::_('- UF -'); ?> </option>
                                                                            <?php echo JHTML::_('select.options',  $this->ufs, 'value', 'text', $this->item->uf_orga_expeditor_pf); ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-6 col-lg-4">
                                                                    <div class="form-group">
                                                                        <label><?php echo JText::_('Data da Expedição:'); ?></label>
                                                                        <div class="input-group date">
                                                                            <input type="text" class="form-control" name="data_expedicao_pf" value="<?php if ($this->item->data_expedicao_pf) echo JHtml::date(JFactory::getDate($this->item->data_expedicao_pf, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="<?php echo JText::_('Data Expedição RG') ?>" />
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </span>
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
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Telefone Fixo:'); ?></label>
                                                        <input type="tel" autocomplete="off" class="form-control" name="tel_residencial_pf" id="tel_residencial_pf" value="<?php echo $this->item->tel_residencial_pf; ?>" placeholder="<?php echo JText::_('Telefone Fixo') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Telefone Celular:'); ?></label>
                                                        <input type="tel" autocomplete="off" class="form-control required" name="tel_celular_pf" id="tel_celular_pf" value="<?php echo $this->item->tel_celular_pf; ?>" placeholder="<?php echo JText::_('Telefone Celular') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('CEP:'); ?></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control required" id="cep_pf" name="cep_pf" autocomplete="off" value="<?php echo $this->item->cep_pf; ?>" placeholder="<?php echo JText::_('CEP') ?>" />
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-primary"
                                                                    data-toggle="modal" data-target="#buscacep" style="height:38px" title="Pesquisar CEP"><i class="fa fa-search fa-fw"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Logradouro:'); ?></label>
                                                        <input type="text" class="form-control required"id="logradouro_pf" name="logradouro_pf" value="<?php echo $this->item->logradouro_pf; ?>"
                                                            placeholder="<?php echo JText::_('Logradouro') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Número:'); ?></label>
                                                        <input type="text" class="form-control" id="numero_pf" name="numero_pf" value="<?php echo $this->item->numero_pf; ?>" placeholder="<?php echo JText::_('Número') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Complemento:'); ?></label>
                                                        <input type="text" class="form-control" id="complemento_pf" name="complemento_pf" value="<?php echo $this->item->complemento_pf; ?>" placeholder="<?php echo JText::_('Complemento') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Bairro:'); ?></label>
                                                        <input type="text" class="form-control" id="bairro_pf" name="bairro_pf" value="<?php echo $this->item->bairro_pf; ?>" placeholder="<?php echo JText::_('Bairro') ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Estado:'); ?></label>
                                                        <select name="id_estado" id="id_estado" class="form-control select2">
                                                            <?php if (empty($this->item->id_estado)) { ?>
                                                            <option disabled selected class="default" value=""><?php echo JText::_('- Estados -'); ?></option>
                                                            <?php } ?>
                                                            <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text', $this->item->id_estado); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Cidade:'); ?></label>
                                                        <select id="id_cidade" name="id_cidade" class="form-control required select2">
                                                            <?php if (empty($this->item->id_cidade)) { ?>
                                                            <option disabled selected class="default" value=""><?php echo JText::_('- Cidades -'); ?></option>
                                                            <?php }
                                                            if ($this->item->id_estado > 0)
                                                                echo JHTML::_('select.options',  $this->cidades, 'value', 'text', $this->item->id_cidade);
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="arquivos" role="tabpanel" aria-labelledby="arquivos-tab">
                                    <div class="row"  id="fileupload">
                                        <div class="col-12">
                                            </br>       
                                            <?php if($this->item->id_pf): ?>                               
                                            <div class="form-group group-fieldset">
                                                <div class="card panel-fieldset" style="border: none;">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <div class="col-auto float-right toolbar-buttons fileupload-buttonbar" id="toolbar" >
                                                                        
                                                                        <span class="btn btn-primary shadow btn-circle btn-lg fileinput-button">
                                                                            <i class="fa fa-plus"></i>
                                                                            <input type="file" name="files[]" multiple>
                                                                        </span>
                                                                        
                                                                        <a href="javascript:void(0);" class="btn btn-danger shadow btn-circle btn-lg delete">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                    </div>                       
                                                                </div> 
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="1%" class=" fileupload-buttonbar" >
                                                                        <input type="checkbox" class="toggle">
                                                                        </th>
                                                                        <th><?php echo JText::_('Arquivo'); ?></th>
                                                                        <th class="hidden-xs hidden-sm text-center"><?php echo JText::_('Tamanho'); ?></th>
                                                                        <th class="hidden-xs hidden-sm text-center"></th>
                                                                    </tr>
                                                                    <tbody class="files"></tbody>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php else: ?>
                                            <h4>Salve uma primeira vez para ativar a inclusão de conteúdos</h4>
                                            <?php endif; ?> 
                                        </div>                  
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="observacoes" role="tabpanel"
                                    aria-labelledby="observacoes-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            </br>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    </br>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <?php
                                                        $editor = JFactory::getEditor('tinymce');
                                                        //$params = array('class_editor' => 'addVarsPlugins', 'custom_plugin' => 'variable', 'custom_button' => 'variable');
                                                        echo $editor->display('observacao_pf', $this->item->observacao_pf, '100%', '400', '60', '20', array('pagebreak', 'article', 'image', 'readmore'));
                                                        ?>
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
        </div>
    </div>
     */ ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_associado; ?>" />
    <input type="hidden" name="id_associado" value="<?php echo $this->item->id_associado; ?>" />
    <input type="hidden" name="controller" value="associado" />
    <input type="hidden" name="view" value="associado" />
    <?php echo JHTML::_('form.token'); ?>
</form>