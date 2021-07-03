function validateInputs() { return validateEmail() && validateDate() && validateZip() && validatePhone() && validateNames() && validatePass() }

function validateNames() { let e = document.getElementById("defaultRegisterFormFirstName"),
        t = document.getElementById("defaultRegisterFormLastName"),
        r = document.getElementById("defaultRegisterUsername"),
        o = /(^$)|^([a-zA-Z\u0080-\u024F]+(?:(\.) |-| |'))*[a-zA-Z\u0080-\u024F]*$/,
        l = !0; if (/[a-zA-Z0-9 ]/.test(String(r.value).toLowerCase()))
        if (o.test(String(e.value).toLowerCase())) { if (!o.test(String(t.value).toLowerCase())) { l = !1, t.style.borderColor = "red", t.style.borderStyle = "solid", t.style.borderWidth = "3px"; var n = t.nextSibling;
                n.style.color = "red", n.textContent = " not a valid last name" } } else { l = !1, e.style.borderColor = "red", e.style.borderStyle = "solid", e.style.borderWidth = "3px"; var d = e.nextSibling;
            d.style.color = "red", d.textContent = " not a valid first name" }
    else { l = !1, r.style.borderColor = "red", r.style.borderStyle = "solid", r.style.borderWidth = "3px"; var s = r.nextSibling;
        s.style.color = "red", s.textContent = " not a valid username" } return l }

function validatePhone() { var e = document.getElementById("defaultRegisterPhonePassword"),
        t = /(^\d{9})|(^\+{1}\d{12})$/; if (!t.test(String(e.value).toLowerCase())) { e.style.borderColor = "red", e.style.borderStyle = "solid", e.style.borderWidth = "3px"; var r = e.nextSibling;
        r.style.color = "red", r.textContent = " not a valid phone number" } return t.test(String(e.value).toLowerCase()) }

function validateZip() { var e = document.getElementById("defaultRegisterCodigo"),
        t = /^(\d{4})$/; if (!t.test(String(e.value).toLowerCase())) { e.style.borderColor = "red", e.style.borderStyle = "solid", e.style.borderWidth = "3px"; var r = e.nextSibling;
        r.style.color = "red", r.textContent = " not a valid zipcode" } return t.test(String(e.value).toLowerCase()) }

function validateEmail() { var e = document.getElementById("defaultRegisterFormEmail"),
        t = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; if (!t.test(String(e.value).toLowerCase())) { e.style.borderColor = "red", e.style.borderStyle = "solid", e.style.borderWidth = "3px"; var r = e.nextSibling;
        r.style.color = "red", r.textContent = " not a valid email address" } return t.test(String(e.value).toLowerCase()) }

function validateDate() { var e = document.getElementById("defaultRegisterDataNasc"); if ("" != e.value) { var t = /(^([0-2][0-9]|(3)[0-1])(\-)(((0)[0-9])|((1)[0-2]))(\-)\d{4}$)|((^$)|^\d{4}(\-)(((0)[0-9])|((1)[0-2]))(\-)([0-2][0-9]|(3)[0-1])$)/; if (!t.test(String(e.value).toLowerCase())) { e.style.borderColor = "red", e.style.borderStyle = "solid", e.style.borderWidth = "3px"; var r = e.nextSibling;
            r.style.color = "red", r.textContent = " not a valid date" } return t.test(String(e.value).toLowerCase()) } return !0 }

function clearInputError(e) { "red" == (el = document.getElementById(e)).style.borderColor && (el.style.borderColor = "", el.style.borderStyle = "", el.style.borderWidth = "", el = el.nextSibling, el.textContent = "") }

function checkPassword(e) { var t, r = !1,
        o = document.getElementById(e);
    console.log(o); if (console.log(o.value.length), 0 != o.value.length)
        if (o.value.length < 8 ? (t = " Password too short!", r = !0) : /[0-9]+/.test(String(o.value).toLowerCase()) ? /[a-zA-Z]+/.test(String(o.value).toLowerCase()) || (t = " must include at least one letter!", r = !0) : (t = " must include at least one number!", r = !0), r) { o.style.borderColor = "red", o.style.borderStyle = "solid", o.style.borderWidth = "3px"; let r = document.querySelector("span.error." + e); if (null == r || r.textContent != t)
                if (null == r) { let r = document.createElement("span");
                    r.setAttribute("class", "error " + e), r.textContent = t, document.getElementById(e).parentNode.insertBefore(r, document.getElementById(e)) } else r.textContent = t } else { o.style.borderColor = "", o.style.borderStyle = "", o.style.borderWidth = "", document.querySelector("span.error." + e).remove() } }

function matchingPasswords() { if (document.getElementById("password-confirm").value != document.getElementById("defaultRegisterFormPassword").value) { if (document.getElementById("password-confirm").style.borderColor = "red", document.getElementById("password-confirm").style.borderStyle = "solid", document.getElementById("password-confirm").style.borderWidth = "3px", null == document.querySelector("span.error.pass-confirm")) { let e = document.createElement("span");
            e.setAttribute("class", "error pass-confirm"), e.textContent = " Passwords don't match!", document.getElementById("password-confirm").parentNode.insertBefore(e, document.getElementById("password-confirm")) } } else { document.getElementById("password-confirm").style.borderColor = "#ced4da", document.getElementById("password-confirm").style.borderStyle = "solid", document.getElementById("password-confirm").style.borderWidth = "1px", document.querySelector("span.error.pass-confirm").remove() } }

function validatePass(e, t) { e.addEventListener("change", function() { checkPassword("defaultRegisterFormPassword") }), t.addEventListener("change", function() { checkPassword("password-confirm") }), t.addEventListener("change", matchingPasswords) }
let pass = document.getElementById("defaultRegisterFormPassword"),
    confirmPass = document.getElementById("password-confirm");
null != confirmPass && null != pass && validatePass(pass, confirmPass);