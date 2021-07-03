﻿

<?php $__env->startSection('title'); ?>
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
&middot; User Profile
<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('/css/profile.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/print.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('/css/products.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/print.css')); ?>">
    <style>
        @import  url('https://fonts.googleapis.com/css?family=Open+Sans');
    </style>    
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(URL::asset ('/js/api.js')); ?>" defer></script>
<script src="<?php echo e(URL::asset ('/js/global.js')); ?>" defer></script>
<script src="<?php echo e(URL::asset ('/js/createProductForm.js')); ?>" defer></script>
<script src="<?php echo e(URL::asset ('/js/profile.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  
<?php echo $__env->make('partials.flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 
<div class="container-fluid" style="margin-top: 20px;">
    <!-- BEGIN editar profile -->
        <div class="col-lg-2"></div>
            <br>
            <div class="container">
                <h2>Editar Perfil</h2>
                <hr>
                <form action="<?php echo e(url('users/edit')); ?>" method="post" enctype="multipart/form-data">
                <?php echo e(csrf_field()); ?> 
                    <div class="col-md-6 pl-0">
                    <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-primary btn-file" style="border-top-right-radius:0;border-bottom-right-radius:0;">
                                        Alterar imagem: 
                                        <input type="file" id="imgInp"  class="form-control" onchange="previewFile();" height="200" width="auto" name="photo" />
                                    
                                    </span>
                                    <img id="imagePreview" src="<?php echo e($user->photo); ?>" height="200" width="auto" alt="Image preview">
                                    <span class="text-danger"> <?php echo e($errors->first('photo')); ?></span>
                                    
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group mb-3">
                        <label for="NomeEditar">Nome:</label>
                        <input type="text" id="NomeEditar" class="form-control" placeholder="<?php echo e($user->name); ?>" aria-label="Username"
                        oninput="this.value=this.value.replace(/(?![A-Z,a-z, ' '])./gmi,'')" name="name" >
                    </div>
                    <div class="form-group mb-3">
                        <label for="EmailEditar">Email:</label>
                        <input type="email" id="EmailEditar" class="form-control" placeholder=<?php echo e($user->email); ?> aria-label="Email" 
                        name="email" >
                    </div>
                    <div class="form-group mb-3">
                        <label for="MoradaEditar">Morada:</label>
                        <input type="text" id="MoradaEditar" class="form-control" placeholder="<?php echo e($user->address); ?>" aria-label="Morada" 
                         name="address" >
                    </div>
                    <div class="form-group mb-3">
                        <label for="CodPostalEditar">Código postal:</label>
                        <input type="text" id="CodPostalEditar" class="form-control" placeholder=<?php echo e($user->id_postal); ?> aria-label="Código Postal" 
                        oninput="this.value=this.value.replace(/(?![0-9, -])./gmi,'')" name="id_postal">
                    </div>
                    <div class="form-group mb-3">
                        <label for="TelEditar">Telefone:</label>
                        <input type="text" id="TelEditar" class="form-control" placeholder=<?php echo e($user->phone_number); ?> aria-label="Telefone"
                        oninput="this.value=this.value.replace(/(?![0-9, +])./gmi,'')" name="phone_number"> 
                    </div>

                    <hr>
                    <br>
                    <div class="form-group mb-3">
                        <label for="DescricaoEditar">Descrição:</label>
                        <textarea class="form-control" rows="5" placeholder="<?php echo e($user->description); ?>" id="DescricaoEditar"
                        name="description" ></textarea>
                    </div>
                    
                    <div class="row mt-4" style="align-content: flex-end;">
                        <div class="col-12 pb-2 center">
                            <button type="submit" class="btn btn-primary" style="width:150px">Actualizar</button>
                        </div>
                        <div class="col-12 center">
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                            id="btn_apagar_in" style="width:150px">
                                Apagar Conta
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
            </div>
        
    <!-- END editar profile-->

    </div>
    <!-- END profile-tabs-div -->

    <!-- BEGIN MODEL BOTAO APAGAR CONTA -->
    <!-- Modal -->
    <div class="modal fade bg-dark" id="apagarContaConfirm" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true   ">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">eBaw - confirmação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Tem a certeza que quer apagar a sua conta?</h5>
                    <h7>Todos os seus dados serão apagados</h7>
                    <h7>e não vai poder voltar a autenticar-se.</h7>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-primary" formaction="#" formmethod="post">Sim</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODEL BOTAO APAGAR CONTA -->
    <br>
</div>
</div>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/pages/editProfile.blade.php ENDPATH**/ ?>