(function($){
    "user strict";

    $( window ).on( 'elementor:init', function() {
        var CTIconsItemView = elementor.modules.controls.BaseData.extend({
            wrapper: null,
            items: null,
            iconpicker_els: null,
            url_els: null,
            add_btn: null,
            delete_btn: null,
            template: null,
            onReady: function () {
                var self = this;
                this.wrapper = $(this.el);
                this.items = this.wrapper.find(".evolt-group-item");
                this.add_btn = this.wrapper.find(".evolt-group-add");
                this.template = this.wrapper.find(".evolt-template").val();

                self.setupIconPicker();
                self.setupUrlInput();
                self.setupDeleteBtn();
                this.add_btn.on("click", function(){
                    var new_item = $(self.template);
                    self.wrapper.find(".evolt-group").append(new_item);
                    setTimeout(function(){
                        self.setupIconPicker();
                        self.setupUrlInput();
                        self.setupDeleteBtn();
                        self.items = self.wrapper.find(".evolt-group-item");
                    }, 300);
                });
            },

            setupIconPicker: function () {
                var self = this;
                self.iconpicker_els = self.wrapper.find(".evolt-iconpicker");
                self.iconpicker_els.fontIconPicker();
                self.iconpicker_els.on("change", function(e){
                    e.preventDefault();
                    self.saveValue();
                });
            },

            setupUrlInput: function () {
                var self = this;
                self.url_els = self.wrapper.find(".evolt-url-input");
                self.url_els = self.wrapper.find(".evolt-content-input");
                self.url_els = self.wrapper.find(".evolt-content-pricing");
                self.url_els = self.wrapper.find(".evolt-class-pricing");
                self.url_els = self.wrapper.find(".evolt-title-input");
                self.url_els = self.wrapper.find(".evolt-number-input");
                self.url_els.on("keyup", function(e){
                    e.preventDefault();
                    self.saveValue();
                });
            },

            setupDeleteBtn: function () {
                var self = this;
                self.delete_btn = self.wrapper.find(".evolt-group-delete");
                self.delete_btn.on("click", function(e){
                    e.preventDefault();
                    $(this).parent().remove();
                    self.items = self.wrapper.find(".evolt-group-item");
                    self.saveValue();
                });
            },

            saveValue: function () {
                var values = [];
                $.each(this.items, function(index, item){
                    var item_val = {};
                    item_val.icon = $(item).find(".evolt-iconpicker").val();
                    item_val.url = $(item).find(".evolt-url-input").val();
                    item_val.content = $(item).find(".evolt-content-input").val();
                    item_val.content_pricing = $(item).find(".evolt-content-pricing").val();
                    item_val.class_pricing = $(item).find(".evolt-class-pricing").val();
                    item_val.title = $(item).find(".evolt-title-input").val();
                    item_val.number = $(item).find(".evolt-number-input").val();
                    values.push(item_val);
                });
                this.setValue(JSON.stringify(values));
            },

            onBeforeDestroy: function () {
                this.saveValue();
            }
        });

        elementor.addControlView('evolt_icons', CTIconsItemView);
        elementor.addControlView('evolt_lists', CTIconsItemView);
        elementor.addControlView('evolt_lists_pricing', CTIconsItemView);
        elementor.addControlView('evolt_progressbar', CTIconsItemView);
    } );
}(jQuery));