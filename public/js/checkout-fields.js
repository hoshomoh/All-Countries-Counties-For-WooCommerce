/**
 * Created by oshomo.oforomeh on 22/09/2016.
 */

var countryWithLocalGovernments = JSON.parse(checkout_fields_data.country_with_local_governments);
var localGovernmentForStatesCountry = JSON.parse(checkout_fields_data.local_government_for_states_country);
var isCart = location.pathname.indexOf('cart') > -1;
var billingSelect = "select#billing_local_government";
var billingField = "#billing_local_government_field";
var shippingSelect = "select#shipping_local_government";
var shippingField = "#shipping_local_government_field";
var billingCountry = "select#billing_country";
var billingState = "select#billing_state";
var shippingCountry = isCart ? "select#calc_shipping_country" : "select#shipping_country";
var shippingState = isCart ? "select#calc_shipping_state" : "select#shipping_state";

/**
 * Update the local government select field based on selected state
 * @param selectedCountry
 * @param selectedState
 * @param selectSelector
 * @param fieldSelector
 * @param defaultValue
 */
function updateSelectField(selectedCountry, selectedState, selectSelector, fieldSelector, defaultValue) {
  var select = jQuery(selectSelector);
  var field = jQuery(fieldSelector);

  if (
    selectedCountry !== "" &&
    selectedState !== "" &&
    jQuery.inArray(selectedCountry, countryWithLocalGovernments) >= 0 &&
    typeof localGovernmentForStatesCountry[selectedCountry][selectedState] !== "undefined"
  ) {
    select.empty();
    jQuery.each(localGovernmentForStatesCountry[selectedCountry][selectedState], function (key, value) {
      select.append(jQuery("<option></option>").attr("value", key).text(value));
    });

    if (defaultValue) {
      select.val(defaultValue);
    }

    select.trigger("change.select2");
    select.trigger("chosen:updated");
    field.show();
  } else {
    select.empty();
    select.trigger("change.select2");
    select.trigger("chosen:updated");
    field.hide();
  }
}

/**
 * Update the billing information section local government field on page load
 */
if (jQuery(billingState).val() !== "") {
  updateSelectField(
    jQuery(billingCountry).val(),
    jQuery(billingState).val(),
    billingSelect,
    billingField,
    ""
  )
}

/**
 * Update the shipping information section local government field on page load
 */
if (jQuery(shippingState).val() !== "") {
  updateSelectField(
    jQuery(shippingCountry).val(),
    jQuery(shippingState).val(),
    shippingSelect,
    shippingField, ""
  )
}

/**
 * Update the billing information section local government field when
 * user select another state
 */
jQuery(billingState).on("change", function () {
  var billingSelectedCountry = jQuery(billingCountry).val();
  var billingSelectedState = jQuery(billingState).val();

  updateSelectField(
    billingSelectedCountry,
    billingSelectedState,
    billingSelect,
    billingField
  )
});

/**
 * Update the shipping information section local government field when
 * user select another state
 */
jQuery("body").on("change", shippingState, function () {
  var shippingSelectedCountry = jQuery(shippingCountry).val();
  var shippingSelectedState = jQuery(shippingState).val();

  updateSelectField(
    shippingSelectedCountry,
    shippingSelectedState, shippingSelect, shippingField)
});
