<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');

jimport('joomla.image.resize');
$resize = new JResize();

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

if ( !empty( $this->item->image_pf ) && file_exists(JPATH_CDN .DS. 'images' .DS. 'avatar' .DS. $this->item->image_pf)):
    $imageUser = $resize->resize(JPATH_CDN .DS. 'images' .DS. 'avatar' .DS. $this->item->image_pf, 100, 100, 'cache/' . $this->item->image_pf, 'manterProporcao');
else:
    $imageUser = $resize->resize(JPATH_IMAGES .DS. 'noimageuser.png' , 100, 100, 'cache/noimageuser.png', 'manterProporcao'); 
endif;   
?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-md-12"> 
            <ul class="nav nav-pills flex-column flex-md-row mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">
                        <i class="bx bx-user me-1"></i> Cadastro
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" data-bs-target="#contact"  role="tab" aria-controls="contact" aria-selected="false">
                        <i class="bx bx-map me-1"></i> Endereços e Contatos
                    </a>
                </li>
                <?php /*
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="athlete-tab" data-bs-toggle="tab" href="#athlete"  data-bs-target="#athlete" role="tab" aria-controls="athlete" aria-selected="false">
                        <i class="bx bx-user-pin me-1"></i> Dados do Atleta
                    </a>
                </li>
                */ ?>
            </ul>
            <div class="card" >
                <div class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="card-title mb-sm-0 me-2">Meu Perfil</h5>
                            <span class="d-none d-sm-block">A opção de salvar alterações está desativada temporariamente</span>
                        </div>
                    </div>
                    <div class="action-btns">
                        <button type="button" disabled <?php /*onclick="Joomla.submitbutton('save')" ; */ ?> class="btn btn-primary">Salvar Alteraçôes</button>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="tab-content m-0 p-0" >
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row mb-3">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                        <img
                                            src="<?php echo $imageUser; ?>"
                                            alt="user-avatar"
                                            class="d-block rounded user-associado"
                                            height="100"
                                            width="100"
                                        />
                                        <input type="hidden" name="image_pf" value="<?php echo $this->item->image_pf; ?>" />
                                        <input type="hidden" id="imgSRC" value="<?php echo $imageUser; ?>" />
                                        <div class="button-wrapper">
                                            <label for="upload"  disabled class=" disabled btn btn-primary me-2 mb-3 upload-image" tabindex="0">
                                                <span class="d-none d-sm-block">Enviar Imagem
                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                <input
                                                    type="file"
                                                    id="upload"
                                                    class="account-file-input"
                                                    hidden
                                                    name="image_pf_new"
                                                    accept="image/png, image/jpeg, image/gif"
                                                />
                                            </label>
                                            <button type="button" class="btn btn-outline-danger me-2 mb-3 skip-upload" style="display:none;">
                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Desistir do Envio</span>
                                            </button>
                                            <div class="col mb-2">
                                                <?php if ( !empty( $this->item->image_pf )): ?>
                                                <fieldset class="checkbox-img-remove">
                                                    <label>
                                                        <input type="checkbox" name="remove_image_pf" value="1" />
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
                                        <?php if(!isset($this->item->id_tipo) || $this->item->id_tipo=='0'): ?> 
                                        <div class="col-md mb-md-0 mb-2">
                                            <div class="form-check custom-option custom-option-icon <?php echo !isset($this->item->id_tipo) || $this->item->id_tipo=='0' ? 'checked' : ''; ?>">
                                                <label class="form-check-label custom-option-content" for="tipo0">
                                                    <input class="form-check-input" <?php echo isset($this->item->id_tipo) ? 'style="display:none"' : 'style="margin: 7px 7px 0 0 !important;"' ?> <?php echo !isset($this->item->tipo) ||  $this->item->tipo=='0' ? 'checked' : ''; ?> type="radio" value="0" name="tipo" id="tipo0">
                                                    <span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2">
                                                        <i class="bx bx-user bx-xs"></i>
                                                    </span>
                                                    Física                                        
                                                </label>
                                            </div>
                                        </div>
                                        <?php  endif; ?>
                                        <?php  if(!isset($this->item->id_tipo) || $this->item->id_tipo=='1'): ?> 
                                        <div class="col-md">
                                            <div class="form-check custom-option custom-option-icon <?php echo isset($this->item->id_tipo) && $this->item->id_tipo=='1' ? 'checked' : ''; ?>">
                                                <label class="form-check-label custom-option-content" for="tipo1">
                                                    <input  class="form-check-input" <?php echo isset($this->item->id_tipo) ? 'style="display:none"' : 'style="margin: 7px 7px 0 0 !important;"' ?> <?php echo isset($this->item->tipo) && $this->item->tipo=='1' ? 'checked' : ''; ?> type="radio" value="1" name="tipo" id="tipo1">
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
                            <div class="row">
                                <div class="mb-3 col-md-8">
                                    <label for="name" class="form-label">Nome Completo</label>
                                    <input
                                        class="form-control required"
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="<?php echo $this->item->name; ?>"
                                        autofocus
                                    />
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="cpf_pf" class="form-label">CPF</label>
                                    <input class="form-control" type="text" disabled value="<?php echo $this->item->cpf_pf; ?>" />
                                </div>
                                <div class="mb-3 col-md-3" class="dte">
                                    <label for="data_nascimento_pf" class="form-label">Data de Nascimento</label>
                                    <div class="input-group input-group-merge date">
                                        <input class="form-control" 
                                            type="text" 
                                            value="<?php echo $this->item->data_nascimento_pf ? JHtml::date(JFactory::getDate($this->item->data_nascimento_pf, $siteOffset)->toISO8601(), 'DATE_FORMAT') : ''; ?>" 
                                            name="data_nascimento_pf" 
                                            id="data_nascimento_pf"
                                        />
                                        <button class="btn btn-outline-primary" type="button">
                                            <i class="bx bx-calendar"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="id_estado_civil" class="form-label"><?php echo JText::_('Estado Civil:'); ?></label>
                                    <select name="id_estado_civil"  id="id_estado_civil"class="form-control select2">
                                        <option disabled selected class="default" value=""><?php echo JText::_('- Estado Civil -'); ?></option>
                                        <?php echo JHTML::_('select.options',  $this->estadoCivil, 'value', 'text', $this->item->id_estado_civil ); ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="tsangue_pf" class="form-label"><?php echo JText::_('Tipo Sanguineo:'); ?></label>                         
                                    <input type="text" class="form-control" name="tsangue_pf" id="tsangue_pf" value="<?php echo $this->item->tsangue_pf ; ?>" placeholder="<?php echo JText::_('Tipo Sanguineo') ?>">
                                </div> 
                                <div class="mb-3 col-md-4">
                                    <label for="sexo_pf" class="form-label"><?php echo JText::_('Sexo:') ?></label>
                                    <fieldset class="">
                                        <?php echo JHTML::_('select.booleanlist', 'sexo_pf', '', $this->item->sexo_pf, 'Feminino', 'Masculino'); ?>
                                    </fieldset>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="nacionalidade_pf" class="form-label"><?php echo JText::_('Nacionalidade:'); ?></label>
                                    <input name="nacionalidade_pf" id="nacionalidade_pf" type="text" class="form-control" value="<?php echo $this->item->nacionalidade_pf; ?>" placeholder="<?php echo JText::_('Nacionalidade') ?>">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="naturalidade_uf_pf" class="form-label"><?php echo JText::_('Estado onde nasceu:'); ?></label>
                                    <select name="naturalidade_uf_pf" id="naturalidade_uf_pf" class="form-control select2">
                                        <option disabled selected class="default" value=""><?php echo JText::_('- UF -'); ?></option>
                                        <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text', $this->item->naturalidade_uf_pf ); ?>
                                    </select>
                                </div>   
                                <div class="mb-3 col-md-4">
                                    <label for="naturalidade_pf" class="form-label"><?php echo JText::_('Cidade onde Nasceu:'); ?></label>
                                    <select id="naturalidade_pf" name="naturalidade_pf" class="form-control select2">
                                        <?php if ( empty($this->item->naturalidade_pf) ) { ?>
                                        <option disabled selected class="default" value=""><?php echo JText::_('- Cidades -'); ?></option>
                                        <?php } ?>
                                        <?php echo JHTML::_('select.options',  $this->cidadesNasceu, 'value', 'text', $this->item->naturalidade_pf ); ?>
                                    </select>
                                </div>   

                                <div class="mb-3 col-md-6">
                                    <label for="npai_pf" class="form-label"><?php echo JText::_('Nome do Pai:'); ?></label>
                                    <input type="text" class="form-control" name="npai_pf" id="npai_pf" value="<?php echo $this->item->npai_pf; ?>" placeholder="<?php echo JText::_('Nome do Pai') ?>">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nmae_pf" class="form-label"><?php echo JText::_('Nome da Mãe:'); ?></label>
                                    <input type="text" class="form-control" name="nmae_pf" id="nmae_pf" value="<?php echo $this->item->nmae_pf; ?>" placeholder="<?php echo JText::_('Nome da Mãe') ?>">
                                </div> 


                                <div class="mb-3 col-md-3">
                                    <label for="rg_pf" class="form-label"><?php echo JText::_('Número do RG:'); ?></label>
                                    <input type="text" class="form-control" name="rg_pf" id="rg_pf" value="<?php echo $this->item->rg_pf; ?>" placeholder="<?php echo JText::_('RG') ?>">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label for="orgao_expeditor_pf" class="form-label"><?php echo JText::_('Orgão Expedidor:'); ?></label>
                                    <input type="text" class="form-control" name="orgao_expeditor_pf" id="orgao_expeditor_pf" value="<?php echo $this->item->orgao_expeditor_pf; ?>" placeholder="<?php echo JText::_('Orgão Expeditor RG') ?>">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="uf_orga_expeditor_pf" class="form-label"><?php echo JText::_('UF Expedidora:'); ?></label>
                                    <select name="uf_orga_expeditor_pf" id="uf_orga_expeditor_pf" class="form-control select2">
                                        <option disabled selected class="default" value=""><?php echo JText::_('- UF -'); ?></option>
                                        <?php echo JHTML::_('select.options',  $this->ufs, 'value', 'text', $this->item->uf_orga_expeditor_pf ); ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label for="data_expedicao_pf" class="form-label">Data de Expedição:</label>
                                    <div class="input-group input-group-merge date">
                                        <input 
                                            type="text" 
                                            name="data_expedicao_pf" 
                                            id="data_expedicao_pf" 
                                            class="form-control" 
                                            value="<?php if( $this->item->data_expedicao_pf ) echo JHtml::date(JFactory::getDate($this->item->data_expedicao_pf, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" 
                                            placeholder="<?php echo JText::_('Data de Expedição RG') ?>" 
                                        />
                                        <button class="btn btn-outline-primary" type="button">
                                            <i class="bx bx-calendar"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row">    
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        id="email"
                                        name="email"
                                        value="<?php echo $this->item->email; ?>"
                                        placeholder="email"
                                    />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="tel_residencial_pf">Telefone</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">BR (55)</span>
                                        <input
                                            type="text"
                                            id="tel_residencial_pf"
                                            name="tel_residencial_pf"
                                            class="form-control"
                                            placeholder="Telefone"
                                            value="<?php echo $this->item->tel_residencial_pf; ?>" 
                                        />
                                    </div>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="tel_celular_pf">Celular</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">BR (55)</span>
                                        <input
                                            type="text"
                                            id="tel_celular_pf"
                                            name="tel_celular_pf"
                                            class="form-control"
                                            placeholder="Celular"
                                            value="<?php echo $this->item->tel_celular_pf; ?>" 
                                        />
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="cep_pf">CEP</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            type="text"
                                            id="cep_pf"
                                            name="cep_pf"
                                            class="form-control cep"
                                            placeholder="CEP"
                                            value="<?php echo $this->item->cep_pf; ?>" 
                                        />
                                        <button class="btn btn-outline-primary complete" type="button"><i class='bx bx-search'></i></button>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-9">
                                    <label class="form-label" for="logradouro_pf">Endereço</label>
                                    <input
                                        type="text"
                                        id="logradouro_pf"
                                        name="logradouro_pf"
                                        class="form-control logradouro required"
                                        placeholder="Endereço"
                                        value="<?php echo $this->item->logradouro_pf; ?>" 
                                    />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="numero_pf">Número</label>
                                    <input
                                        type="text"
                                        id="numero_pf"
                                        name="numero_pf"
                                        class="form-control numero"
                                        placeholder="Número"
                                        value="<?php echo $this->item->numero_pf; ?>" 
                                    />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label" for="complemento_pf">Complemento</label>
                                    <input
                                        type="text"
                                        name="complemento_pf"
                                        class="form-control complemento"
                                        placeholder="Complemento"
                                        value="<?php echo $this->item->complemento_pf; ?>" 
                                    />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="bairro_pf">Bairro</label>
                                    <input
                                        type="text"
                                        name="bairro_pf"
                                        class="form-control bairro"
                                        placeholder="Bairro"
                                        value="<?php echo $this->item->bairro_pf; ?>" 
                                    />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="id_estado">Estado</label>
                                    <select id="id_estado" name="id_estado" class="select2 form-select estado">
                                        <?php if (empty($this->item->id_estado)) { ?>
                                        <option disabled selected class="default" value=""><?php echo JText::_('- Estados -'); ?></option>
                                        <?php } ?>
                                        <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text',  $this->item->id_estado); ?>    
                                    </select>    
                                </div>  
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="id_cidade">Cidade</label>
                                    <select id="id_cidade" name="id_cidade" class="select2 form-select cidade">
                                        <?php if (empty($this->item->id_cidade)) { ?>
                                        <option disabled selected class="default" value=""><?php echo JText::_('- Cidades -'); ?></option>
                                        <?php }
                                        if ($this->item->id_estado > 0)
                                            echo JHTML::_('select.options',  $this->cidades, 'value', 'text', $this->item->id_cidade);
                                        ?>   
                                    </select>    
                                </div>
                                <div class="mb-3 col-12">
                                    <div class="form-check form-switch mt-3">
                                        <input  class="form-check-input" id="check_add_endereco" <?php echo $this->item->add_endereco_pf == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                        <label class="form-check-label" for="checkStatus">Ative para cadastrar outro endereço para correspondêcias</label>
                                        <input type="hidden" name="add_endereco_pf" value="<?php echo $this->item->add_endereco_pf;?>">
                                    </div>
                                </div>
                                <div class="add-endereco m-0 p-0 row" <?php echo $this->item->add_endereco_pf == 1 ? '' :'style="display:none"';?>>
                                    <div class="col-md-12">
                                        <h6 class="card-title mb-sm-0 me-2">Endereço para Correspondências</h6>
                                        <hr class="my-1 mb-3" />
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="add_cep_pf">CEP</label>
                                        <div class="input-group input-group-merge">
                                            <input
                                                type="text"
                                                id="add_cep_pf"
                                                name="add_cep_pf"
                                                class="form-control cep"
                                                placeholder="CEP"
                                                value="<?php echo $this->item->add_cep_pf; ?>" 
                                            />
                                            <button class="btn btn-outline-primary complete" type="button"><i class='bx bx-search'></i></button>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-9">
                                        <label class="form-label" for="add_logradouro_pf">Endereço</label>
                                        <input
                                            type="text"
                                            id="add_logradouro_pf"
                                            name="add_logradouro_pf"
                                            class="form-control logradouro"
                                            placeholder="Endereço"
                                            value="<?php echo $this->item->add_logradouro_pf; ?>" 
                                        />
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="add_numero_pf">Número</label>
                                        <input
                                            type="text"
                                            id="add_numero_pf"
                                            name="add_numero_pf"
                                            class="form-control numero"
                                            placeholder="Número"
                                            value="<?php echo $this->item->add_numero_pf; ?>" 
                                        />
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="add_complemento_pf">Complemento</label>
                                        <input
                                            type="text"
                                            id="add_complemento_pf"
                                            name="add_complemento_pf"
                                            class="form-control complemento"
                                            placeholder="Complemento"
                                            value="<?php echo $this->item->add_complemento_pf; ?>" 
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="add_bairro_pf">Bairro</label>
                                        <input
                                            type="text"
                                            id="add_bairro_pf"
                                            name="add_bairro_pf"
                                            class="form-control bairro"
                                            placeholder="Bairro"
                                            value="<?php echo $this->item->add_bairro_pf; ?>" 
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="add_id_estado">Estado</label>
                                        <select id="add_id_estado" name="add_id_estado" class="select2 form-select estado">
                                            <?php if (empty($this->item->add_id_estado)) { ?>
                                            <option disabled selected class="default" value=""><?php echo JText::_('- Estados -'); ?></option>
                                            <?php } ?>
                                            <?php echo JHTML::_('select.options',  $this->estados, 'value', 'text',  $this->item->add_id_estado); ?>    
                                        </select>    
                                    </div>  
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="add_id_cidade">Cidade</label>
                                        <select id="add_id_cidade" name="add_id_cidade" class="select2 form-select cidade">
                                            <?php if (empty($this->item->add_id_cidade)) { ?>
                                            <option disabled selected class="default" value=""><?php echo JText::_('- Cidades -'); ?></option>
                                            <?php }
                                            if ($this->item->add_id_cidade > 0)
                                                echo JHTML::_('select.options',  $this->cidades, 'value', 'text', $this->item->add_id_cidade);
                                            ?>   
                                        </select>    
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="tab-pane fade" id="athlete" role="tabpanel" aria-labelledby="athlete-tab">
                            <div class="row">    
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Matricula</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        disabled="disabled"
                                        value="<?php echo $this->item->id_associado; ?>"

                                    />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Cadastro</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        disabled="disabled"
                                        value="<?php echo JHtml::date(JFactory::getDate($this->item->cadastro_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>"

                                    />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Validade</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        disabled="disabled"
                                        value="<?php echo JHtml::date(JFactory::getDate($this->item->confirmado_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>"

                                    />
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Validade</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        disabled="disabled"
                                        value="<?php echo JHtml::date(JFactory::getDate($this->item->validate_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>"

                                    />
                                </div>
                                
                            <div class="col-md-4 col-lg-4">
                                <label><?php echo JText::_('Tipo de Filiação:'); ?></label>
                                <select name="tipo_socio" class="form-control select2">
                                <option class="default" value=""><?php echo JText::_('- Tipo de Filiação -'); ?></option>
                                    <?php

                                    $tiposFiliacoes[] = JHTML::_('select.option', '0', JText::_( 'Associado Padrão' ), 'value', 'text' );                                                   
                                    $tiposFiliacoes[] = JHTML::_('select.option', '1', JText::_( 'Associado Ar Comprimido' ), 'value', 'text' );                                            
                                    $tiposFiliacoes[] = JHTML::_('select.option', '2', JText::_( 'Associado Copa Brasil' ), 'value', 'text' );
                                    
                                    if($this->item->compressed_air_pf == 1)
                                        $tipoFiliacao = '1';
                                    elseif($this->item->copa_brasil_pf == 1)
                                        $tipoFiliacao = '2';
                                    else
                                        $tipoFiliacao = '0';

                                    echo JHTML::_('select.options', $tiposFiliacoes, 'value', 'text', $tipoFiliacao );
                                    ?>
                                </select>  
                            </div>  
                            <div class="form-group">
                                <label><?php echo JText::_('Para-Atleta:') ?></label>
                                <fieldset>
                                    <?php echo JHTML::_('select.booleanlist', 'pcd_pf', '', $this->item->pcd_pf); ?>
                                </fieldset>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('Confederado:') ?></label>
                                <fieldset>
                                    <?php echo JHTML::_('select.booleanlist', 'confederado_pf', '', $this->item->confederado_pf); ?>
                                </fieldset>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('Filho de Sócio:') ?></label>
                                <fieldset>
                                    <?php echo JHTML::_('select.booleanlist', 'filho_pf', '', $this->item->filho_pf); ?>
                                </fieldset>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('Clube Filiado:'); ?></label>
                                <select name="id_clube" class="form-control select2">
                                    <option class="default" value=""><?php echo JText::_('INTRANET_GLOBAL_CLUBE'); ?></option>
                                    <?php                                                    
                                    $clubes[] = JHTML::_('select.option', '0', JText::_( 'Clube não cadastrado na FGCT' ), 'value', 'text' );
                                    if (count($this->clubes) > 0)
                                        $clubes = array_merge($clubes, $this->clubes) ;
                                    echo JHTML::_('select.options', $clubes, 'value', 'text', $this->item->id_clube );
                                    ?>
                                </select>  
                            </div>        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4">
                                        <label><?php echo JText::_('Número do CR:'); ?></label>
                                        <input type="text" class="form-control" name="numcr_pf" value="<?php echo $this->item->numcr_pf; ?>" placeholder="<?php echo JText::_('Número do CR') ?>" />
                                    </div>
                                </div>
                            </div>   
                            <div class="form-group">
                                <label><?php echo JText::_('Vencimento CR:') ?></label>
                                <div class="input-group date col-md-4">
                                    <input type="text" class="form-control" name="vencr_pf" value="<?php if( $this->item->vencr_pf ) echo JHtml::date(JFactory::getDate($this->item->vencr_pf, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="<?php echo JText::_('Vencimento CR') ?>" />
                                    <button class="btn btn-outline-primary" type="button">
                                        <i class="bx bx-calendar"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('Status do CR:') ?></label>
                                <fieldset>
                                    <?php echo JHTML::_('select.booleanlist', 'stacr_pf', '', $this->item->stacr_pf, 'Ativo', 'Cancelado'); ?>
                                </fieldset>
                            </div>  

                            <div class="form-group">
                                <fieldset class="group-border">
                                    <legend class="group-border">Atividades do CR</legend>
                                    <fieldset class="radio" for="id_payment_enroll">
                                        <?php //echo JHTML::_('select.checkboxlist',  $this->atividades, 'id_atividade','class="group-radio"', 'value', 'text', $this->itemAtividades ); ?>
                                    </fieldset>
                                </fieldset>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_associado; ?>" />
    <input type="hidden" name="id_pf" value="<?php echo $this->item->id_associado; ?>" />
    <input type="hidden" name="controller" value="profile" />
    <input type="hidden" name="view" value="profile" />
    <?php echo JHTML::_('form.token'); ?>
</form>