<?php $__env->startSection('title'); ?>
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
&middot; Product
<?php $__env->stopSection(); ?>

<?php $__env->startSection('meta'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(URL::asset('/css/products.css')); ?>">
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
<?php $isauction=FALSE; ?>
<table hidden border=1>
    <tr>
        <td>ID</td>
        <td>Name</td>
    </tr>



    <?php if(!empty($auction)){   $isauction=TRUE;  } ?>
    <?php $__currentLoopData = $user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($user->id); ?></td>
        <td><?php echo e($user->username); ?></td>
        <td><?php echo e($user->photo); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</table>

<!--('active', 'inactive', 'removed', 'cancelled'); -->
<div class="container" id="outer" auction="<?php echo e($isauction); ?>">
    <?php if($product->state_product=='active' || $product->state_product=='inactive'){?>
    </br>
    <div class="row">
        <div class="product-title col-8">
            <h1><?php echo e($product->name_product); ?></h1>
        </div>
        </p>
        <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Denúncia:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="title" name="titulo"
                                    placeholder="título da denúncia" required pattern="[a-zA-Z0-9]*" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <textarea class="form-control" placeholder="Explicitar Denúncia." required
                                    pattern="[a-zA-Z0-9\.\_]*" maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-info">Submeter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="imgAbt">
            <a href="#">
                <img src=<?php echo e($product->photo); ?> class="img-thumbnail" alt="<?php echo e($product->name); ?>" id="image" onClick="swipe();"></a>
            </div>
        </div>
        <div class="col-md-8">
            <?php echo e($product->description); ?>


            <hr><?php if (!$isauction && $product->state_product=='active'){ ?>
            <div class="row">

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('buy', $product)): ?>
                <div class="btn-group cart col-md-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBuy">
                        Comprar Agora!
                    </button>
                </div>

                <?php endif; ?>
                <div class="product-price col-md-2">Preço: <span class="font-propria-bold">
                        <?php echo  $buyitnow->final_value ?> Eur </span></div>
            </div>
            <?php } else{ if($product->state_product=='active'){ ?>
            <?php if(!empty($biddings->first())){ ?>

            <div class="product-price">Licitação atual: <span
                    class="font-propria-bold"><?php echo e($biddings->first()->value_bid); ?></span></div>
            </br>
            <?php } {?>
            <div class="product-price">Licitação atual: <span
                    class="font-propria-bold"><?php echo e($auction->bidding_base); ?></span></div>
            </br>
            <?php }} else {?> <h1>Terminado</h1>

            <?php if($isauction && !empty($biddings->first())){  ?>
            <div class="product-price">Licitação vencedora: <span
                    class="font-propria-bold"><?php echo e($biddings->first()->value_bid); ?></span></div>
            <?php } else { ?>
            <div class="product-price"> <span class="font-propria-bold">Sem licitações</span></div>
            <?php } ?>

            </br> <?php  }if($product->state_product=='active'){?>

            <div class="row">


                <div class="input-group mb-3 col-lg-5">
                    <div class="input-group-prepend">
                        <span class="input-group-text ">Sua licitação</span>
                    </div>
                    <?php if(!empty($biddings->first())){ ?>
                    <?php $placeholder = $biddings->first()->value_bid+10; ?>
                    <?php }else{   $placeholder =$auction->bidding_base;  } ?>
                    <input type="text" name="bidValue" id="bidValue" class="form-control col-lg-5"
                        placeholder=<?php echo e($placeholder); ?> aria-label="Amount">
                    <div class="input-group-append">
                        <span class="input-group-text">EUR</span>
                    </div>
                </div>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bid', $product)): ?>
                <div class="btn-group cart col-lg-3">
                    <button onclick="getBid()" type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modalBid">
                        Licitar!
                    </button>

                </div>
                <?php endif; ?>

            </div>
            <?php  } } ?>



            <hr>
            <div>Estado: <span class="font-propria-bold"><?php echo($product->is_new == TRUE)? "Novo": "Usado" ?></span>
            </div>
            <hr>
            <div>Vendedor: <a href="<?php echo e(route('showuser', ['id' => $user->id])); ?>" style="color:var(--main-font-color);"><span
                        class="font-propria-bold"><?php echo e($user->username); ?></span></a></div>
            <div id="votes">
                Votos: <span class="font-propria-bold"><?php echo e($user->total_votes); ?></span>
            </div>
            <?php if($isauction && !empty($biddings->first())){ ?>
            <hr>
            <div class="product-desc">
                </br>
 
             
                <div class="row">
                    <ul class="media-list">
                        <ul>
                            <?php $__currentLoopData = $biddings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bidding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <section id="bids">
                                <?php echo $__env->make('partials.bid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            </section>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <article class="bid" id="bid-article"></article>
                        </ul>
                       
                    </ul>
                </div>
             
            </div>
            <?php } ?>
            <hr>
            <p class=" product-title col-12 pr-0" align="right">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('report',$product)): ?>
                <button type="button" class="btn " data-toggle="modal" data-target="#report">
                    <i class="fa fa-ellipsis-h" style="opacity:0.7;"></i>
                </button>
                <?php endif; ?>
            </p>



            <?php if (!$isauction) { ?>
            <!-- Modal -->
            <div class="modal fade" id="modalBuy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">eBaw - confirmação</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Confirma que quer comprar este produto por <?php echo e($buyitnow->final_value); ?>

                                Eur?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                            <button id="addBuy" product="<?php echo e($product->id); ?>" bidder="<?php echo e(Auth::id()); ?>"
                                value=<?php echo e($buyitnow->final_value); ?> type="button" class="btn btn-info">Sim</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <!-- Modal -->
            <div class="modal fade" id="modalBid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalBid">eBaw - confirmação</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <p>Confirma que quer fazer uma licitação de <span id="biddingValue"></span> Eur?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                            <button id="addBid" product="<?php echo e($product->id); ?>" bidder="<?php echo e(Auth::id()); ?>" value=getBid()
                                type="button" class="btn btn-info">Sim</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div id="commentSection" class="row bootstrap snippets">
                <div class="col-md-10 col-md-offset-2 col-sm-12">
                    <div class="comment-wrapper">
                        <div class="panel panel-info">
                            <div class="panel-body"><?php if(auth()->guard()->check()): ?>
                                <textarea id="commentText" class="form-control" placeholder="Escreva um comentário..."
                                    rows="3"></textarea>
                                <br>
                                <button id="addComment" product="<?php echo e($product->id); ?>" commenter="<?php echo e(Auth::id()); ?>" liker=<?php echo e(Auth::id()); ?>

                                    type="button" class="btn btn-info pull-right">Submeter</button>
                                <div class="clearfix"></div>
                                <hr><?php endif; ?>
                                <ul class="media-list">
                                    <ul>
                                        <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <section id="comments">
                                            <?php echo $__env->make('partials.comment', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                           
                                            
                                        </section>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                         <article class="comment" id="comment-article"></article>
                                    </ul>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $product)): ?>
            <div class="btn-group cart col-lg-3"><a href="/products/<?php echo e($product->id_product); ?>/edit/">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBid">
                        Alterar Venda
                    </button>
                </a>
            </div>
            <?php endif; ?>

        </div>
        <?php } else{
            echo"Produto Inexistente";}?>
    </div>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/wip/lbaw2036/resources/views/pages/product.blade.php ENDPATH**/ ?>