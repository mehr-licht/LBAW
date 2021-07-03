<?php $__env->startSection('title'); ?>
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
&middot; Search Users
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/print.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/admin.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    Admin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(URL::asset ('/js/admin.js')); ?>" defer></script>
<script src="<?php echo e(URL::asset ('/js/global.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container w-75 mb-4" id="admin_main_container" style="margin-top:60px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #f8f9fa;">
            <li class="nav-item">
                <a class="nav-link" id="denuncias-tab"  href="<?php echo e(url('/admin')); ?>" role="tab" aria-controls="denuncias" aria-selected="false">Denúncias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hist_bans-tab" href="<?php echo e(url('/admin/history')); ?>" role="tab" aria-controls="hist_bans" aria-selected="false">Histórico Bans</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="add_admin-tab" href="<?php echo e(url('/admin/add')); ?>" role="tab" aria-controls="add_admin" aria-selected="false">Adicionar Admin</a>
            </li>
            <li class="nav-item ml-md-auto">
                <a class="nav-link active" id="search_user-tab" href="<?php echo e(url('/admin/search')); ?>" role="tab" aria-controls="search_user" aria-selected="true">
                    <i class="fa fa-search"></i> Pesquisar</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- DENUNCIAS CONTENT -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            <div class="tab-pane fade" id="denuncias" role="tabpanel" aria-labelledby="denuncias-tab">
            </div>
            <div class="tab-pane fade" id="hist_bans" role="tabpanel" aria-labelledby="hist_bans-tab">
                <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- BAN HIST CONTENT -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            </div>
            <div class="tab-pane fade" id="add_admin" role="tabpanel" aria-labelledby="add_admin-tab">
                <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- ADD ADMIN -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
            </div>
            <div class="tab-pane fade show active" id="search_user" role="tabpanel" aria-labelledby="search_user-tab">
                <!-- -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- SEARCH USER -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/- -->
                <h3 class="text-center">Utilizadores</h3>
                <hr>
                <div id="search_users">
                    <form method="GET" action="<?php echo e(route('admin.search')); ?>" id="searchFormAdmin" class="form-inline my-2 justify-content-center">
                        <?php echo csrf_field(); ?>
                        <input id="searchInputAdmin" class="form-control" name="search" type="search" placeholder="Procurar User" aria-label="Search">
                        <button class="btn btn-outline-info my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <hr>
                <table class="table table-responsive-md table-bordered table-sm" id="search_user_table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="search_user_table_body" class="not_center">

                        <?php if(isset($users)): ?>
                            <?php echo $__env->renderEach('partials.userSearch', $users, 'users'); ?>
                        <?php endif; ?>
                    </tbody>

                </table>
                <br>
                <div class="row justify-content-around justify-content-md-center">
                    <nav aria-label="AdminPageNav" class="text-center">
                        <ul class="pagination text-center">
                            <?php if(isset($users)): ?>
                                <?php if(isset($search)): ?>
                                    <?php echo e($users->appends(['search' => $search])->links()); ?>

                                <?php else: ?>
                                    <?php echo e($users->links()); ?>

                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/pages/adminsearch.blade.php ENDPATH**/ ?>