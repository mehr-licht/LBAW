<!-- <h1><?php echo e(Session::get('flash_message')); ?><h1> -->
<!-- <h1><?php echo e(Session::get('success')); ?><h1> -->
<!-- <h1><?php echo e(Session::get('error')); ?><h1> -->
<!-- <?php if(Session::has('success')): ?>
        <div id="formMsgGoodbye" class="alert alert-success text-center"><?php echo e(Session::get('success')); ?></div>
 <?php endif; ?>       -->
<?php if($message = Session::get('success')): ?>
<div class="alert alert-success alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <?php echo e($message); ?>

</div>
<?php endif; ?>


<?php if($message = Session::get('error')): ?>
<div class="alert alert-danger alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <?php echo e($message); ?>

</div>
<?php endif; ?>


<?php if($message = Session::get('warning')): ?>
<div class="alert alert-warning alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <?php echo e($message); ?>

</div>
<?php endif; ?>


<?php if($message = Session::get('info')): ?>
<div class="alert alert-info alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <?php echo e($message); ?>

</div>
<?php endif; ?>


<?php if($errors->any()): ?>
<div class="alert alert-danger text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    Please check the form below for errors
</div>
<?php endif; ?>

<!--contact-->
<?php if($message = Session::get('flash_message')): ?>
<div class="alert alert-success alert-block text-center">
    <button type="button" class="close" data-dismiss="alert" id="formMsgGoodbye">×</button>
    <?php echo e($message); ?>

</div>
<?php endif; ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/partials/flash-message.blade.php ENDPATH**/ ?>