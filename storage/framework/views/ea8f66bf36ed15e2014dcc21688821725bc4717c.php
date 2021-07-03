<!DOCTYPE html>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="<?php echo e(URL::asset ('/js/api.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<article class="comment" data-id="<?php echo e($comment->id_comment); ?>">

    <li class="media">
        <a href="<?php echo e(route('showuser', ['id' => $comment->id_commenter])); ?>" class="pull-left">
            <img src="<?php echo e($comment->photo); ?>" width="64px" height="64px" alt="Foto de perfil" class="img-circle mr-2">
            <!--[TODO]mudar para foto real-->
        </a>
        <div class="column">
        <div class="media-body">
            <div class="imgAbt pull-right">
                <?php if(Auth::id() !== $comment->id_commenter): ?>
                <button type="button" class="btn btn-lg" data-toggle="modal" data-target="#reportComment" data-id="<?php echo e($comment->id_comment); ?>">
                    ...
                </button>
                <?php endif; ?>
            </div>
            <span class="text-muted pull-right">
                <small class="text-muted"><?php echo e($comment->date_comment); ?></small>
            </span>
            <a href="<?php echo e(route('showuser', ['id' => $comment->id_commenter])); ?>"><?php echo e($comment->username); ?></a>
            <p>
                <?php echo e($comment->msg_ofcomment); ?>

                <span class="text-muted pull-right">
                <?php if(Auth::guard('admin')->check()): ?>
                    <button style="background-color:#ffffff;border:0">
                    <img src="../trashcan.svg" style="width:20px;" class="remove-comment" id="<?php echo e($comment->id_comment); ?>" product="<?php echo e($product->id); ?>"
                    comment="<?php echo e($comment->id_comment); ?>" liker="<?php echo e(Auth::id()); ?>" />
                    </button>
                <?php elseif(Auth::check()): ?> 
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete',$comment)): ?>
                        <button style="background-color:#ffffff;border:0">
                            <img src="../trashcan.svg" style="width:20px;" class="remove-comment" id="<?php echo e($comment->id_comment); ?>" product="<?php echo e($product->id); ?>"
                                comment="<?php echo e($comment->id_comment); ?>" liker="<?php echo e(Auth::id()); ?>" />
                        </button>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('delete',$comment)): ?>
                        <button style="background-color:#ffffff;border:0">
                        <img src="../like.svg" style="width:20px;" class="put-like" id="putLike" product="<?php echo e($product->id); ?>"
                        comment="<?php echo e($comment->id_comment); ?>" liker="<?php echo e(Auth::id()); ?>" />
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
                    <small class="text-muted" id="numberlikes"><?php echo e($comment->comment_likes); ?><i>likes</i></small>
                </span>

            </p>
        </div> 
        <div>

        </div>
        
    </li>
  
</article>

<div class="modal fade" id="reportComment" tabindex="-1" role="dialog" aria-labelledby="reportCommentModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportCommentModelLabel">Denúncia de Comentário:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo e(url('/products/comments/report/' . $comment->id_comment)); ?>"> 
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
</div><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/partials/comment.blade.php ENDPATH**/ ?>