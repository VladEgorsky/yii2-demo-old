var comment = {
    ajaxUpdateModelUrl: $("#ajax_update_model_url").val(),
    modelClass: $("#comment_model_class").val(),
    pjaxSelector: "#grid_comment_pjax",

    // Change Comment status inside the grid
    changeCommentStatus: function (inGridSelect) {
        var newStatus = $(inGridSelect).val();
        var model = $(inGridSelect).closest("tr").data("comment");
        var mess = "Do you want to ";
        mess += (newStatus === "0") ? "hide this Comment?" : "to make this Comment visible?";
        if (!confirm(mess)) {
            $(inGridSelect).val(model.status);    // Restore old status
            return false;
        }

        var data = {
            classname: comment.modelClass,
            attributes: {id: model.id, status: newStatus},
            validate: "0"
        };

        $.post(comment.ajaxUpdateModelUrl, data, function (response) {
            if (response !== "ok") {
                var mess = $.isArray(response) ? response.join("\\r\\n") : response;
                alert(mess)
            }
            $.pjax.reload({container: comment.pjaxSelector, timeout: 7000});
        }, "json");
    },

    // Show User info
    showUserInfo: function (inGridButton) {
        var model = $(inGridButton).closest("tr").data("comment");
        var mess = "USER" + "\r\n";
        mess += "Username: " + model.user_name + "\r\n";
        mess += "Address: " + model.user_address;
        alert(mess);
    },

    // Show News info
    showNewsInfo: function (inGridButton) {
        var news = $(inGridButton).closest("tr").data("news");
        var mess = "NEWS" + "\r\n";
        mess += "Date: " + main.timestampToDate(parseInt(news.created_at + "000")) + "\r\n";
        mess += "Author: " + news.author + "\r\n";
        mess += "Title: " + news.title;
        alert(mess);
    }
};


$(document).ready(function () {
// Change Comment status inside the grid
    $(document).on("change", "#grid_comment [name='Comment[status]']", function () {
        comment.changeCommentStatus(this)
    });

// Show User info
    $(document).on("click", ".btn_user_info", function () {
        comment.showUserInfo(this)
    });

// Show News info
    $(document).on("click", ".btn_news_info", function () {
        comment.showNewsInfo(this)
    });
});