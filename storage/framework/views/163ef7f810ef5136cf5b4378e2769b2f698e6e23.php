<?php $__env->startSection('title'); ?>
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
&middot; Admin Login
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('/css/register.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div id="bodyIntro" class="container-fluid text-center">
    <div class="row">

        <div class="col-md-4"><?php echo e(isset($url) ? ucwords($url) : ""); ?> 
        </div>

            <div class="col-md-4">
                <p></p>
   
                <!-- <?php if(isset($url)): ?>
                <form method="POST" action='<?php echo e(url("$url")); ?>' aria-label="<?php echo e(__('Login')); ?>">
                <?php else: ?> -->
                <form class="form-signin" method="POST" action="<?php echo e(route('admin.post')); ?>">
                <!-- <?php endif; ?> -->
                    
                    
                    
                     <?php echo e(csrf_field()); ?>                
                    <img src="../logotipo.png" class="mb-4 img-fluid mx-auto" alt="Responsive image">
                        <?php if($errors->any()): ?>
                            <h5 id="errorMessage"><?php echo e($errors->first()); ?></h5>
                        <?php endif; ?>
                    <h1 id="textRecuperarPalavraPasse" class="h3 mb-3 font-weight-normal">Iniciar Sessão de Administrador</h1>
                    <label for="inputEmail" class="sr-only">Email</label>
                    <input type="text" id="inputEmailUsername" class="form-control mb-3" placeholder="Email address ou username" required="" autofocus="" name="email">
                    
                    <?php if($errors->has('email')): ?>
                        <span  id="errorMessage" class="error">
                            <?php echo e($errors->first('email')); ?>

                        </span>
                    <?php endif; ?>

                    
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input type="password" id="inputPassword" class="form-control mb-3" placeholder="Password" required="" name="password">
                    
                    <?php if($errors->has('password')): ?>
                        <span id="errorMessage" class="error">
                            <?php echo e($errors->first('password')); ?>

                        </span>
                    <?php endif; ?>
                    <?php echo $__env->make('partials.flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                    <a href="<?php echo e(route('recovery')); ?>" style="color:#7bc411">Esqueceu-se da palavra passe? </a>
                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" value="remember-me" <?php echo e(old('remember') ? 'checked' : ''); ?>> <i id="textRecuperarPalavraPasse">Relembrar dados</i>
                        </label>
                    </div>
                    <button id="botoesRegister" class="btn btn-lg btn-primary btn-block " type="submit">Iniciar Sessão</button>
                    <div>
                        <h3 id="textRecuperarPalavraPasse" class="h3 font-weight-normal"></h3>
                    </div>
                   
                   
                </form>
            </div>

            <div class="col-md-4">
            </div>
        </div>
    </div>

    <img src="../Banner_01.png" class="img-fluid" alt="Responsive image">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appNoNav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/auth/admin_login.blade.php ENDPATH**/ ?>