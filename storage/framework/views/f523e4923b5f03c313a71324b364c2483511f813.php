<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('css/products.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">
    <style>
        @import  url('https://fonts.googleapis.com/css?family=Open+Sans');
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    eBaw &middot; online shopping
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

    
<div id="body-mainContent" class="container" style="margin-top:45px;"> 
    <!--main slideshow-->
    <div class="jumbotron">

        <div id="main_carousel" class="carousel slide" data-ride="carousel" data-interval="4000">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="row">
                
                <div class="col-12 col-lg-3 carousel_introduction">
                <h2>Veja o que está em alta na Categoria Artes</h2>
                    <a href="<?php echo e(url('/products')); ?>" class="btn btn-outline-primary">Compre agora 
                        <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>
                
                <?php $__currentLoopData = $arts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-4 col-lg-3">
                        <a href="<?php echo e(url('/products', [ $art->id ])); ?>">
                            <img src="<?php echo e($art->photo); ?>" alt="<?php echo e($art->name_product); ?>">
                            <div class="carousel-caption d-none d-sm-block">
                            <h5><?php echo e($art->name_product); ?></h5>
                                <p><?php echo e($art->final_value); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
          </div>

          <div class="carousel-item">
            <div class="row">
                <div class="col-12 col-lg-3 carousel_introduction">
                    <h2>Veja o que está em alta na Categoria Computers</h2>
                    <a href="<?php echo e(url('/products')); ?>" class="btn btn-outline-primary">Compre agora 
                        <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>
                
                <?php $__currentLoopData = $computers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $computer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-sm-4 col-lg-3">
                        <a href="<?php echo e(url('/products', [ $computer->id ])); ?>">
                            <img src="<?php echo e($computer->photo); ?>" alt="<?php echo e($computer->name_product); ?>">
                            <div class="carousel-caption d-none d-sm-block">
                                <h5><?php echo e($computer->name_product); ?></h5>
                                <p><?php echo e($computer->final_value); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                
            </div>
          </div>

          <div class="carousel-item">
            <div class="row">
                <div class="col-12 col-lg-3 carousel_introduction">
                    <h2>Veja o que está em alta na Categoria Comics</h2>
                    <a href="<?php echo e(url('/products')); ?>" class="btn btn-outline-primary">Compre agora 
                        <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>

                <?php $__currentLoopData = $comics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>    
                    <div class="col-sm-4 col-lg-3">
                        <a href="<?php echo e(url('/products', [ $comic->id ])); ?>">
                            <img src="<?php echo e($comic->photo); ?>" alt="<?php echo e($comic->name_product); ?>">
                            <div class="carousel-caption d-none d-sm-block">
                                <h5><?php echo e($comic->name_product); ?></h5>
                                <p><?php echo e($comic->final_value); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
            </div>
          </div>

        </div>
        <a class="carousel-control-prev" href="#main_carousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#main_carousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
        </div>
    </div>
    <!-- Slideshow -->

    <!-- Day Offers-->
    <div class="container">
        <div class="container daily_offer_header">
            <div class="row">
                <h3 class="col-auto daily_offers">
                    <a href="<?php echo e(url('/products')); ?>">
                        Ofertas do dia
                    </a>
                </h3>
                <div class="col-auto see_all">
                    <a href="<?php echo e(url('/products')); ?>">
                        Ver todos <i class="fa fa-arrow-right" style="font-size: small;"></i>
                    </a>
                </div>
            </div>
        </div>
        <div id="daily_offers_carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        
                        <?php $__currentLoopData = $dayOffersPlaneOnes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayOffersPlaneOne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6 col-sm-4 col-lg-2">
                                <a href="<?php echo e(url('/products', [ $dayOffersPlaneOne->id ])); ?>">
                                    <img src="<?php echo e($dayOffersPlaneOne->photo); ?>" alt="<?php echo e($dayOffersPlaneOne->name_product); ?>">
                                    <div class="carousel-caption d-none d-sm-block">
                                        <p><?php echo e($dayOffersPlaneOne->final_value); ?></p>
                                    </div>
                                </a>
                            </div>   
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>

                <div class="carousel-item">
                    <div class="row">
                        
                        <?php $__currentLoopData = $dayOffersPlaneTwos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayOffersPlaneTwo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6 col-sm-4 col-lg-2">
                                <a href="<?php echo e(url('/products', [ $dayOffersPlaneTwo->id ])); ?>">
                                    <img src="<?php echo e($dayOffersPlaneTwo->photo); ?>" alt="<?php echo e($dayOffersPlaneTwo->name_product); ?>">
                                    <div class="carousel-caption d-none d-sm-block">
                                        <p><?php echo e($dayOffersPlaneTwo->final_value); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </div>
                </div>

                
            </div>
            <a class="carousel-control-prev" href="#daily_offers_carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#daily_offers_carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/attempt2/lbaw2036/resources/views/pages/index.blade.php ENDPATH**/ ?>