<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(url('css/products.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('/css/global.css')); ?>">

    <style>
        @import  url('https://fonts.googleapis.com/css?family=Open+Sans');
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
@parent
    <script type="text/javascript" src=<?php echo e(asset('js/createProductForm.js')); ?> defer>
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('title'); ?>
    eBaw &middot; online shopping
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="mx-auto" id="Criar_Prod_Main_Container" style="margin-top:30px;">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div><br />
        <?php endif; ?>

        <form action="<?php echo e(url('products/create')); ?>" method="post"  role="form" enctype="multipart/form-data">
            <br/>
                <h1 class="mb-4">Criar venda</h1>
                <div class="form-group">
                    <?php echo csrf_field(); ?>
                    <label for="titulo">Titulo</label>
                    <input type="text" class="form-control" id="titulo" aria-describedby="tituloProduto" name="nameProduct" 
                        placeholder="Titulo do Produto" required>
                </div>

                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary active">
                        <input type="radio" name="isNew" id="usado" autocomplete="off" value="false"> Usado
                    </label>
                    <label class="btn btn-outline-primary">
                        <input type="radio" name="isNew" id="novo" autocomplete="off" value="true"> Novo
                    </label>
                </div>
                <br/>
                <br/>

                <div class="form-group mb-3">
                    <label for="CategoriaSelect">Categoria</label>
                    <select class="custom-select" id="CategoriaSelect" style="border-radius: 0;" name="category">
                        <option selected>Escolha...</option>
    
                        <option value="antiques">Antiguidades</option>
                        <option value="art">Artes</option>
                        <option value="crafts">Artesanato</option>
                        <option value="baby">Bebés</option>
                        <option value="travel">Viagens</option>
                        <option value="electronics">Electrodomésticos</option>
                        <option value="toys">Brinquedos</option>
                        <option value="cars">Carros</option>    
                        <option value="sports">Desporto</option>
                        <option value="houseGarden">Jardinagem</option>
                        <option value="collecting">Colecção</option>
                        <option value="computers">Computadores</option>
                        <option value="music">Músicas</option>
                        <option value="musicalInstruments">Instrumentos Musicais</option>
                        <option value="movies">Filmes</option>
                        <option value="photo">Fotos</option>
                        <option value="watches">Relógios</option>
                        <option value="comics">Comics</option>
                        <option value="stamps">Selos</option>
                        <option value="stationary">Imóveis</option>
                        <option value="bargains">Negócios</option>
                        <option value="pottery">Cerâmica</option>
                        <option value="memorabiliaPortugal">Recordações</option>
                        <option value="clothingAndAccessories">Roupas e Acessórios</option>
                        <option value="healthBeauty">Beleza</option>
                        <option value="philately">Filatelia</option>
                        <option value="videoGames">VideoJogos</option>
                        <option value="coins">Moedas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Escolher Imagem
                    </label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-primary btn-file" style="border-top-right-radius:0;border-bottom-right-radius:0;">
                                Procurar… 
                                <input type="file" id="imgInp"  class="form-control" onchange="previewFile();" name="photo" />
                               
                            </span>
                            <img id="imagePreview" src="<?php echo e(asset('sample.png')); ?>" height="200" width="200" alt="Image preview">
                            <span class="text-danger"> <?php echo e($errors->first('photo')); ?></span>
                            <!-- print success message after file upload  -->
                            <?php if(Session::has('success')): ?>
                                <div class="alert alert-success">
                                    <?php echo e(Session::get('success')); ?>

                                    <?php
                                        Session::forget('success');
                                    ?>
                                </div>
                            <?php endif; ?>
                        </span>
                    </div>
                   
                </div>

                <div class="form-group">
                    <label for="DescricaoProduto">Descrição do Produto</label>
                    <textarea id="DescricaoProduto" class="form-control" aria-label="DescricaoProduto" name="description"></textarea>
                </div>

                <br/>

                <!--    VENDA DIRETA | LEILÃO   -->
                <div class="container" id="sale-auction-tabs" style="padding-left: 0;">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #f8f9fa;">
                        <li class="nav-item">
                            <a class="nav-link active" id="sale-tab" data-toggle="tab" href="#sale" role="tab" aria-controls="sale" aria-selected="true">Venda Direta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="auction-tab" data-toggle="tab" href="#auction" role="tab" aria-controls="auction" aria-selected="false">Leilão</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                        <br/>
                        <div class="tab-pane fade" id="auction" role="tabpanel" aria-labelledby="auction-tab">
                            <div class="form-group">
                                <label for="LicitacaoBase">Licitação Base (EUR)</label>
                                <input type="text" id="LicitacaoBase" class="form-control" 
                                        aria-label="Amount (to the nearest dollar)" 
                                        placeholder="Euros" 
                                        oninput="this.value=this.value.replace(/(?![0-9])./gmi,'')" name="biddingBase">
                            </div>
                            <div class="form-group">
                                <label for="DataFinal">Data final</label>
                                <input type="date" id="DataFinal" class="form-control" name="dateEndAuction">
                            </div>
                            <div class="form-group">
                                <label for="HoraFinal">Hora final</label>
                                <input type="time" id="HoraFinal" class="form-control" name="hourEndAuction">
                            </div>
                            <button type="submit" class="btn btn-primary">Criar Leilao</button>
                        </div>
                
                
                        <div class="tab-pane fade show active" id="sale" role="tabpanel" aria-labelledby="sale-tab">
                            <div class="form-group">
                                <label for="price">Preço (EUR)</label>
                                <input type="text" id="price" class="form-control" aria-label="Amount (to the nearest dollar)" 
                                        placeholder="Euros" 
                                        oninput="this.value=this.value.replace(/(?!([0-9]))./gmi,'')" 
                                        name="finalValue">
                            </div>
                            <div class="form-group">
                                <label for="FinalDate">Data final</label>
                                <input type="date" id="FinalDate" class="form-control" name="dateEnd">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Criar Venda</button>
                        </div>
                    </div>
                    
                </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luis/git/A4S2/LBAW/wip/lbaw2036/resources/views/pages/productForm.blade.php ENDPATH**/ ?>