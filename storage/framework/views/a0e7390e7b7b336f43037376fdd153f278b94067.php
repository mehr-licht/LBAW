<div class="row">
    <div class="col-lg-3 col-sm-5 prod_th container-fluid ">
        <a href="/products/<?php echo e($product->id); ?>">
            <img src="<?php echo e($product->photo); ?>" width="100%" height="auto" alt="<?php echo e($product->name_product); ?>">
        </a>
    </div>

    <div class="col-lg-9 text-left product">
        <a href="/products/<?php echo e($product->id); ?>">
            <h3 class="product-title product">
                <?php echo e($product->name_product); ?>

            </h3>
            <br>
        </a>
        <div class="row container">
            <div class="column col-lg-5">

                <div>
                    <h6>Estado:
                        <span class="font-propria-bold"><?php echo e($product->state_product); ?></span></h6>
                </div>
                <div>
                    <h6 class="product-price product">
                        <?php if( isset($product->auction->final_value) ): ?>    
                            <span class="font-propria-bold">EUR
                                <?php echo e($product->auction->final_value); ?>

                            </span>
                        <?php else: ?>
                            <span class="font-propria-bold">EUR
                                <?php echo e($product->buyitnow->final_value); ?>

                            </span>
                        <?php endif; ?> 
                    </h6>
                </div>
                <div>
                <?php if($product->id_owner == Auth::Id()): ?>
                    <?php if( isset($product->auction->final_value) ): ?>
                    <button type="button " class="btn btn-primary add_14 "
                        onclick="goToLicitationPage(<?php echo e($product->id); ?> );" data-toggle="modal "
                        data-target="#exampleModal ">
                        Ver! </button>
                    <?php else: ?>
                    <button type="button " class="btn btn-primary add_14 " data-toggle="modal"
                        onclick="goToBuyPage(<?php echo e($product->id); ?>);" data-target="#exampleModal ">
                        Ver! </button>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if( isset($product->auction->final_value) ): ?>
                    <button type="button " class="btn btn-primary add_14 "
                        onclick="goToLicitationPage(<?php echo e($product->id); ?> );" data-toggle="modal "
                        data-target="#exampleModal ">
                        Licitar! </button>
                    <?php else: ?>
                    <button type="button " class="btn btn-primary add_14 " data-toggle="modal"
                        onclick="goToBuyPage(<?php echo e($product->id); ?>);" data-target="#exampleModal ">
                        Comprar! </button>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>

            <div class="column col-lg-2"></div>

            <div class="column col-lg-5">
                <div>
                    <span></span>
                </div>
                <div>
                    <?php if(Auth::check()): ?>
                        <a href="/user/<?php echo e($product->user->id); ?>"><?php echo e($product->user->username); ?> <span>Votes:<?php echo e($product->user->total_votes); ?></span></a>    
                    <?php else: ?>
                         <a href="<?php echo e(url('/login')); ?>"><?php echo e($product->user->username); ?></a>
                    <?php endif; ?>
                    
                </div>
                <div>
                    <h5>

                        <?php if( isset($product->auction->final_value) ): ?>
                        <span id="dateCountDown" class="font-propria-bold">
                            <?php echo e($product->auction->date_end_auction); ?>

                        </span>
                        <?php else: ?>
                        <span id="dateCountDown" class="font-propria-bold">
                            <?php echo e($product->buyitnow->date_end); ?>

                        </span>
                        <?php endif; ?>

                        
                    </h5>
                </div>
            </div>

        </div>
    </div>

</div>
<hr>
<br>
<?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/partials/product.blade.php ENDPATH**/ ?>