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
    <!-- <header class="row"> -->
    <!-- </header> -->
    <!-- <div id="main" class="row"> -->
    <section id="content">
      <?php echo $__env->yieldContent('content'); ?> 
    <!-- </div> -->
    </section>
    <!-- <footer class="row"> -->
      <?php echo $__env->make('includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- </footer> -->
    <?php 
//     
//       <header>
//         <h1><a href="{{ url('/cards') }}">Thingy!</a></h1>
//         @if (Auth::check())
//         <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
//         @endif
//       </header>
//       <section id="content">
//         @yield('content')
//       </section>
//     </main>
//   </body>
// </html> ?>
  </main>
  <!-- </div> -->
</body>

</html>
<?php /**PATH /home/luis/git/A4S2/LBAW/wip/lbaw2036/resources/views/layouts/appNoNav.blade.php ENDPATH**/ ?>