<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/admin.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    Admin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="<?php echo e(URL::asset ('/js/adminHistory.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container w-75 mb-4" id="admin_main_container" style="margin-top:60px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #f8f9fa;">
            <li class="nav-item">
                <a class="nav-link " id="denuncias-tab" href="<?php echo e(url('/admin')); ?>" role="tab" aria-controls="denuncias" aria-selected="false">Denúncias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="hist_bans-tab" href="<?php echo e(url('/admin/history')); ?>" role="tab" aria-controls="hist_bans" aria-selected="true">Histórico Bans</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="add_admin-tab"  href="<?php echo e(url('/admin/add')); ?>" role="tab" aria-controls="add_admin" aria-selected="false">Adicionar Admin</a>
            </li>
            <li class="nav-item ml-md-auto">
                <a class="nav-link" id="search_user-tab" href="<?php echo e(url('/admin/search')); ?>" role="tab" aria-controls="search_user" aria-selected="false">
                    <i class="fa fa-search"></i> Pesquisar</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!---------------------------------------------DENUNCIAS CONTENT-------------------------------------------->
            <div class="tab-pane fade show" id="denuncias" role="tabpanel" aria-labelledby="denuncias-tab">
            </div>
            <div class="tab-pane fade show active" id="hist_bans" role="tabpanel" aria-labelledby="hist_bans-tab">
                <!---------------------------------------------BAN HIST CONTENT-------------------------------------------->
                <h3 class="text-center">Banidos</h3>
                <hr>
                <table class="table table-responsive-lg table-bordered table-sm" id="hist_ban_table">
                    <thead class="thead-light">
                        <tr>
                            <th>Data <button class="btn btn-white btn-sm" type="button"><i class="fa fa-caret-down"></i></button></th>
                            <th>User <button class="btn btn-white btn-sm" type="button"><i class="fa fa-caret-down"></i></button></th>
                            <th colspan="3">Castigo</th>
                            <th colspan="3">Denúncia</th>
                        </tr>
                    </thead>
                    <tbody class="not_center">
                        
                            <?php echo $__env->renderEach('partials.userReport', $usersReported, 'usersReported'); ?>
                        
                    </tbody>
                </table>
                <br>
                <div class="row justify-content-around justify-content-md-center">
                    <nav aria-label="AdminPageNav" class="text-center">
                    </nav>
                </div>
            </div>  
        </div>
    </div>

    <!-- Modals -->
    <!-- Delete Modal -->
    <div class="modal fade" id="DeleteBan" tabindex="-1" role="dialog" aria-labelledby="DeleteBanLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DeleteBanLabel">Desbanir User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    Tens a certeza que queres desbanir este utilizador?
                </div>
                <div class="modal-footer">
                    <button id="action-1" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="action-2" type="button" class="btn btn-primary" data-dismiss="modal">Sim!</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/pages/adminhistory.blade.php ENDPATH**/ ?>