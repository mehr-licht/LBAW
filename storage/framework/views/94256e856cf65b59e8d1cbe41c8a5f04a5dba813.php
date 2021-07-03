

<article class="comment" data-id="<?php echo e($comment->id); ?>">
    
    <li class="media">
        <a href="<?php echo e(route('showuser', ['id' => $comment->id_commenter])); ?>" class="pull-left">
            <img src="<?php echo e($comment->photo); ?>" width="64px" height="64px" alt="Foto de perfil"
                class="img-circle mr-2">
            <!--[TODO]mudar para foto real-->
        </a>
        <div class="media-body">
            <div class="imgAbt pull-right">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('report',$product)): ?>
                <button type="button" class="btn btn-lg" data-toggle="modal" data-target="#report">
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


                <span class="text-muted pull-right"><button style="background-color:#ffffff;border:0" id="putLike" product="<?php echo e($product->id); ?>" comment="<?php echo e($comment->id_comment); ?>" liker="<?php echo e(Auth::id()); ?>"><img src="../like.svg" style="width:20px;"></button>
                    <small class="text-muted"  ><?php echo e($comment->comment_likes); ?><i>likes</i></small>
                </span> </p>
        </div>

    </li>
</article><?php /**PATH /home/luis/git/A4S2/LBAW/attempt2/lbaw2036/resources/views/partials/comment.blade.php ENDPATH**/ ?>