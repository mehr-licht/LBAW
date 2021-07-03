function encodeForAjaxCancel(e) { return null == e ? null : Object.keys(e).map(function(n) { return encodeURIComponent(n) + "=" + encodeURIComponent(e[n]) }).join("&") }

function sendAjaxRequestCancel(e, n, t, o) { let a = new XMLHttpRequest;
   a.open(e, n, !0), a.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content), a.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), a.addEventListener("load", o), a.send(encodeForAjaxCancel(t)) }

function postCancelHandler(){200!=this.status?console.log(this.status,"cancel"):console.log("conta cancelada")}
document.getElementById("btn_apagar_in").addEventListener("click", e => { e.preventDefault(); let n = window.location;
    sendAjaxRequestCancel("put", n.protocol + "//" + n.host + "/" + "users/cancel", {}, postCancelHandler) });