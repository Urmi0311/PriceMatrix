require(['jquery'], function($) {
  $(document).ready(function() {
    $(".product-options-wrapper .swatch-opt").click(function() {
      var selProId = $('[data-role=swatch-options]').data('mage-SwatchRenderer').getProduct();
console.log(selProId);
      $.ajax({
        url: '/sigma_pricematrix/index/pricematrixconfigurable',
        method: 'POST',
        data: {product_id: selProId},
        dataType: 'html',
        success: function(template) {
            renderTemplate(template);

        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    });
  });

  function renderTemplate(template) {
      var responseData = JSON.parse(template);
    var priceMatrix = responseData.priceMatrix;

      var html = '<div id="price_tiers_container">';

    if (Object.keys(priceMatrix).length === 0) {
      html += '<div class="no-price-data"></div>';
      $('.tocart.primary').hide();
    } else {
      html += '<div class="price-tiers">';
      html += '<table>';
      html += '<thead><tr><th>Quantity</th><th>Pricing</th></tr></thead>';
      html += '<tbody>';
      $('.tocart.primary').show();

      var priceTiers = [];
      for (var i = 1; i <= 10; i++) {
        if (priceMatrix['checkbox_' + i] == 1) {
          var basePrice = priceMatrix['display_base_price_' + i];
          var qty = priceMatrix['display_qty_' + i];

          if (basePrice && qty) {
            priceTiers.push({ qty: qty, price: basePrice });
          }
        }
      }

      if (priceTiers.length > 0) {
        for (var j = 0; j < priceTiers.length; j++) {
          var tier = priceTiers[j];
          var nextTier = priceTiers[j + 1];
          html += '<tr>';
          if (nextTier) {
            html += '<td>' + tier.qty + ' - ' + (nextTier.qty - 1) + ' pcs</td>';
          } else {
            html += '<td>' + tier.qty + '+ pcs</td>';
          }
          html += '<td>$' + tier.price + ' each</td>';
          html += '</tr>';
        }
      } else {
        html += '<tr><td colspan="2"></td></tr>';
      }

      html += '</tbody></table></div>';
    }

    html += '</div>';
    $('#price_tiers_container').html(html);
  }
});
