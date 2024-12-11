<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

$config = JFactory::getConfig();
$siteOffset = $config->get('offset'); 

?>


<div class="d-flex align-items-center justify-content-center pt-5">

	<div class="card h-px-500 w-px-400">
		<div class="card-body">
			<h4 class="mb-2 text-center mb-4">Autenticador FGCT</h4>
			<?php if(isset($this->item->id_documento_numero) || isset($this->item->validate_associado)): ?>
			<div class="h-px-350 mb-3">
				<div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex; margin-top:0">
					<div class="swal2-success-circular-line-left" style="background-color: rgb(255 255 255);"></div>
					<span class="swal2-success-line-tip"></span>
					<span class="swal2-success-line-long"></span>
					<div class="swal2-success-ring"></div> 
					<div class="swal2-success-fix" style="background-color: rgb(255 255 255);"></div>
					<div class="swal2-success-circular-line-right" style="background-color: rgb(255 255 255);"></div>
				</div>
				<h4 class="text-center" style="color:#0fad00;margin-top: 0;">Código Validado</h4>
				<div class="col-12 ">
					<table class="table table-bordered" >
						<tr>
							<td><b>Nome</b></td>
							<td><?php echo $this->item->name; ?></td>
						</tr>
						<tr>
							<td><b><?php echo isset($this->item->name_documento) ? 'Documento' : 'Cartira Digital'; ?></b></td>
							<td><?php echo isset($this->item->name_documento) ? $this->item->name_documento : JFactory::getDate($this->item->validate_associado, $siteOffset)->toFormat('%Y',true); ?></td>
						</tr>
						<tr>
							<td><b><?php echo isset($this->item->numero_documento_numero) ? 'Número' : 'Matrícula'; ?></b></td>
							<td><?php echo isset($this->item->numero_documento_numero) ? $this->item->numero_documento_numero : $this->item->id_associado; ?></td>
						</tr>
						<tr>
							<td><b><?php echo isset($this->item->register_documento_numero) ? 'Data' : 'Validade'; ?></b></td>
							<td>
							<?php  echo JHTML::_('date', JFactory::getDate((isset($this->item->register_documento_numero) ? $this->item->register_documento_numero : $this->item->validate_associado), $siteOffset)->toISO8601(true), 'DATE_FORMAT');
							
							?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="d-grid gap-2 mt-2">
				<a href="<?php echo JRoute::_('index.php?view=autenticate', false); ?>" class="btn btn-primary validate">Verificar Novo Código</a>
			</div>

			<?php else: ?>
			<div class="h-px-250 mb-4 pt-1">
				<?php if($this->result === false): ?>
				<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex; margin-top:0">
					<span class="swal2-x-mark">
						<span class="swal2-x-mark-line-left"></span>
						<span class="swal2-x-mark-line-right"></span>
					</span>
				</div>	
				<h4 class="text-center" style="color:#f77575; margin-top: 0;">Erro no Código!</h2>
				<p class="mb-4 text-center" style="color:#f77575; margin-top: 0;">
					Desculpe, mas o código informado não corresponde a nenhum documento válido.
					<br/>
					Revise o código digitado e tente novamente.
				</p>
				<?php else:?>
					<p class="text-center mb-4"><i class="bx bx-qr-scan" style="font-size: 70px;"></i></p>
					<p class="mb-3 text-center">Bem vindo ao sistema de autenticação da Federação Gaúcha de Caça e Tiro!</p>
					<p class="mb-3 text-center">Utilize o código descrito no documento para verificar a validade ou leia o Codigo QR com seu dispositivo.</p>
				<?php endif;?>
			</div>	
			<form method="post" name="adminForm" class="form-validate">

				
				<div class="mb-3">
					<label class="form-label" for="code">Código do Documento</label>
					<input type="text" class="form-control required" id="code" name="code" placeholder="Código do Documento">
				</div>
				<div class="d-grid gap-2">
					<button type="submit" class="btn btn-primary validate">Verificar Código</button>
				</div>

				<?php echo JHTML::_('form.token'); ?>	
			</form>
			<?php endif;?>
		</div>
	</div>
</div>