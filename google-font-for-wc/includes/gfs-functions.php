<?php
function gfs_admin_styling() {
  wp_enqueue_style( 'gfs-wc-style',  plugin_dir_url( __FILE__ ) . '/css/gfs-wc-style.css' );                      
}
add_action( 'admin_enqueue_scripts', 'gfs_admin_styling' );

function gfs_frontend_styling() {
  wp_enqueue_style( 'gfs-field-styling', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css' ); 
  wp_enqueue_style( 'gfs-wc-style',  plugin_dir_url( __FILE__ ) . '/css/gfs-wc-frontend-style.css' );                     
}
add_action( 'wp_enqueue_scripts', 'gfs_frontend_styling' );

function gfs_scripts() {
  wp_enqueue_script( 'gfs-wc-fonts', plugin_dir_url( __FILE__ ) . '/fontselector/js/fonts.js', array('jquery'), '1.0', true );
  wp_enqueue_script( 'gfs-wc-script', plugin_dir_url( __FILE__ ) . '/fontselector/js/init.js', array('jquery'), '1.0', true );
  // wp_enqueue_script( 'gfs-wc-jquery', 'https://code.jquery.com/jquery-1.12.0.min.js', array('jquery'), '1.0', true );
  wp_enqueue_script( 'gfs-wc-select', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js', array('jquery'), '1.0', true );
  wp_enqueue_script( 'gfs-wc-webfont', 'https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js', array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'gfs_scripts' );

/* these code is not needed so you can remove them
function footer_script(){
echo '<script type="text/javascript">
  // Apply selected font to the select field
  $(document).ready(function($) {
      $(\'#select_fontfamily\').on(\'change\', function() {
          var selectedFont = $(this).val();
          $(this).css(\'font-family\', selectedFont);
      });
  });

  $(document).ready(function() {
      $( "#select_fontfamily" ).higooglefonts({         
        selectedCallback:function(e){
          console.log(e);
        },
        loadedCallback:function(font){
          console.log(font);
          $("#ribbon-text").css("font-family", font); 
          }         
      }); 
  });
  </script>';
}
//add_action('wp_footer', 'footer_script'); 

function add_styling() {
  echo ' <style>
   input#ribbon-text {
      font-optical-sizing: auto;
      font-style: normal;
      font-family: inherit;
      background: #f6f5f8;
      color: #4f3085;
    }
    </style>';
}
//add_action('wp_head', 'add_styling');

function my_custom_js() {
  echo '<script type="text/javascript">
  // Apply selected font to the select field
  $(document).ready(function($) {
    $(\'#select_fontfamily\').on(\'change\', function() {
        var selectedFont = $(this).val();
        $(this).css(\'font-family\', selectedFont);
    });
  });
  </script>';
}
//add_action( 'wp_head', 'my_custom_js' );
*/

function gfs_add_menu() {
  add_menu_page(
      __( 'Google Fonts Selector for WooCommerce', 'gfs-wc' ),
      __( 'GFS for WC', 'gfs-wc' ),
      'manage_options',
      'gfs-admin',
      'gfs_admin_content',
      'dashicons-schedule'
  );
}
add_action( 'admin_menu', 'gfs_add_menu' );

function gfs_admin_content() {
  ?>
  <div class="wrap">
    <h1>
      <?php esc_html_e( 'Google Fonts Selector for WooCommerce', 'gfs-wc' ); ?>
    </h1>
    <p>Test</p>
  </div>
  <?php
}

function gfs_populate_google_fonts() {
    $google_fonts_api = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=AIzaSyDR77DTniM2VL0CJznoIn5RQxQlntbExyE';
    $response = wp_remote_get($google_fonts_api);

    if (is_wp_error($response)) {
        return '';
    }

    $body = wp_remote_retrieve_body($response);
    $fonts_data = json_decode($body);

    if (!$fonts_data || !isset($fonts_data->items)) {
        return '';
    }

    $google_fonts = $fonts_data->items;

    // Fonts to exclude as it's material icons fonts
    $exclude_fonts = [
        'Material Icons',
        'Material Icons Outlined',
        'Material Icons Round',
        'Material Icons Sharp',
        'Material Icons Two Tone'
    ];

    // Start building the select field with a class for styling
    $select_field = '<div class="custom-select-wrapper">';
    $select_field .= '<select id="select_fontfamily" name="select_fontfamily" class="custom-select-field" style="font-family:Figtree;">';

    foreach ($google_fonts as $font) {
        $font_family = esc_attr($font->family);

        // Skip excluded fonts
        if (in_array($font_family, $exclude_fonts)) {
            continue;
        }

        $font_family_sanitized = str_replace(' ', '+', $font_family); // Google Fonts API format
        $font_link = "https://fonts.googleapis.com/css?family={$font_family_sanitized}";

        // Creating an option for each font
        $select_field .= '<option class="font-option" style="font-family: ' . $font_family . ';" value="' . $font_family_sanitized . '" data-src="' . $font_link . '">' . $font_family . '</option>';
    }

    $select_field .= '</select>';
    $select_field .= '</div>';

    // Return the final select field
    return $select_field;
}



function gfs_add_google_fonts_select_field() {
  $google_fonts_select_field = gfs_populate_google_fonts();

  if ($google_fonts_select_field) {
      echo $google_fonts_select_field;
  }
}
//add_action('woocommerce_before_add_to_cart_button', 'gfs_add_google_fonts_select_field');

function gfs_add_custom_field_to_cart_item($cart_item_data, $product_id) {
  if (isset($_POST['select_fontfamily'])) {
      $cart_item_data['google_fonts'] = sanitize_text_field($_POST['select_fontfamily']);
  }
  return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'gfs_add_custom_field_to_cart_item', 10, 2);

function gfs_display_font_custom_field_in_cart($item_data, $cart_item) {
  if (isset($cart_item['google_fonts'])) {
      $item_data[] = [
          'key'     => 'Lettertype',
          'value'   => wc_clean($cart_item['google_fonts']),
          'display' => '',
      ];
  }
  return $item_data;
}
add_filter('woocommerce_get_item_data', 'gfs_display_font_custom_field_in_cart', 10, 2);

function gfs_display_custom_field_in_admin_order($item_id, $item, $product) {
  if ($item->get_meta('Google Font')) {
      echo '<p><strong>' . esc_html__('Google Font') . ':</strong> ' . esc_html($item->get_meta('Google Font')) . '</p>';
  }
}
add_action('woocommerce_after_order_itemmeta', 'gfs_display_custom_field_in_admin_order', 10, 3);

function gfs_add_custom_field_to_cart( $cart_item_data, $product_id, $variation_id ) {
  if( isset($_POST['gfs_content']) ) {
      $cart_item_data['gfs_content'] = sanitize_text_field( $_POST['gfs_content'] );
  }
  return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'gfs_add_custom_field_to_cart', 10, 3 );

function gfs_display_custom_field_in_cart( $item_data, $cart_item ) {
  if ( isset( $cart_item['gfs_content'] ) ) {
      $item_data[] = array(
          'key'     => __( 'Jouw tekst', 'gfs-wc' ),
          'value'   => wc_clean( $cart_item['gfs_content'] ),
          'display' => '',
      );
  }
  return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'gfs_display_custom_field_in_cart', 10, 2 );

function gfs_save_custom_field_in_order( $item_id, $values, $cart_item_key ) {
  if ( isset( $values['gfs_content'] ) ) {
      wc_add_order_item_meta( $item_id, '_gfs_content', $values['gfs_content'] );
  }
}
add_action( 'woocommerce_add_order_item_meta', 'gfs_save_custom_field_in_order', 10, 3 );

function gfs_display_custom_field_in_admin_order_meta( $item_id, $item, $product ) {
  if ( $meta_value = wc_get_order_item_meta( $item_id, '_gfs_content', true ) ) {
      echo '<p><strong>' . __( 'Tekst op het lint', 'gfs-wc' ) . ':</strong> ' . $meta_value . '</p>';
  }
}
add_action( 'woocommerce_before_order_itemmeta', 'gfs_display_custom_field_in_admin_order_meta', 10, 3 );


// Add the input field for the ribbon text
function gfs_ribbontext_custom_product_field() {
  echo '<input type="text" id="ribbon-text" name="ribbon_text" placeholder="Jouw tekst.." />';
}
//add_action( 'woocommerce_before_add_to_cart_button', 'gfs_ribbontext_custom_product_field' ); 

// Save the custom field value to the cart item data
function gfs_ribbontext_add_custom_field_to_cart_item($cart_item_data, $product_id) {
  if (isset($_POST['ribbon_text'])) {
      $cart_item_data['ribbon_text'] = sanitize_text_field($_POST['ribbon_text']);
  }
  return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'gfs_ribbontext_add_custom_field_to_cart_item', 10, 2);

// Display the custom field value in the cart
function gfs_ribbontext_display_custom_field_in_cart($item_data, $cart_item) {
  if (isset($cart_item['ribbon_text'])) {
      $item_data[] = array(
          'key'     => __( 'Ribbon Text', 'gfs-wc' ),
          'value'   => wc_clean($cart_item['ribbon_text']),
          'display' => '',
      );
  }
  return $item_data;
}
add_filter('woocommerce_get_item_data', 'gfs_ribbontext_display_custom_field_in_cart', 10, 2);

// Save the custom field value to the order meta
function gfs_ribbontext_save_custom_field_in_order($item_id, $values, $cart_item_key) {
  if (isset($values['ribbon_text'])) {
      wc_add_order_item_meta($item_id, '_ribbon_text', $values['ribbon_text']);
  }
}
add_action('woocommerce_add_order_item_meta', 'gfs_ribbontext_save_custom_field_in_order', 10, 3);

// Display the custom field value in the admin order meta
function gfs_ribbontext_display_custom_field_in_admin_order_meta($item_id, $item, $product) {
  if ($meta_value = wc_get_order_item_meta($item_id, '_ribbon_text', true)) {
      echo '<p><strong>' . __( 'Ribbon Text', 'gfs-wc' ) . ':</strong> ' . esc_html($meta_value) . '</p>';
  }
}
add_action('woocommerce_before_order_itemmeta', 'gfs_ribbontext_display_custom_field_in_admin_order_meta', 10, 3);