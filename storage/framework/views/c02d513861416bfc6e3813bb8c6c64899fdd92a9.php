<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(url('css/register.css')); ?>">
<?php $__env->stopSection(); ?>
    
<div id="bodyIntro" class="container-fluid text-center">
        <div class="row">
           
        <div class="col-md-4">
            </div>

            <div class="col-md-4">
                <form class="form-signin">
                    <img src="logotipo.png" class="mb-4 img-fluid mx-auto" alt="Responsive image">
                    <h1 id="textRecuperarPalavraPasse" class="h3 mb-3 font-weight-normal">Insira email para confirmação</h1>
                    <label for="inputEmail" class="sr-only">Email</label>
                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">

                    <p></p>
                    <br>
                    <br>
                </form>
            </div>
            <img src="Banner_01.png" class="img-fluid" alt="Responsive image">
        </div>
    </div>
    

<?php echo $__env->make('layouts.appNoNav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/auth/recovery.blade.php ENDPATH**/ ?>