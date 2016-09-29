/**
 * Created by oshomo.oforomeh on 22/09/2016.
 */

var country_with_local_governments = JSON.parse(checkout_fields_data.country_with_local_governments);
var local_government_for_states_country = JSON.parse(checkout_fields_data.local_government_for_states_country);
var billing_select = jQuery("select#billing_local_government");
var billing_field = jQuery("#billing_local_government_field");
var shipping_select = jQuery("select#shipping_local_government");
var shipping_field = jQuery("#shipping_local_government_field");

function update_select(selected_country, selected_state, select, field, default_value) {
    if (selected_country != "" && selected_state != "" && jQuery.inArray( selected_country, country_with_local_governments ) >= 0 && typeof local_government_for_states_country[selected_country][selected_state] != "undefined") {
        select.empty();
        jQuery.each(local_government_for_states_country[selected_country][selected_state], function(key,value) {
            select.append(jQuery("<option></option>").attr("value", key).text(value));
        });
        if(default_value) {
            select.val(default_value);
        }
        select.trigger("change.select2");
        select.trigger("chosen:updated");
        field.show();
    }
    else {
        select.empty();
        select.trigger("change.select2");
        select.trigger("chosen:updated");
        field.hide();
    }
}

if(jQuery("select#billing_state").val() != "") {
    update_select(jQuery("select#billing_country").val(), jQuery("select#billing_state").val(), billing_select, billing_field, "")
}

if(jQuery("select#shipping_state").val() != "") {
    update_select(jQuery("select#shipping_country").val(), jQuery("select#shipping_state").val(), shipping_select, shipping_field, "")
}

jQuery("select#billing_state").on("change", function(){
    var billing_selected_country = jQuery("select#billing_country").val();
    var billing_selected_state = jQuery("select#billing_state").val();
    update_select(billing_selected_country, billing_selected_state, billing_select, billing_field)
});

jQuery("select#shipping_state").on("change", function(){
    var shipping_selected_country = jQuery("select#shipping_country").val();
    var shipping_selected_state = jQuery("select#shipping_state").val();
    update_select(shipping_selected_country, shipping_selected_state, shipping_select, shipping_field)
});