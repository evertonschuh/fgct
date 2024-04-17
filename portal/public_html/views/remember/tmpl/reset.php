
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
          <h4 class="mb-2">Nova senha 🔒</h4>
          <p class="mb-4">Agora digite sua nova senha e confirme logo depois.</p>

          <div class="mb-3 form-password-toggle">
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password"
                    class="form-control required validate-password"
                    name="password"
                    aria-describedby="password"
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
          </div>
          <div class="mb-3 form-password-toggle">
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password2"
                    class="form-control required validate-equalspass"
                    name="password2"
                    aria-describedby="password"
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
          </div>
          <div class="mb-3">
            <button class="btn btn-primary d-grid w-100 validate" type="submit">Cadastrar nova senha</button>
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

  <input type="hidden" name="task" id="task" value="resetpass" />
  <input type="hidden" name="controller" id="controller" value="remember" />			
  <input type="hidden" name="view" id="view" value="remember" />
  <?php echo JHTML::_('form.token'); ?>	
</form>

