<?php

/*
Plugin Name: Envoice
Plugin URI:
Description: Use Envoice Buy Button on Wordpress platfrom with simple shortcode
Version: 0.4.5
Author: Aleksey Developer
Author URI: https://aleksey.co
 */

define('ENVOICE_PLUGIN_VERSION', '0.4.5');

// include Envoice API class helper
include plugin_dir_path(__FILE__) . 'EnvoiceService.php';

// products list page
include plugin_dir_path(__FILE__) . 'products.php';

/* Check if plugin settings not empty*/
function isEnvoiceSettingsSet()
{
    $api_key = get_option('auth_key');
    $api_secret = get_option('auth_secret');

    return $api_key && $api_secret;
}

/* Add envoice section to admin menu */
function envoice_section_page()
{
    // add top level menu page Envoice
    add_menu_page(
        'Envoice',
        'Envoice',
        'manage_options',
        'envoice',
        'envoice_options_page_html',
        plugin_dir_url(__FILE__) . 'assets/images/icon.png'
    );

    /* If plugin settings is set, add products list page*/
    if (isEnvoiceSettingsSet()) {

        // add submenu with Products
        add_submenu_page(
            'envoice',
            'Products list',
            'Products',
            'manage_options',
            'envoice-products',
            'envoice_products_list_html'
        );

    }

}

/* Generate Settings page*/
function envoice_options_page_html()
{

    // check if the user have submitted the settings
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        echo '<div class="updated">
        <p>Settings were saved!</p>
        </div>';
    }

    ?>

    <div class="wrap">
        <h1>Settings</h1>

        <div id="envoice-container">

            <div class="envoice-content">
                <div class="header">
                    <i class="fas fa-lock"></i>
                    <h2>Plugin settings</h2>
                    <p>Settings to integrate with your existing Envoice account</p>
                </div>
                <div class="col-row">
                <div class="col-left">
                <div class="caption">
                    <span class="step">1</span>
                    <h3>Connect your Envoice account</h3>
                    <p>Providing your auth key and secret will allow the plugin to connect
                        to your Envoice account</p>
                </div>
                <p>
                    To get started configure your account details. You can find your API KEY
                    and
                    SECRET KEY on this <a href="https://www.envoice.in/account/settings#api-tab"
                                          target="_blank">link.</a>
                </p>

                <form action="options.php" method="post">

                    <?php
settings_fields('envoice_fields');
    ?>

                    <div class="form-input">
                        <input class="text-input"
                               type="text" placeholder=""
                               id="auth_key" name="auth_key"
                               value="<?=get_option('auth_key')?>">
                        <label>API KEY</label>
                        <p class="info-text">
                            <i class="fas fa-info-circle orange-clr"></i>
                            API key is a public unique identifier for your app.
                        </p>
                    </div>

                    <div class="form-input">
                        <input class="text-input"
                               type="text" placeholder=""
                               id="auth_secret" name="auth_secret"
                               value="<?=get_option('auth_secret')?>">
                        <label>SECRET KEY</label>
                        <p class="info-text">
                            <i class="fas fa-info-circle orange-clr"></i>
                            Secret key is a secret shared between Envoice and your app.
                        </p>
                    </div>

                    <button type="submit" class="save-btn">Save</button>
                </form>

                </div>
                <div class="col-right">
                <div class="video-wrap">
                <iframe width="100%" height="auto" src="https://www.youtube.com/embed/7AOtgu3_3_8?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}

// hook for Envoice menu
add_action('admin_menu', 'envoice_section_page');

// register settings in Wordpress Settings API
function setup_fields()
{

    // settings that should be stored via API
    $fields = array(
        array(
            'uid' => 'auth_key',
            'label' => 'Auth key',
            'section' => 'api_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => 'API KEY',
            'helper' => '',
        ),
        array(
            'uid' => 'auth_secret',
            'label' => 'Auth secret',
            'section' => 'api_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => 'SECRET KEY',
            'helper' => '',
        ),
    );

    // go through each on and register it
    foreach ($fields as $field) {
        add_settings_field($field['uid'], $field['label'], null, 'envoice_fields', $field['section'], $field);
        register_setting('envoice_fields', $field['uid']);
    }

}

// register Envoice options section
add_action('admin_init', 'setup_fields');

// hook to load scripts and styles
add_action('admin_enqueue_scripts', 'load_assets');

