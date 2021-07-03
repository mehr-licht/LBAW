@extends('layouts.app')

@section('title')
@parent
&middot; Frequently Asked Questions
@stop

@section('css')
    <link rel="stylesheet" href="{{url('/css/global.css')}}">
    <link rel="stylesheet" href="{{url('/css/print.css')}}">
    <link rel="stylesheet" href="{{url('/css/faq.css')}}">
@stop

@section('title')
    FAQ
@stop


@section('content')


    <div class="row">

        <div class="col-1">
        </div>

        <div class="col-10">
            <!---- 2 Collapble 2 Beggin-->
            <!-- faq and things-->
            <div class="page-header">
                <h1>FAQ <small>Frequently Asked Questions</small></h1>
            </div>




            <div class="accordion" id="accordionExample">

                <div class="card p-0">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                                Por que devo registrar no ebaw?
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            O ebaw quer crescer consigo. Adoramos do conceito de marketplace e com a experiência em
                            e-commerce, nós disponibilizamos ferramentas para ajudar a iniciar vendas online de forma
                            rápida e simples, independentemente do tamanho da sua
                            empresa. </div>
                    </div>
                </div>

                <div class="card p-0">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo"
                                aria-expanded="false" aria-controls="collapseTwo">
                                Quais são as vantagens em vender ou comprar no ebaw
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <li>Pagamento à vista</li>
                            Mesmo que seus clientes realizem compras parceladas, você receberá da ebaw o valor à vista
                            no
                            próximo ciclo de pagamento.

                            <li>Escale seu negócio</li>
                            Sem a necessidade de criar um site, será o seu gestor dos seus produtos.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree"
                                aria-expanded="false" aria-controls="collapseThree">
                                Do que preciso para me registrar no ebaw?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            O registo no ebaw é limitado a membros com morada em Portugal, e com a idade superior
                            ou
                            igual
                            a 18 anos.
                            Para efetuar registo deverão ser disponibilizados obrigatoriamente os seguintes dados:
                            <ol>
                                <li>
                                    Informações de contato: Nome, Morada, Data de Nascimento, Código Postal,
                                    Localidade,
                                    Telefone, email, username e palavra passe.
                                </li>
                                <li>
                                    Ter uma conta do serviço Paypal. Todos os pagamentos e devoluções usam os
                                    serviços
                                    Paypal.
                                </li>
                                <li>
                                    Aceder ao link Registar do ebaw.
                                </li>
                                <li>
                                    Aceitação do cumprimento das regras dos leilões e vendas.
                                </li>

                            </ol>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingQuatro">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseQuatro"
                                aria-expanded="false" aria-controls="collapseQuatro">
                                Como começar a vender no ebaw?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseQuatro" class="collapse" aria-labelledby="headingQuatro" data-parent="#accordionExample">
                        <div class="card-body">
                            Só precisa seguir quatro passos para começar a vender:
                            <ol>
                                <li>
                                    Publique os seus produtos: Adicione os seus produtos em apenas alguns
                                    minutos e
                                    escolha
                                    entre os dois modos de venda:
                                    modo BuyItNow, ou seja, venda com preço fixo, ou modo Leilão, venda com
                                    preço
                                    variável.
                                    Para mais informações consulte
                                    a secção BuyItNow e a secção Leilão.
                                </li>

                                <li>
                                    Venda seus produtos: Depois de publicar os produtos, os seus clientes
                                    poderão
                                    vê-los no
                                    ebaw.
                                </li>
                                <li>
                                    Receba seu pagamento: o ebaw depositará e irá notificá-lo quando os
                                    pagamentos
                                    forem
                                    feitos na sua conta bancária.
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingCinco">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseCinco"
                                aria-expanded="false" aria-controls="collapseCinco">
                                Como são tratados os meus dados após a conta ser
                                cancelada?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseCinco" class="collapse" aria-labelledby="headingCinco" data-parent="#accordionExample">
                        <div class="card-body">
                            Quando uma conta é apagada, o utilizador já não se pode autenticar, no entanto, os
                            seus dados permanecem guardados durante cinco dias e após este prazo os dados
                            pessoais
                            serão removidos da base de dados, apenas se mantendo referência a si nos históricos
                            (leilões, vendas, compras e comentários) com o identificador conta apagada.

                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="headingSeis">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSeis"
                                aria-expanded="false" aria-controls="collapseSeis">
                                O que é o BuyItNow?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeis" class="collapse" aria-labelledby="headingSeis" data-parent="#accordionExample">
                        <div class="card-body">
                            O BuyItNow são todas as vendas com um preço fixo.
                            Só precisa seguir quatro passos para começar a vender: seleccione, pague e receba
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="headingSete">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSete"
                                aria-expanded="false" aria-controls="collapseSete">
                                Posso alterar o preço de um produto depois de o registar para
                                venda?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSete" class="collapse" aria-labelledby="headingSete" data-parent="#accordionExample">
                        <div class="card-body">
                            Após um produto ficar online o respectivo vendedor não pode alterar nem o seu preço nem
                            a sua categoria principal nem o seu título.
                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header" id="headingOito">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOito"
                                aria-expanded="false" aria-controls="collapseOito">
                                O que é o leilao?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOito" class="collapse" aria-labelledby="headingOito" data-parent="#accordionExample">
                        <div class="card-body">
                            O leilao são todas as vendas com um preço inicial decidio pelo vendedor.
                            Cada leilão tem um tempo máximo de duração contado a partir do início do mesmo. Durante
                            este
                            tempo
                            cada utilizador é livre de fazer uma licitação do produto com um valor superior ao
                            actual.
                            Após o fim do tempo máximo, o licitador com a licitação mais alta ganha o leilão, e
                            torna-se no
                            comprador final.
                            Caso haja uma licitação a menos de um minuto do fim do leilão, o prazo é estendido por
                            mais
                            cinco minutos.
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="headingNove">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseNove"
                                aria-expanded="false" aria-controls="collapseNove">
                                Posso cancelar um leilao?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseNove" class="collapse" aria-labelledby="headingNove" data-parent="#accordionExample">
                        <div class="card-body">
                            Um vendedor só pode cancelar um leilão até 48h antes do tempo limite.
                            Outras alterações como alterar o seu preço, categoria o título também não poderão ser
                            efectuadas.
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header" id="headingDez">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseDez"
                                aria-expanded="false" aria-controls="collapseDez">
                                Quem pode licitar um produto?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseDez" class="collapse" aria-labelledby="headingDez" data-parent="#accordionExample">
                        <div class="card-body">
                            Todos os utilizadores com a excepção do vendedor do produto.
                        </div>
                    </div>
                </div>




                <div class="card">
                    <div class="card-header" id="headingDezasete">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseDezasete"
                                aria-expanded="false" aria-controls="collapseDezasete">
                                Que modalidades de venda existem no ebaw?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseDezasete" class="collapse" aria-labelledby="headingDezasete" data-parent="#accordionExample">
                        <div class="card-body">
                            No ebaw existem duas modadilidades diferentes: o BuyItNow, compra de um produto a um
                            preço
                            fixo e Leilões. O leilão de um produto tem um montante mínimo fixo, que cresce à medida
                            que
                            cada pessoa faz a respectiva licitação. Para ganhar um leilão e assim realizar
                            a sua compra só terá de fazer uma licitação o suficiente alto.

                        </div>
                    </div>
                </div>








                <div class="card">
                    <div class="card-header" id="headingOnze">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOnze"
                                aria-expanded="false" aria-controls="collapseOnze">
                                Posso classificar a transacção?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOnze" class="collapse" aria-labelledby="headingOnze" data-parent="#accordionExample">
                        <div class="card-body">
                            Sim, apenas um comprador pode classificar o vendedor do produto.


                        </div>
                    </div>
                </div>



                <div class="card">
                    <div class="card-header" id="headingDoze">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseDoze"
                                aria-expanded="false" aria-controls="collapseDoze">
                                Como funcionam os pagamentos?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseDoze" class="collapse" aria-labelledby="headingDoze" data-parent="#accordionExample">
                        <div class="card-body">
                            Todos as transações monetárias serão efectuadas através da API Paypal payments. Paypal
                            payments é a mais segura provedora de transacções monetárias no e-commerce.

                        </div>
                    </div>
                </div>




                <div class="card">
                    <div class="card-header" id="headingTreze">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTreze"
                                aria-expanded="false" aria-controls="collapseTreze">
                                Qual é o prazo de pagamento?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTreze" class="collapse" aria-labelledby="headingTreze" data-parent="#accordionExample">
                        <div class="card-body">
                            Os pagamentos das compras têm de se realizar num prazo máximo de dois dias.
                        </div>
                    </div>
                </div>




                <div class="card">
                    <div class="card-header" id="headingCatorze">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseCatorze"
                                aria-expanded="false" aria-controls="collapseCatorze">
                                Como efectuar uma devolução ?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseCatorze" class="collapse" aria-labelledby="headingCatorze" data-parent="#accordionExample">
                        <div class="card-body">
                            Não há forma mais fácil de devolução. É só seguir os seguintes passos
                            <ol>
                                <li>
                                    Imprima a etiqueta e autorização
                                </li>
                                <li>Prepare o pacote
                                </li>
                                <li>Cole a etiqueta de postagem no pacote
                                </li>
                                <li>Envie!
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>





                <div class="card">
                    <div class="card-header" id="headingQuinze">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseQuinze"
                                aria-expanded="false" aria-controls="collapseQuinze">
                                O que eu posso devolver?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseQuinze" class="collapse" aria-labelledby="headingQuinze" data-parent="#accordionExample">
                        <div class="card-body">
                            Pode devolver os itens comprados com o modo BuyItNow novos vendidos pele ebaw dentro
                            do
                            prazo de 30 dias após a entrega para obter um reembolso total. Devido ao período de
                            festas
                            do final de ano, produtos enviados pela ebaw entre 1º de Novembro e 31 de
                            Dezembro podem ser devolvidos até 31 de janeiro.
                        </div>
                    </div>
                </div>





                <div class="card">
                    <div class="card-header" id="headingDezasseis">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseDezasseis"
                                aria-expanded="false" aria-controls="collapseDezasseis">
                                Quando vou receber o reembolso?
                            </button>
                        </h5>
                    </div>
                    <div id="collapseDezasseis" class="collapse" aria-labelledby="headingDezasseis" data-parent="#accordionExample">
                        <div class="card-body">
                            Normalmente em cerca de 2 a 3 semanas. A maioria das restituições são totalmente
                            reembolsadas de 3 a 5 dias depois de receber e processar sua devolução. O ebaw evoca o
                            aumento de tempo esperado de reembolso, se os serviços de pagamento não estiverem
                            dísponiveis.
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="col-1">
        </div>


    </div>
    <!---- tejo 2 Collapble 2 End-->


    <img src="Banner_01.png" class="img-fluid" alt="eBaw banner">

@stop

  
