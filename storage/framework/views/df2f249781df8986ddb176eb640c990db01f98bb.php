<?php $__env->startSection('content'); ?>

<body id="bodyIntro">
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <p></p>
                <form class="form-signin" method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo e(csrf_field()); ?>

                    <img src="logotipo.png" class="mb-4 img-fluid mx-auto" alt="Logotipo">
                    <h1 id="textRecuperarPalavraPasse" class="h3 mb-3 font-weight-normal">Iniciar Sessão</h1>
                    
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus name="email">
                    
                    <?php if($errors->has('email')): ?>
                        <span class="error">
                            <?php echo e($errors->first('email')); ?>

                        </span>
                    <?php endif; ?>
                    
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" type="password" required class="form-control mb-3" name="password">
                   
                    <?php if($errors->has('password')): ?>
                        <span class="error">
                            <?php echo e($errors->first('password')); ?>

                        </span>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('recovery')); ?>" style="color:#7bc411">Esqueceu-se da palavra passe? </a>
                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>> Relembrar dados
                        </label>
                    </div>

                    <button id="botoesRegister" class="btn btn-lg btn-primary btn-block " type="submit">Iniciar Sessão</button>
                    <div>
                        <h3 id="textRecuperarPalavraPasse" class="h3 font-weight-normal">ou</h3>
                    </div>
                    <button id="botoesRegisterGoogle" class="btn btn-lg btn-primary btn-block " type="submit" style="padding:0;">
                        <img src="btn_google_signin_dark_normal_web@2x.png" alt="google API image" style="max-height: 48px;">
                    </button>
                    <hr>
                    <div>
                        <p id="textRecuperarPalavraPasse">Se não tem conta </p> 
                           <a href="<?php echo e(route('register')); ?>" style="color:#7bc411">Registe-se</a>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
            </div>
        </div>
    </div>

    <img src="Banner_01.png" class="img-fluid" alt="Responsive image">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appNoNav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/actual/lbaw2036/resources/views/auth/login.blade.php ENDPATH**/ ?>