require(['jquery'], function($) {
  $(document).ready(function() {
    $(".product-item-info").on("click", function() {
      var selProId = $('[data-role^="swatch-option-"]').data('mage-SwatchRenderer').getProduct();

      $.ajax({
        url: '/sigma_pricematrix/index/getlowestprice',
        method: 'POST',
        data: {product_id: selProId},
        dataType: 'json',
        success: function(response) {
          if (response.success && response.lowest_price !== null && typeof response.lowest_price === 'string') {
            var lowestPriceNumber = parseFloat(response.lowest_price);

            if (!isNaN(lowestPriceNumber)) {
              $('#lowest_price_container').html('<div class="price-box"><span class="price">Lowest Price: $' + lowestPriceNumber.toFixed(2) + '</span></div>');
              $('.tocart.primary').show();
            } else {
              $('#lowest_price_container').html('<div class="no-price-data">No lowest price available</div>');
              $('.tocart.primary').hide();
            }
          } else {
            $('#lowest_price_container').html('');
            $('.tocart.primary').hide();
          }
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });
  });
});
