var subscribe = {
    ajaxUpdateModelUrl: $("#ajax_update_model_url").val(),
    modelClass: $("#subscribe_model_class").val(),
    pjaxSelector: "#grid_subscribe_pjax",

    // Change Subscribe status or period inside the grid
    changeSubscribe: function (inGridSelect, model, attributes) {
        if ("status" in attributes && !confirm("Do you want to change Subscribe status?")) {
            $(inGridSelect).val(model.status);    // Restore old status
            return false;
        }
        if ("period" in attributes && !confirm("Do you want to change Subscribe period?")) {
            $(inGridSelect).val(model.period);    // Restore old period
            return false;
        }

        var data = {
            classname: subscribe.modelClass,
            attributes: attributes,
            validate: "0"
        };

        $.post(subscribe.ajaxUpdateModelUrl, data, function (response) {
            if (response !== "ok") {
                var mess = $.isArray(response) ? response.join("\\r\\n") : response;
                alert(mess)
            }
            $.pjax.reload({container: subscribe.pjaxSelector, timeout: 7000});
        }, "json");
    },

    // Show Sections and Tags info
    showLocationInfo: function (inGridButton) {
        var mess = "";

        var sections = $(inGridButton).closest("tr").data("sections");
        if (sections.length == 0) {
            mess += "No Sections selected" + "\r\n";
        } else {
            mess += "SECTIONS: " + sections.join(", ") + "\r\n";
        }

        var tags = $(inGridButton).closest("tr").data("tags");
        if (tags.length == 0) {
            mess += "No Tags selected";
        } else {
            mess += "TAGS: " + tags.join(", ");
        }

        alert(mess);
    }
};


$(document).ready(function () {
// Change Subscribe status inside the grid
    $(document).on("change", "#grid_subscribe [name='Subscribe[status]']", function () {
        var model = $(this).closest("tr").data("subscribe");
        var attributes = {id: model.id, status: $(this).val()};
        subscribe.changeSubscribe(this, model, attributes)
    });

// Change Subscribe period inside the grid
    $(document).on("change", "#grid_subscribe [name='Subscribe[period]']", function () {
        var model = $(this).closest("tr").data("subscribe");
        var attributes = {id: model.id, period: $(this).val()};
        subscribe.changeSubscribe(this, model, attributes);
    });

// Show Sections and Tags info
    $(document).on("click", ".btn_location_info", function () {
        subscribe.showLocationInfo(this)
    });
});