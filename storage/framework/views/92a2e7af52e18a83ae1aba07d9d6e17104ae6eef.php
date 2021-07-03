<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/admin.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    Admin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="<?php echo e(URL::asset ('/js/admin.js')); ?>" defer></script>
<script type="text/javascript" src="<?php echo e(URL::asset ('/js/global.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    <div class="container w-75 mb-4" id="admin_main_container" style="margin-top:60px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #f8f9fa;">
            <li class="nav-item">
                <a class="nav-link active" id="denuncias-tab" data-toggle="tab" href="<?php echo e(url('/admin/report')); ?>" role="tab" aria-controls="denuncias" aria-selected="true">Denúncias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hist_bans-tab" href="<?php echo e(url('/admin/history')); ?>" aria-controls="hist_bans" aria-selected="false">Histórico Bans</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="add_admin-tab" href="<?php echo e(url('/admin/add')); ?>" aria-controls="add_admin" aria-selected="false">Adicionar Admin</a>
            </li>
            <li class="nav-item ml-md-auto">
                <a class="nav-link" id="search_user-tab" href="<?php echo e(url('/admin/search')); ?>" aria-controls="search_user" aria-selected="false">
                    <i class="fa fa-search"></i> Pesquisar</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!---------------------------------------------DENUNCIAS CONTENT-------------------------------------------->
            <div class="tab-pane fade show active" id="denuncias" role="tabpanel" aria-labelledby="denuncias-tab">
                <h3 class="text-center">Denúncias</h3>
                <hr>
                <form class="form-inline">
                    <label class="mr-2"><strong>Filtros:</strong></label>
                    <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                        <option value="myDens">Minhas</option>
                        <option value="toBeSolved">Assumir</option>
                        <option value="solving">Assumido</option>
                        <option value="solved">Resolvido</option>
                        <option value="byDays">+ Recentes</option>
                        <option value="byId">Id</option>
                    </select>
                </form>
                <hr>
                <div class="list-group">
                    <?php echo $__env->renderEach('partials.report', $report, 'report'); ?>                    
                </div>

                <br>
                
                <div class="row justify-content-around justify-content-md-center">
                    <nav aria-label="AdminPageNav" class="text-center">        
                    </nav>
                </div>

            </div>
        </div>
    </div>
    <!-- Modals -->
    <!-- Assume Modal -->
    <div class="modal fade" id="AssumeDen" tabindex="-1" role="dialog" aria-labelledby="AssumeDenLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AssumeDenLabel">Assumir Denúncia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">
                        Tens a certeza que queres assumir esta denúncia?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-info">Sim!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/pages/admin.blade.php ENDPATH**/ ?>