/**
 * Created by oshomo.oforomeh on 22/09/2016.
 */

var country_with_local_governments = JSON.parse(checkout_fields_data.country_with_local_governments);
var local_government_for_states_country = JSON.parse(checkout_fields_data.local_government_for_states_country);
var isCart = location.pathname.indexOf('cart') > -1;
var billing_select = "select#billing_local_government";
var billing_field = "#billing_local_government_field";
var shipping_select = "select#shipping_local_government";
var shipping_field = "#shipping_local_government_field";
var billing_country = "select#billing_country";
var billing_state = "select#billing_state";
var shipping_country = isCart ? "select#calc_shipping_country" : "select#shipping_country";
var shipping_state = isCart ? "select#calc_shipping_state" : "select#shipping_state";

function update_select(selected_country, selected_state, select_selector, field_selector, default_value) {
    var select = jQuery(select_selector);
    var field = jQuery(field_selector);

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

if(jQuery(billing_state).val() != "") {
    update_select(jQuery(billing_country).val(), jQuery(billing_state).val(), billing_select, billing_field, "")
}

if(jQuery(shipping_state).val() != "") {
    update_select(jQuery(shipping_country).val(), jQuery(shipping_state).val(), shipping_select, shipping_field, "")
}

jQuery(billing_state).on("change", function(){
    var billing_selected_country = jQuery(billing_country).val();
    var billing_selected_state = jQuery(billing_state).val();
    update_select(billing_selected_country, billing_selected_state, billing_select, billing_field)
});

jQuery("body").on("change", shipping_state, function(){
    var shipping_selected_country = jQuery(shipping_country).val();
    var shipping_selected_state = jQuery(shipping_state).val();
    update_select(shipping_selected_country, shipping_selected_state, shipping_select, shipping_field)
});
