/**
 * Returns the function that you want to execute through its name.
 * It returns undefined if the function || property doesn't exists
 * 
 * @param functionName {String} 
 * @param context {Object || null}
 */
function getFunctionByName(functionName, context) {
    // If using Node.js, the context will be an empty object
    if (typeof (window) == "undefined") {
        context = context || global;
    } else {
        // Use the window (from browser) as context if none providen.
        context = context || window;
    }

    // Retrieve the namespaces of the function you want to execute
    // e.g Namespaces of "MyLib.UI.alerti" would be ["MyLib","UI"]
    var namespaces = functionName.split(".");

    // Retrieve the real name of the function i.e alerti
    var functionToExecute = namespaces.pop();

    // Iterate through every namespace to access the one that has the function
    // you want to execute. For example with the alert fn "MyLib.UI.SomeSub.alert"
    // Loop until context will be equal to SomeSub
    for (var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }

    // If the context really exists (namespaces), return the function or property
    if (context) {
        return context[functionToExecute];
    } else {
        return undefined;
    }
}

function executeFunctionByName(functionName, context /*, args */ ) {
    var args = Array.prototype.slice.call(arguments, 2);
    var namespaces = functionName.split(".");
    var func = namespaces.pop();
    for (var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }
    return context[func].apply(context, args);
}

function number_filter(string) {
    var point = string.indexOf(".");
    if (point >= 0) {
        var real = string.substring(0, point);
        var fragments = string.substring(point + 1, string.length);
        var str = real.replace(/\D/g, '') + '.' + fragments.replace(/\D/g, '');
        return Number(str);
    }
    return Number(string.replace(/\D/g, ''));
}

function number_format(number, decimals = 2, decPoint = '.', thousandsSep) { // eslint-disable-line camelcase
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    var n = !isFinite(+number) ? 0 : +number
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    var s = ''

    var toFixedFix = function (n, prec) {
        if (('' + n).indexOf('e') === -1) {
            return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
        } else {
            var arr = ('' + n).split('e')
            var sig = ''
            if (+arr[1] + prec > 0) {
                sig = '+'
            }
            return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
        }
    }

    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
    }

    return s.join(dec)
}

function currencyFormat(num, fixedDigits = false) {
    var str = num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    var len = str.length;
    var suffix = str.substring(len - 3, len);

    return suffix == ".00" && !fixedDigits ? str.substring(0, len - 3) : str;
}

function getURLParameters(type = 'array')
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    let sParameters = type == 'array' ? [] : {};
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameter = sURLVariables[i].split('=');
        if (type == 'array'){
            sParameters.push(sParameter[0] + '=' + sParameter[1]);
        }else{
            sParameters[sParameter[0]] = sParameter[1];
        }
    }

    return sParameters;
}

function getURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}