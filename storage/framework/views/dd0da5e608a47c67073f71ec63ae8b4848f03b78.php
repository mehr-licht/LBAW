<?php if(auth()->guard()->guest()): ?>
  
<?php else: ?>
  
<?php endif; ?>

<?php $__env->startSection('title'); ?>
Unprocessable Entity
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>	
        
        <div id="page-card" class="container card-container font-content not-found-container" style="margin-top:50px">
          <header>
              <h1>420 - Unprocessable Entity</h1>
          </header>

          <div class="not-found-body">
              <h1><?php echo e('O produto '.$product.' está '); ?>

                  <?php echo e('O produto '.$product. ' está ' . $status=='' ? 'indisponível':$status); ?>

              </h1>
              <p>
                  Clique <a href="<?php echo e(url()->previous()); ?>" class="btn btn-default">Aqui</a>para voltar atrás
              </p>
          </div>
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.appNoNav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/errors/420.blade.php ENDPATH**/ ?>