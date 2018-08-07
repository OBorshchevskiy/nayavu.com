var msgfield;
var area;
// смотрим в какой из textarea добавлять
function infocus(area) {
msgfield = document.getElementById(area);
}

// функция вставки тэгов в textarea
function inserttags(open,close) {
if (msgfield != null) {
// поддержка Internet Explorer
if (document.selection && document.selection.createRange){
msgfield.focus();
sel = document.selection.createRange();
sel.text = open + sel.text + close;
msgfield.focus();
}
// поддержка Mozilla
else if (msgfield.selectionStart || msgfield.selectionStart == "0"){
var startPos = msgfield.selectionStart;
var endPos = msgfield.selectionEnd;
msgfield.value = msgfield.value.substring(0, startPos) + open + msgfield.value.substring(startPos, endPos) + close + msgfield.value.substring(endPos, msgfield.value.length);
msgfield.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
msgfield.focus();
}
// Поддержка других браузеров
else {
msgfield.value += open + close;
msgfield.focus();
}
return;
 }
}