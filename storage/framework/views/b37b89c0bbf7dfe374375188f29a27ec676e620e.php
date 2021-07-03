<div class="col-md-3 hidden-xs col-lg-3">
    <hr>

    <form method="GET" class="form-inline" action="/products/filter">
    <!--  <button type="button" class="btn input-group-text col-lg-10 text-center" data-toggle="collapse" data-target="#categories">Categoria</button>-->
    <div class=" input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text "
                for="DisponibilidadeSelect" style="width: 150px;">Disponibilidade</label>
        </div>
        <select class="custom-select col-md-5" id="DisponibilidadeSelect" name="disponibility" onchange="this.form.submit()">
        <?php if(isset($disponibility)): ?>    
            <?php if($disponibility == 'Active'): ?>
            <option value="all">Escolha...</option>
            <option value="Active" selected>Activo</option>
            <option value="Inactive">Inativo</option>
            <option value="Bought">Comprado</option>
            <?php elseif($disponibility == 'Inactive'): ?>
            <option value="all">Escolha...</option>
            <option value="Active">Activo</option>
            <option value="Inactive" selected>Inativo</option>
            <option value="Bought">Comprado</option>
            <?php elseif($disponibility == 'Bought'): ?>
            <option value="all">Escolha...</option>
            <option value="Active">Activo</option>
            <option value="Inactive">Inativo</option>
            <option value="Bought" selected>Comprado</option>
            <?php else: ?>
            <option value="all" selected>Escolha...</option>
            <option value="Active">Activo</option>
            <option value="Inactive">Inativo</option>
            <option value="Bought">Comprado</option>
            <?php endif; ?>
        <?php else: ?>
            <option value="all" selected>Escolha...</option>
            <option value="Active">Activo</option>
            <option value="Inactive">Inativo</option>
            <option value="Bought">Comprado</option>
        <?php endif; ?>
        </select>

    </div>
    <hr>
    <div class=" input-group mb-3 ">
        <div class="input-group-prepend ">
            <label class="input-group-text" for="TypeSelect" style="width: 150px;">Tipo de Venda</label>
        </div>
        <select class="custom-select col-md-5" id="TypeSelect" name="saleType" onchange="this.form.submit()">
        <?php if(isset($saleType)): ?>     
            <?php if($saleType == 'Sell'): ?>
            <option value="all">Escolha...</option>
            <option value="Sell" selected>Venda</option>
            <option value="Auction">Leilão</option>
            <?php elseif($saleType == 'Auction'): ?>
            <option value="all">Escolha...</option>
            <option value="Sell">Venda</option>
            <option value="Auction" selected>Leilão</option>
            <?php else: ?>
            <option value="all" selected>Escolha...</option>
            <option value="Sell">Venda</option>
            <option value="Auction">Leilão</option>
            <?php endif; ?>
        <?php else: ?>
            <option value="all" selected>Escolha...</option>
            <option value="Sell">Venda</option>
            <option value="Auction">Leilão</option>
        <?php endif; ?>
        </select>
    </div>
    <hr>

    <div class=" input-group mb-3 ">
        <div class="input-group-prepend ">
            <label class="input-group-text" for="PriceSelect" style="width: 150px;">Preço da Venda</label>
        </div>
        <select class="custom-select col-md-5" id="PriceSelect" name="priceType" onchange="this.form.submit()">
        <?php if(isset($priceType)): ?>    
            <?php if($priceType == 'under25'): ?>
            <option value="all">Escolha...</option>
            <option value="under25" selected> &lt 25€</option>
            <option value="until100"> 25-100€</option>
            <option value="until500"> 100-500€</option>
            <option value="above500"> &gt 500€</option>
            <?php elseif($priceType == 'until100'): ?>
            <option value="all">Escolha...</option>
            <option value="under25"> &lt 25€</option>
            <option value="until100" selected> 25-100€</option>
            <option value="until500"> 100-500€</option>
            <option value="above500"> &gt 500€</option>
            <?php elseif($priceType == 'until500'): ?>
            <option value="all">Escolha...</option>
            <option value="under25"> &lt 25€</option>
            <option value="until100"> 25-100€</option>
            <option value="until500" selected> 100-500€</option>
            <option value="above500"> &gt 500€</option>
            <?php elseif($priceType == 'above500'): ?>
            <option value="all">Escolha...</option>
            <option value="under25"> &lt 25€</option>
            <option value="until100"> 25-100€</option>
            <option value="until500"> 100-500€</option>
            <option value="above500" selected> &gt 500€</option>
            <?php else: ?>
            <option value="all"selected>Escolha...</option>
            <option value="under25"> &lt 25€</option>
            <option value="until100"> 25-100€</option>
            <option value="until500"> 100-500€</option>
            <option value="above500"> &gt 500€</option>
            <?php endif; ?>
        <?php else: ?>
            <option value="all"selected>Escolha...</option>
            <option value="under25"> &lt 25€</option>
            <option value="until100"> 25-100€</option>
            <option value="until500"> 100-500€</option>
            <option value="above500"> &gt 500€</option>
        <?php endif; ?>
        </select>
    </div>
        <!--
        <div class="form-group py-2">
            <label for="formControlRange">Até: <span id="priceRangeNr">500</span>€</label>
            <input type="range" value="500" min="1" max="1000" class="form-control-range" id="formControlRange">
        </div>-->

    <div class="padding"></div>
    <p></p>
    <div class="padding"></div>

    <div class=" input-group mb-3 ">
        <div class="input-group-prepend ">
            <label class="input-group-text" for="DataSelect" style="width: 150px;">Data da Venda</label>
        </div>
        <select class="custom-select col-md-5" id="DataSelect" name="dataType" onchange="this.form.submit()">
        <?php if(isset($dataType)): ?>     
            <?php if($dataType == 'today'): ?>
            <option value="all">Escolha...</option>
            <option value="today" selected> Hoje...</option>
            <option value="week"> Últimos 7 Dias...</option>
            <option value="dweek"> Últimos 14 Dias...</option>
            <option value="month"> Últimos 30 Dias...</option>
            <?php elseif($dataType == 'week'): ?>
            <option value="all">Escolha...</option>
            <option value="today"> Hoje...</option>
            <option value="week" selected> Últimos 7 Dias...</option>
            <option value="dweek"> Últimos 14 Dias...</option>
            <option value="month"> Últimos 30 Dias...</option>
            <?php elseif($dataType == 'dweek'): ?>
            <option value="all">Escolha...</option>
            <option value="today"> Hoje...</option>
            <option value="week"> Últimos 7 Dias...</option>
            <option value="dweek" selected> Últimos 14 Dias...</option>
            <option value="month"> Últimos 30 Dias...</option>
            <?php elseif($dataType == 'month'): ?>
            <option value="all">Escolha...</option>
            <option value="today"> Hoje...</option>
            <option value="week"> Últimos 7 Dias...</option>
            <option value="dweek"> Últimos 14 Dias...</option>
            <option value="month" selected> Últimos 30 Dias...</option>
            <?php else: ?>
            <option value="all" selected>Escolha...</option>
            <option value="today"> Hoje...</option>
            <option value="week"> Últimos 7 Dias...</option>
            <option value="dweek"> Últimos 14 Dias...</option>
            <option value="month"> Últimos 30 Dias...</option>
            <?php endif; ?>
        <?php else: ?>
            <option value="all" selected>Escolha...</option>
            <option value="today"> Hoje...</option>
            <option value="week"> Últimos 7 Dias...</option>
            <option value="dweek"> Últimos 14 Dias...</option>
            <option value="month"> Últimos 30 Dias...</option>
        <?php endif; ?>
        </select>
    </div>
    </form>
</div><?php /**PATH /home/luis/git/A4S2/LBAW/lbaw2036/resources/views/partials/filter.blade.php ENDPATH**/ ?>