﻿

<?php $__env->startSection('title'); ?>
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
&middot; User Profile
<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(URL::asset('/css/profile.css')); ?>">
<?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('css/products.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">
    <style>
        @import  url('https://fonts.googleapis.com/css?family=Open+Sans');
    </style>    
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="<?php echo e(URL::asset ('/js/api.js')); ?>" defer></script>
<script type="text/javascript" src="<?php echo e(URL::asset ('/js/global.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

 
<div class="container-fluid" style="margin-top: 20px;">

    <!-- BEGIN profile-tabs-div -->
    <div class="container row">
        <div class="col-md-3 hidden-xs col-lg-3 d-block">
            <div class="wrappersidebar not_center">
                <!-- Sidebar -->
                <nav id="sidebar" class="vcenter">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-9">
                            <hr>

                            <ul id="sidebar_components" class="list-unstyled components">

                                <li id="sidebar_perfil" class="active">
                                    <a class="nav-link2" onclick="showGeral()" href="/users/<?php echo e($user->id); ?>"
                                        data-toggle="collapse" data-toggle="collapse"
                                        aria-expanded="false">Perfil</a>
                                </li>
                                <li id="sidebar_notifications">
                                    <a class="nav-link2" onclick="showNotif()" href="/users/<?php echo e($user->id); ?>/notifications"
                                        data-toggle="collapse">Notificações</a>
                                </li>
                                <li id="sidebar_historico">
                                    <a class="nav-link2" onclick="showHist()" href="/users/<?php echo e($user->id); ?>/history"
                                        data-toggle="collapse" data-toggle="collapse"
                                        aria-expanded="false">Histórico</a>

                                </li>
                                <!-- <li>
                                    <a class="nav-link2" onclick="showEditar()" href="#" data-toggle="collapse">Editar Perfil</a>
                                </li>-->
                            </ul>
                            <hr>

                        </div>
                        <div class="col-lg-1"></div>
                    </div>
                </nav>

            </div>
        </div>

        <!-- MAIN SECTION -->

        <div class="col-lg-9 col-sm-9 d-block push" id="geral">
            <!-- BEGIN PERFIL geral -->
            <br>
            <br>
            <div class="row">
                <div class="col-lg-3 prod_th">
                    <img src=<?php echo e($user->photo); ?> width="160px" height="auto" alt=" ">
                    <p></p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $user)): ?>
                    <a href="/users/<?php echo e($user->id); ?>/edit"><button class="btn btn-primary btn-sm"
                            type="button" style="width: 6rem"><i class="fa fa-edit"></i> Editar</button></a>
                    <?php endif; ?>
                </div>
                <div class="col-lg-3">
                    <br>
                    <h3 class="profile-name profile"> <?php echo e($user->username); ?>

                    </h3>
                    <div>
                        <a href="# "><?php echo e($user->username); ?></a>
                        <br>
                    </div>
                    <div>
                        <span><?php if ($user->total_votes > 0) echo "+";?>
                        <?php echo e($user->total_votes); ?></span>
                    </div>
                    <div style="margin: 10px 0;">
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                        <a href="#"><i class="fa fa-facebook"></i></a>

                    </div>
                </div>

                <div class="col-lg-5 text-left profile">

                    <div class="container row">

                        <div class=" profile-descr profile ">
                            <br>
                            <p> <?php echo e($user->description); ?>

                            </p>
                        </div>
                        <div class="col-sm-1 col-xs-1 col-md-1"></div>
                    </div>

                </div>

            </div>

        </div>
        <!-- END PERFIL geral -->

    </div>
    <!-- END profile-tabs-div -->


    <!-- BEGIN BOTAO DENUNCIAR -->
    <div class="container row">
        <div class="col-10"></div>
        <div class="col-2" id="btn_denunciar">
            <div class="row">
                <p class="product-title">
                    <a class="denounce" data-toggle="modal" data-target="#report" id="btn_denunciar_in">
                        <strong>...</strong>
                    </a>
                </p>
                <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="reportModelLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reportModelLabel">Denúncia:</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="<?php echo e(url('users/' . $user->id . '/report')); ?>"> 
                                <?php echo csrf_field(); ?>
                                <div class="modal-body">
                                    <?php if($errors->any()): ?>
                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($error); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="title" name="reason"
                                                placeholder="título da denúncia" required pattern="[a-zA-Z0-9\-\.\_]*" maxlength="50">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-2">
                                            <textarea class="form-control" name="textReport" placeholder="Explicitar Denúncia." required
                                                pattern="[a-zA-Z0-9\-\.\_]*" maxlength="500"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button id="reportConfirmation" type="submit" class="btn btn-primary">Submeter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END BOTAO DENUNCIAR -->
    <br>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/pages/users.blade.php ENDPATH**/ ?>