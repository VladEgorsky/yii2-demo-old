var tpl = {
    indexGrid: "#grid_template",
    indexGridPjax: "#grid_template_pjax",
    indexGridDeleteButton: "#grid_template .btn_delete",

    updateForm: "#template_form",
    updateFormPjax: "#template_form_pjax",
    updateTemplateZone: ".grid_template",
    updateTemplateZoneDeleteButton: ".grid_template i",
    updateAvailableItems: ".grid_source",
    updateAvailableItemsAddButton: ".grid_source i",
    updateGridItemSelector: ".grid-item",
    updateFieldItemsAmount: "#template-items_amount",
    updateFieldItemsClasses: "#template-items_classes",
    updateFieldData: "#template-data",
    updateSubmitButton: "#template_form_submit_button",

    initPackery: function () {
        $(".grid_template, .grid_source").packery({
            columnWidth: 150,
            rowHeight: 50,
            itemSelector: tpl.updateGridItemSelector
        });

        var templateZoneItems = $(tpl.updateTemplateZone).find(tpl.updateGridItemSelector).draggable();
        $(tpl.updateTemplateZone).packery('bindUIDraggableEvents', templateZoneItems);
        $(tpl.updateAvailableItems).find(tpl.updateGridItemSelector).draggable({opacity: 0.7, helper: "clone"});
        return true;

    },
    deleteTemplate: function (eventTarget) {
        if (!confirm("Do you want to delete Template ?")) {
            return false;
        }

        var url = $(eventTarget).closest("table").data("delete_url");
        var data = {id: $(eventTarget).closest("tr").data("key")};
        main.makeAjaxRequest(url, data, {pjaxSelector: tpl.indexGridPjax});
    },
    removeItem: function (eventTarget) {
        $(tpl.updateTemplateZone).packery("remove", $(eventTarget).closest("div")).packery("shiftLayout");
    },
    addItem: function (eventTarget) {
        var el = $(eventTarget).closest("div").clone().find("i")
            .removeClass("fa-plus-square").addClass("fa-window-close").end();
        $(tpl.updateTemplateZone).append(el.draggable())
            .packery("appended", el).packery("bindUIDraggableEvents", el);
        return true;
    },
    saveData: function (eventTarget) {
        var elems = $(tpl.updateTemplateZone).packery('getItemElements');
        $(tpl.updateFieldItemsAmount).val(elems.length);

        if (elems.length == 0) {
            alert("Template zone should not be empty. Please select some available items");
            return false;
        }

        var html = [];
        var items_classes = [];
        $(elems).each(function (ndx, el) {
            html[ndx] = el.outerHTML;
            items_classes[ndx] = $(el).data("item_class");
        });
        $(tpl.updateFieldData).val(JSON.stringify(html));
        $(tpl.updateFieldItemsClasses).val(JSON.stringify(items_classes));

        $(eventTarget).closest("form").submit();
        return true;
    }
};


$(document).ready(function () {
    var isIndex = (window.location.pathname.indexOf('/template/index') === 0);
    var isUpdate = (window.location.pathname.indexOf('/template/update') === 0);
    var isCreate = (window.location.pathname.indexOf('/template/create') === 0);

    if (isIndex) {
        $(document).on("click", tpl.indexGridDeleteButton, function () {
            tpl.deleteTemplate(this);
        });
    }

    if (isUpdate || isCreate) {
        tpl.initPackery();

        $(document).on("click", tpl.updateTemplateZoneDeleteButton, function (event) {
            tpl.removeItem(this);
        });

        $(document).on("click", tpl.updateAvailableItemsAddButton, function (event) {
            tpl.addItem(this);
        });

        $(document).on("click", tpl.updateSubmitButton, function (event) {
            tpl.saveData(this);
        });
    }
});
