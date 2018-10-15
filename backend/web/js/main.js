var main = {
    makeAjaxRequest: function (url, data, params) {
        $.post(url, data, "json").done(function (response) {
            if (response === "ok") {
                if (params.modalSelector !== undefined) {
                    $(params.modalSelector).modal("hide");
                }

                if (params.pjaxSelector !== undefined) {
                    $.pjax.reload({container: params.pjaxSelector, timeout: 10000});
                }
            } else if ($.isArray(response)) {
                alert(response.join("\r\n"));
            } else {
                alert(response);
            }

            return true;
        });
    },
    modalAjaxOnModalSubmit: function (widget, response, pjaxSelector) {
        if (response === "ok") {
            $(widget).modal("hide");

            if (pjaxSelector !== undefined) {
                $.pjax.reload({container: pjaxSelector, timeout: 10000});
            } else {
                location.reload();
            }
        } else if ($.isArray(response)) {
            alert(response.join("\\r\\n"));
        } else {
            alert(response);
        }
    },
    find_in_array: function (array, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i] == value) {
                return i;
            }
        }

        return -1;
    },
    basename: function (path) {
        if (path === undefined) {
            path = document.location.href;
        }

        if (path.indexOf('\\')) {
            parts = path.split('\\');
        } else {
            parts = path.split('/');
        }

        return parts[parts.length - 1];
    },
    formatDate: function (date, format) {
        var yyyy = date.getFullYear();
        var yy = yyyy.toString().substring(2);
        var m = date.getMonth() + 1;
        var mm = m < 10 ? "0" + m : m;
        var d = date.getDate();
        var dd = d < 10 ? "0" + d : d;

        var h = date.getHours();
        var hh = h < 10 ? "0" + h : h;
        var n = date.getMinutes();
        var nn = n < 10 ? "0" + n : n;
        var s = date.getSeconds();
        var ss = s < 10 ? "0" + s : s;

        format = format.replace(/yyyy/i, yyyy);
        format = format.replace(/yy/i, yy);
        format = format.replace(/mm/i, mm);
        format = format.replace(/m/i, m);
        format = format.replace(/dd/i, dd);
        format = format.replace(/d/i, d);
        format = format.replace(/hh/i, hh);
        format = format.replace(/h/i, h);
        format = format.replace(/nn/i, nn);
        format = format.replace(/n/i, n);
        format = format.replace(/ss/i, ss);
        format = format.replace(/s/i, s);

        return format;
    },
    timestampToDate: function (ts) {
        var d = new Date(ts);
        return ('0' + d.getDate()).slice(-2) + '.' + ('0' + (d.getMonth() + 1)).slice(-2) + '.' + d.getFullYear();
    },
    formatNumber: function (number) {
        if (number === undefined || isNaN(number)) {
            return "#N/A";
        }

        return number.replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g, "\$1" + " ");
    }
};


$(document).ready(function () {
    $("#logout_anchor").on("click", function () {
        return confirm("Are you going to logout?");
    });

});



////////////////////////////////////////////////////////////////////////////////
//                  ON  LOAD
// $(window).load(function()
// {
//     $(document).ajaxError( function(event, jqxhr, ajaxOptions, thrownError) {
//         let mess = '';
//
//         if (jqxhr.responseText != undefined && jqxhr.responseText != "") {
//             mess = jqxhr.responseText;
//         } else if (jqxhr.statusText != undefined && jqxhr.statusText != "") {
//             mess = jqxhr.statusText;
//         }
//
//         if (mess == "" && thrownError != undefined && thrownError != "") {
//             mess = thrownError;
//         } else if (mess == "") {
//             mess = 'Unknown error occurs during request';
//         }
//
//         swal("Oops ...", mess, "error");
// 	});
//
//     $(document).ajaxSuccess( function(event, XMLHttpRequest, ajaxOptions, data) {
//         if (data.message != undefined && data.message != "") {
//             let swal_title = (data.title != undefined && data.title != "") ? data.title : "Message";
//             let swal_type = (data.type != undefined && data.type != "") ? data.type : "warning";
//             swal(swal_title, data.message, swal_type);
//         }
// 	});
// });

