var story = {
    ajaxUpdateModelUrl: $("#ajax_update_model_url").val(),
    modelClass: $("#story_model_class").val(),
    pjaxSelector: "#grid_story_pjax",

    // Change story status inside the grid
    changeStoryStatus: function (inGridSelect) {
        var newStatus = $(inGridSelect).val();
        var model = $(inGridSelect).closest("tr").data("story");
        var mess = "Do you want to mark this Story as ";
        mess += (newStatus === "0") ? "New?" : "Processed?";
        if (!confirm(mess)) {
            $(inGridSelect).val(model.status);    // Restore old status
            return false;
        }

        var data = {
            classname: story.modelClass,
            attributes: {id: model.id, status: newStatus},
            validate: "0"
        };
        $.post(story.ajaxUpdateModelUrl, data, function (response) {
            if (response !== "ok") {
                var mess = $.isArray(response) ? response.join("\\r\\n") : response;
                alert(mess)
            }
            $.pjax.reload({container: story.pjaxSelector, timeout: 7000});
        }, "json");
    },

    // Show User info
    showUserInfo: function (inGridButton) {
        var model = $(inGridButton).closest("tr").data("story");
        var mess = "USER: " + "\r\n";
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
// Change story status inside the grid
    $(document).on("change", "#grid_story [name='Story[status]']", function () {
        story.changeStoryStatus(this)
    });

// Show User info
    $(document).on("click", ".btn_user_info", function () {
        story.showUserInfo(this)
    });

// Show Filenames
    $(document).on("click", ".btn_files_info", function () {
        story.showFileNames(this)
    });
});
