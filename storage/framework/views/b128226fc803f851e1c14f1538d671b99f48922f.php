<div class="row">
    <div class="col-lg-3 prod_th">
        <a href="/products/<?php echo e($product->id); ?>">
            <img src="<?php echo e($product->photo); ?>" width="auto" height="auto" alt="<?php echo e($product->name_product); ?>">
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
                        <h6 class="product-price product"><span class="font-propria-bold">EUR
                            <?php echo e($product->final_value); ?></span>
                        </h6>
                    </div>
                    <div>
                    
                        <?php if( isset($product->auction->bidding_base) ): ?>
                            <button type="button " class="btn btn-primary add_14 "  onclick="goToLicitationPage(<?php echo e($product->id); ?> );" 
                            data-toggle="modal " data-target="#exampleModal ">
                                Licitar! </button>
                        <?php else: ?>
                            <button type="button " class="btn btn-primary add_14 " data-toggle="modal" onclick="goToBuyPage(<?php echo e($product->id); ?>);"  
                            data-target="#exampleModal ">
                            Comprar! </button>
                        <?php endif; ?>

                    </div>
            </div>

         <div class="column col-lg-2"></div> 

            <div class="column col-lg-5">
                <div>
                    <span></span>
                </div>
                <div>
                    <a href="/products/<?php echo e($product->id); ?>"><?php echo e($product->description); ?></a> 
                </div>
                <div>
                    <h5>
    
                        <?php if( isset($product->auction->bidding_base) ): ?>
                            <span id="dateCountDown" class="font-propria-bold">
                                  <?php echo e($product->auction->date_end_auction); ?>

                            </span>
                        <?php else: ?>
                            <span id="dateCountDown" class="font-propria-bold">
                                <?php echo e($product->buyitnow->date_end); ?>

                            </span>            
                        <?php endif; ?>
                        
                        </span>
                    </h5>
                </div>
            </div>

        </div>
    </div>
    
</div>
<?php /**PATH /home/luis/git/A4S2/LBAW/actual/lbaw2036/resources/views/partials/product.blade.php ENDPATH**/ ?>