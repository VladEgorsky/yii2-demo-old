var advertise = {
    ajaxUpdateModelUrl: $("#ajax_update_model_url").val(),
    modelClass: $("#advertise_model_class").val(),
    pjaxSelector: "#grid_advertise_pjax",

    // Change advertise status inside the grid
    changeAdvertiseStatus: function (inGridSelect) {
        var newStatus = $(inGridSelect).val();
        var model = $(inGridSelect).closest("tr").data("advertise");
        var mess = "Do you want to mark this Advertise as ";
        mess += (newStatus === "0") ? "New?" : "Processed?";
        if (!confirm(mess)) {
            $(inGridSelect).val(model.status);    // Restore old status
            return false;
        }

        var data = {
            classname: advertise.modelClass,
            attributes: {id: model.id, status: newStatus},
            validate: "0"
        };
        $.post(advertise.ajaxUpdateModelUrl, data, function (response) {
            if (response !== "ok") {
                var mess = $.isArray(response) ? response.join("\\r\\n") : response;
                alert(mess)
            }
            $.pjax.reload({container: advertise.pjaxSelector, timeout: 7000});
        }, "json");
    },

    // Show User info
    showUserInfo: function (inGridButton) {
        var model = $(inGridButton).closest("tr").data("advertise");
        var mess = "USER" + "\r\n";
        mess += "Name: " + model.name + "\r\n";
        mess += "Email: " + model.email;
        alert(mess);
    },

    // Show Filenames
    showFileNames: function (inGridButton) {
        var files = $(inGridButton).closest("tr").data("files");
        var mess = "FILES: " + "\r\n" + files.join(", ");
        alert(mess);
    }
};


$(document).ready(function () {
// Change advertise status inside the grid
    $(document).on("change", "#grid_advertise [name='Advertise[status]']", function () {
        advertise.changeAdvertiseStatus(this)
    });

// Show User info
    $(document).on("click", ".btn_user_info", function () {
        advertise.showUserInfo(this)
    });

// Show Filenames
    $(document).on("click", ".btn_files_info", function () {
        advertise.showFileNames(this)
    });
});
