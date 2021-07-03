<?php $__env->startSection('title'); ?>
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
&middot; Contact Us
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('/css/print.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-1">
    </div>

    <div class="col-10 col-md-5">

        <div id="textContact" class="container">
            <p></p>
            <p> Para ajuda com a sua conta PayPal ou efectuar pagamentos com PayPal é melhor contactar o PayPal
                Customer Support directamente. Pode contactar PayPal Customer Support ao escolher o botão em baixo, ou
                ao seleccionar Help & Contact no fundo de qualquer
                página do PayPal website.</p>
        </div>
    </div>


    <div class="col-10 offset-1 col-md-5 offset-md-0">
        <p></p>
         <?php echo $__env->make('partials.flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
       
        <!--Form with header-->
        <form method="post" action="<?php echo e(route('contact.store')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="card rounded-0">

                <div class="text-center">
                    <h2>Contate-nos</h2>
                </div>
                <div class="card-body p-3">

                    <!--Body-->
                    <div class="form-group">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="nome" name="name" placeholder="Nome" required>
                        </div>
                        <?php if($errors->has('name')): ?>
                        <span class="error" style="color:red">
                            <?php echo e($errors->first('name')); ?>

                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <div class="input-group mb-2">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Exemplo@gmail.com" required>

                        </div>
                        <?php if($errors->has('email')): ?>
                        <span class="error" style="color:red">
                            <?php echo e($errors->first('email')); ?>

                        </span>
                        <?php endif; ?>

                    </div>

                    <div class="form-group">
                        <div class="input-group mb-2">
                            <textarea class="form-control" name="msg" placeholder="Escrever mensagem..."
                                required></textarea>

                        </div>
                        <?php if($errors->has('msg')): ?>
                        <span class="error" style="color:red">
                            <?php echo e($errors->first('msg')); ?>

                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="text-center">
                        <input id="botoesRegister" type="submit" value="Enviar" class="btn btn-info btn-block">
                    </div>
                </div>

            </div>
        </form>
        <!--Form with header-->

    </div>

    <div class="col-1">
    </div>
</div>
</div>

<img src="Banner_01.png" class="img-fluid" alt="eBaw banner">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/pages/contact.blade.php ENDPATH**/ ?>