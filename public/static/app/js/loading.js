
function showLoading(){
    $(document.body).append('<div class="backdrop">\
    <div class="floatingWrap"><div class="floatingBarsG">\
    <div class="blockG rotateG_01"></div>\
    <div class="blockG rotateG_02"></div>\
    <div class="blockG rotateG_03"></div>\
    <div class="blockG rotateG_04"></div>\
    <div class="blockG rotateG_05"></div>\
    <div class="blockG rotateG_06"></div>\
    <div class="blockG rotateG_07"></div>\
    <div class="blockG rotateG_08"></div>\
    </div></div>\
    </div>');
}

function hideLoading(){
    $('.backdrop').remove();
}
