(function ($) {
  "use strict";
  toastr.options = {
    positionClass:'toast-bottom-right'
};
  let base_url = $("#base_url").val();
  let not_data_in_cart = $("#not_data_in_cart").val();
  let precision = $("#precision").val();
  let warning = $("#alert_txt").val();
  let ok = $("#ok_text").val();
  let cart_need_clean = $("#cart_need_clean").val();
  let order_copied = $("#order_copied").val();
  let currency = $("#currency").val();
  let website_theme_color = $("#website_theme_color").val();
  let already_added_to_the_cart = $("#already_added_to_the_cart").val();
  let item_add_success = $("#item_add_success").val();
  let ir_precision = 2;

  $(document).on("click", ".single_order", function (e) {
    e.preventDefault();
    let food_menu_id = $(this).attr("data_single_order_id");
    let exist_check_food_menu_id;
    let exist_check = "No";
    
    // Check if product has variations and if one is selected
    let has_variations = $("#has_variations_check").val() == '1';
    let selected_variation = $(".product_variation_radio:checked");
    
    if(has_variations && selected_variation.length === 0){
      let selected_variation_msg = $("#selected_variation").val() || "Please select a Variation";
      toastr['error'](selected_variation_msg, '');
      return false;
    }
    
    // If variation is selected, use variation ID instead of parent product ID
    if(selected_variation.length > 0){
      food_menu_id = selected_variation.val();
    }
    
    $(".sidebar-cart-card").each(function () {
      exist_check_food_menu_id = $(this).attr("data-order-cart-id");
      if (exist_check_food_menu_id == food_menu_id) {
        exist_check = "Yes";
        toastr['error'](already_added_to_the_cart, '');
      }
    });
    
    $(".single_order").hide();
    $(".button_show_cl").show();
    if(exist_check=="No"){
      toastr['success'](item_add_success, '');
    }
    singleItemOrder(food_menu_id, exist_check);
  });

  function checkTaxApply(tax) {
    let return_status = true;
    return return_status;
  }

  function get_total_vat() {
    let tax_object = {};
    let tax_name = [];
    $(".sidebar-cart-card").each(function () {
      let food_menu_id = $(this).attr("data-order-cart-id");
      
      let qty = Number($(this).attr("data-qty")) || 1;

      let inline_total = $(this).find(".subtotal_cal").attr("data-inline_total");
      let item_total_price = parseFloat(inline_total || 0).toFixed(ir_precision);

      item_total_price = item_total_price * qty;
      let menu_details = search_by_menu_id(food_menu_id, window.items);
      
      // If variation not found, try to get parent product tax info
      if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
        // Check if this cart item has a parent ID stored
        let parent_id = $(this).attr("data-parent-id");
        if (parent_id) {
          menu_details = search_by_menu_id(Number(parent_id), window.items);
        }
        // If still not found, try to get from the original details_item_price
        if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
          let original_food_menu_id = Number($("#details_item_price").attr("data-food_menu_id"));
          menu_details = search_by_menu_id(original_food_menu_id, window.items);
        }
      }
      
      // Skip if menu details still not found
      if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
        return;
      }

      let tax_information = JSON.parse(menu_details[0].tax_information);

      if (tax_information.length > 0) {
        for (let k in tax_information) {
          if (
            tax_name.includes(tax_information[k].tax_field_name) &&
            checkTaxApply(tax_information[k].tax_field_name)
          ) {
            let previous_value =
              tax_object["" + tax_information[k].tax_field_name];
            let current_value = 0;
            let tax_type = Number($("#tax_type").val());
            if (tax_type == 1) {
              current_value = parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(item_total_price)
                ) / parseFloat(100)
              );
            } else {
              current_value = (
                parseFloat(item_total_price) -
                parseFloat(item_total_price) /
                  (1 + tax_information[k].tax_field_percentage / 100)
              ).toFixed(ir_precision);
            }

            tax_object["" + tax_information[k].tax_field_name] = (
              parseFloat(previous_value) + Number(current_value)
            ).toFixed(ir_precision);
          } else {
            if (checkTaxApply(tax_information[k].tax_field_name)) {
              tax_name.push(tax_information[k].tax_field_name);
              let current_value = 0;
              let tax_type = Number($("#tax_type").val());

              if (tax_type == 1) {
                current_value = parseFloat(
                  parseFloat(
                    parseFloat(tax_information[k].tax_field_percentage) *
                      parseFloat(item_total_price)
                  ) / parseFloat(100)
                );
              } else {
                current_value = (
                  parseFloat(item_total_price) -
                  parseFloat(item_total_price) /
                    (1 + tax_information[k].tax_field_percentage / 100)
                ).toFixed(ir_precision);
              }
              tax_object["" + tax_information[k].tax_field_name] =
                Number(current_value).toFixed(ir_precision);
            }
          }
        }
      }

      $(this)
        .find(".sidebar-cart-card-meta li")
        .each(function () {
          let modifier_id = $(this).attr("data-id");
        
          let item_total_price = Number($(this).attr("data-total_price"));
            item_total_price = item_total_price*qty;

          let modifier_details = search_by_modifer_id(
            modifier_id,
            window.only_modifiers
          );
         
          if (modifier_details[0].tax_information) {
            let tax_information = JSON.parse(
              modifier_details[0].tax_information
            );
            if (tax_information.length > 0) {
              for (let k in tax_information) {
                if (
                  tax_name.includes(tax_information[k].tax_field_name) &&
                  checkTaxApply(tax_information[k].tax_field_name)
                ) {
                  let previous_value =
                    tax_object["" + tax_information[k].tax_field_name];
                  let current_value = 0;
                  let tax_type = Number($("#tax_type").val());
                  if (tax_type == 1) {
                    current_value = parseFloat(
                      parseFloat(
                        parseFloat(tax_information[k].tax_field_percentage) *
                          parseFloat(item_total_price)
                      ) / parseFloat(100)
                    );
                  } else {
                    current_value = (
                      parseFloat(item_total_price) -
                      parseFloat(item_total_price) /
                        (1 + tax_information[k].tax_field_percentage / 100)
                    ).toFixed(ir_precision);
                  }
                  tax_object["" + tax_information[k].tax_field_name] = (
                    parseFloat(previous_value) + Number(current_value)
                  ).toFixed(ir_precision);
                } else {
                  if (checkTaxApply(tax_information[k].tax_field_name)) {
                    tax_name.push(tax_information[k].tax_field_name);
                    let current_value = 0;
                    let tax_type = Number($("#tax_type").val());

                    if (tax_type == 1) {
                      current_value = parseFloat(
                        parseFloat(
                          parseFloat(tax_information[k].tax_field_percentage) *
                            parseFloat(item_total_price)
                        ) / parseFloat(100)
                      );
                    } else {
                      current_value = (
                        parseFloat(item_total_price) -
                        parseFloat(item_total_price) /
                          (1 + tax_information[k].tax_field_percentage / 100)
                      ).toFixed(ir_precision);
                    }
                    tax_object["" + tax_information[k].tax_field_name] =
                      Number(current_value).toFixed(ir_precision);
                  }
                }
              }
            }
          }
        });
    });

    let collect_tax = $("#collect_tax").val();

    let vat_amount = collect_tax == "Yes" ? tax_object : null;
    let total_vat = 0;
    let html_modal = "";

    $.each(vat_amount, function (key, value) {
      let row_id = 1;
      let key_value = key;
      total_vat += Number(value);
      html_modal +=
        "<tr class='tax_field' data-tax_field_id='" +
        row_id +
        "'  data-tax_field_type='" +
        key_value +
        "'  data-tax_field_amount='" +
        value +
        "'>\n" +
        "                            <td>" +
        key_value +
        "</td>\n" +
        "                            <td>" +
        value +
        "</td>\n" +
        "                        </tr>";
    });
    $("#tax_row_show").html(html_modal);

    $("#total_vat_hidden").val((total_vat).toFixed(2));
    
    // Store tax breakdown globally for use in cart and checkout
    window.cartTaxBreakdown = tax_object;
  }
  function getFoodMenuTax(details_item_price,food_menu_id){
   
      let menu_details = search_by_menu_id(food_menu_id, window.items);
      
      // If variation not found in window.items, try to get parent product tax info
      if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
        // Check if we have a selected variation with parent ID
        let selected_variation = $(".product_variation_radio:checked");
        if (selected_variation.length > 0) {
          let variation_item = selected_variation.closest(".product-variation-item");
          let parent_id = variation_item.attr("data-parent-id");
          if (parent_id) {
            menu_details = search_by_menu_id(Number(parent_id), window.items);
          }
        }
        // If still not found, use the original food_menu_id from details_item_price element
        if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
          let original_food_menu_id = Number($("#details_item_price").attr("data-food_menu_id"));
          menu_details = search_by_menu_id(original_food_menu_id, window.items);
        }
      }

      // If still no menu details found, return 0 tax
      if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
        return 0;
      }

      let tax_information = JSON.parse(menu_details[0].tax_information);
      let total_tax = 0;
      if (tax_information.length > 0) {
        for (let k in tax_information) {
          let current_value = 0;
            let tax_type = Number($("#tax_type").val());

            if (tax_type == 1) {
              current_value = parseFloat(
                parseFloat(
                  parseFloat(tax_information[k].tax_field_percentage) *
                    parseFloat(details_item_price)
                ) / parseFloat(100)
              );
            } else {
              current_value = (
                parseFloat(details_item_price) -
                parseFloat(details_item_price) /
                  (1 + tax_information[k].tax_field_percentage / 100)
              ).toFixed(ir_precision);
            }
            total_tax+=current_value;
        }
      }

      return total_tax;
  }
  function getModifierTax(details_item_price,modifier_id){
   
    let modifier_details = search_by_modifer_id(
      modifier_id,
      window.only_modifiers
    );
    let total_tax = 0;
      if(modifier_details.length){
        let tax_information = JSON.parse(modifier_details[0].tax_information);
     
        if (tax_information.length > 0) {
          for (let k in tax_information) {
            let current_value = 0;
              let tax_type = Number($("#tax_type").val());
  
              if (tax_type == 1) {
                current_value = parseFloat(
                  parseFloat(
                    parseFloat(tax_information[k].tax_field_percentage) *
                      parseFloat(details_item_price)
                  ) / parseFloat(100)
                );
              } else {
                current_value = (
                  parseFloat(details_item_price) -
                  parseFloat(details_item_price) /
                    (1 + tax_information[k].tax_field_percentage / 100)
                ).toFixed(ir_precision);
              }
              total_tax+=current_value;
          }
        }
      }
  

      return total_tax;
}
  function detailsFoodTotalCalculate(){
    if($("#details_item_price").length){
    let details_item_price = Number($("#details_item_price").attr("data-price"));
    let food_menu_id = Number($("#details_item_price").attr("data-food_menu_id"));
    let tax_calculation_id = food_menu_id; // Use parent ID for tax calculation

    // Check if a variation is selected
    let selected_variation = $(".product_variation_radio:checked");
    if(selected_variation.length > 0){
      let variation_item = selected_variation.closest(".product-variation-item");
      details_item_price = Number(variation_item.attr("data-price"));
      food_menu_id = Number(variation_item.attr("data-food_menu_id"));
      
      // Use parent ID for tax calculation (variations inherit tax from parent)
      let parent_id = variation_item.attr("data-parent-id");
      if (parent_id) {
        tax_calculation_id = Number(parent_id);
      } else {
        // Fallback to original food_menu_id if parent_id not available
        tax_calculation_id = Number($("#details_item_price").attr("data-food_menu_id"));
      }
      
      // Update the displayed price
      $("#details_item_price").attr("data-price", details_item_price);
      $("#details_item_price").attr("data-food_menu_id", food_menu_id);
      $("#details_item_price").html(currency + parseFloat(details_item_price).toFixed(precision));
    }

      let total_modifier_cost  = 0;
      let total_tax = 0;
      $('.modifier_checkbox').each(function() {
        if ($(this).prop('checked')) {
            let id = Number($(this).parent().parent().attr("data-id"));
            let selected_cost = Number($(this).parent().parent().attr("data-price"));
            total_modifier_cost+=selected_cost;

            let total_mod_tax = getModifierTax(selected_cost,id);
            total_tax+=total_mod_tax;
        }
    });

    // Use tax_calculation_id (parent ID) for tax calculation
    let total_food_tax = getFoodMenuTax(details_item_price, tax_calculation_id);
    let item_details_qty = Number($("#item_details_qty").val());
    let total = ((details_item_price + total_modifier_cost + total_tax + total_food_tax)*item_details_qty).toFixed(2);
    $(".show_total_amount").html(currency+total);
    }
  }
  
  $(document).on("click", ".customize-variation-item", function (e) {
    detailsFoodTotalCalculate();
  });
  
  // Handle product variation radio button selection
  $(document).on("change", ".product_variation_radio", function (e) {
    // Remove active class from all tiles
    $(".variation-tile").removeClass("active");
    // Add active class to selected tile
    $(this).closest(".variation-tile").addClass("active");
    detailsFoodTotalCalculate();
  });
  
  // Also trigger on click to ensure it works
  $(document).on("click", ".product_variation_radio", function (e) {
    // Remove active class from all tiles
    $(".variation-tile").removeClass("active");
    // Add active class to selected tile
    $(this).closest(".variation-tile").addClass("active");
    setTimeout(function() {
      detailsFoodTotalCalculate();
    }, 10);
  });
  
  // Handle tile click to trigger radio selection
  $(document).on("click", ".variation-tile", function (e) {
    // Don't trigger if clicking directly on the radio button (it handles itself)
    if (!$(e.target).is('.product_variation_radio')) {
      let radio = $(this).find('.product_variation_radio');
      if (radio.length && !radio.prop('disabled')) {
        radio.prop('checked', true).trigger('change');
      }
    }
  });
  
  $(document).on("click", ".call_qty", function (e) {
    detailsFoodTotalCalculate();
  });
  
  // Initialize total calculation on page load if variation is selected
  $(document).ready(function() {
    if($(".product_variation_radio:checked").length > 0){
      setTimeout(function() {
        detailsFoodTotalCalculate();
      }, 100);
    }
  });
  
  $(document).on("click", ".ct-social-login", function (e) {
    let is_demo_mode = $("#is_demo_mode").val();
     if(is_demo_mode=="demo"){
       toastr['warning']("Not allowed in demo mode!", '');
       e.preventDefault();
     }
  });
  
  $(document).on("click", ".minus_cart", function (e) {
      let qty = Number($(this).parent().find(".cart_qty").val()) || 1;
      let update_qty = qty;
      if(qty==1){

      }else{
        update_qty = qty-1;
      }
      $(this).parent().find(".cart_qty").attr("value",update_qty);
      $(this).closest(".sidebar-cart-card").attr("data-qty", update_qty);

      // Get base price per unit from data-price attribute
      let base_price = Number($(this).closest(".sidebar-cart-card").attr("data-price")) || 0;
      let inline_total = base_price * update_qty;
      let modifer_price = 0;
      $(this).closest(".sidebar-cart-card").find(".modifier_div").each(function () {
           let mod_price = Number($(this).attr("data-total_price")) || 0;
           modifer_price += (mod_price * update_qty);
      });
      $(this).parent().parent().find(".subtotal_cal").attr("data-inline_total", base_price);
      $(this).parent().parent().find(".subtotal_cal").text(currency+(modifer_price+inline_total).toFixed(precision));
      

      setTimeout(function () {
        storageCartDataInLocal();
        setCheckOutCartItem();
        cartItemCalculate();
        setTimeout(function () {
          get_total_vat();
          subtotalCal();
          cartItemCalculate();
        }, 100);
      }, 100);
  });

  $(document).on("click", ".plus_cart", function (e) {
    let qty = Number($(this).parent().find(".cart_qty").val()) || 1;
    let update_qty = qty+1;

    $(this).parent().find(".cart_qty").attr("value",update_qty);
    $(this).closest(".sidebar-cart-card").attr("data-qty", update_qty);
    
    // Get base price per unit from data-price attribute
    let base_price = Number($(this).closest(".sidebar-cart-card").attr("data-price")) || 0;
    let inline_total = base_price * update_qty;
    
    let modifer_price = 0;
    $(this).closest(".sidebar-cart-card").find(".modifier_div").each(function () {
         let mod_price = Number($(this).attr("data-total_price")) || 0;
         modifer_price += (mod_price * update_qty);
    });
      $(this).parent().parent().find(".subtotal_cal").attr("data-inline_total", base_price);
      $(this).parent().parent().find(".subtotal_cal").text(currency+(modifer_price+inline_total).toFixed(precision));
      
    setTimeout(function () {
      storageCartDataInLocal();
      setCheckOutCartItem();
      cartItemCalculate();
      setTimeout(function () {
        get_total_vat();
        subtotalCal();
        cartItemCalculate();
      }, 100);
    }, 100);
});

  detailsFoodTotalCalculate();

  function setReorderCheckout(order_info){
    $("#order_html_render").empty();
    let html_content = "";
    for (let key in order_info.items) {
      let modifier_html = "";
      let this_item = order_info.items[key];
 
      let total_modifier = 0;
      if(this_item.modifiers_id!=''){
          total_modifier = (this_item.modifiers_id.split(',')).length;
      }
     
      let modifier_ids_custom = [];
      let modifier_names_custom = [];
      let modifier_prices_custom = [];
      if(total_modifier){
          modifier_ids_custom = this_item.modifiers_id.split(',');
          modifier_names_custom = this_item.modifiers_name.split(',');
          modifier_prices_custom = this_item.modifiers_price.split(',');
      }
      let modifier_sum = 0;
      if (total_modifier) { 
        for (let mod_key_custom in modifier_names_custom) {
                modifier_html +=
                `<li data-id="` +
                modifier_ids_custom[mod_key_custom] +
                `" data-total_price="` +
                modifier_prices_custom[mod_key_custom] +
                `" data-name="` +
                modifier_names_custom[mod_key_custom] +
                `" class="d-block modifier_div">${modifier_names_custom[mod_key_custom]} (${Number(modifier_prices_custom[mod_key_custom]).toFixed(
                  precision
                )})</li>`;
                modifier_sum += Number(modifier_prices_custom[mod_key_custom]);
        }
      }

        let special_instructions = '';
        if (this_item.item_note != "" && this_item.item_note!=undefined && this_item.item_note!="undefined") {
          special_instructions = this_item.item_note;
        }
        let menu_details = search_by_menu_id(this_item.food_menu_id, window.items);
       
        let quantity = Number(this_item.qty);
        let food_order_id = Number(this_item.food_menu_id);
        html_content += `
        <div class="sidebar-cart-card" data-note="${special_instructions}" data-image="${menu_details[0].photo}" data-name="${this_item.menu_name}" data-price="${this_item.menu_unit_price}" data-qty="${Number(quantity)}"  data-order-cart-id="${food_order_id}">
            <div class="card-header">
                <img src="${menu_details[0].photo}" alt="${this_item.menu_name}" />
                <div class="d-flex flex-column gap15px w-100">
                    <div>
                        <h3 class="card-title">${this_item.menu_name}</h3>
                        <div class="card-prices sidebar-cart-card-meta">
                            <ul>
                                ${modifier_html}
                            </ul>
                        </div>
                        ${special_instructions && special_instructions.trim() !== '' ? `<p class="card-description"><strong>Note:</strong> ${special_instructions}.</p>` : ''}
                    </div>
                    <div class="price-section">
                        <div class="food-qty-price-${food_order_id}">${currency}${parseFloat(this_item.menu_unit_price).toFixed(precision)}</div>
                        <div class="quantity">
                            <button class="minus_cart">-</button>
                            <input type="text"  class="cart_qty" value="${Number(quantity)}" readonly />
                            <button class="plus_cart">+</button>
                        </div>
                        <div class="subtotal_cal cart-sidebar-price-${food_order_id}" data-inline_total="`+((Number(quantity) * Number(this_item.menu_unit_price)))+`">${currency}${ parseFloat(Number(modifier_sum) + (Number(quantity) * Number(this_item.menu_unit_price))).toFixed(precision)}</div>
                    </div>
                </div>
            </div>
            <button class="delete-btn close-btn single-item-remove">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_37_504)">
                        <path d="M4.875 0.75H7.125C7.22446 0.75 7.31984 0.789509 7.39017 0.859835C7.46049 0.930161 7.5 1.02554 7.5 1.125V1.875H4.5V1.125C4.5 1.02554 4.53951 0.930161 4.60984 0.859835C4.68016 0.789509 4.77554 0.75 4.875 0.75ZM8.25 1.875V1.125C8.25 0.826631 8.13147 0.540483 7.9205 0.329505C7.70952 0.118526 7.42337 0 7.125 0L4.875 0C4.57663 0 4.29048 0.118526 4.0795 0.329505C3.86853 0.540483 3.75 0.826631 3.75 1.125V1.875H1.125C1.02554 1.875 0.930161 1.91451 0.859835 1.98484C0.789509 2.05516 0.75 2.15054 0.75 2.25C0.75 2.34946 0.789509 2.44484 0.859835 2.51516C0.930161 2.58549 1.02554 2.625 1.125 2.625H1.5285L2.16825 10.62C2.19842 10.9959 2.36907 11.3466 2.64621 11.6024C2.92335 11.8581 3.28665 12.0001 3.66375 12H8.33625C8.71335 12.0001 9.07665 11.8581 9.35379 11.6024C9.63093 11.3466 9.80158 10.9959 9.83175 10.62L10.4715 2.625H10.875C10.9745 2.625 11.0698 2.58549 11.1402 2.51516C11.2105 2.44484 11.25 2.34946 11.25 2.25C11.25 2.15054 11.2105 2.05516 11.1402 1.98484C11.0698 1.91451 10.9745 1.875 10.875 1.875H8.25ZM9.7185 2.625L9.084 10.56C9.06892 10.7479 8.98359 10.9233 8.84502 11.0512C8.70645 11.1791 8.5248 11.25 8.33625 11.25H3.66375C3.4752 11.25 3.29355 11.1791 3.15498 11.0512C3.01641 10.9233 2.93108 10.7479 2.916 10.56L2.2815 2.625H9.7185ZM4.10325 3.375C4.2025 3.36926 4.29997 3.40317 4.37422 3.46927C4.44848 3.53537 4.49345 3.62825 4.49925 3.7275L4.87425 10.1025C4.87819 10.2006 4.84351 10.2963 4.77766 10.3691C4.71181 10.4419 4.62004 10.486 4.52206 10.4919C4.42407 10.4978 4.32768 10.465 4.25358 10.4006C4.17949 10.3362 4.13359 10.2453 4.12575 10.1475L3.75 3.7725C3.74696 3.72323 3.75367 3.67385 3.76975 3.62719C3.78583 3.58052 3.81097 3.53749 3.84372 3.50056C3.87646 3.46363 3.91618 3.43353 3.96059 3.41198C4.005 3.39043 4.05322 3.37787 4.1025 3.375H4.10325ZM7.89675 3.375C7.94603 3.37787 7.99425 3.39043 8.03866 3.41198C8.08307 3.43353 8.12279 3.46363 8.15553 3.50056C8.18828 3.53749 8.21342 3.58052 8.2295 3.62719C8.24558 3.67385 8.25229 3.72323 8.24925 3.7725L7.87425 10.1475C7.87225 10.1973 7.86033 10.2463 7.83918 10.2914C7.81804 10.3366 7.78809 10.3771 7.75111 10.4106C7.71412 10.444 7.67083 10.4698 7.62377 10.4863C7.57671 10.5028 7.52683 10.5098 7.47705 10.5068C7.42726 10.5038 7.37858 10.4909 7.33384 10.4688C7.2891 10.4468 7.2492 10.4161 7.21649 10.3784C7.18378 10.3408 7.1589 10.297 7.14332 10.2496C7.12774 10.2022 7.12177 10.1522 7.12575 10.1025L7.50075 3.7275C7.50655 3.62825 7.55152 3.53537 7.62578 3.46927C7.70004 3.40317 7.7975 3.36926 7.89675 3.375ZM6 3.375C6.09946 3.375 6.19484 3.41451 6.26517 3.48484C6.33549 3.55516 6.375 3.65054 6.375 3.75V10.125C6.375 10.2245 6.33549 10.3198 6.26517 10.3902C6.19484 10.4605 6.09946 10.5 6 10.5C5.90054 10.5 5.80516 10.4605 5.73484 10.3902C5.66451 10.3198 5.625 10.2245 5.625 10.125V3.75C5.625 3.65054 5.66451 3.55516 5.73484 3.48484C5.80516 3.41451 5.90054 3.375 6 3.375Z" fill="#CF6161"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_37_504">
                            <rect width="12" height="12" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </button>
            
        </div>`;
      }
      $("#order_html_render").append(html_content);
 
 
          setTimeout(function () {
            storageCartDataInLocal();
            setCheckOutCartItem();
            cartItemCalculate();
            toastr['success'](order_copied, '');

            get_total_vat();
            subtotalCal();
            cartItemCalculate();

            setTimeout(function () {
              window.location.href = base_url + "checkout";
            }, 4000);
          }, 200);
  

  }

  $(document).on("click", ".re_order", function (e) {
    e.preventDefault();
    let is_exist_cart = $(".sidebar-cart-card").length;
    let id = $(this).attr("data-id");
    let order_details = $("#order_details_"+id).html();

    if(is_exist_cart){
        swal(
          {
              title: warning + "!",
              text: cart_need_clean,
              confirmButtonColor: website_theme_color,
              confirmButtonText: ok,
              showCancelButton: true,
          },
          function () {
             let response = jQuery.parseJSON(order_details);
            setReorderCheckout(response);
          }
      );
  
    }else{
      let response = jQuery.parseJSON(order_details);
      setReorderCheckout(response);
    }
  




  });

  function setCheckOutCartItem() {
    let checkOutHtml = "";
    $(".sidebar-cart-card").each(function () {
      let food_menu_id = $(this).attr("data-order-cart-id");
      let name = $(this).attr("data-name");
      let price = Number($(this).attr("data-price"));
      let qty = Number($(this).attr("data-qty"));
      let photo = $(this).attr("data-image");
      let note = $(this).attr("data-note");
      let modifier_html = "";
      let modifier_sum = 0;

      $(this)
        .find(".sidebar-cart-card-meta li")
        .each(function () {
          let modifier_id = $(this).attr("data-id");
          let total_price = Number($(this).attr("data-total_price"));
          modifier_sum += Number(total_price);
          let modifier_name = $(this).attr("data-name");
      
          modifier_html +=
                `<li data-id="` +
                modifier_id +
                `" data-total_price="` +
                total_price +
                `" data-name="` +
                modifier_name +
                `" class="d-block modifier_div">${modifier_name} (${total_price.toFixed(
                  precision
                )})</li>`;
        });

      checkOutHtml +=
        `
            <div class="item">
              <div class="item-details">
                <div class="d-flex align-items-center gap10px">
                <img src="${photo}" alt="${name}" />
                <div>
                  <h4>${name} <span>x${qty}</span></h4>
                  <ul>
                    ${modifier_html}
                  </ul>                  
                  ${note && note.trim() !== '' ? `<p class="note"><strong>Note:</strong> ${note}.</p>` : ''}
                </div>
                </div>
                <p class="price checkout_single_subtotal checkout_single_subtotal_${food_menu_id}" data-inline_total="` +
                Number(qty) * Number(price) +
                `">${currency}${parseFloat(
                  Number(modifier_sum) + Number(qty) * Number(price)
                ).toFixed(precision)}</p>
              </div>
            </div>`;
    });

    localStorage["checkout_cart_html_irp"] = checkOutHtml;
    get_total_vat();
  }
  setTimeout(function () {
    get_total_vat();
  }, 50);
  function singleItemOrder(food_order_id, exist_check) {
    // Get quantity - use parent product ID for quantity input selector
    let quantity_input_id = food_order_id;
    let selected_variation = $(".product_variation_radio:checked");
    if(selected_variation.length > 0){
      let variation_item = selected_variation.closest(".product-variation-item");
      let parent_id = variation_item.attr("data-parent-id");
      if (parent_id) {
        quantity_input_id = parent_id;
      }
    }
    let quantity = $(`.item_details_qty_${quantity_input_id}`).val() || 1;
    quantity = Number(quantity) || 1;
    $.ajax({
      type: "POST",
      url: base_url + "Frontend/singleItemOrder",
      data: {
        food_id: food_order_id,
      },
      dataType: "json",
      success: function (response) {
        let html_content = "";
        if (response.status == "success") {
          let isChecked;
          let modifier_id = 0;
          let modifier_name = "";
          let modifier_price = 0;
          let modifier_html = "";
          let modifier_sum = 0;
          let special_instructions = $("#special_instructions").val();
          $(".modifier_checkbox").each(function () {
            isChecked = $(this).is(":checked");
            if (isChecked) {
              modifier_id = $(this)
                .closest(".customize-variation-item")
                .data("id");
              modifier_name = $(this)
                .closest(".customize-variation-item")
                .data("name");
              modifier_price = $(this)
                .closest(".customize-variation-item")
                .data("price");
              // Store per-unit price, will be multiplied by quantity when calculating
              let modifier_price_per_unit = Number(modifier_price);
              let modifier_price_total = modifier_price_per_unit * Number(quantity);
              modifier_html +=
                `<li data-id="` +
                modifier_id +
                `" data-total_price="` +
                modifier_price_per_unit +
                `" data-name="` +
                modifier_name +
                `" class="d-block modifier_div">${modifier_name} (${modifier_price_total.toFixed(
                  precision
                )})</li>`;
              modifier_sum += modifier_price_total;
            }
          });

          if (exist_check == "Yes") {
            $(`.checkout_single_subtotal_${response.data.id}`).text(
              `${parseFloat(
                Number(modifier_sum) +
                  Number(quantity) * Number(response.data.sale_price)
              ).toFixed(precision)}`
            );
          }

          if (exist_check == "Yes") {
            $(`.food-qty-price-${response.data.id}`).text(
              `${quantity} X ${response.data.sale_price}`
            );
            $(`.cart-sidebar-price-${response.data.id}`).text(
              `${parseFloat(
                Number(modifier_sum) +
                  Number(quantity) * Number(response.data.sale_price)
              ).toFixed(precision)}`
            );
            $(".sidebar-cart-card-meta").html("").html(modifier_html);
          } else {
            // Check if this is a variation product
            let selected_variation = $(".product_variation_radio:checked");
            let cart_image = response.data.photo;
            let cart_name = response.data.name;
            
            if(selected_variation.length > 0){
              let variation_item = selected_variation.closest(".product-variation-item");
              let parent_image = variation_item.attr("data-parent-image");
              let parent_name = variation_item.attr("data-parent-name");
              let alternative_name = variation_item.attr("data-alternative-name");
              
              // Use parent product image
              if(parent_image && parent_image.trim() !== ''){
                cart_image = parent_image;
              }
              
              // Build name with parent name and alternative name in brackets
              if(parent_name && parent_name.trim() !== ''){
                if(alternative_name && alternative_name.trim() !== ''){
                  cart_name = parent_name + " (" + alternative_name + ")";
                } else {
                  cart_name = parent_name;
                }
              }
            }
            
            // Store parent ID if this is a variation
            let parent_id_attr = "";
            if(selected_variation.length > 0){
              let variation_item = selected_variation.closest(".product-variation-item");
              let parent_id = variation_item.attr("data-parent-id");
              if (parent_id) {
                parent_id_attr = ` data-parent-id="${parent_id}"`;
              }
            }
            
            html_content += `
            <div class="sidebar-cart-card" data-note="${special_instructions}" data-image="${cart_image}" data-name="${cart_name}" data-price="${response.data.sale_price}" data-qty="${Number(quantity)}"  data-order-cart-id="${food_order_id}"${parent_id_attr}>
                <div class="card-header">
                    <img src="${cart_image}" alt="${cart_name}" />
                    <div class="d-flex flex-column gap15px w-100">
                        <div>
                            <h3 class="card-title">${cart_name}</h3>
                            <div class="card-prices sidebar-cart-card-meta">
                                <ul>
                                    ${modifier_html}
                                </ul>
                            </div>
                            ${special_instructions && special_instructions.trim() !== '' ? `<p class="card-description"><strong>Note:</strong> ${special_instructions}.</p>` : ''}
                        </div>
                        <div class="price-section">
                            <div class="food-qty-price-${response.data.id}">${currency}${parseFloat(response.data.sale_price).toFixed(precision)}</div>
                            <div class="quantity">
                                <button class="minus_cart">-</button>
                                <input type="text"  class="cart_qty" value="${Number(quantity)}" readonly />
                                <button class="plus_cart">+</button>
                            </div>
                            <div class="subtotal_cal cart-sidebar-price-${response.data.id}" data-inline_total="`+((Number(quantity) * Number(response.data.sale_price)))+`">${currency}${ parseFloat(Number(modifier_sum) + (Number(quantity) * Number(response.data.sale_price))).toFixed(precision)}</div>
                        </div>
                    </div>
                </div>
                <button class="delete-btn close-btn single-item-remove">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_37_504)">
                            <path d="M4.875 0.75H7.125C7.22446 0.75 7.31984 0.789509 7.39017 0.859835C7.46049 0.930161 7.5 1.02554 7.5 1.125V1.875H4.5V1.125C4.5 1.02554 4.53951 0.930161 4.60984 0.859835C4.68016 0.789509 4.77554 0.75 4.875 0.75ZM8.25 1.875V1.125C8.25 0.826631 8.13147 0.540483 7.9205 0.329505C7.70952 0.118526 7.42337 0 7.125 0L4.875 0C4.57663 0 4.29048 0.118526 4.0795 0.329505C3.86853 0.540483 3.75 0.826631 3.75 1.125V1.875H1.125C1.02554 1.875 0.930161 1.91451 0.859835 1.98484C0.789509 2.05516 0.75 2.15054 0.75 2.25C0.75 2.34946 0.789509 2.44484 0.859835 2.51516C0.930161 2.58549 1.02554 2.625 1.125 2.625H1.5285L2.16825 10.62C2.19842 10.9959 2.36907 11.3466 2.64621 11.6024C2.92335 11.8581 3.28665 12.0001 3.66375 12H8.33625C8.71335 12.0001 9.07665 11.8581 9.35379 11.6024C9.63093 11.3466 9.80158 10.9959 9.83175 10.62L10.4715 2.625H10.875C10.9745 2.625 11.0698 2.58549 11.1402 2.51516C11.2105 2.44484 11.25 2.34946 11.25 2.25C11.25 2.15054 11.2105 2.05516 11.1402 1.98484C11.0698 1.91451 10.9745 1.875 10.875 1.875H8.25ZM9.7185 2.625L9.084 10.56C9.06892 10.7479 8.98359 10.9233 8.84502 11.0512C8.70645 11.1791 8.5248 11.25 8.33625 11.25H3.66375C3.4752 11.25 3.29355 11.1791 3.15498 11.0512C3.01641 10.9233 2.93108 10.7479 2.916 10.56L2.2815 2.625H9.7185ZM4.10325 3.375C4.2025 3.36926 4.29997 3.40317 4.37422 3.46927C4.44848 3.53537 4.49345 3.62825 4.49925 3.7275L4.87425 10.1025C4.87819 10.2006 4.84351 10.2963 4.77766 10.3691C4.71181 10.4419 4.62004 10.486 4.52206 10.4919C4.42407 10.4978 4.32768 10.465 4.25358 10.4006C4.17949 10.3362 4.13359 10.2453 4.12575 10.1475L3.75 3.7725C3.74696 3.72323 3.75367 3.67385 3.76975 3.62719C3.78583 3.58052 3.81097 3.53749 3.84372 3.50056C3.87646 3.46363 3.91618 3.43353 3.96059 3.41198C4.005 3.39043 4.05322 3.37787 4.1025 3.375H4.10325ZM7.89675 3.375C7.94603 3.37787 7.99425 3.39043 8.03866 3.41198C8.08307 3.43353 8.12279 3.46363 8.15553 3.50056C8.18828 3.53749 8.21342 3.58052 8.2295 3.62719C8.24558 3.67385 8.25229 3.72323 8.24925 3.7725L7.87425 10.1475C7.87225 10.1973 7.86033 10.2463 7.83918 10.2914C7.81804 10.3366 7.78809 10.3771 7.75111 10.4106C7.71412 10.444 7.67083 10.4698 7.62377 10.4863C7.57671 10.5028 7.52683 10.5098 7.47705 10.5068C7.42726 10.5038 7.37858 10.4909 7.33384 10.4688C7.2891 10.4468 7.2492 10.4161 7.21649 10.3784C7.18378 10.3408 7.1589 10.297 7.14332 10.2496C7.12774 10.2022 7.12177 10.1522 7.12575 10.1025L7.50075 3.7275C7.50655 3.62825 7.55152 3.53537 7.62578 3.46927C7.70004 3.40317 7.7975 3.36926 7.89675 3.375ZM6 3.375C6.09946 3.375 6.19484 3.41451 6.26517 3.48484C6.33549 3.55516 6.375 3.65054 6.375 3.75V10.125C6.375 10.2245 6.33549 10.3198 6.26517 10.3902C6.19484 10.4605 6.09946 10.5 6 10.5C5.90054 10.5 5.80516 10.4605 5.73484 10.3902C5.66451 10.3198 5.625 10.2245 5.625 10.125V3.75C5.625 3.65054 5.66451 3.55516 5.73484 3.48484C5.80516 3.41451 5.90054 3.375 6 3.375Z" fill="#CF6161"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_37_504">
                                <rect width="12" height="12" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                </button>
                
            </div>`;
            $("#order_html_render").append(html_content);
          }
          setTimeout(function () {
            storageCartDataInLocal();
            setCheckOutCartItem();
            cartItemCalculate();
            setTimeout(function () {
              get_total_vat();
              subtotalCal();
              cartItemCalculate();
            }, 200);
          }, 200);
        }
      },
    });
  }

  $(document).on("click", ".single-item-remove", function () {
    $(this).parent().remove();
    subtotalCal();
    cartItemCalculate();
    storageCartDataInLocal();
    setCheckOutCartItem();
  });

  $(document).on("click", ".call_calculation", function () {
    get_total_vat();
    subtotalCal();
  });

  function subtotalCal() {
    setTimeout(function () {
      let subtotal = 0;
      $(".subtotal_cal").each(function () {
        let subtotalText = $(this).text();
        let subtotalValue = subtotalText.replace(currency, '').trim();
        
        subtotal += parseFloat(subtotalValue);
      });
      
      // Use stored tax breakdown from get_total_vat() if available, otherwise calculate
      let tax_object = window.cartTaxBreakdown || {};
      let collect_tax = $("#collect_tax").val();
      
      // If no stored breakdown, calculate it
      if (collect_tax == "Yes" && Object.keys(tax_object).length === 0) {
        let tax_name = [];
        $(".sidebar-cart-card").each(function () {
          let food_menu_id = $(this).attr("data-order-cart-id");
          let qty = Number($(this).attr("data-qty")) || 1;
          let inline_total = $(this).find(".subtotal_cal").attr("data-inline_total");
          let item_total_price = parseFloat(inline_total || 0).toFixed(ir_precision);
          item_total_price = item_total_price * qty;
          
          let menu_details = search_by_menu_id(food_menu_id, window.items);
          
          // If variation not found, try to get parent product tax info
          if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
            let parent_id = $(this).attr("data-parent-id");
            if (parent_id) {
              menu_details = search_by_menu_id(Number(parent_id), window.items);
            }
            if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
              let original_food_menu_id = Number($("#details_item_price").attr("data-food_menu_id"));
              menu_details = search_by_menu_id(original_food_menu_id, window.items);
            }
          }
          
          if (menu_details && menu_details.length > 0 && menu_details[0]) {
            let tax_information = JSON.parse(menu_details[0].tax_information);
            
            if (tax_information.length > 0) {
              for (let k in tax_information) {
                if (checkTaxApply(tax_information[k].tax_field_name)) {
                  let tax_field_name = tax_information[k].tax_field_name;
                  let current_value = 0;
                  let tax_type = Number($("#tax_type").val());
                  
                  if (tax_type == 1) {
                    current_value = parseFloat(
                      parseFloat(tax_information[k].tax_field_percentage) * parseFloat(item_total_price)
                    ) / parseFloat(100);
                  } else {
                    current_value = (
                      parseFloat(item_total_price) -
                      parseFloat(item_total_price) / (1 + tax_information[k].tax_field_percentage / 100)
                    );
                  }
                  
                  if (tax_name.includes(tax_field_name)) {
                    tax_object[tax_field_name] = (
                      parseFloat(tax_object[tax_field_name] || 0) + Number(current_value)
                    ).toFixed(ir_precision);
                  } else {
                    tax_name.push(tax_field_name);
                    tax_object[tax_field_name] = Number(current_value).toFixed(ir_precision);
                  }
                }
              }
            }
          }
          
          // Calculate tax for modifiers
          $(this).find(".sidebar-cart-card-meta li").each(function () {
            let modifier_id = $(this).attr("data-id");
            let modifier_total_price = Number($(this).attr("data-total_price")) * qty;
            
            let modifier_details = search_by_modifer_id(modifier_id, window.only_modifiers);
            
            if (modifier_details.length > 0 && modifier_details[0].tax_information) {
              let tax_information = JSON.parse(modifier_details[0].tax_information);
              
              if (tax_information.length > 0) {
                for (let k in tax_information) {
                  if (checkTaxApply(tax_information[k].tax_field_name)) {
                    let tax_field_name = tax_information[k].tax_field_name;
                    let current_value = 0;
                    let tax_type = Number($("#tax_type").val());
                    
                    if (tax_type == 1) {
                      current_value = parseFloat(
                        parseFloat(tax_information[k].tax_field_percentage) * parseFloat(modifier_total_price)
                      ) / parseFloat(100);
                    } else {
                      current_value = (
                        parseFloat(modifier_total_price) -
                        parseFloat(modifier_total_price) / (1 + tax_information[k].tax_field_percentage / 100)
                      );
                    }
                    
                    if (tax_name.includes(tax_field_name)) {
                      tax_object[tax_field_name] = (
                        parseFloat(tax_object[tax_field_name] || 0) + Number(current_value)
                      ).toFixed(ir_precision);
                    } else {
                      tax_name.push(tax_field_name);
                      tax_object[tax_field_name] = Number(current_value).toFixed(ir_precision);
                    }
                  }
                }
              }
            }
          });
        });
      }
      
      // Calculate total tax from breakdown
      let total_tax = 0;
      $.each(tax_object, function (key, value) {
        total_tax += Number(value);
      });
      
      // Fallback to total_vat_hidden if breakdown is empty
      if (total_tax === 0) {
        total_tax = Number($("#total_vat_hidden").val()) || 0;
      }
      
      // Display tax breakdown in cart
      let tax_breakdown_html = "";
      if (Object.keys(tax_object).length > 0) {
        $.each(tax_object, function (tax_name, tax_amount) {
          tax_breakdown_html += '<div class="d-flex justify-content-between">';
          tax_breakdown_html += '<p>' + tax_name + ':</p>';
          tax_breakdown_html += '<p>' + currency + parseFloat(tax_amount).toFixed(precision) + '</p>';
          tax_breakdown_html += '</div>';
        });
        $("#cart-tax-breakdown").html(tax_breakdown_html);
        $(".cart-tax-total-fallback").hide();
      } else {
        $("#cart-tax-breakdown").html("");
        $(".cart-tax-total-fallback").show();
      }

      let delivery_amount_hidden = ($("#delivery_amount_hidden").val());
      let apply_on_delivery_charge = Number($("#apply_on_delivery_charge").val());
      let delivery_charge_amount_tax = 0;
      let delivery_charge_amount_tmp = 0;
      let total_delivery_charge = 0;
      
      // Only calculate delivery charge if there are items in cart (subtotal > 0)
      if (subtotal > 0) {
        if (total_tax) {
          if(apply_on_delivery_charge==2){
            delivery_charge_amount_tax = Number(get_particular_item_discount_amount(delivery_amount_hidden,total_tax));
          }
        }
        
        delivery_charge_amount_tmp = Number(get_particular_item_discount_amount(delivery_amount_hidden,subtotal));
        total_delivery_charge = delivery_charge_amount_tax + delivery_charge_amount_tmp;
      }
      
      if (!subtotal) {
        total_tax = 0;
        $("#cart-tax-breakdown").html("");
        $(".cart-tax-total-fallback").show();
      }
      
      let total_payable = subtotal + total_tax + total_delivery_charge;
      $(".cart-subtotal").text(`${currency}${subtotal.toFixed(precision)}`);
      $(".cart-tax").text(`${currency}${total_tax.toFixed(precision)}`);
      $("#side_cart_delivery_charge").text(currency+(parseFloat(total_delivery_charge).toFixed(precision)));
      $(".cart-total").text(`${currency}${total_payable.toFixed(precision)}`);
    }, 200);
  }

  function cartItemCalculate() {
    let cart_length = $(".sidebar-cart-card").length;
    $(".cart-item-count").text(cart_length);
    $(".cart-item-count-static").text(cart_length);
    $(".cart-item-total").text(`(${cart_length})`);
  }

  function storageCartDataInLocal() {
    localStorage["cart_html_irp"] = $("#order_html_render").html();
    setCheckOutCartItem();
  }

  let local_cart_data = localStorage["cart_html_irp"];
  if (local_cart_data) {
    $("#order_html_render").html(local_cart_data);
    subtotalCal();
    cartItemCalculate();
  }

  let checkout_cart_html = localStorage["checkout_cart_html_irp"];
  if (checkout_cart_html) { 
    $(".card-checkout-item").html(checkout_cart_html);
    subtotalCal();
    cartItemCalculate();
  }

  function get_particular_item_discount_amount(discount, item_price) {
    if (discount.length > 0 && discount.substr(discount.length - 1) == "%") {
      return (
        (parseFloat(item_price) * parseFloat(discount)) /
        parseFloat(100)
      ).toFixed(ir_precision);
    } else {
      return parseFloat(discount).toFixed(ir_precision);
    }
  }
  
  function checkoutCalculation() {
    let subtotal = 0;
    let grandTotal = 0;
    $(".checkout_single_subtotal").each(function () {
      let subtotal_inline = parseFloat($(this).text().replace(currency, '').trim());
      grandTotal += subtotal_inline;
      subtotal+=subtotal_inline;
    });

    // Use stored tax breakdown from get_total_vat() if available, otherwise calculate
    let tax_object = window.cartTaxBreakdown || {};
    let collect_tax = $("#collect_tax").val();
    
    // If no stored breakdown, try to calculate from checkout items
    if (collect_tax == "Yes" && Object.keys(tax_object).length === 0) {
      let tax_name = [];
      $(".checkout-item-row").each(function () {
        let food_menu_id = $(this).attr("data-food-menu-id");
        let qty = Number($(this).attr("data-qty")) || 1;
        let item_price = parseFloat($(this).find(".checkout_single_subtotal").text().replace(currency, '').trim()) / qty;
        let item_total_price = item_price * qty;
        
        let menu_details = search_by_menu_id(food_menu_id, window.items);
        
        if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
          let parent_id = $(this).attr("data-parent-id");
          if (parent_id) {
            menu_details = search_by_menu_id(Number(parent_id), window.items);
          }
        }
        
        if (menu_details && menu_details.length > 0 && menu_details[0]) {
          let tax_information = JSON.parse(menu_details[0].tax_information);
          
          if (tax_information.length > 0) {
            for (let k in tax_information) {
              if (checkTaxApply(tax_information[k].tax_field_name)) {
                let tax_field_name = tax_information[k].tax_field_name;
                let current_value = 0;
                let tax_type = Number($("#tax_type").val());
                
                if (tax_type == 1) {
                  current_value = parseFloat(
                    parseFloat(tax_information[k].tax_field_percentage) * parseFloat(item_total_price)
                  ) / parseFloat(100);
                } else {
                  current_value = (
                    parseFloat(item_total_price) -
                    parseFloat(item_total_price) / (1 + tax_information[k].tax_field_percentage / 100)
                  );
                }
                
                if (tax_name.includes(tax_field_name)) {
                  tax_object[tax_field_name] = (
                    parseFloat(tax_object[tax_field_name] || 0) + Number(current_value)
                  ).toFixed(ir_precision);
                } else {
                  tax_name.push(tax_field_name);
                  tax_object[tax_field_name] = Number(current_value).toFixed(ir_precision);
                }
              }
            }
          }
        }
        
        // Calculate tax for modifiers in checkout
        $(this).find(".checkout-modifier-item").each(function () {
          let modifier_id = $(this).attr("data-modifier-id");
          let modifier_price = parseFloat($(this).find(".checkout-modifier-price").text().replace(currency, '').trim()) * qty;
          
          let modifier_details = search_by_modifer_id(modifier_id, window.only_modifiers);
          
          if (modifier_details.length > 0 && modifier_details[0].tax_information) {
            let tax_information = JSON.parse(modifier_details[0].tax_information);
            
            if (tax_information.length > 0) {
              for (let k in tax_information) {
                if (checkTaxApply(tax_information[k].tax_field_name)) {
                  let tax_field_name = tax_information[k].tax_field_name;
                  let current_value = 0;
                  let tax_type = Number($("#tax_type").val());
                  
                  if (tax_type == 1) {
                    current_value = parseFloat(
                      parseFloat(tax_information[k].tax_field_percentage) * parseFloat(modifier_price)
                    ) / parseFloat(100);
                  } else {
                    current_value = (
                      parseFloat(modifier_price) -
                      parseFloat(modifier_price) / (1 + tax_information[k].tax_field_percentage / 100)
                    );
                  }
                  
                  if (tax_name.includes(tax_field_name)) {
                    tax_object[tax_field_name] = (
                      parseFloat(tax_object[tax_field_name] || 0) + Number(current_value)
                    ).toFixed(ir_precision);
                  } else {
                    tax_name.push(tax_field_name);
                    tax_object[tax_field_name] = Number(current_value).toFixed(ir_precision);
                  }
                }
              }
            }
          }
        });
      });
    }
    
    // Calculate total tax from breakdown
    let total_tax = 0;
    $.each(tax_object, function (key, value) {
      total_tax += Number(value);
    });
    
    // Use total_vat_hidden if available (from get_total_vat), otherwise use calculated total_tax
    let total_vat_hidden = $("#total_vat_hidden").val();
    if (!total_vat_hidden || total_vat_hidden == "0" || total_vat_hidden == "0.00") {
      total_vat_hidden = total_tax.toFixed(2);
    } else if (total_tax > 0) {
      // Use calculated total if breakdown exists
      total_vat_hidden = total_tax.toFixed(2);
    }
    
    // Display tax breakdown in checkout
    let tax_breakdown_html = "";
    if (Object.keys(tax_object).length > 0) {
      $.each(tax_object, function (tax_name, tax_amount) {
        tax_breakdown_html += '<p class="shipping">' + tax_name + ': <span>' + currency + parseFloat(tax_amount).toFixed(precision) + '</span></p>';
        tax_breakdown_html += '<hr />';
      });
      $("#checkout-tax-breakdown").html(tax_breakdown_html);
      $(".checkout-tax-total-fallback").hide();
    } else {
      $("#checkout-tax-breakdown").html("");
      $(".checkout-tax-total-fallback").show();
      $(".checkout_tax_total").text(parseFloat(total_vat_hidden).toFixed(precision));
    }

    let delivery_amount_hidden = ($("#delivery_amount_hidden").val());
    let apply_on_delivery_charge = Number($("#apply_on_delivery_charge").val());
    let delivery_charge_amount_tax = 0;
    let delivery_charge_amount_tmp = 0;
    let total_delivery_charge = 0;
    
    // Only calculate delivery charge if there are items in cart (subtotal > 0)
    if (subtotal > 0) {
      if (total_vat_hidden) {
        if(apply_on_delivery_charge==2){
          delivery_charge_amount_tax = Number(get_particular_item_discount_amount(delivery_amount_hidden,total_vat_hidden));
        }
      }

      delivery_charge_amount_tmp = Number(get_particular_item_discount_amount(delivery_amount_hidden,subtotal));
      total_delivery_charge = delivery_charge_amount_tax + delivery_charge_amount_tmp;
    }
    
    grandTotal += total_delivery_charge;
    $(".checkout_delivery_fee").text(parseFloat(total_delivery_charge).toFixed(precision));
    $(".checkout_sub_total").text(parseFloat(subtotal).toFixed(precision));
    let g_total = Number(total_vat_hidden) + Number(grandTotal);
    $(".checkout_grand_total").text(parseFloat(g_total).toFixed(precision));
  }
  setTimeout(function () {
    checkoutCalculation();
  }, 100);

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
  function getPadTwo(str) {
    str = str.toString();
    return str.length < 3 ? getPadTwo("0" + str, 3) : str;
  }
  function generateSaleNo() {
    //for date and time
    let today = new Date();
    let dd = today.getDate();
    let mm = today.getMonth() + 1; //January is 0!
    let yyyy = today.getFullYear();
    let twoDigitYear = yyyy.toString().substr(-2);
    if (dd < 10) {
      dd = "0" + dd;
    }
    if (mm < 10) {
      mm = "0" + mm;
    }
    let time_a = new Date();
    let t_h = time_a.getHours();
    let t_m = time_a.getMinutes();
    let t_s = time_a.getSeconds();

    if (t_h < 10) {
      t_h = "0" + t_h;
    }
    if (t_m < 10) {
      t_m = "0" + t_m;
    }
    if (t_s < 10) {
      t_s = "0" + t_s;
    }
    let username_short = $("#username_short").val();
    let invoice_counter_value_tmp = Number(
      localStorage["invoice_counter_value"]
    )
      ? Number(localStorage["invoice_counter_value"])
      : 0;
    let invoice_counter_value = invoice_counter_value_tmp + 1;
    localStorage["invoice_counter_value"] = invoice_counter_value;
    let sale_no =
    username_short +
      twoDigitYear +
      mm +
      dd +
      "-" +
      getPadTwo(invoice_counter_value);


    return sale_no;
  }
  function getDateTime() {
    //for date and time
    let today1 = new Date();
    let dd1 = today1.getDate();
    let mm1 = today1.getMonth() + 1; //January is 0!
    let yyyy = today1.getFullYear();
    if (dd1 < 10) {
      dd1 = "0" + dd1;
    }
    if (mm1 < 10) {
      mm1 = "0" + mm1;
    }
    let time_a = new Date().toLocaleTimeString();
    let today_date = yyyy + "-" + mm1 + "-" + dd1;
    let date_time = today_date + " " + time_a;
    return [date_time, time_a];
  }
  function getRandomCode(length) {
    let result = "";
    //this is random character pattern
    let characters =
      "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }
  function search_by_menu_id(menu_id, myArray) {
    let foundResult = new Array();
    for (let i = 0; i < myArray.length; i++) {
      if (Number(myArray[i].item_id) === Number(menu_id)) {
        foundResult.push(myArray[i]);
      }
    }
    return foundResult.sort();
  }
  function search_by_modifer_id(menu_modifier_id, myArray) {
    let foundResult = new Array();
    for (let i = 0; i < myArray.length; i++) {
      if (Number(myArray[i].menu_modifier_id) === Number(menu_modifier_id)) {
        foundResult.push(myArray[i]);
      }
    }
    return foundResult.sort();
  }

  $(document).on("click", ".pay_now", function (e) {
    e.preventDefault();
    let fname = $("#fname").val();
    let phone = $("#phone").val();
    let email = $("#email").val();
    let faddress = $("#faddress").val();
    var outlet_id =  Number($("#online_selected_outlet").val());

    let status = true;

    if (fname == "") {
      status = false;
      $("#fname").css("border", "1px solid red");
      $("#fname").focus();
    } else if (phone == "") {
      status = false;
      $("#phone").css("border", "1px solid red");
      $("#phone").focus();
    } else if (faddress == "") {
      status = false;
      $("#faddress").css("border", "1px solid red");
      $("#faddress").focus();
    } else if (!isEmail(email)) {
      status = false;
      $("#email").css("border", "1px solid red");
      $("#email").focus();
    }
    if ($(".sidebar-cart-card").length <= 0) {
      status = false;
      toastr['error'](not_data_in_cart, '');
    }
    if (status == true) {
      $(this).addClass("no_access");
      let sale_no_new = 0;
      let random_code = "";
      sale_no_new = generateSaleNo();
      random_code = getRandomCode(15);

      let order_status = 1;
      let rounding_amount_hidden = 0;
      let customer_current_due = 0;
      let token_number = "";
      let hidden_given_amount = 0;
      let hidden_change_amount = 0;
      let counter_id = 0;
      let customer_id = $("#hidden_customer_id").val();
      let faddress = $("#faddress").val();
      let open_invoice_date_hidden = $("#open_invoice_date_hidden").val();
      let total_items_in_cart = $(".cart-item-count").text();
      let total_items_in_cart_qty = 0;

      $(".sidebar-cart-card").each(function () {
        let this_qty = Number($(this).attr("data-qty"));
        total_items_in_cart_qty += this_qty;
      });
      let total_amount = $(".checkout_grand_total").eq(1).text().replace(currency, '').trim();
      let checkout_delivery_fee = Number($(".checkout_delivery_fee").text().replace(currency, '').trim());
      let checkout_sub_total = $(".checkout_sub_total").text().replace(currency, '').trim();
      let total_vat = $("#total_vat_hidden").val();
      let outlet_id_indexdb = outlet_id;
      let order_info = "{";
      order_info += '"sale_no":"' + sale_no_new + '",';
      order_info += '"is_online_order":"Yes",';
      order_info += '"outlet_id":"' + outlet_id_indexdb + '",';
      order_info += '"waiter_app_status":"",';
      order_info += '"hidden_given_amount":"' + hidden_given_amount + '",';
      order_info += '"hidden_change_amount":"' + hidden_change_amount + '",';
      order_info += '"counter_id":"' + counter_id + '",';
      order_info += '"random_code":"' + random_code + '",';
      order_info += '"token_number":"' + token_number + '",';
      order_info += '"customer_id":"' + customer_id + '",';
      order_info += '"customer_address":"'+faddress+'",';
      order_info += '"customer_gst_number":"",';
      order_info += '"status":"Pending",';
      order_info += '"user_name":"",';
      order_info += '"user_id":"",';
      order_info += '"customer_name":"' + fname + '",';
      order_info += '"delivery_partner_id":"",';
      order_info += '"self_order_table_id":"",';
      order_info += '"self_order_table_person":"",';
      order_info +=
        '"rounding_amount_hidden":"' + rounding_amount_hidden + '",';
      order_info += '"previous_due_tmp":"' + customer_current_due + '",';
      order_info += '"waiter_id":"",';
      order_info += '"waiter_name":"",';
      order_info +=
        '"open_invoice_date_hidden":"' + open_invoice_date_hidden + '",';
      order_info += '"total_items_in_cart":"' + total_items_in_cart + '",';
      order_info +=
        '"total_items_in_cart_qty":"' + total_items_in_cart_qty + '",';
      order_info += '"sub_total":"' + checkout_sub_total + '",';
      order_info += '"sale_date":"' + open_invoice_date_hidden + '",';
      order_info += '"date_time":"' + getDateTime()[0] + '",';
      order_info += '"order_time":"' + getDateTime()[1] + '",';
      order_info += '"charge_type":"delivery",';
      order_info += '"total_vat":"' + total_vat + '",';
      order_info += '"total_payable":"' + total_amount + '",';
      order_info += '"total_item_discount_amount":"0",';
      order_info += '"sub_total_with_discount":"",';
      order_info += '"sub_total_discount_amount":"0",';
      order_info += '"total_discount_amount":"0",';
      order_info += '"delivery_charge":"'+checkout_delivery_fee+'",';
      order_info += '"tips_amount":"0",';
      order_info += '"delivery_charge_actual_charge":"'+checkout_delivery_fee+'",';
      order_info += '"tips_amount_actual_charge":"0",';
      order_info += '"sub_total_discount_value":"0",';
      order_info += '"sub_total_discount_type":"",';
      order_info += '"order_type":"3",';
      order_info += '"order_status":"' + order_status + '",';

      let sale_vat_objects = [];
      $("#tax_row_show .tax_field").each(function (i, obj) {
        let tax_field_id = $(this).attr("data-tax_field_id");
        let tax_field_type = $(this).attr("data-tax_field_type");
        let tax_field_amount = $(this).attr("data-tax_field_amount");
        sale_vat_objects.push({
          tax_field_id: tax_field_id,
          tax_field_type: tax_field_type,
          tax_field_amount:
            parseFloat(tax_field_amount).toFixed(ir_precision),
        });
      });

 
      order_info +=
        '"sale_vat_objects":' + JSON.stringify(sale_vat_objects) + ",";

      let hidden_table_name = "";
      let hidden_table_id = "";
      let hidden_table_capacity = 1;

      let total_person = 0;

      let orders_table = "";
      orders_table += '"orders_table":';
      orders_table += "[";
      let x = 1;

      let orders_table_text = "";
      total_person = hidden_table_capacity;
      orders_table_text = hidden_table_name;
      orders_table +=
        '{"table_id":"' +
        hidden_table_id +
        '", "persons":"' +
        hidden_table_capacity +
        '"}';

      let items_info = "";

      items_info += '"items":';
      items_info += "[";

      if ($(".sidebar-cart-card").length > 0) {
        let k = 1;
        $(".sidebar-cart-card").each(function () {
          let item_id = $(this).attr("data-order-cart-id");
          let menu_details = search_by_menu_id(item_id, window.items);
          
          // If variation not found, try to get parent product tax info
          if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
            // Check if this cart item has a parent ID stored
            let parent_id = $(this).attr("data-parent-id");
            if (parent_id) {
              menu_details = search_by_menu_id(Number(parent_id), window.items);
            }
            // If still not found, try to get from the original details_item_price
            if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
              let original_food_menu_id = Number($("#details_item_price").attr("data-food_menu_id"));
              if (original_food_menu_id) {
                menu_details = search_by_menu_id(original_food_menu_id, window.items);
              }
            }
          }
          
          // Use fallback values if menu details still not found
          let item_price_from_attr = parseFloat($(this).attr("data-price") || "0");
          let tax_information_fallback = "[]";
          let item_price_fallback = item_price_from_attr;
          
          // Check if this is a variation (has parent_id attribute)
          let parent_id_attr = $(this).attr("data-parent-id");
          let is_variation = (parent_id_attr && parent_id_attr !== "" && parent_id_attr !== "0");
          
          if (!menu_details || menu_details.length === 0 || !menu_details[0]) {
            console.warn("Menu details not found for item_id:", item_id, "Using fallback values");
            menu_details = [{
              tax_information: tax_information_fallback,
              price: item_price_fallback.toString()
            }];
          }
          
          let item_name = $(this).attr("data-name");
          let qty = Number($(this).attr("data-qty"));

          let item_vat = [];
          let tax_information_tmp = [];
          
          try {
            tax_information_tmp = JSON.parse(menu_details[0].tax_information || "[]");
          } catch(e) {
            console.error("Error parsing tax_information for item_id:", item_id, e);
            tax_information_tmp = [];
          }

          if (tax_information_tmp.length > 0) {
            for (let k in tax_information_tmp) {
              item_vat.push({
                tax_field_id: 1,
                tax_field_type: tax_information_tmp[k].tax_field_name,
                tax_field_amount: parseFloat(
                  tax_information_tmp[k].tax_field_percentage
                ).toFixed(ir_precision),
              });
            }
          } 
          let item_discount = 0;
          let discount_type = "fixed";

          let item_previous_id = 0;
          let item_cooking_done_time = "";
          let item_cooking_start_time = "";
          let item_cooking_status = "";
          let item_type = "";

          // For variations, prioritize price from cart item attribute (data-price)
          // This ensures we use the variation price, not the parent price
          let item_unit_price;
          if (is_variation && item_price_from_attr > 0) {
            item_unit_price = item_price_from_attr;
          } else {
            item_unit_price = parseFloat(menu_details[0].price || item_price_from_attr || "0");
          }
          let item_quantity = qty;
          let is_kot_print = "";
          let tmp_qty = qty;
          let p_qty = qty;
          let item_price_with_discount = item_unit_price * qty;
          let item_discount_amount = 0;
          let item_price_without_discount = item_price_with_discount; 

          items_info +=
            '{"food_menu_id":"' +
            item_id +
            '", "is_print":"' +
            1 +
            '", "is_kot_print":"' +
            is_kot_print +
            '", "menu_name":"' +
            item_name +
            '", "kitchen_id":"", "kitchen_name":"", "is_free":"0", "rounding_amount_hidden":"0", "item_vat":' +
            JSON.stringify(item_vat) +
            ",";
          items_info +=
            '"menu_discount_value":"' +
            item_discount +
            '","discount_type":"' +
            discount_type +
            '","menu_price_without_discount":"' +
            item_price_without_discount +
            '",';
          items_info +=
            '"menu_unit_price":"' +
            item_unit_price +
            '","qty":"' +
            item_quantity +
            '","tmp_qty":"' +
            tmp_qty +
            '","p_qty":"' +
            p_qty +
            '",';
          items_info +=
            '"item_previous_id":"' +
            item_previous_id +
            '","item_cooking_done_time":"' +
            item_cooking_done_time +
            '",';
          items_info +=
            '"item_cooking_start_time":"' +
            item_cooking_start_time +
            '","item_cooking_status":"' +
            item_cooking_status +
            '","item_type":"' +
            item_type +
            '",';
          items_info +=
            '"menu_price_with_discount":"' +
            item_price_with_discount +
            '","item_discount_amount":"' +
            item_discount_amount +
            '"';

          let modifiers_id = "";
          let modifiers_name = "";
          let modifiers_price = "";

          let iii = 1;
          let modifier_vat = "";
          let total_row = $(this).find(".sidebar-cart-card-meta li").length;

          $(this)
            .find(".sidebar-cart-card-meta li")
            .each(function () {
              let modifier_id = $(this).attr("data-id");
              let total_price = $(this).attr("data-total_price");
              let modifier_name = $(this).attr("data-name");
              let modifier_details = search_by_modifer_id(
                modifier_id,
                window.only_modifiers
              );

              let item_vat_m = [];
              let tax_information_tmp1 = JSON.parse(
                modifier_details[0].tax_information
              );
              if (tax_information_tmp1.length > 0) {
                for (let k in tax_information_tmp1) {
                  item_vat_m.push({
                    tax_field_id: 1,
                    tax_field_name: tax_information_tmp1[k].tax_field_name,
                    tax_field_percentage: parseFloat(
                      tax_information_tmp1[k].tax_field_percentage
                    ).toFixed(ir_precision),
                    item_vat_amount_for_unit_item: 0,
                    item_vat_amount_for_all_quantity: 0,
                  });
                }
              }

              if (iii == total_row) {
                modifiers_id += modifier_id;
                modifiers_name += modifier_name;
                modifiers_price += total_price;
                modifier_vat += item_vat_m;
              } else {
                modifiers_id += modifier_id + ",";
                modifiers_name += modifier_name + ",";
                modifiers_price += total_price + ",";
                modifier_vat += item_vat_m + "|||";
              }
              iii++;
            });
        
          modifier_vat = "";
          items_info +=
            ',"modifiers_id":"' +
            modifiers_id +
            '", "modifiers_name":"' +
            modifiers_name +
            '", "modifiers_price":"' +
            modifiers_price +
            '", "modifier_vat":' +
            JSON.stringify(modifier_vat);

          items_info += ',"item_note":""';
          items_info += ',"menu_combo_items":""';
          items_info += k == $(".sidebar-cart-card").length ? "}" : "},";
          k++;
        });
      }
      items_info += "]";
      order_info += items_info + "}";
     
      let payment_method = $(".payment_method:checked").val();
      $.ajax({
        url: base_url + "PaymentController/add_kitchen_sale_by_ajax",
        method: "POST",
        dataType: "json",
        data: {
          order: order_info,
          is_self_order: "Yes",
          payment_method: payment_method,
          close_order: 0,
        },
        success: function (data) {
          console.log("Order response:", data);
          
          if (data && data.status == true) {
            localStorage["cart_html_irp"] = "";
            localStorage["checkout_cart_html_irp"] = "";
            let order_id = data.order_id;
            localStorage["xxxx_zakir"] = order_id;
            
            if (payment_method == "cash_on_delivery") {
              callCashOnDeliveryPayment(order_id);
            } else if (payment_method == "paypal") {
              callPaypalPayment(total_amount, order_id);
            } else if (payment_method == "stripe") {
              callStripePayment(total_amount, order_id);
            } else if (payment_method == "razorpay") {
              callResorpayPayment(total_amount, order_id);
            }
          } else {
            console.error("Order failed:", data);
            let error_msg = (data && data.message) ? data.message : 'Order placement failed. Please try again.';
            toastr['error'](error_msg, 'Error');
            $(".pay_now").removeClass("no_access");
            if (data && data.status === false) {
              window.location.replace(base_url + "payment-fail");
            }
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.error("Response:", xhr.responseText);
          console.error("Request URL:", base_url + "PaymentController/add_kitchen_sale_by_ajax");
          toastr['error']('Order placement failed. Please check your connection and try again.', 'Error');
          $(".pay_now").removeClass("no_access");
        },
      });
    }
  });

  function callCashOnDeliveryPayment(order_id) {
    window.location.href = base_url + "order-success/" + order_id;
  }
  function callPaypalPayment(total_amount, order_id) {
    $("#total_payable").val(total_amount);
    $("#order_id_p").val(order_id);
    setTimeout(function () {
      $("#paypal_form").submit();
    }, 200);
  }
  function callStripePayment(total_amount, order_id) {
    $("#total_payable_str_custom").val(total_amount);
    $("#order_id_str").val(order_id);
    setTimeout(function () {
      $("#stripe_form").submit();
    }, 200);
  }
  function callResorpayPayment(total_amount, order_id) {
    let key_id_razorpay = $("#key_id_razorpay").val();
    let site_title = $("#site_title").val();
    let site_logo = $("#site_logo").val();
    total_amount = Number(total_amount);

    let options = {
      key: key_id_razorpay,
      amount: total_amount * 100, // 2000 paise = INR 20
      name: site_title,
      description: "Online Payment",
      image: site_logo,
      handler: function (response) {
        if (response.razorpay_payment_id) {
          $.ajax({
            url: base_url + "PaymentController/updateOrderSuccess",
            method: "POST",
            async: false,
            data: {
              razorpay_payment_id: response.razorpay_payment_id,
              last_order_id: order_id,
              total_amount: total_amount,
            },
            dataType: "json",
            success: function (data) {
              window.location.href = base_url + "order-success/"+order_id;
            },
          });
        } else {
          window.location.href = base_url + "payment-fail";
        }
      },
      theme: {
        color: "#7367f0",
      },
    };

    let rzp1 = new Razorpay(options);
    rzp1.open();
  }

  $('.popup-with-move-anim').magnificPopup({
      type: 'inline',
      fixedContentPos: false,
      fixedBgPos: true,
      overflowY: 'auto',
      closeBtnInside: true,
      preloader: true,
      midClick: true,
      removalDelay: 50,
      gallery: {
        enabled: true // Enables the image slider functionality
    },
      mainClass: 'my-mfp-slide-bottom'
});
})(jQuery);
