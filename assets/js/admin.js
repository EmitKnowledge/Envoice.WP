jQuery.notify.addStyle('envoice-success', {
    html: '<div><div class="howl"><div class="howl-slot howl-has-icon"><div class="howl-message howl-success"><button class="close howl-close">×</button><div class="howl-message-inner"><h5 class="howl-title">Success!</h5>Successfully copied to clipboard</div><i class="howl-icon far fa-check-square"></i></div></div></div></div>',
    classes: {
        success: {

        },
        error: {

        }
    }
});

jQuery(document).ready(function () {

    jQuery(document).on('click', '.howl-message .howl-close', function () {
        //programmatically trigger propogating hide event
        jQuery(this).trigger('notify-hide');
    });

    if (typeof products != 'undefined') {

        var gridFields = [{
                name: "Image",
                title: "Image",
                type: "text",
                width: "120px",
                filtering: false,
                sorting: false,
                itemTemplate: function (value, item) {
                    var url = 'https://www.envoice.in/productImage/image?productItemId=' + item.Id + '&size=104';
                    var $img = jQuery('<img style="max-width: 100px;max-height: 100px;display: block;margin: 0 auto;"/>');
                    jQuery($img).attr('src', url);
                    return $img;
                }
            },
            {
                name: "Name",
                title: "Name",
                type: "text",
                width: "150px",
                itemTemplate: function (value, item) {
                    return '<a href="https://www.evnoice.in/product/preview?id=' + item.Id + '" target="_blank">' + item.Name + '</a>';
                }
            },
            {
                name: "Total",
                title: "Total",
                type: "text",
                width: "100px",
                itemTemplate: function (value, item) {
                    return item.Total + ' ' + item.Currency;
                }
            },
            {
                name: "Status",
                title: "Status",
                type: "select",
                items: [{
                        value: 'Active'
                    },
                    {
                        value: 'Not available'
                    },
                    {
                        value: 'Inactive'
                    }
                ],
                valueField: "value",
                textField: "value",
                width: "120px",
                itemTemplate: function (value, item) {
                    return '<span class="label_status code_' + item.StatusCode + '">' + item.Status + '</span>';
                }
            }
        ];

        var db = {

            loadData: function (filter) {
                return jQuery.grep(products, function (product) {
                    return (!filter.Name || product.Name.indexOf(filter.Name) > -1) &&
                        (!filter.Total || product.Total.indexOf(filter.Total) > -1) &&
                        (!filter.Status || product.Status === filter.Status);
                });
            },

            insertItem: function (insertingClient) {},

            updateItem: function (updatingClient) {},

            deleteItem: function (deletingClient) {}

        };

        window.db = db;
        var selectedItem = {};

        jQuery("#jsGrid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            pageLoading: false,
            paging: true,
            autoload: true,
            data: products,
            rowClick: function (args) {
                // clear all styles and set for clicked row active class
                jQuery('.jsgrid-table tr').removeClass('active');
                jQuery(args.event.target).parent().addClass('active');
                // set selected product
                selectedItem = args.item;
                // enable tools
                jQuery('.tools-links').addClass('enabled');
            },
            pageSize: 5,
            pageIndex: 1,
            pageButtonCount: 4,
            pagerFormat: "{prev} {pages} {next}",
            pagePrevText: '<i class="fas fa-arrow-circle-left"></i>',
            pageNextText: '<i class="fas fa-arrow-circle-right"></i>',
            controller: db,
            fields: gridFields,
            onDataLoaded: function (args) {
                jQuery('.jsgrid-cell input').attr("placeholder", "Search...");
            }
        });

        /**
         * Tools actions
         */

        // copy checkout link
        new ClipboardJS('.copy_checkout_link', {
            text: function (trigger) {
                return 'https://www.envoice.in/secure/checkout?token=' + selectedItem.Token + '';
            }
        }).on('success', function (e) {
            jQuery.notify("Checkout link copied to clipboard", {
                position: "right bottom",
                style: 'envoice-success'
            });
        });

        // copy embed code
        new ClipboardJS('.copy_embed_code', {
            text: function (trigger) {
                return '[envoice product_id="' + selectedItem.Id + '"]';
            }
        }).on('success', function (e) {
            jQuery.notify("Embed code copied to clipboard", {
                position: "right bottom",
                style: 'envoice-success'
            });
        });;

        // redirect to envoice website for product editing
        jQuery('.configure_link').click(function () {
            window.open(
                'https://www.envoice.in/product/id/' + selectedItem.Id,
                '_blank'
            );
        });
    }

});