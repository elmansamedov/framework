var url = window.location.href.replace(/\/$/, '');

var link = $("a[href='"+ document.location.protocol + '//' + document.domain + document.location.pathname + "']");
link.click(function(e){
    e.preventDefault();
});

function nawMenue()
{
    $("#nawMenue").slideToggle(200);
    return false;
}

$(document).on('click', function(e)
{
    if($("#nawMenue").css('display') != 'none')
        if(e.target.closest('nav') || $(e.target).is('.getMenue') || $(e.target).is('.menue_icon'))
            return;
        else
            nawMenue();
});

$(".viewInfo").hover(function(){
    var text = $(this).attr("data-about");
    var bg = "";
    var imginfo = "";
    var left = $(this).position().left - $(this).parent().position().left;
    var right = $(this).parent().position().left - $(this).parent().width();
    $(this).append("<div class='afterInfo " + bg + "'><div class='row alItemsCenter'>" + imginfo + "<span class='column'>" + text + "</span></div></div>");
    var aif = $(this).find(".afterInfo");
    if(left < right) {
        var pos = $(this).width() + 50;
        aif.css({"left": "-" + pos + "px", "display":"block", "z-index":100, "position":"absolute"});
    }
    aif.show(200);
}, function(){
    var aif = $(this).find(".afterInfo");
    aif.hide(200);
    setTimeout(function(){
        aif.remove();
    }, 190);
});

$(document).on('click', ".password_toggle", function(){
    var now = $(this).parent().find('input');
    if (now.attr('type') == 'password') {
        now.attr('type', 'text')
    } else {
        now.attr('type', 'password')
    }
    $(this).toggleClass('show')
});

function getView(element, data)
{
    element = $(element);
    var block = element.prev(".view");
    $.ajax({url:url,type:'POST',data:data,dataType:'json',success:function(result){
            if(result.result !== '')
            {
                block.html(result.result);
                var view = block.children('#view');
                view.append("<button class='button button_delete'>&#10008;</button>");
                if(!block.hasClass("relative"))
                {
                    view.css({"position":"fixed","top":'20px',"right":'20px'});
                }
            }
        }});
    return false;
}

$(document).on("click", ".button_delete", function(){
    var view = $("#view");
    view.hide(200);
    setTimeout(function(){
        view.remove();
    }, 200);
});

function submitForm(element){
    var his = element.find("button.button");
    loadButtonStart(his);
    disableButton(his);
    var func = his.val();
    var mess = element.find('.result');
    mess.html('');
    if(element.find("#text").length > 0){
        var textarea = document.getElementById("text");
        $("#text").val(sceditor.instance(textarea).val());
    }
    var formData = getForm(element, func);
    $.ajax({url:url,type:'POST',data:formData, dataType:'json', contentType: false, processData: false, success:function(result){
            setTimeout(function(){
                if(typeof result.error !== 'undefined' && result.error !== false){
                    mess.css({'color':'red', 'textShadow':'0 0 1px white'});
                    mess.html(result.result);
                }else{
                    if(his.val() === 'reset')
                        form.trigger('reset');
                    mess.css('color', 'rgba(0,0,0,0.8)');
                    mess.html(result.result);
                }
                if(typeof result.img !== 'undefined'){
                    $('.load_button').remove();
                    his.children('[data-img="result"]').attr('src', result.img);
                }
                if(typeof result.editeButton !== 'undefined')
                    his.html(result.editeButton);
                if(typeof result.text !== 'undefined')
                    his.html(result.text);
                else if(typeof result.hide !== 'undefined'){
                    var h = his.parents(result.hide);
                    setTimeout(function(){
                        h.hide(300);
                        setTimeout(function(){
                            h.remove();
                        }, 250);
                    }, 100);
                }else if(typeof result.remclass !== 'undefined'){
                    var clas = his.parents('.' + result.remclass);
                    setTimeout(function(){
                        clas.hide(300);
                        setTimeout(function(){
                            clas.remove();
                        }, 250);
                    },100);
                }
                if(typeof result.remid !== 'undefined'){
                    var idc = $('#' + result.remid);
                    setTimeout(function(){
                        idc.hide(300);
                        setTimeout(function(){
                            idc.remove();
                            if(his.closest("#view"))
                                his.closest("#view").remove();
                            if(his.closest(".view"))
                                his.closest(".view").remove();
                        }, 250);
                    },100);

                }
                if(typeof result.refer !== 'undefined'){
                    setTimeout(function(){
                        window.location.replace(result.refer);
                    }, 100);
                }
                if(typeof result.htm !== 'undefined')
                {
                    $(result.htm).html(result.htmlData);
                }
                if(typeof result.valClear !== 'undefined')
                {
                    $(result.valClear).val('');
                }
                loadButtonStop(his);
                if(typeof result.disablebutton !== 'undefined')
                {
                    disableButton(his);
                }else
                    enableButton(his);
            }, 80);
            if(element.find("#text").length > 0){
                var textarea = document.getElementById("text");
                sceditor.instance(textarea).val('');
                scrollChat();
            }
        }});
    return false;
}
window.onload = function() {
    scrollChat();
};

