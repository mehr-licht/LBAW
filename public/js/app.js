function encodeForAjaxHelper(e) { return null == e ? null : Object.keys(e).map(function(t) { return encodeURIComponent(t) + "=" + encodeURIComponent(e[t]) }).join("&") }

function sendAjaxRequestHelper(e, t, n, o) { let i = new XMLHttpRequest;
    i.open(e, t, !0), i.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]').content), i.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"), i.addEventListener("load", o), i.send(encodeForAjaxHelper(n)) }

function addEventListeners() { let e = document.querySelectorAll("a#navbar_register_bt.nav-link"); if (null != document.querySelector("div#notification-list-section")) { let t = document.querySelector("div#notification-list-section").getAttribute("data-user-id");
        e[0].addEventListener("click", function(e) { sendAjaxRequestHelper("get", "/api/notifications/user/" + t, { id: t }, setNotificationsHandler) }) } else console.log("Error on selector") }

function setNotificationsHandler() { 200 != this.status && console.log(2); let e = JSON.parse(this.response);
    document.querySelector("span#h-nr-notifications1").innerHTML = e.notifications.length; let t = document.querySelector("div#notification-list-section"); for (let n = 0; n < e.notifications.length; n++) { let o, i = document.createElement("li");
        null != e.notifications[n].id_comment && (o = document.createTextNode("[" + e.notifications[n].id_notif + "] Comentário: " + e.notifications[n].text_notification + " Tipo: " + e.notifications[n].type_ofnotification)), null != e.notifications[n].id_item && (o = document.createTextNode("[" + e.notifications[n].id_notif + "] Produto comentado: " + e.notifications[n].text_notification + " Tipo: " + e.notifications[n].type_ofnotification)), i.appendChild(o), t.appendChild(i) } }

function nextPage(e) { let t = document.getElementById(e).substring(4, 1);
    alert(t) }

function goToLicitationPage(e) { window.location.href = "/products/" + e }

function goToBuyPage(e) { window.location.href = "/products/" + e }

function setDateCountDownId() { var e = document.querySelectorAll("span#dateCountDown");
    e.forEach(changeId); for (var t = 0; t < e.length; t++) { countDown("dateCountDown" + t) } }

function changeId(e, t, n) { e.id = "dateCountDown" + t }

function countDown(e) { var t = Date.parse(document.getElementById(e).innerText),
        n = setInterval(function() { var o = (new Date).getTime(),
                i = t - o,
                a = Math.floor(i / 864e5),
                r = Math.floor(i % 864e5 / 36e5),
                c = Math.floor(i % 36e5 / 6e4),
                d = Math.floor(i % 6e4 / 1e3);
            document.getElementById(e).innerHTML = a + "d " + r + "h " + c + "m " + d + "s ", 0 == a ? (document.getElementById(e).innerHTML = r + " h " + c + "m " + d + "s para terminar", 0 == r && (document.getElementById(e).innerHTML = c + "m " + d + "s para terminar", 0 == c && (document.getElementById(e).innerHTML = d + "s para terminar"))) : document.getElementById(e).innerHTML = a + " dias para terminar ", i < 0 && (clearInterval(n), document.getElementById(e).innerHTML = "EXPIRED") }, 1e3) }

function swipe() { var e = document.getElementById("image").getAttribute("src");
    window.open(e, "_blank"), window.focus() }
addEventListeners(), setDateCountDownId();