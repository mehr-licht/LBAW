<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('css/register.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/print.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- REGISTER BODY -/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-md-4">
                <!--1 of 3 Column-->
            </div>
            <div class="col-md-4">
                <p></p>
                <div class="form-signin">
                    <img src="logotipo.png" class="mb-4 img-fluid mx-auto" alt="eBaw logo">
                    <div>
                        <p id="textRecuperarPalavraPasse">Se já tem conta
                            <a href="<?php echo e(route('login')); ?>" style="color:#7bc411">Inicie a sessão
                            </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!--3 of 3 Column-->
            </div>
        </div>


        <div class="row">
            <div class="col-md-3">
                <!--1 of 3 Column-->
            </div>
            <div class="col-md-6">
                <!-- Default form register -->
                <form class="text-center border border-light p-3" method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo e(csrf_field()); ?>


                    <h1 id="textRecuperarPalavraPasse" class="h4 mb-3">Registo</h1>
                    <div class="form-row mb-3">
                        <div class="col">
                            <!-- Name -->
                            <?php if($errors->has('name')): ?>
                            <span class="error">
                                <?php echo e($errors->first('name')); ?>

                            </span>
                            <?php endif; ?>
                            <input type="text" id="defaultRegisterFormFirstName" class="form-control" name="name" value="<?php echo e(old('name')); ?>" placeholder="Nome *" required autofocus>
                        </div>
                    </div>

                    <!-- username -->
                    <?php if($errors->has('username')): ?>
                        <span class="error">
                            <?php echo e($errors->first('username')); ?>

                        </span>
                    <?php endif; ?>
                    <input type="text" id="username" class="form-control mb-3" placeholder="Username *" name="username" value="<?php echo e(old('username')); ?>"
                        aria-describedby="defaultRegisterFormUsernameHelpBlock" required>

                    <!-- E-mail -->
                    <?php if($errors->has('email')): ?>
                        <span class="error">
                            <?php echo e($errors->first('email')); ?>

                        </span>
                    <?php endif; ?>
                    <input type="email" id="defaultRegisterFormEmail" class="form-control mb-3" name="email" value="<?php echo e(old('email')); ?>" placeholder="E-mail *" required>

                    <!-- Password -->
                    <input type="password" id="defaultRegisterFormPassword" class="form-control" name="password" placeholder="Palavra-passe *"
                        aria-describedby="defaultRegisterFormPasswordHelpBlock" required>
                        <?php if($errors->has('password')): ?>
                        <span class="error">
                            <?php echo e($errors->first('password')); ?>

                        </span>
                        <?php endif; ?>
                    <small id="defaultRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                        Pelo menos 8 carateres com letras e digitos
                    </small>

                    <!-- Confirmar Password -->
                    <input type="password" id="password-confirm" class="form-control mb-3" name="password_confirmation" placeholder="Confirmar palavra-passe *"
                        aria-describedby="defaultRegisterFormPasswordHelpBlock" required>

                    <!-- Phone number -->
                    <?php if($errors->has('phone_number')): ?>
                    <span class="error">
                        <?php echo e($errors->first('phone_number')); ?>

                    </span>
                    <?php endif; ?>
                    <input type="tel" id="defaultRegisterPhoneNumber" class="form-control mb-3" name="phone_number" value="<?php echo e(old('phone_number')); ?>" placeholder="Número de telefone *"
                        aria-describedby="defaultRegisterFormPhoneHelpBlock" required>

                    <!-- Morada -->
                    <?php if($errors->has('address')): ?>
                    <span class="error">
                        <?php echo e($errors->first('address')); ?>

                    </span>
                    <?php endif; ?>
                    <input type="text" id="defaultRegisterAddress" class="form-control mb-3" name="address" value="<?php echo e(old('address')); ?>" placeholder="Morada *"
                        aria-describedby="defaultRegisterFormAddressHelpBlock" required>

                    <!-- CódigoPostal -->
                    <?php if($errors->has('id_postal')): ?>
                    <span class="error">
                        <?php echo e($errors->first('id_postal')); ?>

                    </span>
                    <?php endif; ?>
                    <input type="text" id="defaultRegisterPostal" class="form-control mb-3" name="id_postal" value="<?php echo e(old('id_postal')); ?>" placeholder="Código Postal(XXXX-XXX) *"
                        aria-describedby="defaultRegisterFormPostalHelpBlock" required>

                    

                    <!-- Data de Nascimento -->
                    <?php if($errors->has('birth_date')): ?>
                    <span class="error">
                        <?php echo e($errors->first('birth_date')); ?>

                    </span>
                    <?php endif; ?>
                    <input type="text" id="defaultRegisterBirthdate" class="form-control mb-3" name="birth_date" value="<?php echo e(old('birth_date')); ?>" placeholder="Data de Nascimento(YYYY/MM/DD) *"
                        aria-describedby="defaultRegisterFormBirthdateHelpBlock" required>


                    <!-- Sign up button -->
                    <button class="btn btn-primary my-4 btn-block" type="submit">Registar</button>

                    <!-- Social register -->
                    <p id="textRecuperarPalavraPasse">ou registe-se com:</p>
                    <button id="botoesRegisterGoogle" class="btn btn-lg btn-primary btn-block " type="submit" style="padding:0;">
                        <img src="btn_google_signin_dark_normal_web@2x.png" alt="google API image" style="max-height: 38px;">
                    </button>
                    <hr>

                    <!-- Terms of service -->
                    <p id="textRecuperarPalavraPasse">Ao clickar em
                        <em>Registar</em> está a concordar com  
                        <a href="" target="_blank" style="color:#7bc411">as regras do serviço</a>

                </form>
                <!-- Default form register -->


            </div>
            <div class="col-md-3">
                <!--3 of 3 Column-->
            </div>
        </div>


    </div>

    <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- REGISTER BODY END -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->

    <img src="Banner_01.png" class="img-fluid" alt="eBaw banner">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appNoNav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/auth/register.blade.php ENDPATH**/ ?>