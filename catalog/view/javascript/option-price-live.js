var Oprice = {
    original_price: 0,
    is_featured: false,
    price: 0,
    selected: [],
    bind: function() {
        // Get original product price and detect if is featured
        var f_price = parseFloat($('#product .price-new > .onlyNumber').text());
        if(f_price) {
            this.price = this.original_price = f_price;
            this.is_featured = true;
        } else {
            this.price = this.original_price = parseFloat($('#product .onlyNumber').text());
        }
        // Bind on change events to next option types: radio, radiocolor, radiolabel, checkbox, checkboxcolor, checkboxlabel, image, select
        /* Selects */
        $(document).on('change', '.form-group.select select.option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
        /* Image */
        $(document).on('change', '.form-group.image input.option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
        /* Checkbox */
        $(document).on('change', '.form-group.checkboxD input.option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
        /* CheckboxLabel */
        $(document).on('change', '.form-group.button input[type=checkbox].option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
        /* CheckboxColor */
        $(document).on('change', '.form-group.color input[type=checkbox].option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
        /* Radio */
        $(document).on('change', '.form-group.radioD input.option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
        /* RadioColor */
        $(document).on('change', '.form-group.color input[type=radio].option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
        /* RadioLabel */
        $(document).on('change', '.form-group.button input[type=radio].option_value', function(){
            Oprice.change($(this), $(this).closest('.option').attr('id'));
        });
    },
    change: function($option, option_id) {
        // Detect chenged type
        var type = ''
        var prefix = '';
        var sub_price = 0;
        if(option_id) {
            option_id = option_id.split('-');
            if(option_id[1] !== undefined) {
                option_id = option_id[1];
            } else {
                option_id = false;
                alert('Cant detect option id.');
            }
        }
        if(option_id) {
            if($option.is('select')) {
                /* SELECT */
                var selected_option_value_id = false;
                if(
                    $option.children('option:selected').attr('option-price-prefix') &&
                    $option.children('option:selected').attr('option-price-value')
                ) {
                    var option_value_id  = $option.children('option:selected').attr('id');
                    var new_price_value  = parseFloat($option.children('option:selected').attr('option-price-value'));
                    var new_price_prefix = $option.children('option:selected').attr('option-price-prefix');
                    
                    var selected_price   = false;
                    var selected_prefix  = false;
                    $(this.selected).each(function(){
                        if(this.option_id == option_id) {
                            selected_option_value_id = this.option_value_id;
                        }
                    });
                    if(option_value_id && (option_value_id !== selected_option_value_id)) {
                        if(new_price_prefix == '+') {
                            this.price = this.price + new_price_value;
                        } else if(new_price_prefix == '-') {
                            this.price = this.price - new_price_value;
                        }
                        this.selected.push({
                            option_id: option_id,
                            option_value_id: option_value_id
                        });
                    }
                    if(selected_option_value_id) {
                        selected_price  = parseFloat($('#' + selected_option_value_id).attr('option-price-value'));
                        selected_prefix = $('#' + selected_option_value_id).attr('option-price-prefix');
                        if(selected_prefix == '+') {
                            this.price = this.price - selected_price;
                        } else if(selected_prefix == '-') {
                            this.price = this.price + selected_price;
                        }
                        var to_delete = false;
                        $(this.selected).each(function(k, v){
                            if(v.option_value_id == selected_option_value_id) {
                                to_delete = k;
                            }
                        });
                        if(to_delete !== false) {
                            this.selected.splice(to_delete, 1);
                        }
                    }
                } else {
                    $(this.selected).each(function(){
                        if(this.option_id == option_id) {
                            selected_option_value_id = this.option_value_id;
                        }
                    });
                    if(selected_option_value_id) {
                        selected_price  = parseFloat($('#' + selected_option_value_id).attr('option-price-value'));
                        selected_prefix = $('#' + selected_option_value_id).attr('option-price-prefix');
                        if(selected_prefix == '+') {
                            this.price = this.price - selected_price;
                        } else if(selected_prefix == '-') {
                            this.price = this.price + selected_price;
                        }
                        var to_delete = false;
                        $(this.selected).each(function(k, v){
                            if(v.option_value_id == selected_option_value_id) {
                                to_delete = k;
                            }
                        });
                        if(to_delete !== false) {
                            this.selected.splice(to_delete, 1);
                        }
                    }
                }
            } else if($option.is('[type=radio]')) {
                /* RADIO */
                var selected_option_value_id = false;
                if(
                    $option.attr('option-price-prefix') &&
                    $option.attr('option-price-value')
                ) {
                    var option_value_id  = $option.attr('id');
                    var new_price_value  = parseFloat($option.attr('option-price-value'));
                    var new_price_prefix = $option.attr('option-price-prefix');
                    var selected_price   = false;
                    var selected_prefix  = false;
                    $(this.selected).each(function(){
                        if(this.option_id == option_id) {
                            selected_option_value_id = this.option_value_id;
                        }
                    });
                    if(option_value_id && (option_value_id !== selected_option_value_id)) {
                        console.log(this.price);
                        console.log(new_price_value, new_price_prefix);
                        if(new_price_prefix == '+') {
                            this.price = this.price + new_price_value;
                        } else if(new_price_prefix == '-') {
                            console.log('why?');
                            this.price = this.price - new_price_value;
                        }
                        console.log(this.price);
                        this.selected.push({
                            option_id: option_id,
                            option_value_id: option_value_id
                        });
                    }
                    if(selected_option_value_id) {
                        selected_price  = parseFloat($('#' + selected_option_value_id).attr('option-price-value'));
                        selected_prefix = $('#' + selected_option_value_id).attr('option-price-prefix');
                        if(selected_prefix == '+') {
                            this.price = this.price - selected_price;
                        } else if(selected_prefix == '-') {
                            this.price = this.price + selected_price;
                        }
                        var to_delete = false;
                        $(this.selected).each(function(k, v){
                            if(v.option_value_id == selected_option_value_id) {
                                to_delete = k;
                            }
                        });
                        if(to_delete !== false) {
                            this.selected.splice(to_delete, 1);
                        }
                    }
                } else {
                    $(this.selected).each(function(){
                        if(this.option_id == option_id) {
                            selected_option_value_id = this.option_value_id;
                        }
                    });
                    if(selected_option_value_id) {
                        selected_price  = parseFloat($('#' + selected_option_value_id).attr('option-price-value'));
                        selected_prefix = $('#' + selected_option_value_id).attr('option-price-prefix');
                        if(selected_prefix == '+') {
                            this.price = this.price - selected_price;
                        } else if(selected_prefix == '-') {
                            this.price = this.price + selected_price;
                        }
                        var to_delete = false;
                        $(this.selected).each(function(k, v){
                            if(v.option_value_id == selected_option_value_id) {
                                to_delete = k;
                            }
                        });
                        if(to_delete !== false) {
                            this.selected.splice(to_delete, 1);
                        }
                    }   
                }
            } else if($option.is('[type=checkbox]')) {
                /* CHECKBOX */
                if(
                    $option.attr('option-price-prefix') &&                    
                    $option.attr('option-price-value') 
                ) {
                    var new_price_value  = parseFloat($option.attr('option-price-value'));
                    var new_price_prefix = $option.attr('option-price-prefix');
                    if(new_price_prefix == '+') {
                        if($option.is(':checked')) {
                            this.price = this.price + new_price_value;
                        } else {
                            this.price = this.price - new_price_value;
                        }
                    } else if(new_price_prefix == '-') {
                        if($option.is(':checked')) {
                            this.price = this.price - new_price_value;
                        } else {
                            this.price = this.price + new_price_value;
                        }
                    }
                }
            }
            this.apply();
        }
    },
    apply: function(){
        if(this.is_featured) {
            $('#product .price-new > .onlyNumber').text(this.price);
        } else {
            $('#product .onlyNumber').text(this.price);
        }
    }
}
$(function(){
   Oprice.bind(); 
});