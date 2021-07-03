function encodeForAjaxSearch(e) { return null == e ? null : Object.keys(e).map(function(t) { return encodeURIComponent(t) + "=" + encodeURIComponent(e[t]) }).join("&") }

function sendAjaxRequestSearch(e, t, n, o) { let r = new XMLHttpRequest;
    r.open(e, t, !0), r.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content), r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), r.addEventListener("load", o), r.send(encodeForAjaxSearch(n)) }

function getInput() { let e = document.getElementById("searchInputProduct"); if (null != e) { let t = e.value,
            n = document.getElementById("searchCategoryDropDown").value,
            o = window.location,
            r = o.protocol + "//" + o.host; "" != t && sendAjaxRequestSearch("post", r + "/products/search/", { search: t, category: n }, ProductSearchHandler) } }

function searchButtonFunc(e) { e.preventDefault(), getInput() }

function searchInputFunc(e) { console.log(e.keyCode), 13 != e.keyCode && 32 != e.keyCode || (e.preventDefault(), getInput()) }

function ProductSearchHandler() { if (200 != this.status && 201 != this.status) errorHandler(this.status, "getProduct"), window.alert("handlERROR");
    else { document.body.innerHTML = "", document.body.innerHTML = this.responseText, window.history.pushState("Resultados da pesquisa", "eBaw · online shopping", "/"); let e = document.getElementById("searchInputProduct"),
            t = document.getElementById("searchButton"),
            n = document.getElementById("searchCategoryDropDown");
        e.addEventListener("keydown", searchInputFunc), t.addEventListener("click", searchButtonFunc), n.addEventListener("change", searchCategoriesFunc) } }

function searchCategoriesFunc(e) { e.preventDefault(), sendAjaxRequestSearch("get", "/products/categories", {}, enumHandler) }

function docReady(e) { "complete" === document.readyState || "interactive" === document.readyState ? setTimeout(e, 1) : document.addEventListener("DOMContentLoaded", e) }

function enumHandler() { console.log(this.responseText) }
docReady(function() {}), window.onload = (() => { let e = document.getElementById("searchInputProduct"); if (null !== e) { let t = document.getElementById("searchButton"),
            n = document.getElementById("searchCategoryDropDown");
        e.addEventListener("keydown", searchInputFunc), t.addEventListener("click", searchButtonFunc), n.addEventListener("change", searchCategoriesFunc) } let t = (new Date).getTime(); if (document.querySelectorAll(".commentDates") && document.querySelectorAll(".commentDates").forEach(e => { let n = e.innerHTML,
                o = t - Date.parse(n),
                r = Math.floor(o / 864e5),
                a = Math.floor(o % 864e5 / 36e5 - 1),
                c = Math.floor(o % 36e5 / 6e4),
                d = Math.floor(o % 6e4 / 1e3),
                u = "";
            u = r ? "há " + r + " dias" : a ? "há " + a + " horas" : c ? "há " + c + " minutos" : "há " + d + " segundos", e.innerHTML = u }), document.querySelector(".end-date")) { let e = document.querySelector(".end-date"),
            n = e.innerHTML,
            o = Date.parse(n) - t,
            r = Math.floor(o / 864e5),
            a = Math.floor(o % 864e5 / 36e5 - 1),
            c = Math.floor(o % 36e5 / 6e4),
            d = Math.floor(o % 6e4 / 1e3),
            u = "";
        u = r ? r + " dias" : a ? a + " horas" : c ? c + " minutos" : d + " segundos", e.innerHTML = u } });