// register assetsfor plugin
function load_assets($hook)
{

    // load assets only on plugin page
    if ($hook != 'toplevel_page_envoice' && $hook != 'envoice_page_envoice-products') {
        return;
    }

    // include jsgrid script
    wp_register_script(
        'envoice-admin-jsgrid',
        //'https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js',
        plugins_url('/assets/js/jsgrid.min.js', plugin_basename(__FILE__)),
        array('jquery'),
        ENVOICE_PLUGIN_VERSION
    );
    wp_enqueue_script('envoice-admin-jsgrid');

    // clipboard js
    wp_register_script(
        'envoice-admin-clipboard',
        plugins_url('/assets/js/clipboard.min.js', plugin_basename(__FILE__)),
        array('jquery'),
        ENVOICE_PLUGIN_VERSION
    );
    wp_enqueue_script('envoice-admin-clipboard');

    // notifyjs
    wp_register_script(
        'envoice-admin-notify',
        plugins_url('/assets/js/notify.min.js', plugin_basename(__FILE__)),
        array('jquery'),
        ENVOICE_PLUGIN_VERSION
    );
    wp_enqueue_script('envoice-admin-notify');

    // admin scripts
    wp_register_script(
        'envoice-admin',
        plugins_url('/assets/js/admin.js', plugin_basename(__FILE__)),
        array('jquery'),
        ENVOICE_PLUGIN_VERSION
    );
    wp_enqueue_script('envoice-admin');

    // load styles
    wp_enqueue_style(
        'envoice-admin',
        plugins_url('/assets/css/admin.css', plugin_basename(__FILE__)),
        array(),
        //        ENVOICE_PLUGIN_VERSION
        time()
    );

    // include font awesome
    wp_enqueue_style(
        'envoice-admin-fonts',
        'https://use.fontawesome.com/releases/v5.3.1/css/all.css',
        array(),
        ENVOICE_PLUGIN_VERSION
    );

    // jsgrid styles
    wp_enqueue_style(
        'envoice-admin-jsgrid',
        //'https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css',
        plugins_url('/assets/css/jsgrid.css', plugin_basename(__FILE__)),
        array(),
        ENVOICE_PLUGIN_VERSION
    );

}

// helper, if plugin activated and API settings sets,
// add new button to TinyMCE to insert Shorcode with Product ID
if (isEnvoiceSettingsSet()) {
    add_action('init', 'add_button');
}

/**
 * Register a hook and create new table to store id-token pairsof products
 * such as we use product Id in shortcodes, we have to know what is that
 * and generate a correct URL for checkout page that work with product Token
 *
 * @todo: we can remove using database if checkout link will have Id instead
 */

register_activation_hook(__FILE__, 'install_envoice');

/**
 * Create a table to store Id - AccessToken data
 *
 * @return void
 */
function install_envoice()
{

    global $wpdb;

    $table_name = $wpdb->prefix . 'envoice_products';
    $charset_collate = $wpdb->get_charset_collate();

    // database schema uid is Product Id
    $sql = "CREATE TABLE $table_name (
		uid mediumint(9) NOT NULL,
		name tinytext NOT NULL,
		token text NOT NULL,
		PRIMARY KEY  (uid)
	) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

}

/**
 * Add shortcode support
 * example: [envoice product_id=123]
 * will be replaced with <a href="[CHECKOUT_URL_WITH_PRODUCT_TOKEN]">Item name - Buy now</a>
 */
add_shortcode('envoice', 'envoice_shortcode');

/**
 * Generate a special link for checkout
 *
 * @param mixed $atts Attributes from shortcode
 *
 * @return String $btn
 */
function envoice_shortcode($atts)
{

    // get token by id from database
    global $wpdb;
    $uid = $atts['product_id'];
    $item = $wpdb->get_row('select * from ' . $wpdb->prefix . 'envoice_products where uid=' . $uid);

    // generate a button view
    $btn = '<a href="https://www.envoice.in/secure/checkout?token=' . $item->token . '" class="envoice_btn"
        style="width:300px; height:100px; background-color:#007bbd; padding:10px 40px; color: #fff; border-radius: 3px; text-decoration: none;">
        ' . $item->name . ' - Buy Now
        </a>';

    return $btn;
}

/**
 * Add select2 support for shortcode insterting
 */
function enqueue_select2_jquery()
{
    wp_register_style('select2css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', false, ENVOICE_PLUGIN_VERSION);
    wp_register_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array('jquery'), ENVOICE_PLUGIN_VERSION);
    wp_enqueue_style('select2css');
    wp_enqueue_script('select2');
}

// initi the scripts
add_action('admin_enqueue_scripts', 'enqueue_select2_jquery');

function select2jquery()
{
    ?>
<script type='text/javascript'>

        jQuery(document).ready(function ($) {

        // init  select2 for media button
        $( '#envoiceSelector' ).select2({
            placeholder: 'Insert Envoice Product',
        });

        // manage on select event
        $('#envoiceSelector').on('select2:select', function (e) {
                var shortcode = '[envoice product_id="' + e.params.data.id + '"]';
                // insert shorcode to tinyMCE
                tinymce.editors.content.selection.setContent(shortcode);
                // clear selected item
                $('#envoiceSelector').val(null).trigger('change');
    });
});
</script>
    <?php
}

add_action('admin_head', 'select2jquery');

// add media button
add_action('media_buttons', 'add_envoice_media_button');
function add_envoice_media_button()
{

    $api = new EnvoiceService(get_option('auth_key'), get_option('auth_secret'));
    $products = $api->getProductsListJson();

    $html = '<select id="envoiceSelector">';
    $html .= '<option></option>';

    foreach ($products as $p) {
        $html .= '<option value="' . $p['Id'] . '">' . $p['Name'] . '</option>';
    }

    $html .= '</select>';

    echo $html;

}