function scrollChat()
{
    var block = document.getElementById("messagesDiv");
    if(block !== null && typeof block !== 'undefined')
    {
        block.scrollTop = block.scrollHeight;
    }
}
var workPagin = false;

function loadChatScroll() {
    var paginId = $('#lastMess');
    if (paginId.length > 0) {
        var chBody = $('#messagesDiv');
        var tempScrollTop, currentScrollTop = 30;
        chBody.scroll(function () {
            currentScrollTop = chBody.scrollTop();
            if (chBody.scrollTop() <= 20 && !workPagin && tempScrollTop > currentScrollTop) {
                workPagin = true;
                var func = "loadingLastMessage";
                var id = paginId.val();
                if (id === '' || id === 0) {
                    workPagin = false;
                    return false;
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {'query': func, id: id},
                    success: function (res) {
                        if (typeof res.error !== 'undefined' || res.error === true)
                        {
                            workPagin = false;
                            return false;
                        }
                        var ended = $(".messageBlock").first();
                        paginId.val(res.lastid);
                        chBody.prepend(res.old);
                        var sqr = ended.position().top - 30;
                        chBody.scrollTop(sqr);
                        workPagin = false;
                    }
                });
            }
            tempScrollTop = currentScrollTop;
        });
    }
}

loadChatScroll();


function jsSubmitFormdata(data, his = null, mess = null){

    $.ajax({
        url:url,
        type:'POST',
        data:data,
        dataType:'json',
        processData: false,
        contentType: false,
        success:function(result){
            setTimeout(function(){
                if(typeof result.img !== 'undefined'){
                    $('.load_button').remove();
                    his.children('[data-img="result"]').attr('src', result.img);
                }
                if(typeof result.disablebutton !== undefined && result.disablebutton)
                {
                    disableButton(his);
                }
                if(typeof result.editeButton !== 'undefined')
                    his.html(result.editeButton);
                if(typeof result.text !== 'undefined')
                    his.html(result.text);
                else if(typeof result.hide !== 'undefined'){
                    var h = his.parents(result.hide);
                    setTimeout(function(){
                        h.hide(300);
                        setTimeout(function(){
                            h.remove();
                        }, 250);
                    }, 100);
                }else if(typeof result.remclass !== 'undefined'){
                    var clas = his.parents('.' + result.remclass);
                    setTimeout(function(){
                        clas.hide(300);
                        setTimeout(function(){
                            clas.remove();
                        }, 250);
                    },100);
                }
                if(typeof result.remid !== 'undefined'){
                    var idc = $('#' + result.remid);
                    setTimeout(function(){
                        idc.hide(300);
                        setTimeout(function(){
                            idc.remove();
                            if(his.closest("#view"))
                                his.closest("#view").remove();
                            if(his.closest(".view"))
                                his.closest(".view").remove();
                        }, 250);
                    },100);

                }
                if(typeof result.refer !== 'undefined'){
                    setTimeout(function(){
                        window.location.replace(result.refer);
                    }, 100);
                }
                if(typeof result.htm !== 'undefined')
                {
                    $(result.htm).html(result.htmlData);
                }
                if(typeof result.valClear !== 'undefined')
                {
                    $(result.valClear).val('');
                }
            }, 80);
        }});
}

