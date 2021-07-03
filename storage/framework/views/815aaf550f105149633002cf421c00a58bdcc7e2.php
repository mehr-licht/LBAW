<?php $__env->startSection('title'); ?>
<?php echo e(config('app.name', 'Laravel')); ?>

<?php $__env->stopSection(); ?>
<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
  <?php echo $__env->make('includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body>
  <main>
    <?php echo $__env->make('includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <section id="content">
      <?php echo $__env->make('partials.flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->yieldContent('content'); ?>

    </section>

    <?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  </main>
</body>

</html>
<?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/layouts/app.blade.php ENDPATH**/ ?>