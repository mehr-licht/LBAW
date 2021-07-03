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
    
<div class="row container-fluid" style="margin-top:60px;">
    <div class="col-md-12 container">
        <div class="row ">
            
            <?php echo $__env->make('partials.filter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8" id="list_products">

                <br/>
                <h6 class="text-muted not_center">A mostrar todos os resultados</h6>
                <br/>
                    <div class="row">
                        <div class="col-sm-8 col-xs-12 col-lg-8">
                    
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                <a class="nav-link active" href="#">Populares</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" href="#"> + Recentes</a>
                                </li>
                                <li class="nav-item">
                                <a class="nav-link" href="#"> A Acabar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"> + Caros</a>
                                </li>
                            </ul>
                        </div>
                    
                        <div class="visible-xs col-xs-4 col-sm-4 col-lg-4">
                            <div class="dropdown xsFilter">
                                <button class="btn btn-default dropdown-toggle" type="button"
                                    data-toggle="dropdown">Filtros
                                    <span class="caret"></span>
                                </button>
                                
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header">Categoria</li>
                                    <li class="checkbox">
                                        <label><input type="checkbox" class="category"> Mobiliário</label>
                                    </li>
                                    <li class="checkbox">
                                        <label><input type="checkbox" class="category"> Electrodomésticos</label>
                                    </li>
                                    <li class="checkbox">
                                        <label><input type="checkbox" class="category"> Jogos</label>
                                    </li>

                                    <li class="divider"></li>
                                    <li class="dropdown-header">Preço
                                        <ul class="dropdown-menu">
                                        </ul>

                                    <li class="divider"></li>

                                    <li class="dropdown-header">Data</li>
                                    <li class="divider"></li>
                                    <li class="dropdown-header">Estado</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                <section id="cards">
                    <?php echo $__env->renderEach('partials.product', $products, 'product'); ?>
                </section>        
            </div>
            
            <div class="row justify-content-center">
                <div id="pagination">
                    <?php echo $products->render(); ?>
                </div>
            </div> 
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/attempt2/lbaw2036/resources/views/pages/products.blade.php ENDPATH**/ ?>