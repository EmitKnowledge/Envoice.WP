jQuery(document).ready(function () {

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
                items: [],
                valueField: "value",
                textField: "name",
                width: "120px"
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

        jQuery("#jsGrid").jsGrid({
            width: "100%",
            filtering: true,
            editing: false,
            sorting: true,
            pageLoading: false,
            paging: true,
            autoload: true,
            data: products,
            //rowClick: onGridRowClick,
            pageSize: 10,
            pageIndex: 1,
            pageButtonCount: 5,
            pagePrevText: '<i class="fa fa-arrow-circle-o-left"></i>',
            pageNextText: '<i class="fa fa-arrow-circle-o-right"></i>',
            controller: db,
            fields: gridFields
        });

        //jQuery("#jsGrid").jsGrid("fieldOption", "Id", "visible", false);

        // jQuery("#jsGrid").jsGrid({
        //     width: "100%",
        //     height: "450px",
        //     inserting: false,
        //     editing: false,
        //     sorting: true,
        //     paging: true,
        //     filtering: true,

        //     // load products form variable from products.php
        //     data: products,

        //     // set header
        //     fields: [
        //         {name: "Name", type: "text", width: 150, validate: "required"},
        //         {name: "Total", type: "text", width: 150, validate: "required"},
        //         {name: "Status", type: "select", width: 150, validate: "required"},

        //     ]
        // });

    }

});