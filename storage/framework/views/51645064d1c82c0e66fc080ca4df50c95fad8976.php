<a href="/report/<?php echo e($report->id); ?>" class="list-group-item list-group-item-action">
    <div class="d-sm-flex w-100 justify-content-between">
    <h5><span class="badge badge-info">#<?php echo e($report->id); ?></span></h5>
    <h5 class="mb-1"><?php echo e($report->reason); ?></h5>
        <h5>
            <?php if($report->state_report == 'assume'): ?>
                <button class="btn btn-success btn-sm" 
                    type="button" 
                    style="width: 5rem" 
                    data-toggle="modal" 
                    data-target="#AssumeDen">Assumir</button>

            <?php elseif($report->state_report == 'assumed'): ?>
                <button class="btn btn-secondary btn-sm" 
                        type="button" 
                        style="width: 5rem" 
                        data-toggle="modal" 
                        data-target="#AssumeDen">Assumido</button>       
            <?php else: ?>
                <button class="btn btn-danger btn-sm" 
                        type="button" 
                        style="width: 5rem" 
                        data-toggle="modal" 
                        data-target="#AssumeDen"><?php echo e($report->state_report); ?></button>       
            <?php endif; ?>

    </h5>
    </div>
    <hr class="mt-2" />
    <p class="mb-1 text-left"><?php echo e($report->text_report); ?></p>
    <div class="d-flex w-100 mt-3 justify-content-between">
    <small>Denunciante: <strong><?php echo e($report->userReporter->username); ?></strong></small> <small><?php echo e($report->date_report); ?></small>
    </div>
</a>
<?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/partials/report.blade.php ENDPATH**/ ?>