<!---------------------------------------------- NAV BEGIN ------------------------------------------------>
<div>
    <!-- class="fixed-top"-->
    <div role="navigation" class="shadow-lg bg-white rounded">
        <nav id="barra_menu" class="navbar navbar-expand-md ">
            <a class="navbar-brand" href="/">
                <img class="img" src="../Logo_ebaw_Horizontal.png" style="width:200px;height: auto;">
            </a>
            <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!----------------------------------------- BEGIN SEARCH BAR -------------------------------------------->
            <div id="search" style="width:100%; min-width: 33%;">
                <form class="form-inline">
                    <div id="search_bar_container" class="container">
                        <div class="row justify-content-center">
                            <div class="col-3 col-lg-2 px-0">
                                <!--Categorias-->
                                <select class="custom-select" name="categoria">
                                    <option selected value="todos">Todos</option>
                                    <option value="x">Mobiliário</option>
                                    <option value="y">Eletrodomésticos</option>
                                    <option value="z">Jogos</option>
                                    <option value="outros">Outros</option>

                                </select>
                            </div>
                            <div class="col-7 col-lg-9 px-0">
                                <input class="form-control" type="search" name="search" placeholder="Procurar"
                                    aria-label="Search" style="width:100%; height:100%;">
                            </div>
                            <div class="col-2 col-lg-1 px-0">
                                <button class="btn btn-info" type="submit" style="height:100%; width:100%;">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!----------------------------------------- END SEARCH BAR -------------------------------------------->
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <?php if(Auth::check()): ?>
                            <a class="nav-link" href="<?php echo e(url('/products/create')); ?>">Vender</a>
                        <?php else: ?>
                         <a class="nav-link" href="<?php echo e(url('/login')); ?>">Vender</a>
                        <?php endif; ?>    
                    </li>
                   
                    <li class="nav-item">
                        
                        <?php if(Auth::check()): ?>
                            <a class="nav-link" href="/users/<?php echo e(Auth::getUser()->id); ?>/notifications">    
                        <?php else: ?>
                            <a class="nav-link" href="<?php echo e(url('/login')); ?>">
                        <?php endif; ?>

                            <div class="d-none d-md-inline-flex">
                                <img src="../si-glyph-bellw.svg" style="width:20px;">
                                <span id="h-nr-notifications1" class="badge badge-danger"
                                    style="font-size: 10px; top: -10px; position: relative; padding: .55em .5em;">9</span>
                            </div>
                            <!--Notificações-->
                            <span id="h-notifications" class="d-block d-md-none">
                                Notificações
                                    
                                <?php if(Auth::check()): ?>
                                        <span id="h-nr-notifications2" class="badge badge-danger d-inline-flex d-md-none mx-2">
                                            <?php // {{Auth::user()->notifications()}} ?>
                                        </span>
                                <?php else: ?>
                                    <span id="h-nr-notifications2" class="badge badge-danger d-inline-flex d-md-none mx-2">
                                        9
                                    </span>
                                <?php endif; ?>
                            </span>
                        </a>
                    </li>

                        <li class="nav-item dropdown"> 
                            <span id="Conta" style="color:white;">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: inline-flex;">
                                    <img src="../si-glyph-person-2w.svg" style="width:20px;"> 
                                    <span id="h-user" class="h-user d-block d-md-none d-lg-block" style="margin-left:.3em;">
                               
                                <?php if(Auth::check()): ?>
                                     <?php echo e(Auth::getUser()->name); ?>

                                <?php else: ?>
                                        User
                                <?php endif; ?>

                                    </span>
                                </a>
                                
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                
                                    <?php if(Auth::check()): ?>
                                        <a class="dropdown-item" href="/users/<?php echo e(Auth::getUser()->id); ?>/history">Histórico</a>
                                        <a class="dropdown-item" href="/users/<?php echo e(Auth::getUser()->id); ?>">Ver perfil</a>
                                        <a class="dropdown-item" href="/users/<?php echo e(Auth::getUser()->id); ?>/notifications">Notificações</a>
                                    <?php else: ?>
                                        <a class="dropdown-item" href="<?php echo e(url('/login')); ?>">Histórico</a>
                                        <a class="dropdown-item" href="<?php echo e(url('/login')); ?>">Ver perfil</a>
                                        <a class="dropdown-item" href="<?php echo e(url('/login')); ?>">Notificações</a>
                                    <?php endif; ?>
                                    
                                
                                    <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo e(url('/faq')); ?>">FAQ</a>
                                <a class="dropdown-item" href="<?php echo e(url('/contact')); ?>">Help & Contact</a>
                                <div class="dropdown-divider"></div>
                                <?php if(Auth::check()): ?>        
                                    <a class="dropdown-item" href="/users/<?php echo e(Auth::getUser()->id); ?>/edit">Editar Perfil</a>    
                                <?php else: ?>
                                    <a class="dropdown-item" href="<?php echo e(url('/login')); ?>">Editar Perfil</a>
                                <?php endif; ?>    
                            </div>
                                    
                                
                        </span>
                    </li>
                    <li class="nav-item"><?php if(auth()->guard()->check()): ?>
                        <a class="nav-link" href="<?php echo e(url('/logout')); ?>">
                            <img src="../si-glyph-sign-outw.svg" style="width:25px;"><?php else: ?> <a class="nav-link"
                                href="<?php echo e(url('/login')); ?>"> <img src="../si-glyph-sign-inw.svg"
                                    style="width:25px;"><?php endif; ?>
                            </a>
                    </li>
                </ul>
        </nav>
    </div>
</div>

<!---------------------------------------------- NAV END ------------------------------------------------>
<?php /**PATH /home/luis/git/A4S2/LBAW/wip/lbaw2036/resources/views/includes/header.blade.php ENDPATH**/ ?>