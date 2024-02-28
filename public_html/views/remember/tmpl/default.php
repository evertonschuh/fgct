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
      <!-- /Left Text -->
      <!-- Login -->
      <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-5"></div>
          <!-- /Logo -->
          <h4 class="mb-2">Esqueceu sua senha? 🔒</h4>
          <p class="mb-4">Digite seu usuário e enviaremos instruções para redefinir sua senha</p>

          <div class="mb-3 fv-plugins-icon-container">
            <label for="email" class="form-label">Usuário</label>
            <input type="text" class="form-control required" id="username" name="username" placeholder="Escreva seu usuário" autofocus />
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <div class="mb-3 d-flex align-items-center justify-content-center">  
            <?php 
              JPluginHelper::importPlugin('captcha');
              $dispatcher = JDispatcher::getInstance();
              $dispatcher->trigger('onInit','recaptcha');	
              $output = $dispatcher->trigger('onDisplay');
              echo $output[0];
            ?>        
          </div>
          <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Enviar link de redefinição</button>
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

  <input type="hidden" name="task" id="task" value="remember" />
  <input type="hidden" name="controller" id="controller" value="remember" />			
  <input type="hidden" name="view" id="view" value="remember" />
  <?php echo JHTML::_('form.token'); ?>	
</form>




<?php /*

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Área Restrita</h4>
              <p class="mb-4">Por favor, insira seus dados de acesso para continuar</p>

                <div class="mb-3">
                  <label for="email" class="form-label">E-mail</label>
                  <input type="text" class="form-control required" id="username" name="username" placeholder="Escreva seu e-mail" autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="auth-forgot-password-basic.html">
                      <small>Esqueceu sua senha?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control required"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <?php /*
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                */ /*?>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Acessar</button>
                </div>
            <?php /*
              <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                  <span>Create an account</span>
                </a>
              </p>
               */ /*?>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
 */ /*?>

<?php /*
    <!-- Outer Row -->
    <div class="row justify-content-center">
    	<div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                            	<div class="form-group" style="padding:0 20px 20px 20px">
                            		<img class="img-fluid" src="views/system/img/logo_login.png" alt="RS Shooting & Sport Club">
                                </div>
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Acesso Restrito</h1>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-user" required="required" placeholder="E-mail">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user" required="required" placeholder="Senha">
                                </div>
                                <input type="submit" class="btn btn-primary btn-user btn-block" value="Entrar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    */
    ?>
