<?php $__env->startSection('title'); ?>
Product is not available
<?php $__env->stopSection(); ?>
	
	<?php $__env->startSection('code'); ?>	
        
     666
         <?php $__env->stopSection(); ?>
	
		<?php $__env->startSection('description'); ?>	
        
        Product <?php echo e($product); ?> is not avaliable
         <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/errors/666.blade.php ENDPATH**/ ?>