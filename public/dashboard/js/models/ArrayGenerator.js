var ArrayGenerator = (function () {
    var args = [].slice.call(arguments);
    var Arr = new Array();
    var overiddenPush = Arr.push;
    args.forEach(function (arg) {
        Arr.push(arg);
    });
    args = null;
    Arr.push = function (args) {
        overiddenPush.apply(this, arguments);
    }
    Arr.TableName = "YourTable";
    return Arr;
});