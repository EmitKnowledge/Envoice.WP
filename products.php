<?php

/**
 * Products list
 *
 * Generate page with products and jsgrid
 * jsgrid initialized with php variable "products"
 * and then json_encode to use in jsgrid
 *
 * At first we request to Envoice API and update products list
 *
 */
function envoice_products_list_html()
{

    // call to envoice api and check result
    $api = new EnvoiceService(get_option('auth_key'), get_option('auth_secret'));

    // get products list
    $products = $api->getProductsListJson();

    ?>

    <div class="wrap">

    <h1>Products</h1>

    <div id="envoice-container">

<?php if (count($products) > 0) {?>
        <div class="envoice-content tools">

        <ul class="tools-links">
            <li class="copy_embed_code">
            <div class="livicon-evo livicon-evo-holder" data-options="name: globe; size: 30px; strokeColor: #cccccc; style: lines; eventType:hover; eventOn:parent; tryToSharpen: true" style="visibility: visible; width: 30px;"><div class="lievo-svg-wrapper"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve" data-shift="xy" data-animoptions="{'duration':'0.7', 'repeat':'3', 'repeatDelay':'0'}" preserveAspectRatio="xMinYMin meet" style="left: -0.25px; top: 0px;"><g class="lievo-setrotation"><g class="lievo-setsharp" data-svg-origin="31 30" style="transform-origin: 0px 0px 0px; transform: matrix(1, 0, 0, 1, -1, -1);"><g class="lievo-setflip"><g class="lievo-main">
		<g class="lievo-lineicon lievo-filledicon">
			<defs>
				<mask id="livicon_globe_9">
					<circle class="lievo-donotdraw lievo-nohovercolor lievo-nohoverstroke lievo-savefill" fill="#ffffff" cx="30" cy="30" r="23.9"></circle>
				</mask>
			</defs>
			<circle transform="rotate(-90, 30, 30)" stroke="#007bbd" stroke-width="0" fill="none" cx="30" cy="30" r="24" style=""></circle>
			<g mask="url(#livicon_globe_9)">
				<g>
					<line class="lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="8" y1="20" x2="52" y2="20" style=""></line>
					<line class="lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="6" y1="30" x2="56" y2="30" style=""></line>
					<line class="lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="8" y1="40" x2="52" y2="40" style=""></line>
				</g>
				<g>
					<path class="lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M29.95,55C21.85,50.08,16,40.17,16,30c0-10.17,5.85-20.08,13.95-25" style="" data-original="M30,55C16.19,55,5,43.81,5,30C5,16.19,16.19,5,30,5"></path>
					<path class="lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M30,55c0-7.68,0-14.41,0-25c0-10.59,0-16.24,0-25" style="" data-original="M29.95,55C21.85,50.08,16,40.17,16,30c0-10.17,5.85-20.08,13.95-25"></path>
					<path class="lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M30,55c8.1-4.92,14-14.83,14-25c0-10.17-5.9-20.08-14-25" style="" data-original="M30,55c0-7.68,0-14.41,0-25c0-10.59,0-16.24,0-25"></path>
					<path class="lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M30,5c13.81,0,25,11.19,25,25c0,13.81-11.19,25-25,25" style="" data-original="M30,55c8.1-4.92,14-14.83,14-25c0-10.17-5.9-20.08-14-25"></path>
				</g>
			</g>
			<circle class="lievo-altstroke" transform="rotate(-90, 30, 30)" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" cx="30" cy="30" r="24" style=""></circle>
		</g>


	<rect x="-20" y="-20" width="4" height="4" fill="none" stroke="#007bbd" style="stroke-width: 2; stroke-linecap: butt; stroke-linejoin: round; opacity: 0;" class="lievo-checkshift lievo-donotdraw lievo-nohoverstroke lievo-nohovercolor"></rect></g></g></g></g>

<desc>LivIcons Evolution</desc></svg>
</div>
                Embed code
            </li>
            <li class="copy_checkout_link">
            <div class="livicon-evo livicon-evo-holder" data-options="name: link; size: 30px; strokeColor: #cccccc; style: lines; eventType:hover; eventOn:parent; tryToSharpen: true" style="visibility: visible; width: 30px;"><div class="lievo-svg-wrapper"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve" data-animoptions="{'duration':'0.5', 'repeat':'1', 'repeatDelay':'0'}" preserveAspectRatio="xMinYMin meet" style="left: 0.25px; top: 0px;"><g class="lievo-setrotation"><g class="lievo-setsharp"><g class="lievo-setflip"><g class="lievo-main">
		<g class="lievo-filledicon lievo-lineicon">
			<path fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M30,23.44l-0.1-0.1c-1.17-1.17-1.17-3.07,0-4.24L39.1,9.9c1.17-1.17,3.07-1.17,4.24,0l6.78,6.78c1.17,1.17,1.17,3.07,0,4.24l-9.19,9.19c-1.17,1.17-3.07,1.17-4.24,0l-0.1-0.1" style="transform: matrix(1, 0, 0, 1, 0, 0);" data-svg-origin="29.022499084472656 9.022499084472656"></path>
			<path fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M30.02,36.56l0.1,0.1c1.17,1.17,1.17,3.07,0,4.24l-9.19,9.19c-1.17,1.17-3.07,1.17-4.24,0L9.9,43.31c-1.17-1.17-1.17-3.07,0-4.24l9.19-9.19c1.17-1.17,2.97-1.27,4.14-0.1l0.1,0.1" style="transform: matrix(1, 0, 0, 1, 0, 0);" data-svg-origin="9.022499084472656 28.95090103149414"></path>
			<line class="lievo-savelinecap lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="36.72" y1="23.28" x2="23.28" y2="36.72" style="transform: matrix(1, 0, 0, 1, 0, 0); transform-origin: 0px 0px 0px;" data-svg-origin="30 30"></line>
		</g>


	<rect x="-19" y="-19" width="4" height="4" fill="none" stroke="#007bbd" style="stroke-width: 2; stroke-linecap: butt; stroke-linejoin: round; opacity: 0;" class="lievo-checkshift lievo-donotdraw lievo-nohoverstroke lievo-nohovercolor"></rect></g></g></g></g>

<desc>LivIcons Evolution</desc><defs></defs></svg></div></div>
                Copy checkout link
            </li>
            <li class="configure_link">
            <div class="livicon-evo livicon-evo-holder" data-options="name: pencil; size: 30px; morphState:end; animated: false; strokeColor: #cccccc; style: lines; eventType:hover; eventOn:parent; tryToSharpen: true" style="visibility: visible; width: 30px;"><div class="lievo-svg-wrapper"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve" data-animoptions="{'duration':'2.5', 'repeat':'0', 'repeatDelay':'1'}" preserveAspectRatio="xMinYMin meet" style="left: 0.015625px; top: 0px;"><g class="lievo-setrotation"><g class="lievo-setsharp"><g class="lievo-setflip"><g class="lievo-main">
		<g class="lievo-common">
			<path class="lievo-donotdraw" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M7,51L30,8L53,51z" opacity="0" style=""></path>
		</g>

		<g class="lievo-lineicon">
			<line class="lievo-savelinecap" fill="none" stroke="#007bbd" stroke-width="2" stroke-miterlimit="10" x1="13.64" y1="42.11" x2="17.89" y2="46.36" style=""></line>
			<line class="lievo-savelinecap lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="42.28" y1="17.72" x2="21.78" y2="38.22" style=""></line>
			<line class="lievo-savelinecap lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-miterlimit="10" x1="38.39" y1="13.12" x2="46.88" y2="21.61" style=""></line>
			<path class="lievo-savelinecap lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-miterlimit="10" d="M21.78,38.22c-1.36,1.36-0.6,3.64,2.12,6.36" style=""></path>
			<path class="lievo-savelinecap lievo-altstroke" fill="none" stroke="#007bbd" stroke-width="2" stroke-miterlimit="10" d="M21.78,38.22c-1.36,1.36-3.64,0.6-6.36-2.12" style=""></path>
			<path fill="none" stroke="#007bbd" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M47.59,12.41l2.83,2.83c0.78,0.78,0.78,2.05,0,2.83L23.54,44.94l-11.31,2.83l2.83-11.31L41.93,9.59c0.78-0.78,2.05-0.78,2.83,0L47.59,12.41" style=""></path>
		</g>


	<rect x="-19" y="-19" width="4" height="4" fill="none" stroke="#007bbd" style="stroke-width: 2; stroke-linecap: butt; stroke-linejoin: round; opacity: 0;" class="lievo-checkshift lievo-donotdraw lievo-nohoverstroke lievo-nohovercolor"></rect></g></g></g></g>

<desc>LivIcons Evolution</desc><defs></defs></svg></div></div>
                Configure
            </li>
        </ul>

        </div>

        <div class="envoice-content">

            <div class="products-table">
                <div id="jsGrid"></div>
            </div>

        </div>

<?php } else {?>

<div class="envoice-content">

<p>
Hey! Your product list is empty.
</p>

<p class="mt-4">
    <a href="https://www.envoice.in/product/new" target="_blank" class="save-btn">Create a new product</a>
</p>

</div>


<?php }?>

    </div>

    <script>
        var products = <?php echo json_encode($products) ?>;
    </script>


    <?php
}