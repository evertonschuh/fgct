<?php
defined('_JEXEC') or die('Restricted access'); 
$_app	 = JFactory::getApplication(); 
?>
<style>
@page {margin: 0;}
body {
    margin: 0;
    padding: 0;
}
* {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}
.img-qrcode{
	position:absolute; 
	right:150px; 
	bottom: 95px;
}
.autenticate {
	font-family: 'courier';
	position:absolute; 
	right:160px; 
	bottom: 54px;
}
.autenticate-code {
	font-family: 'courier';
	position:absolute; 
	right:160px; 
	bottom: 37px;
}
strong {
	font-weight:bold !important;

}
.page1, .page {
	background-image:url("/media/images/ptimbrado/<?php echo $this->item->skin_documento ?>.jpg");
	background-repeat: no-repeat;
	background-position: center top;
}
.documento{
	padding:40px 80px;
	margin-top: 120px;
}
p{margin: 5px 0;}
.page {margin: 0; padding:0 ;height:27cm; position:absolute}  
</style>








<div class="page1 page">
	<div class="paisagem documento">
		<?php echo $this->item->text_documento; ?>
	</div>
	<span class="autenticate">Código de Autenticação</span>
	<span class="autenticate-code">CODIGOEXEMPLO11</span>
	<img class="img-qrcode" src="../media/images/ptimbrado/qr_code_exemplo.jpg" />
</div>