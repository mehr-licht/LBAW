function encodeForAjaxAdmin(e) { return null == e ? null : Object.keys(e).map(function(t) { return encodeURIComponent(t) + "=" + encodeURIComponent(e[t]) }).join("&") }

function sendAjaxRequestAdmin(e, t, n, a) { let d = new XMLHttpRequest; "get" == e ? (d.addEventListener("load", a), d.open(e, t + "?" + encodeForAjaxAdmin(n), !0), d.send()) : (d.open(e, t, !0), d.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content), d.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), d.addEventListener("load", a), d.send(encodeForAjaxAdmin(n))) }

function sendSearchAdminRequest(e) { let t = document.getElementById("searchInputAdmin").value; "" != t && sendAjaxRequestAdmin("get", "/api/admin/search", { search: t }, adminSearchHandler), e.preventDefault() }

function adminSearchHandler() { let e = JSON.parse(this.responseText),
        t = e.path,
        n = t.indexOf("/api/");
    t = t.slice(0, n) + "/admin/search?search=", t += document.getElementById("searchInputAdmin").value; let a = document.createElement("tbody");
    a.classList.add("not_center"), a.setAttribute("id", "search_user_table_body"); for (let t = 0; t < e.data.length; t++) { let n = document.createElement("tr");
        n.classList.add("userSearch-info"), n.setAttribute("data-id-user", e.data[t].id), n.setAttribute("data-id-username", e.data[t].username), n.innerHTML = `\n        <th scope="row">${e.data[t].id}</th>\n        <td><a href="/users/${e.data[t].id}">${e.data[t].username}</a></td>\n        <td>${e.data[t].email}</td>\n        <td><button class="btn btn-white btn-sm searchBanBtn" type="button" data-toggle="modal" data-target="#BanModel">\n            <i class="fa fa-ban"></i></button>\n        </td>`, a.appendChild(n) } let d = document.getElementById("search_user_table_body");
    document.getElementById("search_user_table").replaceChild(a, d); let l = document.querySelector("ul.pagination.text-center"),
        r = document.createElement("ul");
    r.classList.add("pagination"), r.classList.add("text-center"); let s = document.createElement("ul");
    s.classList.add("pagination"), s.setAttribute("role", "navigation"), s.innerHTML = '\n    <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">\n        <span class="page-link" aria-hidden="true">‹</span>\n    </li>\n    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>', e.last_page >= 2 ? (s.innerHTML = s.innerHTML + `\n        <li class="page-item">\n            <a class="page-link" href="${t+"&page=2"}">2</a>\n        </li>`, e.last_page > 2 && (s.innerHTML = s.innerHTML + '\n            <li class="page-item disabled" aria-disabled="true">\n                <span class="page-link">...</span>\n            </li>'), s.innerHTML = s.innerHTML + `\n        <li class="page-item">\n            <a class="page-link" href="${t+"&page=2"}" rel="next" aria-label="Next »">›</a>\n        </li>`) : s.innerHTML = s.innerHTML + '\n        <li class="page-item disabled aria-disabled="true" aria-label="Next »">\n            <span class="page-link" aria-hidden="true">›</span>\n        </li>', r.appendChild(s), document.querySelector("nav.text-center").replaceChild(r, l); let i = document.querySelectorAll(".btn.btn-white.searchBanBtn");
    null != i && [].forEach.call(i, function(e) { e.addEventListener("click", banModelHandler) }) }

function editConseqModelHandler(e) { const t = e.target.value; let n = document.getElementById("punishement_spanForm"),
        a = document.getElementById("banReasonTextForm"),
        d = document.getElementById("punishement_span"),
        l = document.getElementById("banReasonText"); "suspend" == t ? (n.style.display = "none", d.removeAttribute("required"), a.style.display = "none", l.removeAttribute("required")) : "ban" == t ? (n.style.display = "block", d.setAttribute("required", "true"), a.style.display = "block", l.setAttribute("required", "true")) : (n.style.display = "none", d.removeAttribute("required"), a.style.display = "block", l.setAttribute("required", "true")) }

function banModelHandler(e) { let t = e.currentTarget.parentNode.parentNode; const n = t.dataset.idUser,
        a = t.dataset.idUsername;
    document.getElementById("BanModelLabel").textContent = "Banir " + a, document.querySelector("#BanModel form").setAttribute("action", "/admin/bans/member/" + n) }
let searchInput = document.getElementById("searchInputAdmin"),
    searchForm = document.getElementById("searchFormAdmin");
null != searchInput && searchInput.addEventListener("change", sendSearchAdminRequest), null != searchForm && searchForm.addEventListener("submit", sendSearchAdminRequest);
let consequenceSelect = document.getElementById("typeOfConsequenceSelect");
null != consequenceSelect && consequenceSelect.addEventListener("change", editConseqModelHandler);
let searchBanBtn = document.querySelectorAll(".btn.btn-white.searchBanBtn");
null != searchBanBtn && [].forEach.call(searchBanBtn, function(e) { e.addEventListener("click", banModelHandler) });