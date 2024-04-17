
<?php
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');
$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

$filename = 'cache/image_login.png';
if (!file_exists($filename) || (file_exists($filename) && JFactory::getDate(date ("Y-m-d", filectime($filename)), $siteOffset )->toFormat('%Y-%m-%d', true) < JFactory::getDate('now - 2 days', $siteOffset )->toFormat('%Y-%m-%d', true))) {

  jimport('joomla.image.resize');
  $resize = new JResize(); 
  $image = $resize->resize(JPATH_BASE.DS. 'images' .DS. 'login'  .DS. 'atirador00' . rand(1,10) . '.jpg', 1250, 1200,  'cache/image_login.png');

}							
?>
<style>

.cover {
  background-image: url(<?php echo $filename; ?>);
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  object-fit: cover;
}
</style>
<form method="post" name="adminForm" class="form-validate">
  <div class="authentication-wrapper authentication-cover">
    <div class="authentication-inner row m-0">
      <!-- /Left Text -->
      <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center cover">
      <?php /*
      <div class="w-100 d-flex justify-content-center">
          <img src="images/login/atirador001.jpg" class="img-fluid cover" alt="Login image" >
        </div>
        */ ?>
      </div>
      <!-- /Left Text -->
      <!-- Login -->
      <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-5"></div>
          <!-- /Logo -->
          <h4 class="mb-2">Confirme o Código 🔒</h4>
          <p class="mb-4">Digite o código que recebeu em seu email.</p>

          <div class="mb-3 fv-plugins-icon-container">
            <label for="email_remember" class="form-label">Usuário</label>
            <input type="text" class="form-control required" id="username" name="username" placeholder="Escreva seu usuário" value="<?php echo JRequest::getVar('u', '', 'GET'); ?>" />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <div class="mb-3 fv-plugins-icon-container">
            <label for="token_remember" class="form-label">Código</label>
            <input type="text" class="form-control required" id="token_remember" name="token_remember" placeholder="Informe o código" value="<?php echo JRequest::getVar('c', '', 'GET'); ?>" />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>

          <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Redefinir Senha</button>
          </div>
          <div class="text-center">
            <a href="<?php echo JRoute::_('index.php?view=login'); ?>" class="d-flex align-items-center justify-content-center">
              <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
              <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">
                  Volte ao login
                </font>
              </font>
            </a>
          </div>
        </div>
      </div>
      <!-- /Login -->
    </div>
  </div>

  <input type="hidden" name="task" id="task" value="confirm" />
  <input type="hidden" name="controller" id="controller" value="remember" />			
  <input type="hidden" name="view" id="view" value="remember" />
  <?php echo JHTML::_('form.token'); ?>	
</form>