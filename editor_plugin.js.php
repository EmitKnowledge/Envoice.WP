<?php
require_once('../../../wp-load.php');
require_once('../../../wp-admin/includes/admin.php');
do_action('admin_init');

if (!is_user_logged_in())
    die('You must be logged in to access this script.');


// get products list form API
$api = new EnvoiceService(get_option('auth_key'), get_option('auth_secret'));
$data = $api->getProducts();

// decode to json
$data = json_decode($data['body']);

// generate array for tinymce acceptable
// format that passed as json array to js script below
$envoice_products = [];
foreach ($data->Result as $item) {
    $envoice_products[] = [
        'text' => $item->Name,
        'value' => $item->Id
    ];
}
?>

(function () {

    tinymce.PluginManager.add('EnvoiceSelector', function (editor) {
        editor.addButton('EnvoiceSelector', {
            type: 'listbox',
            text: 'Envoice Listbox',
            onselect: function (e) {
                editor.selection.setContent('[envoice product_id="' + this.value() + '"]');
            },
            values: <?php echo json_encode($envoice_products)?>
        });
    });

})();