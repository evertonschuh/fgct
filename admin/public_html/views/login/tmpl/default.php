<?php
defined('_JEXEC') or die('Restricted access');
$uri = JFactory::getURI();

?>
<form method="post" name="adminForm" class="form-validate">
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
                */ ?>
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
              */ ?>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>


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
    <input type="hidden" name="task" value="login" />
    <input type="hidden" name="controller" value="login" />			
    <input type="hidden" name="view" value="login" />
    <input type="hidden" name="return" value="<?php echo base64_encode( $uri->toString() ); ?>" />
    <?php echo JHTML::_('form.token'); ?>	
</form>