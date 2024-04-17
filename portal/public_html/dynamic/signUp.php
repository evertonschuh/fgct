<?php

include( 'joomla.inc.php' );

$obj = new EASistemasDynamicSignUp();

class EASistemasDynamicSignUp {
	
	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_siteOffset = null;
	var $_htmlResponse = null;

	var $_value = null;
	var $_class_modalidade = null;

	function __construct()
	{	
	
		$lang = JFactory::getLanguage();
		$extension = 'tpl_system';
		$base_dir = JPATH_SITE;
		$language_tag = 'pt-BR';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
        $this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();	
		$this->_user = JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');

		$execute = JRequest::getVar( 'execute', '', 'post' );
		$this->_value['id_etapa'] = JRequest::getVar( 'id_etapa', '', 'post' );
		$this->_value['id_prova'] = JRequest::getVar( 'id_prova', '', 'post' );


		switch($execute){
			case 'show-modal':
				if($this->setShowModalSignUp()){
					echo $this->_htmlResponse;
					return;
				}
					
			break;
		}
		echo 'error';
		return;
	}

	function setShowModalSignUp()
	{

		if(empty($this->_data))
			$this->getItem();
		
		$this->_class_modalidade	= $this->getClassModalidade();	

		//$paramProva = $this->getParamProva($this->_data->id_prova , $this->_data->params_inscricao_etapa);
	
		$gencatclass = $this->getGenCatClass( $this->_data->id_prova );

		$generos = $this->getGenero( $this->_data->id_prova );

		$categorias = $this->getCategoria( (!empty($this->_data->id_genero) ? $this->_data->id_genero : $gencatclass->id_genero ) );
		
		$classes = $this->getClasse( (!empty($this->_data->id_categoria) ? $this->_data->id_categoria : $gencatclass->id_categoria ) );

		$equipes = $this->getEquipes( $this->_data->id_campeonato );

		$clubes = $this->getClubes( $this->_data->id_campeonato );

		$armas = $this->getArmas();

		$genero = (!empty($this->_data->id_genero) ? $this->_data->id_genero : $gencatclass->id_genero );
		$categoria = (!empty($this->_data->id_categoria) ? $this->_data->id_categoria : $gencatclass->id_categoria );
		$classe = (!empty($this->_data->id_classe) ? $this->_data->id_classe : $gencatclass->id_classe );
        
		$this->_htmlResponse = '<div class="bs-stepper-header border-0 p-1">';
			$this->_htmlResponse .= '<div class="step" data-target="#details">';
				$this->_htmlResponse .= '<button type="button" class="step-trigger" aria-selected="false">';
					$this->_htmlResponse .= '<span class="bs-stepper-circle"><i class="bx bx-file fs-5"></i></span>';
					$this->_htmlResponse .= '<span class="bs-stepper-label">';
						$this->_htmlResponse .= '<span class="bs-stepper-title text-uppercase">Como</span>';
						$this->_htmlResponse .= '<span class="bs-stepper-subtitle">Defina sua participação</span>';
					$this->_htmlResponse .= '</span>';
				$this->_htmlResponse .= '</button>';
			$this->_htmlResponse .= '</div>';
			$this->_htmlResponse .= '<div class="line"></div>';
			$this->_htmlResponse .= '<div class="step" data-target="#local">';
				$this->_htmlResponse .= '<button type="button" class="step-trigger" aria-selected="false" disabled>';
					$this->_htmlResponse .= '<span class="bs-stepper-circle"><i class="bx bx-box fs-5"></i></span>';
					$this->_htmlResponse .= '<span class="bs-stepper-label">';
						$this->_htmlResponse .= '<span class="bs-stepper-title text-uppercase">Onde</span>';
						$this->_htmlResponse .= '<span class="bs-stepper-subtitle">Escolha o Local</span>';
					$this->_htmlResponse .= '</span>';
				$this->_htmlResponse .= '</button>';
			$this->_htmlResponse .= '</div>';
			$this->_htmlResponse .= '<div class="line"></div>';
			$this->_htmlResponse .= '<div class="step" data-target="#reserve">';
				$this->_htmlResponse .= '<button type="button" class="step-trigger" aria-selected="false">';
					$this->_htmlResponse .= '<span class="bs-stepper-circle"><i class="bx bx-data fs-5"></i></span>';
					$this->_htmlResponse .= '<span class="bs-stepper-label">';
						$this->_htmlResponse .= '<span class="bs-stepper-title text-uppercase">Quando</span>';
						$this->_htmlResponse .= '<span class="bs-stepper-subtitle">Agende seu dia de Atirar</span>';
					$this->_htmlResponse .= '</span>';
				$this->_htmlResponse .= '</button>';
			$this->_htmlResponse .= '</div>';
			$this->_htmlResponse .= '<div class="line"></div>';
			$this->_htmlResponse .= '<div class="step" data-target="#database">';
				$this->_htmlResponse .= '<button type="button" class="step-trigger" aria-selected="false">';
					$this->_htmlResponse .= '<span class="bs-stepper-circle"><i class="bx bx-data fs-5"></i></span>';
					$this->_htmlResponse .= '<span class="bs-stepper-label">';
						$this->_htmlResponse .= '<span class="bs-stepper-title text-uppercase">xxx</span>';
						$this->_htmlResponse .= '<span class="bs-stepper-subtitle">xxx</span>';
					$this->_htmlResponse .= '</span>';
				$this->_htmlResponse .= '</button>';
			$this->_htmlResponse .= '</div>';



			
			$this->_htmlResponse .= '<div class="line"></div>';
			$this->_htmlResponse .= '<div class="step" data-target="#billing">';
				$this->_htmlResponse .= '<button type="button" class="step-trigger" aria-selected="false">';
					$this->_htmlResponse .= '<span class="bs-stepper-circle"><i class="bx bx-credit-card fs-5"></i></span>';
					$this->_htmlResponse .= '<span class="bs-stepper-label">';
						$this->_htmlResponse .= '<span class="bs-stepper-title text-uppercase">Reserva</span>';
						$this->_htmlResponse .= '<span class="bs-stepper-subtitle">Reserve seu Horário</span>';
					$this->_htmlResponse .= '</span>';
				$this->_htmlResponse .= '</button>';
			$this->_htmlResponse .= '</div>';
			$this->_htmlResponse .= '<div class="line"></div>';
			$this->_htmlResponse .= '<div class="step" data-target="#submit">';
				$this->_htmlResponse .= '<button type="button" class="step-trigger" aria-selected="false">';
					$this->_htmlResponse .= '<span class="bs-stepper-circle"><i class="bx bx-check fs-5"></i></span>';
					$this->_htmlResponse .= '<span class="bs-stepper-label">';
						$this->_htmlResponse .= '<span class="bs-stepper-title text-uppercase">Finalizar</span>';
						$this->_htmlResponse .= '<span class="bs-stepper-subtitle">Finalizar</span>';
					$this->_htmlResponse .= '</span>';
				$this->_htmlResponse .= '</button>';
			$this->_htmlResponse .= '</div>';
		$this->_htmlResponse .= '</div>';

		$this->_htmlResponse .= '<div class="bs-stepper-content p-1">';
			$this->_htmlResponse .= '<form  onsubmit="return false">';

				$this->_htmlResponse .= '<!-- Details -->';
				$this->_htmlResponse .= '<div id="details" class="content pt-3 pt-lg-0 dstepper-block">';
					$this->_htmlResponse .= '<div class="mb-3">';
						$this->_htmlResponse .= '<h5>Dados da Inscrição</h5>';
						$this->_htmlResponse .= '<input type="hidden" id="id_classe" name="id_classe"  value="' . $classe .'">';
						$this->_htmlResponse .= '<input type="hidden" id="id_categoria" name="id_categoria"  value="' . $categoria.'">';
						$this->_htmlResponse .= '<input type="hidden" id="id_genero" name="id_genero" value="' .  $genero .'">';

						$this->_htmlResponse .= '<div class="row mb-3">';
							$this->_htmlResponse .= '<label class="col-sm-2 col-form-label">Gênero:</label>';
							$this->_htmlResponse .= '<div class="col-sm-10">';
								$this->_htmlResponse .= '<input type="text" name="name_genero" disabled class="form-control"  value="' . (!empty($this->_data->name_genero) ? $this->_data->name_genero : $gencatclass->name_genero) . '">';
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>';

						$this->_htmlResponse .= '<div class="row mb-3">';
							$this->_htmlResponse .= '<label class="col-sm-2 col-form-label">Categoria:</label>';
							$this->_htmlResponse .= '<div class="col-sm-10">';
								$this->_htmlResponse .= '<input type="text" name="name_categoria"  disabled class="form-control"  value="' . (!empty($this->_data->name_categoria) ? $this->_data->name_categoria : $gencatclass->name_categoria ). '">';
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>';    

						$this->_htmlResponse .= '<div class="row mb-3">';
							$this->_htmlResponse .= '<label class="col-sm-2 col-form-label">Classe:</label>';
							$this->_htmlResponse .= '<div class="col-sm-10">';
								$this->_htmlResponse .= '<input type="text" name="name_classe"  disabled class="form-control disabled"  value="' .(!empty($this->_data->name_classe) ? $this->_data->name_classe : $gencatclass->name_classe).'">';
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>'; 

						if( $this->_data->equipe_prova>0):
						if( $this->_data->equipe_prova==1 || $this->_data->equipe_prova==4):
						$this->_htmlResponse .= '<div class="row mb-3">'; 
							$this->_htmlResponse .= '<label class="col-sm-2 col-form-label">Equipe:</label>'; 
							$this->_htmlResponse .= '<div class="col-sm-10">'; 
								if(isset($this->_data->id_equipe)):
								$this->_htmlResponse .= '<input type="text" disabled class="form-control disabled"  value="' . $this->_data->name_equipe . '">'; 
								$this->_htmlResponse .= '<input type="hidden" id="id_equipe" name="id_equipe" value="' . $this->_data->id_equipe . '">';
								else:
								$this->_htmlResponse .= '<select id="id_equipe" name="id_equipe" class="form-control select2 required">';
									$this->_htmlResponse .= '<option disabled selected class="default" value="">- Selecione a Equipe -</option>';
									$this->_htmlResponse .= JHTML::_('select.options',  $equipes, 'value', 'text');
								$this->_htmlResponse .= '</select>';
								endif;
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>';
						endif;
						if($this->_data->equipe_prova==3 || $this->_data->equipe_prova==4 || $this->_data->equipe_prova==5):
						$this->_htmlResponse .= '<div class="row mb-3">';
							$this->_htmlResponse .= '<label class="col-sm-2 col-form-label">Equipe:</label>';
							$this->_htmlResponse .= '<div class="col-sm-10">';
								if($this->_data->id_estado):
								$this->_htmlResponse .= '<input type="text" disabled class="form-control form-control-plaintext"  value="' . $this->_data->name_estado . '">';
								$this->_htmlResponse .= '<input type="hidden" id="id_estado" name="id_estado" value="' . $this->_data->id_estado . '">';
								else:
								$this->_htmlResponse .= '<select id="id_estado" name="id_estado" class="form-control required">';
									$this->_htmlResponse .= '<option disabled selected class="default" value="">- Selecione o Estado -</option>';
									$this->_htmlResponse .= JHTML::_('select.options',  $this->estados, 'value', 'text', $this->_data->id_estado ); 
								$this->_htmlResponse .= '</select>';
								endif;
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>';
						endif;
						endif;
						$this->_htmlResponse .= '<div class="row mb-3">';
							$this->_htmlResponse .= '<label class="col-sm-2 col-form-label">Arma:</label>';
							$this->_htmlResponse .= '<div class="col-sm-10">';
								if(count($armas)>0):
									$this->_htmlResponse .= '<select id="id_arma" name="id_arma" class="form-control required">';
										$this->_htmlResponse .= '<option class="default" value="">- Escolha a Arma -</option>';
										$this->_htmlResponse .= JHTML::_('select.options',  $armas, 'value', 'text' ); 
									$this->_htmlResponse .= '</select>';
								else:
									$this->_htmlResponse .= '<input type="text" disabled class="form-control form-control-plaintext"  value="Não há armas em seu acero compativel com esta prova">';
								endif;
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>';

					$this->_htmlResponse .= '</div>';
					$this->_htmlResponse .= '<div class="col-12 d-flex justify-content-between mt-4">';
						$this->_htmlResponse .= '<button class="btn btn-label-secondary btn-prev" disabled=""> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i>';
							$this->_htmlResponse .= '<span class="align-middle d-sm-inline-block d-none">Voltar</span>';
						$this->_htmlResponse .= '</button>';
						$this->_htmlResponse .= '<button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Avançar</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>';
					$this->_htmlResponse .= '</div>';
				$this->_htmlResponse .= '</div>';



				$this->_htmlResponse .= '<!-- Local -->';

				jimport('joomla.image.resize');
				$resize = new JResize();
				
				$this->_htmlResponse .= '<div id="local" class="content pt-3 pt-lg-0 active dstepper-block">';
					$this->_htmlResponse .= '<h5>Escolha o Local</h5>';
					$this->_htmlResponse .= '<div class="mb-3 row">';
					if( count($clubes)>0):
						foreach($clubes as $i => $clube):
							$image = '';
							if(!empty($clube->logo_pj) && file_exists(JPATH_CDN .DS. 'images' .DS. 'logos'  .DS. $clube->logo_pj)):
							
								$image = $resize->resize(JPATH_CDN .DS. 'images' .DS. 'logos'  .DS. $clube->logo_pj, 70, 50,  '../cache/' . $clube->logo_pj);
								
								/*
								<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar pull-up" style="width:auto;" aria-label="<?php echo $clube->name . '" data-bs-original-title="<?php echo $clube->name . '">
									<img style="border: 1px solid #CCC" src="<?php echo $resize->resize(JPATH_CDN .DS. 'images' .DS. 'logos'  .DS. $clube->logo_pj, 180, 100, 'cache/' . $clube->logo_pj, 'manterProporcao') . '" alt="<?php echo $clube->name . '" alt="<?php echo $clube->name . '" class="rounded">
								</li>*/
							else:

									$name = explode(' ', trim($clube->name));
									$sigla = substr($name[0], 0, 1) . substr(end($name), 0, 1);
							/*
								<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-md pull-up" style="width:52px;" aria-label="<?php echo $clube->name . '" data-bs-original-title="<?php echo $clube->name . '">
									<span class="avatar-initial rounded bg-label-danger"><?php echo strtoupper($sigla);. '</span>
								</li>
								*/
							endif;



							
						$this->_htmlResponse .= '<div class="col-md-3 mb-1 mx-0">';
							$this->_htmlResponse .= '<div class="form-check custom-option custom-option-image custom-option-image-radio" >';
								$this->_htmlResponse .= '<label class="form-check-label custom-option-content" for="id_local' . $i .'" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"  aria-label="" data-bs-original-title="' . $clube->name . '">';
									$this->_htmlResponse .= '<span class="custom-option-body">';
										$this->_htmlResponse .= '<img src="'.$image.'" alt="'.$clube->id_clube.'" class="img-fluid">';
									$this->_htmlResponse .= '</span>';
									
								$this->_htmlResponse .= '<input name="id_local" class="form-check-input" type="radio" value="'.$clube->id_clube.'" id="id_local' . $i .'">';
								$this->_htmlResponse .= '</label>';
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>';


						/*
						$this->_htmlResponse .= '<div class="col-md mb-md-0 mb-2">';
							$this->_htmlResponse .= '<div class="form-check custom-option custom-option-icon">';
								$this->_htmlResponse .= '<label class="form-check-label custom-option-content" for="customRadioSvg1">';
									$this->_htmlResponse .= '<span class="custom-option-body">';
										$this->_htmlResponse .= '<img src="../../assets/img/icons/unicons/paypal.png" class="w-px-40 mb-2" alt="paypal">';
										$this->_htmlResponse .= '<span class="custom-option-title"> Design </span>';
										$this->_htmlResponse .= '<small>Cake sugar plum fruitcake I love sweet roll jelly-o.</small>';
									$this->_htmlResponse .= '</span>';
									$this->_htmlResponse .= '<input name="customRadioSvg" class="form-check-input" type="radio" value="" id="customRadioSvg1" checked="">';
								$this->_htmlResponse .= '</label>';
							$this->_htmlResponse .= '</div>';
						$this->_htmlResponse .= '</div>';
						*/
						endforeach;
					endif;
					$this->_htmlResponse .= '</div>';
					$this->_htmlResponse .= '<div class="col-12 d-flex justify-content-between mt-4">';
						$this->_htmlResponse .= '<button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>';
						$this->_htmlResponse .= '<button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>';
					$this->_htmlResponse .= '</div>';
				$this->_htmlResponse .= '</div>';



				//$method_event['amount_method'] = '1';

				//$method_event['name_method'] = 'teste';
				//$i = '0';

				$myReserve = array();

				$methods_event[0]['amount_method'] = 1;
				$methods_event[0]['name_method'] = 'Trap 100';
				
				$methods_event[1]['amount_method'] = 2;
				$methods_event[1]['name_method'] = 'Trap 200';

				$methods_event[2]['amount_method'] = 3;
				$methods_event[2]['name_method'] = 'Trap 200 + Liga';


				$test = new stdClass();
				$test->method['id_method'] = '0';
				$myReserve[1] = $test;

				$this->_htmlResponse .= '<!-- reserve -->';
				$this->_htmlResponse .= '<div id="reserve" class="content pt-3 pt-lg-0">';
					$this->_htmlResponse .= '<div class="mb-3">';


					 if(count($methods_event)>1):
						$this->_htmlResponse .= '<div class="row justify-content-center mt-3">';
						foreach($methods_event as $i => $method_event):
							$this->_htmlResponse .= '<div class="col-auto col-button text-center">';
								$this->_htmlResponse .= '<label class="custom-radio-option" for="method' .  $i. '">';
									$this->_htmlResponse .= '<input type="radio" class="required" name="method" id="method' .$i. '" data-amount="' . ($method_event['amount_method'] + 0) . '"  value="' .  $i. '" ' .  ($myReserve[1]->method['id_method'] == $i ? 'checked' : ''). ' />';
									$this->_htmlResponse .= '<span class="radio-btn-option">';
										$this->_htmlResponse .= '<i class="fas fa-check"></i>';
										$this->_htmlResponse .= '<div class="hobbies-icon-option">';
											$this->_htmlResponse .= '<h5>' .  $method_event['name_method'] . '</h5>';
										$this->_htmlResponse .= '</div>';
									$this->_htmlResponse .= '</span>';
								$this->_htmlResponse .= '</label>';
							$this->_htmlResponse .= '</div>';
						endforeach; 
						$this->_htmlResponse .= '</div>';
						else:
							$this->_htmlResponse .= '<input type="hidden" name="radio-option" checked name="method" value="' .  ($methods_event[0]['amount_method'] + 0). '"/>';
						endif;
						$this->_htmlResponse .= '<!-- Fim Escolha Tipo -->';
			


					$this->_htmlResponse .= '</div>';
					$this->_htmlResponse .= '<div class="col-12 d-flex justify-content-between mt-4">';
						$this->_htmlResponse .= '<button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>';
						$this->_htmlResponse .= '<button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>';
					$this->_htmlResponse .= '</div>';
				$this->_htmlResponse .= '</div>';





			
			/*
						$this->_htmlResponse .= '<input type="hidden" id="max_methods" value="' .  $this->item->max_methods. '" />';
						$this->_htmlResponse .= '<input type="hidden" id="limit_day" value="' .  $this->item->limit_day_event. '" />';
						if($this->item->max_methods>1):
							$this->_htmlResponse .= '<h4 class="mt-3 mb-1 text-center">Reservas</h1> ';
							$this->_htmlResponse .= '<ul id="tabAmounts" role="tablist" class="nav nav-pills flex-row text-center border-0 rounded-nav justify-content-center">';
							for($i=1; $i <= $this->item->max_methods; $i++):
								$this->_htmlResponse .= '<li class="nav-item ' .  ($i>1 && $this->myReserve[1]->method['amount_method'] < $i ? 'disabled' : ''). '">';
								$this->_htmlResponse .= '<a id="reserve-tab' . $i . '" data-toggle="tab" href="#reserve' . $i . '" data-numero="' .  $i. '" role="tab" aria-controls="reserve' .  $i. '" aria-selected="true" class="nav-link border-0 text-uppercase font-weight-bold ' .  ($i == 1 ? 'active' : ''). ' ' .  ($i>1 && $this->myReserve[1]->method['amount_method'] < $i ? 'disabled' : ''). '" >Reserva<br/>' .  $i. '</a>';
								$this->_htmlResponse .= '</li>';
							endfor;
							$this->_htmlResponse .= '</ul>';
						endif;
			
			
					$this->_htmlResponse .= '<div id="myTabContent" class="tab-content bg-reserve">';
			
						for($x=1; $x <= $this->item->max_methods; $x++):
							$this->_htmlResponse .= '<div id="reserve' . $x. '" role="tabpanel" aria-labelledby="reserve' . $x. '-tab" class="tab-pane fade px-4 py-5 ' . ( $x == 1 ? 'show active' : ''). '">';
							$this->_htmlResponse .= '<input type="hidden" name="number" value="' .  $x. '" />';
							
							$this->_htmlResponse .= '<!-- Datas -->';
							if(count($this->datasAg)>1):
								$this->_htmlResponse .= '<div class="row justify-content-center text-center">';
								$this->_htmlResponse .= '<section class="fieldset mt-3">';
								$this->_htmlResponse .= '<h5 class="title text-center title-options">Escolha a Data</h5>';
								$this->_htmlResponse .= '<div class="d-flex flex-wrap justify-content-center" id="date_' .  $x. '">';
									foreach($this->datasAg as $i => $dataAg):
										$this->_htmlResponse .= '<div class="col-auto col-button text-center ">';
										$this->_htmlResponse .= '<label class="custom-radio-date" for="date' .  ($i + ($x*count($this->datasAg)-count($this->datasAg))). '" >';
										$this->_htmlResponse .= '<input type="radio" ' . (isset($this->intervals[$x-1]->days) && $this->intervals[$x-1]->days > $i  ? 'disabled="disabled"' : '') . ' class="required agenda-data" data-numero="' . $x . '" name="date[' . $x . ']" id="date' .  ($i + ($x*count($this->datasAg)-count($this->datasAg))) . '" value="' . $dataAg->value . '" ' . ($this->myReserve[$x]->date == $dataAg->value ? 'checked' : '') . ' ' .  ($dataAg->remaining <= 0 ? 'disabled' : ''). ' />';
										$this->_htmlResponse .= '<span class="radio-btn-date" >';
										$this->_htmlResponse .= '<i class="fas fa-check"></i>';
										$this->_htmlResponse .= '<div class="hobbies-icon-date">';
										$this->_htmlResponse .= '<h3>' .  $dataAg->text . '</h3>';
										$this->_htmlResponse .= '<h5>' .  $dataAg->legend . '</h5>';
										$this->_htmlResponse .= '</div>';
										$this->_htmlResponse .= '</span>';
										$this->_htmlResponse .= '</label>';
										$this->_htmlResponse .= '</div>';
									endforeach;
									$this->_htmlResponse .= '</div>';
									$this->_htmlResponse .= '</section>';
									$this->_htmlResponse .= '</div>';
							else:
								
								$this->_htmlResponse .= '<input type="hidden" name="date[' . $x . ']" value="' .  $this->datasAg[0]->value . '"/>';
							endif;
							$this->_htmlResponse .= '<!-- Fim Datas -->';



							$this->_htmlResponse .= '<!-- Baterias -->';
							if($this->item->drums_event>1):
								$this->_htmlResponse .= '<div class=" row justify-content-center text-center">';
								$this->_htmlResponse .= '<section class="fieldset mt-5">';
								$this->_htmlResponse .= '<h5 class="title text-center title-options">Escolha a Bateria</h5>';
								$this->_htmlResponse .= '<div class="d-flex flex-wrap justify-content-center" id="drums_' . $x . '">';
									if(isset($this->myReserve[$x]->drums)): // if ele já salvou reserva ou datas for igaul a 1. ' 
									foreach($this->bateriasAg[$x] as $i => $bateriaAg):
										$this->_htmlResponse .= '<div class="col-auto col-button text-center">';
										$this->_htmlResponse .= '<label class="custom-radio" for="drums' .  ($i + ($x*$this->item->drums_event-$this->item->drums_event)) . '">';
										$this->_htmlResponse .= '<input type="radio" ' . (isset($this->intervals[$x-1]->drums) && $this->intervals[$x-1]->drums > $bateriaAg->value ? 'disabled="disabled"' : '') . '  class="required agenda-bateria" data-numero="' . $x . '" name="drums[' . $x . ']" id="drums' . ( $i + ($x*$this->item->drums_event-$this->item->drums_event)) . '" value="' . $bateriaAg->value . '" ' . ($this->myReserve[$x]->drums == $bateriaAg->value ? 'checked' : '') . ' ' . ($bateriaAg->remaining <= 0 ? 'disabled' : '') . ' />';
										$this->_htmlResponse .= '<span class="radio-btn">';
										$this->_htmlResponse .= '<i class="fas fa-check"></i>';                
										$this->_htmlResponse .= '<div class="hobbies-icon">';
										$this->_htmlResponse .= '<h3>' . $bateriaAg->text . '</h3>';
										$this->_htmlResponse .= '<h5>' . $bateriaAg->legend . '</h5>';
														if(!is_null($this->item->time_drums_event) && !is_null($this->item->duration_drums_event)):
															$this->_htmlResponse .= '<h6>' .  JHtml::date(JFactory::getDate($this->item->time_drums_event . ($i>1 ? ' + ' . (($i-1)*$this->item->duration_drums_event) . 'min' : ''), $siteOffset)->toISO8601(true), 'DATE_FORMAT_TIME'). '</h6>';
														endif;
														$this->_htmlResponse .= '</div>';
														$this->_htmlResponse .= '</span>';
														$this->_htmlResponse .= '</label>';
														$this->_htmlResponse .= '</div>';
									endforeach;
									else:
										$this->_htmlResponse .= 'Aguardando Definir Etapas Anteriores.';
									endif;
									$this->_htmlResponse .= '</div>';
									$this->_htmlResponse .= '</section>';
								$this->_htmlResponse .= '</div>';
							else:  
								$this->_htmlResponse .= '<input type="hidden" name="drums[' . $x . ']" value="1"/>';
							endif;
							$this->_htmlResponse .= '<!-- Fim Baterias -->';
			
							$this->_htmlResponse .= '<!-- Squads -->';
							if($this->item->squad_event>1):
								$this->_htmlResponse .= '<div class=" row justify-content-center text-center">';
								$this->_htmlResponse .= '<section class="fieldset mt-5">';
								$this->_htmlResponse .= '<h5 class="title text-center title-options">Escolha ' . ($this->item->type_event == 2 ? 'o Squad ' : 'a Turma ') . '</h5>';
								$this->_htmlResponse .= '<div class="d-flex flex-wrap justify-content-center" id="squad_' . $x . '">';
									if(isset($this->myReserve[$x]->squad)): // if ele já salvou reserva ou datas for igaul a 1 
									foreach($this->turmasAg[$x] as $i => $turmaAg):
										$this->_htmlResponse .= '<div class="col-auto col-button text-center">';
										$this->_htmlResponse .= '<label class="custom-radio-squad" for="squad' . $i + ($x*$this->item->squad_event-$this->item->squad_event) . '">';
										$this->_htmlResponse .= '<input type="radio" ' . (isset($this->intervals[$x-1]->squad) && $this->intervals[$x-1]->squad > $turmaAg->value && ($this->item->limit_break_event == 1 || $this->item->limit_break_event == 0 && isset($this->myReserve[$x-1]->date) && $this->myReserve[$x-1]->date == $this->myReserve[$x]->date && isset($this->myReserve[$x-1]->drums) && $this->myReserve[$x-1]->drums == $this->myReserve[$x]->drums) ? 'disabled="disabled"' : '') . '  class="required agenda-turma" data-numero="' . $x . '" name="squad[' . $x . ']" id="squad' . ($i + ($x*$this->item->squad_event-$this->item->squad_event)) . '" value="' . $turmaAg->value . '"  ' . ($this->myReserve[$x]->squad == $turmaAg->value ? 'checked' : '') . ' ' . ($turmaAg->remaining <= 0 ? 'disabled' : '') . ' />';
												$this->_htmlResponse .= '<span class="radio-btn-squad">';
												$this->_htmlResponse .= '<i class="fas fa-check"></i>';
													$this->_htmlResponse .= '<div class="hobbies-icon-squad">';
													$this->_htmlResponse .= '<h3>' . $turmaAg->text . '</h3>';
													$this->_htmlResponse .= '<h5>' . $turmaAg->legend . '</h5>';
														if(!is_null($this->item->time_squad_event) && !is_null($this->item->duration_squad_event)):
															$this->_htmlResponse .= '<h6>' .  JHtml::date(JFactory::getDate($this->item->time_squad_event . ($i>0 ? ' + ' . (($i)*$this->item->duration_squad_event) . 'min' : ''), $siteOffset)->toISO8601(true), 'DATE_FORMAT_TIME'). '</h6>';
														endif;
														$this->_htmlResponse .= '</div>';
														$this->_htmlResponse .= '</span>';
														$this->_htmlResponse .= '</label>';  
														$this->_htmlResponse .= '</div>';
									endforeach;
									else:
										$this->_htmlResponse .= 'Aguardando Definir Etapas Anteriores.';
									endif;
									$this->_htmlResponse .= '</div>';
									$this->_htmlResponse .= '</section>';
									$this->_htmlResponse .= '</div>';
							else:
								$this->_htmlResponse .= '<input type="hidden" name="squad[' .  $x. ']" value="1"/>';
							endif;
							$this->_htmlResponse .= '<!-- Fim Squads -->';
			
							$this->_htmlResponse .= '<!-- Posições -->';
							if($this->item->position_event>1):
								$this->_htmlResponse .= '<div class="row justify-content-center text-center">';
								$this->_htmlResponse .= '<section class="fieldset mt-5">';
								$this->_htmlResponse .= '<h5 class="title text-center title-options">Escolha a Posição</h5>';
								$this->_htmlResponse .= '<div class="d-flex flex-wrap justify-content-center" id="position_' . $x . '">';
									if(isset($this->myReserve[$x]->position)): // if ele já salvou reserva ou datas for igaul a 1  
									foreach($this->postosAg[$x] as $i => $postoAg):
										$this->_htmlResponse .= '<div class="position col-12 text-center mb-3">';
										$this->_htmlResponse .= '<label class="custom-radio-position" for="position' . ($i + ($x*$this->item->position_event-$this->item->position_event)) . '">';
										$this->_htmlResponse .= '<input type="radio" class="required agenda-posto' . ($this->myReserve[$x]->position == $postoAg->value ? ' my-reserve' : '') . ' " data-reserved="0" data-id="' . $this->myReserve[$x]->date . '_' . $this->myReserve[$x]->drums . '_' . $this->myReserve[$x]->squad . '_' . ($i+1) . '" data-numero="' . $x . '" name="position[' . $x . ']" id="position' . ($i + ($x*$this->item->position_event-$this->item->position_event)) . '" value="' . $postoAg->value . '"  ' . ($this->myReserve[$x]->position == $postoAg->value ? 'disabled checked' : ( !empty($postoAg->legend) && $postoAg->legend !='Disponível' ? 'disabled' : '')) . ' />';
										$this->_htmlResponse .= '<span class="radio-btn-position">';
										$this->_htmlResponse .= '<div class="hobbies-icon-position">';
										$this->_htmlResponse .= '<h3>' . $postoAg->text . '</h3>';
										$this->_htmlResponse .= '<span class="name">' . $postoAg->legend . '</hspan>';
										$this->_htmlResponse .= '</div>';
										$this->_htmlResponse .= '</span>';
										$this->_htmlResponse .= '</label>';  
										$this->_htmlResponse .= '</div>';
									endforeach;
									else:
										$this->_htmlResponse .= 'Aguardando Definir Etapas Anteriores.';
									endif;
									$this->_htmlResponse .= '</div>';
									$this->_htmlResponse .= '</section>';
									$this->_htmlResponse .= '</div>';
							else:
								$this->_htmlResponse .= '<input type="hidden" name="position[' . $x . ']" value="1"/>';
							endif;
							$this->_htmlResponse .= '</div>';
						endfor;
						$this->_htmlResponse .= '</div>';

*/













		  $this->_htmlResponse .= '<!-- Database -->';
		  $this->_htmlResponse .= '<div id="database" class="content pt-3 pt-lg-0">';
		  $this->_htmlResponse .= '<div class="mb-3">';
		  $this->_htmlResponse .= '<input type="text" class="form-control form-control-lg" id="exampleInputEmail2" placeholder="Database Name">';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<h5>Select Database Engine</h5>';
		  $this->_htmlResponse .= '<ul class="p-0 m-0">';
		  $this->_htmlResponse .= '<li class="d-flex align-items-start mb-3">';
		  $this->_htmlResponse .= '<div class="badge bg-label-danger p-2 me-3 rounded"><i class="bx bxl-firebase bx-sm"></i></div>';
		  $this->_htmlResponse .= '<div class="d-flex justify-content-between w-100 flex-wrap gap-2">';
		  $this->_htmlResponse .= '<div class="me-2">';
		  $this->_htmlResponse .= '<h6 class="mb-0">Firebase</h6>';
		  $this->_htmlResponse .= '<small class="text-muted">Cloud Firestone</small>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="d-flex align-items-center">';
		  $this->_htmlResponse .= '<div class="form-check form-check-inline">';
		  $this->_htmlResponse .= '<input name="databaseradio" class="form-check-input required" type="radio" value="">';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</li>';
		  $this->_htmlResponse .= '<li class="d-flex align-items-start mb-3">';
		  $this->_htmlResponse .= '<div class="badge bg-label-warning p-2 me-3 rounded"><i class="bx bxl-amazon bx-sm"></i></div>';
		  $this->_htmlResponse .= '<div class="d-flex justify-content-between w-100 flex-wrap gap-2">';
		  $this->_htmlResponse .= '<div class="me-2">';
		  $this->_htmlResponse .= '<h6 class="mb-0">AWS</h6>';
		  $this->_htmlResponse .= '<small class="text-muted">Amazon Fast NoSQL Database</small>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="d-flex align-items-center">';
		  $this->_htmlResponse .= '<div class="form-check form-check-inline">';
		  $this->_htmlResponse .= '<input name="databaseradio" class="form-check-input required" type="radio" value="" checked="">';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</li>';



		  $this->_htmlResponse .= '<li class="d-flex align-items-start">';
		  $this->_htmlResponse .= '<div class="badge bg-label-info p-2 me-3 rounded"><i class="bx bx-data bx-sm"></i></div>';
		  $this->_htmlResponse .= '<div class="d-flex justify-content-between w-100 flex-wrap gap-2">';
		  $this->_htmlResponse .= '<div class="me-2">';
		  $this->_htmlResponse .= '<h6 class="mb-0">MySQL</h6>';
		  $this->_htmlResponse .= '<small class="text-muted">Basic MySQL database</small>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="d-flex align-items-center">';
		  $this->_htmlResponse .= '<div class="form-check form-check-inline">';
		  $this->_htmlResponse .= '<input name="databaseradio" class="form-check-input required" type="radio" value="">';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</li>';
		  $this->_htmlResponse .= '</ul>';
		  $this->_htmlResponse .= '<div class="col-12 d-flex justify-content-between mt-4">';
		  $this->_htmlResponse .= '<button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>';
		  $this->_htmlResponse .= '<button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<!-- billing -->';
		  $this->_htmlResponse .= '<div id="billing" class="content">';
		  $this->_htmlResponse .= '<div id="AppNewCCForm" class="row g-3 pt-3 pt-lg-0 mb-5" onsubmit="return false">';
		  $this->_htmlResponse .= '<div class="col-12">';
		  $this->_htmlResponse .= '<div class="input-group input-group-merge">';
		  $this->_htmlResponse .= '<input class="form-control app-credit-card-mask" type="text" placeholder="1356 3215 6548 7898" aria-describedby="modalAppAddCard">';
		  $this->_htmlResponse .= '<span class="input-group-text cursor-pointer p-1" id="modalAppAddCard"><span class="app-card-type"></span></span>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="col-12 col-md-6">';
		  $this->_htmlResponse .= '<input type="text" class="form-control" placeholder="John Doe">';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="col-6 col-md-3">';
		  $this->_htmlResponse .= '<input type="text" class="form-control app-expiry-date-mask" placeholder="MM/YY">';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="col-6 col-md-3">';
		  $this->_htmlResponse .= '<div class="input-group input-group-merge">';
		  $this->_htmlResponse .= '<input type="text" id="modalAppAddCardCvv" class="form-control app-cvv-code-mask" maxlength="3" placeholder="654">';
		  $this->_htmlResponse .= '<span class="input-group-text cursor-pointer" id="modalAppAddCardCvv2"><i class="text-muted bx bx-help-circle" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Card Verification Value" data-bs-original-title="Card Verification Value"></i></span>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="col-12">';
		  $this->_htmlResponse .= '<label class="switch">';
		  $this->_htmlResponse .= '<input type="checkbox" class="switch-input" checked="">';
		  $this->_htmlResponse .= '<span class="switch-toggle-slider">';
		  $this->_htmlResponse .= '<span class="switch-on"></span>';
		  $this->_htmlResponse .= '<span class="switch-off"></span>';
		  $this->_htmlResponse .= '</span>';
		  $this->_htmlResponse .= '<span class="switch-label">Save card for future billing?</span>';
		  $this->_htmlResponse .= '</label>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<div class="col-12 d-flex justify-content-between mt-4">';
		  $this->_htmlResponse .= '<button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>';
		  $this->_htmlResponse .= '<button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-right-arrow-alt bx-xs"></i></button>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '<!-- submit -->';
		  $this->_htmlResponse .= '<div id="submit" class="content text-center pt-3 pt-lg-0">';
		  $this->_htmlResponse .= '<h5 class="mb-2 mt-3">Submit</h5>';
		  $this->_htmlResponse .= '<p>Submit to kick start your project.</p>';
		  $this->_htmlResponse .= '<!-- image -->';
		  $this->_htmlResponse .= '<img src="../../assets/img/illustrations/man-with-laptop-light.png" alt="Create App img" width="200" class="img-fluid" data-app-light-img="illustrations/man-with-laptop-light.png" data-app-dark-img="illustrations/man-with-laptop-dark.png">';
		  $this->_htmlResponse .= '<div class="col-12 d-flex justify-content-between mt-4 pt-2">';
		  $this->_htmlResponse .= '<button class="btn btn-label-secondary btn-prev"> <i class="bx bx-left-arrow-alt bx-xs me-sm-1 me-0"></i> <span class="align-middle d-sm-inline-block d-none">Previous</span> </button>';
		  $this->_htmlResponse .= '<button class="btn btn-success btn-submit"> <span class="align-middle d-sm-inline-block d-none">Submit</span> <i class="bx bx-check bx-xs ms-sm-1 ms-0"></i> </button>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</form>';
          $this->_htmlResponse .= '</div>';
		  $this->_htmlResponse .= '</div>';



		return true;
	}

