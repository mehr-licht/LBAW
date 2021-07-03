function getCity() { var e = document.getElementById("defaultRegisterMorada");
    console.log(e.value), 4 == e.length && is_int(e.value) && $.ajax({ url: "/getCityFromZip/" + e.value, cache: !1, dataType: "json", type: "GET", success: function(e, t) { $("#city").val(e.city) }, error: function(e, t) { $("#defaultRegisterMorada").val() } }) }

function is_int(e) { return parseFloat(e) == parseInt(e) && !isNaN(e) }