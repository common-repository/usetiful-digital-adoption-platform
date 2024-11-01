
function availableTagsName() {
    jQuery(function() {
        var availableTags = usetiful_args.availabletagsName
        previousValue = "";

        jQuery('.add_tags').autocomplete({
            autoFocus: true,
            source: availableTags,
            minLength: 0,
            select: function( event , ui ) {
                jQuery(this).addClass('selected');
            }
        }).focus(function(){            
           jQuery(this).autocomplete('search', "")
        }).keyup(function() {
            jQuery(this).removeClass('selected');
            /*var isValid = false;
            for (i in availableTags) {
                if (availableTags[i].toLowerCase().match(this.value.toLowerCase())) {
                    isValid = true;
                }
            }
            if (!isValid) {
                this.value = previousValue
            } else {
                previousValue = this.value;
            }*/
        }).focusout(function(){  
            /*var isValid = false;
            for (i in availableTags) {
                if (availableTags[i].toLowerCase().match(this.value.toLowerCase())) {
                    isValid = true;
                }
            }
            if (!isValid) {
                this.value = previousValue
            } else {
                if(jQuery(this).hasClass('selected')){
                    previousValue = this.value;
                }else{
                    jQuery(this).val('');
                }
                
            }*/
        });

    });
}

function availableWpvariable() {
    jQuery(function() {
        var availableTags = usetiful_args.availableWptags
        previousValue = "";
        jQuery(".add_tag_value").autocomplete({
            source: availableTags,
            minLength: 0,
            select: function( event , ui ) {
                jQuery(this).addClass('selected');
            }
        }).focus(function(){            
           jQuery(this).autocomplete('search', "")
        }).keyup(function() {
            jQuery(this).removeClass('selected');
            var isValid = false;
            for (i in availableTags) {
                if (availableTags[i].toLowerCase().match(this.value.toLowerCase())) {
                    isValid = true;
                }
            }
            if (!isValid) {
                this.value = previousValue
            } else {
                previousValue = this.value;
            }
        }).focusout(function(){  
            var isValid = false;
            for (i in availableTags) {
                if (availableTags[i].toLowerCase().match(this.value.toLowerCase())) {
                    isValid = true;
                }
            }
            if (!isValid) {
                this.value = previousValue
            } else {
                if(jQuery(this).hasClass('selected')){
                    previousValue = this.value;
                }else{
                    jQuery(this).val('');
                }
                
            }
        });
    });
}

function usetiful_tag_validate() {
    jQuery(".add_tags").on("keyup change focus", function(e) {
        jQuery(".add_tags").each(function(index) {
            jQuery(this).removeClass('current');
        });
        jQuery(this).addClass('current')
        //if (this.value.length > 2) {
            var allTag = get_all_tags();
            var valid = checkValue(this.value, allTag);
            if (valid == 'Exist') {
                jQuery('body').find('#error_msg').text(this.value + ' Tag already exist.')
                jQuery('body').find('.usetiful-submit').prop('disabled', true)
            } else {
                jQuery('body').find('#error_msg').text('')
                jQuery('body').find('.usetiful-submit').prop('disabled', false)
            }
        //}
    });
}

function get_all_tags() {
    const tagName = [];
    jQuery(".add_tags").each(function(index) {
        if (!jQuery(this).hasClass('current')) {
            tagName.push(jQuery(this).val());
        }
    });
    return tagName;
}

function checkValue(value, arr) {
    var status = 'Not exist';
    for (var i = 0; i < arr.length; i++) {
        var name = arr[i];
        if (name == value) {
            status = 'Exist';
            break;
        }
    }
    return status;
}

function reset_input_name(){
    jQuery('body').find(".add_tags" ).each(function( index ) {
       var cnt = index + 1;
        console.log( index + ": " + jQuery( this ).attr('name') );
        jQuery(this).attr('name', 'usetiful_option['+cnt+'][tag]');
    });

    jQuery('body').find(".add_tag_value" ).each(function( index ) {
       var cnt = index + 1;
        console.log( index + ": " + jQuery( this ).attr('name') );
        jQuery(this).attr('name', 'usetiful_option['+cnt+'][tag_value]');
    });
}
jQuery(document).ready(function() {
    usetiful_tag_validate();
    availableTagsName();
    availableWpvariable();
     removefield();
    jQuery('.cls').click(function() {
        jQuery(this).closest('tr').find('.add_tags').removeAttr('value');
        jQuery(this).closest('tr').find('.add_tag_value').removeAttr('value');
    });
    jQuery('#add_new_tag_field').click(function() {
        var tn = jQuery('.tr-content').length;
        tn = parseInt(tn) + 1;
        //tn = parseInt(tn) - 1;
        jQuery('#tags-tabel').append('<tr class="tr-content"><td><input type="text" name="usetiful_option[' + tn + '][tag]" class="add_tags" placeholder="Add tag"></td><td><input type="text" name="usetiful_option[' + tn + '][tag_value]" class="add_tag_value" placeholder="Add tag value"></td><td align="center"><button type="button" class="rmv_field">Remove</button></td></tr>');
        removefield();
        availableTagsName();
        availableWpvariable();
        usetiful_tag_validate();
    });

    function removefield() {
        jQuery('.rmv_field').click(function() {
            jQuery(this).closest('tr').remove();

            setTimeout(function(){
                console.log('This is done');
                reset_input_name();
            }, 300 );

        });
    }
});