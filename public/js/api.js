function encodeForAjax(e) { return null == e ? null : Object.keys(e).map(function(t) { return encodeURIComponent(t) + "=" + encodeURIComponent(e[t]) }).join("&") }

function sendAjaxRequest(e, t, n, o) { let r = new XMLHttpRequest; try { r.open(e, t, !0), r.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content), r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), r.addEventListener("load", o), r.send(encodeForAjax(n)) } catch (e) { console.log(e) } }
let global, isauction = document.getElementById("outer").getAttribute("auction");

function postCommentHandler() { 200 != this.status && errorHandler(this.status, "comment"), document.getElementById("commentText").value = ""; let e = JSON.parse(this.response),
        t = e.comment,
        n = document.createElement("article");
    n.classList.add("comment"), n.setAttribute("data-id", e.comment.id_comment), n.innerHTML = ` \n    <article class="comment" data-id="${t.id_comment}">\n    <li class="media">\n        <a href="#" class="pull-left">\n            <img src="${t.photo}" width="64px" height="64px" alt="Foto de perfil" class="img-circle mr-2">\n        </a>\n        <div class="media-body">\n            <div class="imgAbt pull-right">\n                   \n                </div>\n                <span class="text-muted pull-right">\n                    <small class = "text-muted" > agora mesmo </small>\n                </span>\n                <a href = "#"> ${t.username} </a>\n                <p> ${t.msg_ofcomment}\n                    <span class="text-muted pull-right" >\n\n<button style = "background-color:#ffffff;border:0" class="remove-comment" id="new-comment" product = "${t.id}" comment = "${t.id_comment}"\nliker = "${t.id_commenter}" ref="delete"> <img src = "../trashcan.svg" alt="remover comentario"\nstyle = "width:20px;" > </button>\n\n                        <small class = "text-muted" > ${t.comment_likes} <i> likes</i>\n                        </small>\n                    </span>\n                </p>\n            </div>\n        </li>\n    </article >`, global = t.id_comment; let o = document.getElementById("comment-article");
    o.parentNode.insertBefore(n, o.nextSibling) }

function timeFunction() { setTimeout(function() {}, 20) }

function deleteComment(e) { id_comment = global, sendAjaxRequest("delete", "/api/products/" + e + "/comments/" + id_comment, {}, delCommentHandler) }

function delCommentHandler() { 200 != this.status && errorHandler(this.status, "comment"), document.querySelector(".comment[data-id='" + global + "']").innerHTML = " \n    <article>\n    </article >" }

function postBidHandler() { if (200 != this.status) return void errorHandler(this.status, "bid"); let e = JSON.parse(this.response),
        t = e.bid,
        n = document.createElement("article");
    n.classList.add("bid"), n.setAttribute("data-id", e.bid.id_bid), n.innerHTML = ` \n    <article class = "bid"\n    data-id = "${t.id}" >\n        <li class = "media" >\n        <div class="media-body col-lg-10 col-md-10">\n            <span class=" col-lg-5 col-md-5 col-sm-8 text-muted">\n                <small class = " text-muted " > ${t.bidding_date} </small>\n            </span >\n            <span class=" col-lg-2 col-md-2 col-sm-4">${t.value_bid} Eur </span> \n        </div> \n            <span class=" col-lg-2 col-md-2 col-sm-3 pull-right"> ${t.bidguy}</span> </li>\n        </article> `; let o = document.getElementById("bid-article");
    o.parentNode.insertBefore(n, o.nextSibling) }

function postBuyHandler() { document.getElementById("modalBuy");
    200 != this.status && errorHandler(this.status, "buy"), document.getElementById("buyorpay").innerHTML = '\n    <div class = "btn-group cart col-md-3" >\n          <button type = "button" class = "btn btn-primary" id="paybutton">\n          Pagar!</button>\n    </div>\n    ' }

function addPay(e) { 200 != this.status && errorHandler(this.status, "like"), document.getElementById("buyorpay").innerHTML = "\n    " }

function postLikeHandler() { 200 != this.status && errorHandler(this.status, "like"); let e = document.querySelector(".comment[data-id='" + global + "'] #numberlikes");
    value = Number(e.innerHTML.substring(0, e.innerHTML.indexOf("<"))), e.innerHTML = 1 + value + "<i>likes</i>" }

