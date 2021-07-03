<tr class="userSearch-info" data-id-user="<?php echo e($users->id); ?>" data-id-username="<?php echo e($users->username); ?>">

    <th scope="row"><?php echo e($users->id); ?></th>
    <td><a href="/users/<?php echo e($users->id); ?>"><?php echo e($users->username); ?></a></td>
    <td><?php echo e($users->email); ?></td>
    <td><button class="btn btn-white btn-sm searchBanBtn" type="button" data-toggle="modal" data-target="#BanModel">
        <i class="fa fa-ban"></i></button>
    </td>

</tr>

<!-- Modals -->
<!-- Ban Modal -->
<div class="modal fade" id="BanModel" tabindex="-1" role="dialog" aria-labelledby="BanModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="BanModelLabel">Banir <?php echo e($users->username); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo e(url('/admin/bans/member/' . $users->id)); ?>"> 
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="consequence" value="ban">

                <div class="modal-body text-left">
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
                        <label for="id_product">Id Produto (opcional)</label>
                        <input type="number" class="form-control" id="id_product" name="id_product" min="1">
                    </div>
                    <div class="form-group" id="punishement_spanForm" required>
                        <label for="punishement_span">Dias de ban *</label>
                        <input type="number" class="form-control" id="punishement_span" name="punishement_span" min="1" required>
                    </div>
                    <div class="form-group" id="banReasonTextForm" required>
                        <label for="banReasonText">Razão *</label>
                        <textarea class="form-control" id="banReasonText" name="observation_admin" rows="3" placeholder="Razões para consequência..." maxlength="1000" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="consequenceSearchConfirmation" type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/partials/userSearch.blade.php ENDPATH**/ ?>