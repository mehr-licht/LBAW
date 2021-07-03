<!-- Required meta tags -->
<?php echo $__env->yieldContent('meta'); ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- CSRF Token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo $__env->yieldContent('title'); ?></title>


<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Styles -->
<!--<link rel="stylesheet" href="<?php echo e(url('/css/products.css')); ?>">-->
<link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">
<link rel="stylesheet" href="<?php echo e(url('/css/register.css')); ?>">
<?php echo $__env->yieldContent('css'); ?>
<link rel="icon" href="<?php echo e(URL::asset('favicon.ico')); ?>" type="image/x-icon"/>
<style>
    @import  url('https://fonts.googleapis.com/css?family=Open+Sans');
</style>
<?php echo $__env->yieldContent('scripts'); ?>
<!-- <link href="<?php echo e(asset('css/milligram.min.css')); ?>" rel="stylesheet"> -->
<!-- <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet"> -->
<script type="text/javascript">
    // Fix for Firefox autofocus CSS bug
    // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
</script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script href="<?php echo e(url('/js/global.js')); ?>">
</script>

<script type="text/javascript" src=<?php echo e(asset('js/app.js')); ?> defer>
</script>
<?php echo $__env->yieldContent('js'); ?>
<?php /**PATH /home/luis/git/A4S2/LBAW/attempt2/lbaw2036/resources/views/includes/head.blade.php ENDPATH**/ ?>