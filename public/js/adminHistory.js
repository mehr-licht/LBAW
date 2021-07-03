function encodeForAjaxAdmin(e) { return null == e ? null : Object.keys(e).map(function(t) { return encodeURIComponent(t) + "=" + encodeURIComponent(e[t]) }).join("&") }

function sendAjaxRequestAdmin(e, t, n, o) { let r = new XMLHttpRequest;
    r.open(e, t, !0), r.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content), r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), r.addEventListener("load", o), r.send(n) }
const addEventListenersAdmin = () => { const e = document.querySelector("#DeleteBan");
        document.querySelectorAll("#user-info").forEach(t => { const n = t.getAttribute("data-id-report");
            t.querySelector("#deleteban").addEventListener("click", t => { t.stopPropagation(), $("#DeleteBan").modal(), e.querySelector(".modal-title").setAttribute("data-id-report", n) }) }), e.querySelector("#action-2").addEventListener("click", t => { t.preventDefault(); const n = e.querySelector(".modal-title").getAttribute("data-id-report");
            deleteBan(n) }) },
    deleteBan = e => { fetch("/api/admin/bans/user", { method: "DELETE", body: JSON.stringify({ id: e }), headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), Accept: "application/json" } }).then(t => { if (200 === t.status) { document.querySelector("tr#user-info[data-id-report=" + CSS.escape(e) + "]").remove() } else console.log("2") }) };
addEventListenersAdmin();