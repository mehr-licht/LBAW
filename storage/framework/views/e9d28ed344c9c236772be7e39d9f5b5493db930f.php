<div class="col-md-3 hidden-xs col-lg-3">

    <hr>

    <!--  <button type="button" class="btn input-group-text col-lg-10 text-center" data-toggle="collapse" data-target="#categories">Categoria</button>-->
    <div class=" input-group mb-3">
        <div class="input-group-prepend ">

            <label class="input-group-text "
                for="CategoriaSelect">Categoria&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>

        </div>
        <select class="custom-select col-md-5 " id=" CategoriaSelect ">
            <option selected>Escolha...</option>
            <option value="1 ">Mobiliário</option>
            <option value="2 ">Electrodomésticos</option>
            <option value="3 ">Jogos</option>
        </select>

    </div>
    <hr>
    <div class=" input-group mb-3 ">
        <div class="input-group-prepend ">
            <label class="input-group-text " for="TypeSelect">Tipo de Venda</label>
        </div>
        <select class="custom-select col-md-5" id=" TypeSelect ">
            <option selected>qualquer</option>
            <option value="1 ">Venda</option>
            <option value="2 ">Leilão</option>
        </select>
    </div>
    <hr>

    <button type="button" class="btn input-group-text col-lg-10 text-center" data-toggle="collapse"
        data-target="#price">
        Preço
    </button>
    <div class="collapse in col-lg-10" id="price">
        <div class="form-group py-2">
            <label for="formControlRange">Até: <span id="priceRangeNr">500</span>€</label>
            <input type="range" value="500" min="1" max="1000" class="form-control-range" id="formControlRange">
        </div>
    </div>

    <div class="padding"></div>
    <p></p>
    <div class="padding"></div>

    <button type="button" class="btn input-group-text col-lg-10 text-center" data-toggle="collapse"
        data-target="#date">
        Datas
    </button>
    <div class="collapse in col-lg-10" id="date">
        <div class="form-group py-2">
            <label for="formControlRange">Acaba em <span id="dateRangeNr">5</span> dias</label>
            <input type="range" value="5" min="1" max="10" class="form-control-range" id="formControlRange">
        </div>
    </div>
</div><?php /**PATH /home/luis/git/A4S2/LBAW/wip/lbaw2036/resources/views/partials/filter.blade.php ENDPATH**/ ?>