	function getMyInfo()
	{

		if(!$this->_user->get('guest') == 1){
			$query = $this->_db->getQuery(true);
			$query->select('*' );	
			$query->from( $this->_db->quoteName('#__users') );
			$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_associado') . 'USING('. $this->_db->quoteName('id_user').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_conveniado') . 'USING('. $this->_db->quoteName('id_user').')' );
			$query->where( $this->_db->quoteName('id') .  '=' . $this->_db->quote( $this->_user->get('id') ) );		
			$this->_db->setQuery($query);
			return $this->_db->loadObject();
		}

	}

	function getItem()
	{
		if (empty($this->_data)) {

			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName(array( 'id_prova',
														 'name_prova',
														 'id_campeonato',
														 'id_etapa',
														 //'id_clube',
														 'nr_etapa_prova',
														 'rf_inscricao_prova',
														 'inscricao_agenda_prova',
														 'inscricao_bateria_prova',
														 'intervalo_bateria_prova',
														 'inscricao_turma_prova',
														 'intervalo_turma_prova',
														 'inscricao_posto_prova',
														 'intervalo_posto_prova',
														 'equipe_prova',
														 'text_agenda_prova'
														)));
			$query->select( 'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS name_campeonato' );
			$query->from( $this->_db->quoteName('#__ranking_prova') );			
			$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato').')' );	
			$query->where( $this->_db->quoteName('id_etapa') . '=' . $this->_db->quote( $this->_value['id_etapa'] ));	
			$query->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->quote( $this->_value['id_prova'] ));	
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
		return $this->_data;









			$myInfo = $this->getMyinfo();
		
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName(array( 'id_prova',
														 'name_prova',
														 'id_campeonato',
														 'id_etapa',
														 'id_clube',
														 'nr_etapa_prova',
														 'rf_inscricao_prova',
														 'inscricao_agenda_prova',
														 'inscricao_bateria_prova',
														 'intervalo_bateria_prova',
														 'inscricao_turma_prova',
														 'intervalo_turma_prova',
														 'inscricao_posto_prova',
														 'intervalo_posto_prova',
														 'equipe_prova',
														 'text_agenda_prova'
														)));
	
			$query->select('inscricao.id_inscricao AS id_inscricao');
			$query->select('inscricao.id_inscricao_etapa AS id_inscricao_etapa');
			$query->select('inscricao.id_genero AS id_genero');
			$query->select('inscricao.name_genero AS name_genero');
			$query->select('inscricao.id_categoria AS id_categoria');
			$query->select('inscricao.name_categoria AS name_categoria');
			$query->select('inscricao.id_classe AS id_classe');
			$query->select('inscricao.name_classe AS name_classe');
			$query->select('inscricao.id_equipe AS id_equipe');
			$query->select('inscricao.name_equipe AS name_equipe');
			$query->select('inscricao.id_estado AS id_estado');
			$query->select('inscricao.name_estado AS name_estado');
			$query->select('inscricao.params_inscricao_etapa AS params_inscricao_etapa');
			$query->select('inscricao.quantidade_inscricao_etapa AS quantidade_inscricao_etapa');
			
			$query->select('resultado.resultado_inscricao AS resultado_inscricao');
			$query->select('resultado.resultado_inscricao_etapa AS resultado_inscricao_etapa');
	
			$query->from( $this->_db->quoteName('#__users') );
			$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova_clube_map') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_clube').')' );	
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa_clube_map') . 'USING('. $this->_db->quoteName('id_clube').')' );	
			$query->from( $this->_db->quoteName('#__ranking_etapa') );
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_etapa').')' );	
			$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova').','. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
			
			
			$queryInscricao = $this->_db->getQuery(true);
			$queryInscricao->select( $this->_db->quoteName(array( 'id_prova',
																  'id_genero',
																  'name_genero',
																  'id_categoria',
																  'name_categoria',
																  'id_classe',
																  'name_classe',
																  'id_local',
																  'id_estado',
																  'name_estado',
																  'params_inscricao_etapa',
																  'quantidade_inscricao_etapa',
																  'id_inscricao_etapa',
																  '#__ranking_inscricao.id_inscricao',
																)));
	
			$queryInscricao->select('IF( ISNULL(AddEquipe.id_addequipe), IF( Clube.id = 7617, \'AVULSO\', Clube.name), name_addequipe) AS name_equipe');
			$queryInscricao->select('IF( ISNULL(AddEquipe.id_addequipe), id_equipe, CONCAT(\'A\', id_addequipe)) AS id_equipe');										
			$queryInscricao->from( $this->_db->quoteName('#__ranking_inscricao') );
			$queryInscricao->innerJoin( $this->_db->quoteName('#__ranking_genero') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_prova').')' );
			$queryInscricao->innerJoin( $this->_db->quoteName('#__ranking_categoria') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').')' );
			$queryInscricao->innerJoin( $this->_db->quoteName('#__ranking_classe') . 'USING('. $this->_db->quoteName('id_categoria') .','. $this->_db->quoteName('id_classe').')' );
			$queryInscricao->leftJoin( $this->_db->quoteName('#__intranet_estado') . ' USING( ' . $this->_db->quoteName('id_estado') . ')' );		
			$queryInscricao->leftJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'ON('
																						   . $this->_db->quoteName('#__ranking_inscricao.id_inscricao') .'='. $this->_db->quoteName('#__ranking_inscricao_etapa.id_inscricao')
																						   . ' AND ' 
																						   . $this->_db->quoteName('#__ranking_inscricao_etapa.id_etapa') .'='. $this->_db->quote( $this->_id[1]  )
																						   . ')' );
																						   
			$queryInscricao->leftJoin( $this->_db->quoteName('#__users') . ' AS Clube ON('. $this->_db->quoteName('#__ranking_inscricao.id_equipe') .'='. $this->_db->quoteName('Clube.id'). ')' );
			$queryInscricao->leftJoin( $this->_db->quoteName('#__intranet_addequipe') . ' AS AddEquipe USING('. $this->_db->quoteName('id_addequipe'). ')' );
			$queryInscricao->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $this->_user->get('id') ));
			
			$query->leftJoin('(' . $queryInscricao . ') inscricao USING( ' . $this->_db->quoteName('id_prova') . ')' );		
				
				
			$queryResultado = $this->_db->getQuery(true);
			$queryResultado->select( $this->_db->quoteName(array( 'id_prova',
																  '#__ranking_inscricao.id_inscricao',
																)));
			$queryResultado->select( $this->_db->quoteName('A.id_resultado') . ' AS resultado_inscricao' );												
			$queryResultado->select( $this->_db->quoteName('B.id_resultado') . ' AS resultado_inscricao_etapa');												
																
														
			$queryResultado->from( $this->_db->quoteName('#__ranking_inscricao') );
			$queryResultado->innerJoin( $this->_db->quoteName('#__ranking_resultado') . ' A USING('. $this->_db->quoteName('id_inscricao').')' );
			
			$queryResultado->leftJoin( $this->_db->quoteName('#__ranking_resultado')  . ' B ON('
																					  . $this->_db->quoteName('#__ranking_inscricao.id_inscricao') .'='. $this->_db->quoteName('B.id_inscricao')
																					  . ' AND ' 
																					  . $this->_db->quoteName('B.id_etapa') .'='. $this->_db->quote( $this->_id[1]  )
																					  . ')' );
																					//  echo $this->_id[1];
			
			$queryResultado->where($this->_db->quoteName('#__ranking_inscricao.id_user') . ' = ' . $this->_db->quote( $this->_user->get('id') ));
			
			$query->leftJoin('(' . $queryResultado . ') resultado USING( ' . $this->_db->quoteName('id_prova') . ')' );		
			
			$query->where( $this->_db->quoteName('status_campeonato') . '=' . $this->_db->quote( '1' ) );
			$query->where( $this->_db->quoteName('finalized_campeonato') . '=' . $this->_db->quote( '0' ) );
	
			$query->where( $this->_db->quoteName('id_etapa') . '=' . $this->_db->quote( $this->_value['id_etapa'] ));	
			$query->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->quote( $this->_value['id_prova'] ));	
	
			if(!isset($myInfo->validate_associado ) || $myInfo->validate_associado <= JFactory::getDate('now', $this->_siteOffset)->format('Y-m-d',true) )	:
				$query->where( $this->_db->quoteName('convenio_prova') . ' = ' . $this->_db->quote('1') );
			elseif($myInfo->compressed_air_pf == 1):
				$query->where( $this->_db->quoteName('socioar_prova') . ' = ' . $this->_db->quote('1') );
			endif;
	
			$query->where( $this->_db->quoteName('#__ranking_etapa.insc_beg_etapa') . ' <= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(true) ) );
			$query->where( $this->_db->quoteName('#__ranking_etapa.insc_end_etapa') . ' >= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(true) ) );
				
			$query->group('id_prova');
			
			$query->order( $this->_db->quoteName('id_inscricao_etapa') . '  DESC');
			$query->order( $this->_db->quoteName('id_inscricao') . '  DESC');
			$query->order( $this->_db->quoteName('name_prova') . '  ASC');
			
			
			
			$this->_db->setQuery($query);
			
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		//}
		//return $this->_data;














/*





			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName(array( 'name_etapa',
														 'id_etapa',
														 'id_campeonato',
														 'id_prova',
														 'name_prova',
														)));
			$query->select( 'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS name_campeonato' );
			$query->from( $this->_db->quoteName('#__ranking_prova') );			
			$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato').')' );	
			$query->where( $this->_db->quoteName('id_etapa') . '=' . $this->_db->quote( $this->_value['id_etapa'] ));	
			$query->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->quote( $this->_value['id_prova'] ));	
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
		return $this->_data;*/
	}


	function getEquipes($id_campeonato = null)
	{
		
		
		$queryAddEquipe = $this->_db->getQuery(true);
		$queryAddEquipe->select( 'CONCAT(\'A\', ' . $this->_db->quoteName('#__intranet_addequipe.id_addequipe') . ') AS value, ' 
								. $this->_db->quoteName('#__intranet_addequipe.name_addequipe') . ' AS text' );	
		$queryAddEquipe->from( $this->_db->quoteName('#__intranet_addequipe') );
		$queryAddEquipe->innerJoin( $this->_db->quoteName('#__intranet_addequipe_map') . 'USING('. $this->_db->quoteName('id_addequipe').')' );
		$queryAddEquipe->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
		$queryAddEquipe->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_campeonato'). ')' );
		//$queryAddEquipe->innerJoin( $this->_db->quoteName('#__ranking_inscricao') . 'USING('. $this->_db->quoteName('id_campeonato'). ')' );
		//$queryAddEquipe->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa') . 'USING('. $this->_db->quoteName('id_inscricao'). ')' );
		
		$queryAddEquipe->where( $this->_db->quoteName('status_addequipe') . '=' . $this->_db->quote('1'));	
		$queryAddEquipe->where($this->_db->quoteName('id_campeonato') . ' = ' . $this->_db->quote( $id_campeonato ));		
		$queryAddEquipe->where($this->_db->quoteName('id_etapa') . ' = ' . $this->_db->quote( $this->_value['id_etapa'] ));	

		$queryAddEquipe->group($this->_db->quoteName('#__intranet_addequipe.id_addequipe'));
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id') . ' AS value');	
		$query->select('IF('.$this->_db->quoteName('id').'= 7617, \'AVULSO\', '.$this->_db->quoteName('name'). ') AS text');
		$query->from($this->_db->quoteName('#__intranet_associado'));
		$query->innerJoin($this->_db->quoteName('#__users').' U ON ('.$this->_db->quoteName('U.id').'='.$this->_db->quoteName('id_user').')');
		$query->innerJoin($this->_db->quoteName('#__intranet_pj') . ' AS Clube USING ('.$this->_db->quoteName('id_user').')');
		$query->where( $this->_db->quoteName('status_associado') . '=' . $this->_db->quote('1'));
		$query->where( $this->_db->quoteName('validate_associado') . '>=' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->Format('Y-m-d', true)));

		
		$query->unionAll($queryAddEquipe);
		$query->order( $this->_db->quoteName('text') );
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList(); 
		
	}



	function getParamProva( $id_prova = null, $params_inscricao_etapa = null)
	{
		if($id_prova)
		{
			$options = array();
			$options['id_prova'] = $id_prova;
			$options['params_inscricao_etapa'] = $params_inscricao_etapa;

			if(!$this->_class_modalidade)
				$this->_class_modalidade = $this->getClassModalidade();	
							
			if($this->_class_modalidade)
				return $this->_class_modalidade->getParamProva( $options );
				
		}	
	}
	
	function getAdditionalPrint()
	{
		if((boolean) $inscricao = $this->getInscricaoPrint())
		{
			$options = array();
			$options['inscricao'] = $inscricao;
			$options['agendamento'] = $this->getAgendamentosPrint();

			if(!$this->_class_modalidade)
				$this->_class_modalidade = $this->getClassModalidade();	
							
			if($this->_class_modalidade)
				return $this->_class_modalidade->getAdditionalPrint( $options );
				
		}	
	}
	
	function getAdditionalAgenda()
	{
		if(!$this->_class_modalidade)
			$this->_class_modalidade = $this->getClassModalidade();	
						
		if($this->_class_modalidade)
			return $this->_class_modalidade->getAdditionalAgenda();
	}
	

	function getGenCatClass( $id_prova = null )
	{
		if($id_prova)
		{
			$options = array();
			$options['id_prova'] = $id_prova;
			$options['id_user'] = $this->_user->get('id');

			if(!$this->_class_modalidade)
				$this->_class_modalidade = $this->getClassModalidade();	
							
			if($this->_class_modalidade)
				return $this->_class_modalidade->getGenCatClass( $options );
				
		}	
		return null;
	}
	
	function getGenero( $id_prova = null)
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName('id_genero') . ' AS value, ' . 
						$this->_db->quoteName('name_genero') . ' AS text');
		$query->from( $this->_db->quoteName('#__ranking_genero') );
		$query->where($this->_db->quoteName('id_prova') . ' = ' . $this->_db->quote($id_prova));
		$query->where($this->_db->quoteName('status_genero') . ' = ' . $this->_db->quote('1'));
		$query->order($this->_db->quoteName('ordering'));
		$this->_db->setQuery($query);
		return  $this->_db->loadObjectList();
	}
	
	function getCategoria( $id_genero = null)
	{
		if($id_genero)
		{			
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id_categoria') . ' AS value, ' . 
							$this->_db->quoteName('name_categoria') . ' AS text');
			$query->from( $this->_db->quoteName('#__ranking_categoria') );
			$query->where($this->_db->quoteName('id_genero') . ' = ' . $this->_db->quote($id_genero));
			$query->where($this->_db->quoteName('status_categoria') . ' = ' . $this->_db->quote('1'));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			
			return  $this->_db->loadObjectList();
		}
	}
	
	function getClasse( $id_categoria = null)
	{
		
		if($id_categoria)
		{
			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName('id_classe') . ' AS value, ' . 
							$this->_db->quoteName('name_classe') . ' AS text');
			$query->from( $this->_db->quoteName('#__ranking_classe') );
			$query->where($this->_db->quoteName('id_categoria') . ' = ' . $this->_db->quote($id_categoria));
			$query->where($this->_db->quoteName('status_classe') . ' = ' . $this->_db->quote('1'));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			return  $this->_db->loadObjectList();
		}
	}



	function getClassModalidade()
	{
		if($this->_value['id_prova']) {
			$query	= $this->_db->getQuery(true);
			$query->select($this->_db->quoteName('file_modalidade'));	
			$query->from($this->_db->quoteName('#__ranking_campeonato'));
			$query->innerJoin($this->_db->quoteName('#__ranking_modalidade') . 'USING(' . $this->_db->quoteName('id_modalidade') . ')');		
			$query->innerJoin($this->_db->quoteName('#__ranking_prova') . 'USING(' . $this->_db->quoteName('id_campeonato') . ')');
			$query->where($this->_db->quoteName('id_prova') . ' = ' . $this->_db->quote( $this->_value['id_prova'] ));		  
			$this->_db->setQuery($query);
	
	
	
			if( !(boolean) $fileModalidade =  $this->_db->loadObject())
				return false;

			$file =  JPATH_SYSTEM .DS. 'core' .DS. $fileModalidade->file_modalidade;

			if (!file_exists($file))
				return false;

			require_once($file);

			$prefix  = 'TorneiosClasses';
			$type = JFile::getName($fileModalidade->file_modalidade);
			$type = str_replace('.' . JFile::getExt($type), '', $type);
			$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
			$metodClass = $prefix . ucfirst($type);
			
			if (!class_exists($metodClass))
				return false;

			return new $metodClass();

		}	
		return false;
	}

	function getArmas()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id_arma as value,
						CONCAT(numero_arma, \' | \', sigla_especie, \' | \', name_calibre, \' | \', name_marca) as text');	
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_arma') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_especie') . 'USING('. $this->_db->quoteName('id_especie').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_calibre') . 'USING('. $this->_db->quoteName('id_calibre').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_marca') . 'USING('. $this->_db->quoteName('id_marca').')' );
		$query->leftJoin( $this->_db->quoteName('#__ranking_prova_calibre_map') . 'USING('. $this->_db->quoteName('id_calibre').')' );
		$query->where( $this->_db->quoteName('id') .  '=' . $this->_db->quote( $this->_user->get('id') ) );		
		$query->where( $this->_db->quoteName('id_prova') .  '=' . $this->_db->quote( $this->_value['id_prova'] ) );	
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}

	function getClubes()
	{
		$myInfo = $this->getMyinfo();
		//if(empty($this->_config))
		//	$this->_config = $this->getConfigTorneios();
		
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( '#__users.name',
													'id_campeonato',
													'id_etapa',
													'id_clube',
													'logo_pj',
													'name_cidade',
													'sigla_estado'									 
													)));

		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').','. $this->_db->quoteName('id_estado').')' );
		$query->innerJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado').')' );		
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova_clube_map') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_clube').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa_clube_map') . 'USING('. $this->_db->quoteName('id_clube').')' );	
		
		$query->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_etapa').')' );	
		$query->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova').','. $this->_db->quoteName('id_campeonato').')' );
		$query->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
		



		/*
		if($this->_config->type_category_config ==1):
			$query->leftJoin( $this->_db->quoteName('#__categories') . 'ON('. $this->_db->quoteName('#__categories.id'). ' = '. $this->_db->quoteName('#__ranking_profile.category').')' );
		elseif($this->_config->type_category_config ==2):
			$query->select( $this->_db->quoteName('#__k2_categories.image'));
			$query->leftJoin( $this->_db->quoteName('#__k2_categories') . 'ON('. $this->_db->quoteName('#__ranking_profile.category').' = '. $this->_db->quoteName('#__k2_categories.id').')' );
		endif;
		*/
		//$query->where($this->_db->quoteName('id_campeonato') . ' = ' . $this->_db->quote( $this->_id[0] ));		

		$query->where( $this->_db->quoteName('id_etapa') . '=' . $this->_db->quote( $this->_value['id_etapa'] ));	
		$query->where( $this->_db->quoteName('id_prova') . '=' . $this->_db->quote( $this->_value['id_prova'] ));	
		
		/*
		if(!isset($myInfo->validate_associado ) || $myInfo->validate_associado <= JFactory::getDate('now', $this->_siteOffset)->format('Y-m-d',true) )	:
			$query->where( $this->_db->quoteName('convenio_prova') . ' = ' . $this->_db->quote('1') );
		elseif($myInfo->compressed_air_pf == 1):
			$query->where( $this->_db->quoteName('socioar_prova') . ' = ' . $this->_db->quote('1') );
		endif;
		*/
		$query->where( $this->_db->quoteName('#__ranking_etapa.insc_beg_etapa') . ' <= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(true) ) );
		$query->where( $this->_db->quoteName('#__ranking_etapa.insc_end_etapa') . ' >= ' . $this->_db->quote( JFactory::getDate('now', $this->_siteOffset)->toISO8601(true) ) );
			
		$query->group('id_clube');
		
		$query->order( $this->_db->quoteName('name') . '  ASC');
			
		$this->_db->setQuery($query);
		return  $this->_db->loadObjectList();
			
	}

















	function teste()
	{

		$id_estado = JRequest::getVar( 'id_estado', '', 'post' );
		$id_corredor_categoria = JRequest::getVar( 'id_corredor_categoria', '', 'post' );
		if ( !empty( $id_estado ) )
		{
			$query = $this->_db->getQuery(true);		
			$query->select($this->_db->quoteName('id_cidade') .' as value, '. $this->_db->quoteName('name_cidade') . ' as text');
			$query->from($this->_db->quoteName('#__intranet_cidade'));
			$query->where($this->_db->quoteName('status_cidade') . ' = 1');
			$query->where($this->_db->quoteName('id_estado') . ' = '. $this->_db->quote( $id_estado ));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo '<option disabled selected class="default" value="">' . JText::_('- Cidades -') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}

		if ( !empty( $id_corredor_categoria ) )
		{

			$rows = array();
			$arrayTest = array();
			if(!empty($id_corredor_categoria)) {

				$query = $this->_db->getQuery(true);
				$query->select('mes_treino');
				$query->from('#__treino');
				$query->where($this->_db->quoteName('id_corredor_categoria') . '=' . $this->_db->quote($id_corredor_categoria));
				$this->_db->setQuery($query);
				$mesesDisabled = $this->_db->loadColumn();
	
				$mesInicio = JFactory::getDate('now - 2 month', $this->_siteOffset )->toFormat('%Y-%m', true) . '-01';
				for($x = 1; $x <= 4; $x++){
					$disabled = '';
					$value = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%Y-%m-%d', true);
					$text = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%m/%Y', true);
					if(in_array($value, $mesesDisabled)){
						$disabled  = ' disabled="disabled"';
						$text .= ' (Treino Existente)';
					}	
					$rows[] = JHTML::_('select.option', $value, $text, 'value', 'text', $disabled );
				}
			}
			echo '<option disabled selected class="default" value="">' . JText::_('- Mês do Treino -') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}
		
		/*
		if ( !empty( $id_module ) )
		{
			$query = $this->_db->getQuery(true);
			$query->select('slug_module');
			$query->from('#__module');
			$query->where( $this->_db->quoteName('id_module') . '=' . $id_module );
			$this->_db->setQuery($query);
			$module = $this->_db->loadResult();

			if($module)
			{
				$query->clear();
				$query->select('id_module_'.$module.' as value, name_module_'.$module.' as text');
				$query->from('#__module_'.$module);
				$query->order($this->_db->quoteName('name_module_'.$module));
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
			
			echo '<option disabled selected class="default" value="">' . JText::_('- Item do Módulo -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}

		if ( !empty( $id_conteudo_type ) && empty( $id_conteudo ) )
		{
			$query = $this->_db->getQuery(true);
			$query->select('table_conteudo_type');
			$query->from('#__conteudo_type');
			$query->where( $this->_db->quoteName('id_conteudo_type') . '=' . $id_conteudo_type );
			$this->_db->setQuery($query);
			$table = $this->_db->loadResult();

			if($table)
			{
				$query->clear();
				$query->select('id_'.$table.' as value, name_'.$table.' as text');
				$query->from('#__'.$table);
				$query->order($this->_db->quoteName('name_'.$table));
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
			
			echo '<option disabled selected class="default" value="">' . JText::_('- Conteúdo Acadêmico -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}
		
		if ( !empty( $id_conteudo_type_id ) )
		{
			$query = $this->_db->getQuery(true);
			$query->select('table_conteudo_type');
			$query->from('#__conteudo_type');
			$query->where( $this->_db->quoteName('id_conteudo_type') . '=' . $id_conteudo_type_id );
			$this->_db->setQuery($query);
			$table = $this->_db->loadResult();

			if($table)
			{
				$query->clear();
				$query->select('id_'.$table.' as value, CONCAT( id_'.$table.', \' - \', name_'.$table.') as text');
				$query->from('#__'.$table);
				if ( !empty( $id_conteudo_id ) )
					$query->where( $this->_db->quoteName('id_'.$table) . '<>' . $id_conteudo_id );
				$query->order($this->_db->quoteName('id_'.$table));
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
            if ( empty( $id_conteudo_id ))
                echo '<option disabled selected class="default" value="">' . JText::_('- Conteúdo Acadêmico -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}
		
		if ( !empty( $id_conteudo )  && !empty( $id_conteudo_type ))
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_turma as value, name_turma as text');
			$query->from('#__turma');
			$query->innerJoin($this->_db->quoteName('#__turma_structure').' USING ('.$this->_db->quoteName('id_turma').')');	
			$query->where( $this->_db->quoteName('id_conteudo_type') . '=' . $id_conteudo_type );
			$query->where( $this->_db->quoteName('id_conteudo') . '=' . $id_conteudo );
			$query->where( $this->_db->quoteName('status_turma') . '= 1' );

			$query->group($this->_db->quoteName('id_turma'));

			$query->order($this->_db->quoteName('name_turma'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();

			echo '<option disabled selected class="default" value="">' . JText::_('- Turmas -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}

		if ( !empty( $id_turma ) )
		{
			$cid = array();
			if(isset($cids) && !empty($cids) && is_array($cids) && count($cids)>0){
				foreach($cids as $key => $value)
					$cid[] = $value['value'];
			}


            $query = $this->_db->getQuery(true);			
            $query->select($this->_db->quoteName(array( 'name',
                                                        'id_inscricao',
                                                        )));
            $query->from($this->_db->quoteName('#__inscricao'));
            $query->innerJoin($this->_db->quoteName('#__users').' ON('.$this->_db->quoteName('user_id').'='.$this->_db->quoteName('id').')');


            $query->leftJoin($this->_db->quoteName('#__pagamento').' ON('.$this->_db->quoteName('#__inscricao.id_inscricao').'='.$this->_db->quoteName('#__pagamento.id_pagamento_produto')	
                                                                        . ' AND '
                                                                        . $this->_db->quoteName('#__pagamento.id_pagamento') . '=' . $this->_db->quoteName('#__inscricao.id_pagamento')	
                                                                        . ')');
            

			$query->where( $this->_db->quoteName('id_turma') . '=' . $this->_db->quote( $id_turma  ) );
            $query->where('(' .	
                '(' . 	
                    $this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) .	
                    ' AND ' .	
                    '(' . 		
                        '(' .	
                            $this->_db->quoteName('vencimento_pagamento') . '>=' . $this->_db->quote( JFactory::getDate('now - 6 month', $this->_siteOffset )->toFormat('%Y-%m-%d',true)) . 
                        ' AND ' .	
                            $this->_db->quoteName('#__pagamento.ordering') . '>' . $this->_db->quote( '1' ) .	
                        ')' . 	
                        ' OR ' .
                        $this->_db->quoteName('confirmado_pagamento') . 'IS NOT NULL ' .
                    ')' . 
                ')' .	
                ' OR ' .
                '(' . 		
                    $this->_db->quoteName('status_pagamento') . ' IS NULL' .
                    ' AND ' .
                    $this->_db->quoteName('free_inscricao') . '>' . $this->_db->quote( '0' ) .	
                ')' . 
			')');	
			//if(count($cid)>0)
			//	$query->where( $this->_db->quoteName('id_inscricao') . 'NOT IN(' . implode(',', $cid) . ')');

            $query->where( $this->_db->quoteName('id_situation') . '=' . $this->_db->quote( '1' ) );
            $query->where( $this->_db->quoteName('status_inscricao') . '=' . $this->_db->quote( '1' ) );
            $query->group($this->_db->quoteName('id_inscricao'));	


            
           // $query->group($this->_db->quoteName('user_id'));
            $this->_db->setQuery($query);
			$alunos = $this->_db->loadObjectList();	
			//htmlResponse = '';
			if(count($alunos)>0)
				foreach($alunos as $i => $aluno)
					echo '<div itemid="itm-' .$aluno->id_inscricao.'" class="btn btn-default box-item" style="white-space: normal;" data-turma="' .$id_turma.'" data-inscricao="' .$aluno->id_inscricao.'" data-origem="'.$id_elemento.'"><table width="100%"><tr><td rowspan="2" width="100%" align="center" class="name">'. $aluno->name. '</td><td class="cont-mentor"></td></tr><tr><td class="cont-mentorado"></td></tr></table></div>';											
			else
				echo '<h4>Nenhum Aluno Localizado nesta turma</h4>';
					//echo '<div itemid="itm-' .$aluno->id_inscricao.'" class="btn btn-default box-item"><input type="hidden" value="' .$aluno->id_inscricao .'" name="id_isncricao[]">'. $aluno->name. '</div>';
			
		}	
		*/
	}
}

