var AjaxSubmitForm = null;
/**
 * Abre o ajax criando o elemento Ajax
 */
function openAjax() {
    try {
        // XMLHttpRequest para browsers mais populares, como: Firefox, Safari, dentre outros.
        var Ajax = new XMLHttpRequest();
    } catch (ee) {
        try {
            Ajax = new ActiveXObject("Msxml2.XMLHTTP"); // Para o IE da MS
        } catch (e) {
            try {
                Ajax = new ActiveXObject("Microsoft.XMLHTTP"); // Para o IE da
                // MS
            } catch (e) {
                Ajax = false;
            }
        }
    }
    return Ajax;
}
function dtag_redirect(url, target, type, loadMsg, postExecute, url_path) {
    //alert(url);
    if (document.getElementById) {
        if (!target) {
            window.location.href = url;
        } else {
            var containerResult = document.getElementById(target);
            if (type == 'get') {
                $.ajax(
                        {
                            type: 'get',
                            url: url,

                            beforeSend: function ()
                            {
                                $('#' + target).html("<div align='center' " +
                                        "style='border:0px solid #0044FF;"
                                        + "position:relative'>"
                                        + "<div align='center' " +
                                        "style='position:absolute;top:10%;width:100%'>"
                                        + "<img src='" + url_path + "/dtag/load/gif' /> " +
                                        "<font face='Tahoma' size='-1' color='#CCCCCC'>" +
                                        "<strong>"
                                        + loadMsg + " </strong>" +
                                        "</font></div></div>");
                            },
                            success: function (data)
                            {
                                $('#' + target).html(data);
                                postExecute();
                            },
                            async: true,
                            error: function (xhr, status, error)
                            {
                                $('#' + target).html(xhr.responseText);
                            }
                        });
            }
            if (type == 'post') {
                var formData = new FormData();
                for (i = 0; i < form.elements.length; i++) {
                    elm = form.elements[i];
                    if (elm.type != 'radio' || (elm.type == 'radio' && elm.checked == true)) {
                        formData.append(elm.name, encodeURIComponent(elm.value));
                    }
                    if (elm.type == 'file') {
                        // Input de arquivos
                        var files = elm.files;
                        // Loop para cada arquivo
                        for (var j = 0; j < files.length; j++) {
                            var file = files[j];

                            // Checando o tipo.
                            //if (!file.type.match('image.*')) {
                            //  continue;
                            //}

                            // Adicionando o arquivo
                            formData.append(elm.name, file, file.name);
                        }
                    }
                }
                $.ajax(
                        {
                            type: 'post',
                            url: url,
                            data: formData,
                            beforeSend: function ()
                            {
                                $('#' + target).html("<div align='center' " +
                                        "style='border:0px solid #0044FF;"
                                        + "position:relative'>"
                                        + "<div align='center' " +
                                        "style='position:absolute;top:10%;width:100%'>"
                                        + "<img src='" + url_path + "/dtag/load/gif' /> " +
                                        "<font face='Tahoma' size='-1' color='#CCCCCC'>" +
                                        "<strong>"
                                        + loadMsg + " </strong>" +
                                        "</font></div></div>");
                            },
                            success: function (data)
                            {
                                $('#' + target).html(data);
                                postExecute();
                            },
                            error: function (xhr, status, error)
                            {
                                $('#' + target).html(xhr.responseText);
                            }
                        });
            }
        }
    }
}

function dtag_submit(url, target, formName, loadMsg, postExecute, url_path) {
    var form = document.getElementById(formName);
    var formData = new FormData();
    for (i = 0; i < form.elements.length; i++) {
        elm = form.elements[i];
        if (elm.type != 'radio' || (elm.type == 'radio' && elm.checked == true)) {
            formData.append(elm.name, encodeURIComponent(elm.value));
        }
        if (elm.type == 'file') {
            // Input de arquivos
            var files = elm.files;
            // Loop para cada arquivo
            for (var j = 0; j < files.length; j++) {
                var file = files[j];

                // Checando o tipo.
                //if (!file.type.match('image.*')) {
                //  continue;
                //}

                // Adicionando o arquivo
                formData.append(elm.name, file, file.name);
            }
        }
    }
    $.ajax(
            {
                type: 'POST',
                url: url,
                data: formData,
                sync: false,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function ()
                {
                    if (loadMsg != '') {
                        $('#' + target).html("<img src='" + url_path + "/dtag/load/gif' /> " +
                                "<font face='Tahoma' size='-1' color='#CCCCCC'>" +
                                "<strong>"
                                + loadMsg + " </strong>" +
                                "</font>");
                    }
                },
                success: function (data)
                {
                    $('#' + target).html(data);
                    postExecute();
                },
                error: function (xhr, status, error)
                {
                    try {
                        var txt = '';
                        var obj = jQuery.parseJSON(xhr.responseText);
                        $.each(obj, function (key, value) {
                            txt = txt + value + '<br />';
                            //alert(value);
                        });
                        $('#' + target).html('<br />' + txt);
                    } catch (err) {
                        $('#' + target).html(xhr.responseText);
                    }
                    postExecute();
                }
            });
}