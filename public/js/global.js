function toggleTab() { var e = document.getElementById("within-historico");
    e.classList.contains("d-none") ? (e.classList.remove("d-none"), e.classList.add("d-block")) : (e.classList.remove("d-block"), e.classList.add("d-none")) }

function hideHist() { hideFiltros(); var e = document.getElementById("historico");
    e.classList.remove("d-block"), e.classList.add("d-none"), e.classList.add("text-muted") }

function showHist() { hideGeral(), hideNotif(), hideDenunciarBtn(), showFiltros(); var e = document.getElementById("historico");
    e.classList.remove("d-none"), e.classList.add("d-block") }

function hideFiltros() { var e = document.getElementById("filtros");
    e.classList.remove("d-block"), e.classList.add("d-none"), e.classList.add("text-muted") }

function showFiltros() { var e = document.getElementById("filtros");
    e.classList.remove("d-none"), e.classList.add("d-block") }

function hideGeral() { var e = document.getElementById("geral");
    e.classList.remove("d-block"), e.classList.add("d-none"), e.classList.add("text-muted") }

function showGeral() { hideHist(), hideNotif(), showDenunciarBtn(); var e = document.getElementById("geral");
    e.classList.remove("d-none"), e.classList.add("d-block") }

function showNotif() { hideHist(), hideGeral(), hideDenunciarBtn(); var e = document.getElementById("profile-notifs");
    e.classList.remove("d-none"), e.classList.add("d-block") }

function hideNotif() { var e = document.getElementById("profile-notifs");
    e.classList.remove("d-block"), e.classList.add("d-none"), e.classList.add("text-muted") }

function hideApagarBtn() { var e = document.getElementById("btn_apagar");
    e.classList.remove("d-block"), e.classList.add("d-none"), e.classList.add("text-muted") }

function showApagarBtn() { var e = document.getElementById("btn_apagar");
    e.classList.remove("d-none"), e.classList.add("d-block") }

function hideDenunciarBtn() { var e = document.getElementById("btn_denunciar");
    e.classList.remove("d-block"), e.classList.add("d-none"), e.classList.add("text-muted") }

function showDenunciarBtn() { var e = document.getElementById("btn_denunciar");
    e.classList.remove("d-none"), e.classList.add("d-block") }
var $star_rating = $(".star-rating .fa"),
    SetRatingStar = function() { return $star_rating.each(function() { return parseInt($star_rating.siblings("input.rating-value").val()) >= parseInt($(this).data("rating")) ? $(this).removeClass("fa-star-o").addClass("fa-star") : $(this).removeClass("fa-star").addClass("fa-star-o") }) };

function bid(e) { let t = getBid();
    alert(t);
    fetch("/products/${id}/bid/${value}", { method: "PUT", credentials: "omit", mode: "same-origin", headers: { "Content-Type": "application/json; charset=utf-8" }, body: JSON.stringify({ name: "Customer Name" }) }) }

function readURL(e) { if (e.files && e.files[0]) { var t = new FileReader;
        t.onload = function(e) { var t = document.querySelector("#blah");
            document.querySelector("#blah").setAttribute("src", e.target.result), t.width = "150", t.height = "150" }, t.readAsDataURL(e.files[0]) } }

function getBid() { return document.getElementById("bidValue").value < document.getElementById("bidValue").placeholder ? txt = document.getElementById("bidValue").placeholder : txt = document.getElementById("bidValue").value, document.getElementById("biddingValue").innerHTML = txt, txt }

function getBuyId() { x = document.getElementById("modalBuy").previousElementSibling.innerHTML, alert(x) }

function getBiddId(e) { document.querySelectorAll(["input"]);
    forEach(e => { alert(i) }); let t = document.getElementById("modalBuy").previousElementSibling.innerHTML;
    alert(t) }

function nextPage(e) { let t = e.id.substring(4);
    x = document.getElementById("page" + t) }

function previewFile() { let e = document.querySelector("#imagePreview");
    console.log(e); let t = document.querySelector("input[type=file]").files[0],
        n = new FileReader;
    n.onloadend = function() { e.src = n.result }, t ? n.readAsDataURL(t) : e.src = "" }
$star_rating.on("click", function() { return $star_rating.siblings("input.rating-value").val($(this).data("rating")), SetRatingStar() }), SetRatingStar(), $(document).ready(function() {}), $(function() { $('[data-toggle="popover"]').popover() }), $(document).ready(function() { $('[data-toggle="popover"]').popover() });