function jsSubmit(data, his = null, mess = null){

    $.ajax({
        url:url,
        type:'POST',
        data:data,
        dataType:'json',
        success:function(result){
            setTimeout(function(){
                if(typeof result.img !== 'undefined'){
                    $('.load_button').remove();
                    his.children('[data-img="result"]').attr('src', result.img);
                }
                if(typeof result.disablebutton !== undefined && result.disablebutton)
                {
                    disableButton(his);
                }
                if(typeof result.editeButton !== 'undefined')
                    his.html(result.editeButton);
                if(typeof result.text !== 'undefined')
                    his.html(result.text);
                else if(typeof result.hide !== 'undefined'){
                    var h = his.parents(result.hide);
                    setTimeout(function(){
                        h.hide(300);
                        setTimeout(function(){
                            h.remove();
                        }, 250);
                    }, 100);
                }else if(typeof result.remclass !== 'undefined'){
                    var clas = his.parents('.' + result.remclass);
                    setTimeout(function(){
                        clas.hide(300);
                        setTimeout(function(){
                            clas.remove();
                        }, 250);
                    },100);
                }
                if(typeof result.remid !== 'undefined'){
                    var idc = $('#' + result.remid);
                    setTimeout(function(){
                        idc.hide(300);
                        setTimeout(function(){
                            idc.remove();
                            if(his.closest("#view"))
                                his.closest("#view").remove();
                            if(his.closest(".view"))
                                his.closest(".view").remove();
                        }, 250);
                    },100);

                }
                if(typeof result.refer !== 'undefined'){
                    setTimeout(function(){
                        window.location.replace(result.refer);
                    }, 100);
                }
                if(typeof result.htm !== 'undefined')
                {
                    $(result.htm).html(result.htmlData);
                }
                if(typeof result.valClear !== 'undefined')
                {
                    $(result.valClear).val('');
                }
            }, 80);
        }});
}

var animationButton;

function loadButtonStart(but){
    but.css({'position':'relative', "width": but.outerWidth(false) + "px"});
    but.attr("data-text", but.text());
    but.text('.');
    animationButton = setInterval(function(){
        but.text(but.text() + ".");
        if(but.text().length >= 25)
            but.text('.');
    }, 150);
}

function loadButtonStop(but){
    setTimeout(function(){
        clearInterval(animationButton);
        but.html(but.attr("data-text"));
    }, 280);
}

function disableButton(but){
    but.prop('disabled', true);
    but.css({'opacity':'0.6', 'cursor':'default'});
}

function enableButton(but){
    but.prop('disabled', false);
    but.css({'opacity':'1', 'cursor':'pointer'});
}

function getForm(form, func){
    var formData = new FormData(form[0]);
    form.each(function () {
        formData.append($(this).attr('name'), $(this).val());
    });
    formData.append('query', func);
    return formData;
}

$('.number').bind("change keyup input click", function() {
    if (this.value.match(/[^0-9]/g)) {
        this.value = this.value.replace(/[^0-9]/g, '');
    }
});

function submitPhotos(form)
{
    var fileSize = 50;
    var listImages = $(".listImages");
    var imgBlock = $("#imageLoadPhoto");
    var maxFileSize = fileSize * 1024 * 1024;
    var files = $("#inputPhoto")[0].files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if ( !file.type.match(/image\/(jpeg|jpg|png|webp)/) ) {
            alert( 'Фотография должна быть в формате jpg, png или webp' );
            continue;
        }
        if ( file.size > maxFileSize ) {
            alert( 'Размер фотографии не должен превышать ' + fileSize + ' Мб' );
            continue;
        }
        imgBlock.show(150);
        var formData = new FormData(form[0]);
        formData.append('img', file);
        formData.append('query', 'uploadFhotos');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (result) {
                imgBlock.hide(150);
                listImages.html(result.images);
                form.find(".result").text(result.result);
            },
            error: function(){
                console.log('error : ');
                return false;
            }
        });
        if(i >= 10)break;
    }
    return false;
}

function getInputCash(inp)
{
    inp.fadeToggle(200);
}