function remProductHandler() { 200 != this.status && errorHandler(this.status, "remove product"), window.location.href = "/admin" }

function cancelProductHandler() { 200 != this.status && errorHandler(this.status, "cancel product"), document.write(this.responseText), window.history.pushState("products", "eBaw · online shopping", "/products") }

function clickCommentHandler() { const e = event.currentTarget.dataset.id;
    document.querySelector("#reportComment form").setAttribute("action", "/products/comments/report/" + e) }
document.getElementById("addComment") && document.getElementById("addComment").addEventListener("click", e => { e.preventDefault(); let t = e.target.getAttribute("product");
    global = t, sendAjaxRequest("post", "/api/products/" + t + "/comments", { id_commenter: e.target.getAttribute("commenter"), id: t, msg_ofcomment: document.getElementById("commentText").value }, postCommentHandler) }), document.getElementsByClassName("remove-comment") && document.querySelectorAll(".remove-comment").forEach(e => { e.addEventListener("click", e => { e.preventDefault(); let t = e.target.getAttribute("product"),
            n = e.target.getAttribute("comment");
        global = n, sendAjaxRequest("delete", "/api/products/" + t + "/comments/" + n, {}, delCommentHandler) }) }), document.getElementById("addBid") && document.getElementById("addBid").addEventListener("click", e => { e.preventDefault(), $("#modalBid").modal("hide"); let t = e.target.getAttribute("product");
    value = document.getElementById("biddingValue").textContent, sendAjaxRequest("post", "/api/products/" + t + "/bids", { id_auction: t, bidder: e.target.getAttribute("bidder"), value_bid: value }, postBidHandler) }), document.getElementById("addBuy") && document.getElementById("addBuy").addEventListener("click", e => { e.preventDefault(), $("#modalBuy").modal("hide"); let t = e.target.getAttribute("product");
    global = t, sendAjaxRequest("post", "/api/products/" + t + "/buy", { id_buy: t, buyer: e.target.getAttribute("buyer"), seller: e.target.getAttribute("seller"), value: e.target.getAttribute("value") }, postBuyHandler) }), document.getElementById("paybutton") && document.getElementById("paybutton").addEventListener("click", e => { e.preventDefault() }), document.getElementsByClassName("put-like") && document.querySelectorAll(".put-like").forEach(e => { e.addEventListener("click", e => { e.preventDefault(); let t = e.target.getAttribute("product"),
            n = e.target.getAttribute("comment"),
            o = e.target.getAttribute("liker");
        global = n, sendAjaxRequest("put", "/api/products/" + t + "/comments/" + n, { id_liker: o }, postLikeHandler) }) }), document.getElementById("remove-product") && document.getElementById("remove-product").addEventListener("click", e => { e.preventDefault(), sendAjaxRequest("put", "/admin/bans/product/" + e.target.getAttribute("product"), {}, remProductHandler) }), document.getElementById("cancel-product") && document.getElementById("cancel-product").addEventListener("click", e => { e.preventDefault(), sendAjaxRequest("put", "/products/" + e.target.getAttribute("product") + "/cancel/", {}, cancelProductHandler) });
let commentReportBtn = document.querySelectorAll(".comment");

function errorHandler(e, t) { switch (console.log("error " + e + " submitting " + t), e) {
        case 400:
            window.location.href = "/400", window.history.pushState("Bad Request", "eBaw · online shopping", "/400"); break;
        case 401:
            window.location.href = "/401", window.history.pushState("Unauthorized", "eBaw · online shopping", "/401"); break;
        case 403:
            window.location.href = "/403", window.history.pushState("Forbidden", "eBaw · online shopping", "/403"); break;
        case 404:
            window.location.href = "/404", window.history.pushState("Not Found", "eBaw · online shopping", "/404"); break;
        case 422:
            window.location.href = "/422", window.history.pushState("Unprocessable Entity", "eBaw · online shopping", "/422"); break;
        default:
            window.location.href = "/500", window.history.pushState("Internal Server Error", "eBaw · online shopping", "/500") } }
null != commentReportBtn && [].forEach.call(commentReportBtn, function(e) { e.addEventListener("click", clickCommentHandler) });