<?php

defined('_JEXEC') or die('Restricted access'); 
$_app	 = JFactory::getApplication(); 

$config = JFactory::getConfig();
$siteOffset = $config->get('offset'); 
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

.box-autenticate {
	font-family: 'courier';
	position:absolute; 
	right:160px; 
	bottom: 37px;
    width:200px;
}

.autenticate {
	font-family: 'courier';
	position:absolute; 
	right:160px; 
	bottom: 64px;
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
	background-image:url("<?php echo $this->item->skin_documento_numero; ?>");
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
		<?php echo $this->item->texto_documento_numero; ?>
	</div>



	
    <?php
    $document_code = str_replace('=', '', strrev( base64_encode(base64_encode($this->item->id_documento_numero ) ) ) );
    //$link =    'https://www.fgct.com.br'  . JRoute::_('index.php?option=com_intranet&view=autenticate', false) . '?code=' . $document_code;
    $link =    'https://www.fgct.com.br/index.php?option=com_intranet&view=autenticate&code=' . $document_code;
                                                  
    if(!JFile::exists(JPATH_BASE . DS . 'cache' . DS .  $this->item->id_documento_numero . '-documento.png'))
        QRcode::png(  $link, JPATH_BASE . DS . 'cache' . DS . $this->item->id_documento_numero . '-documento.png', 'L', 3, 2);

        $new_html_document_code = '';
    for ($X = 0; $X <= (strlen($document_code)-1); $X++) {
        if ( is_numeric(substr($document_code, $X, 1) ) ) {
            $new_html_document_code .= '<span style="font-size:32px"><strong>' . substr($document_code, $X, 1) . '</strong></span>';
        } else {
            $new_html_document_code .= '<span style="font-size:20px;">' .  substr($document_code, $X, 1) . '</span>';
        }
    }

    ?>
    <span class="box-autenticate">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td style="font-size:14px;font-weight:normal;text-align: center;">Código de Autenticação</td></tr>
            <tr><td style="font-family:serif;text-align:center;"><?php echo $new_html_document_code; ?></td></tr>
            <tr><td style="font-size:11px;font-weight:normal;text-align: center;"><?php echo 'Emitido em ' .  JHTML::_('date', JFactory::getDate('now', $siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME')?></td></tr>
            <tr><td style="font-size:11px;font-weight:normal;text-align: center;">Este documento pode ser validado no site</td></tr>
            <tr><td style="font-size:11px;font-weight:normal;text-align: center;">https://www.fgct.com.br</td></tr>
        </table>
    </span>
    <?php /*
    <span class="autenticate">Código de Autenticação</span>
    <span class="autenticate-code"><?php echo $new_html_document_code ?></span>
    */ ?>
	<img class="img-qrcode" src="cache/<?php echo $this->item->id_documento_numero . '-documento.png' ?>" />
</div>