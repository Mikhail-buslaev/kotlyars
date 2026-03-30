<?php

$_['kotlyars_catalog_product_types'] = array(
	'fixed_price' => array(
		'code'              => 'fixed_price',
		'label'             => 'Fixed Price',
		'cart_available'    => true,
		'checkout_available'=> true
	),
	'auction' => array(
		'code'              => 'auction',
		'label'             => 'Auction',
		'cart_available'    => false,
		'checkout_available'=> false
	)
);

$_['kotlyars_catalog_category_registry'] = array(
	'diamonds.natural' => array(
		'code'                  => 'diamonds.natural',
		'domain'                => 'diamonds',
		'domain_label'          => 'Diamonds',
		'label'                 => 'Natural Diamonds',
		'path'                  => array('Diamonds', 'Natural'),
		'expected_category_path'=> array('Diamonds', 'Natural'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'diamonds.coloured' => array(
		'code'                  => 'diamonds.coloured',
		'domain'                => 'diamonds',
		'domain_label'          => 'Diamonds',
		'label'                 => 'Coloured Diamonds',
		'path'                  => array('Diamonds', 'Coloured'),
		'expected_category_path'=> array('Diamonds', 'Coloured'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'gemstones.ruby' => array(
		'code'                  => 'gemstones.ruby',
		'domain'                => 'gemstones',
		'domain_label'          => 'Gemstones',
		'label'                 => 'Ruby',
		'path'                  => array('Gemstones', 'Ruby'),
		'expected_category_path'=> array('Gemstones', 'Ruby'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'gemstones.sapphire' => array(
		'code'                  => 'gemstones.sapphire',
		'domain'                => 'gemstones',
		'domain_label'          => 'Gemstones',
		'label'                 => 'Sapphire',
		'path'                  => array('Gemstones', 'Sapphire'),
		'expected_category_path'=> array('Gemstones', 'Sapphire'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'gemstones.emerald' => array(
		'code'                  => 'gemstones.emerald',
		'domain'                => 'gemstones',
		'domain_label'          => 'Gemstones',
		'label'                 => 'Emerald',
		'path'                  => array('Gemstones', 'Emerald'),
		'expected_category_path'=> array('Gemstones', 'Emerald'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.gold.gold_by_weight' => array(
		'code'                  => 'precious_metals.gold.gold_by_weight',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Gold by Weight',
		'path'                  => array('Precious Metals', 'Gold', 'Gold by Weight'),
		'expected_category_path'=> array('Precious Metals', 'Gold', 'Gold by Weight'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.gold.gold_bars_rounds_by_brand' => array(
		'code'                  => 'precious_metals.gold.gold_bars_rounds_by_brand',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Gold Bars / Rounds by Brand',
		'path'                  => array('Precious Metals', 'Gold', 'Gold Bars / Rounds by Brand'),
		'expected_category_path'=> array('Precious Metals', 'Gold', 'Gold Bars / Rounds by Brand'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.gold.industrial_gold' => array(
		'code'                  => 'precious_metals.gold.industrial_gold',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Industrial Gold',
		'path'                  => array('Precious Metals', 'Gold', 'Industrial Gold'),
		'expected_category_path'=> array('Precious Metals', 'Gold', 'Industrial Gold'),
		'supports_product_types'=> array('fixed_price')
	),
	'precious_metals.gold.vintage_gold_bars_rounds' => array(
		'code'                  => 'precious_metals.gold.vintage_gold_bars_rounds',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Vintage Gold Bars / Rounds',
		'path'                  => array('Precious Metals', 'Gold', 'Vintage Gold Bars / Rounds'),
		'expected_category_path'=> array('Precious Metals', 'Gold', 'Vintage Gold Bars / Rounds'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.silver.silver_bars' => array(
		'code'                  => 'precious_metals.silver.silver_bars',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Silver Bars',
		'path'                  => array('Precious Metals', 'Silver', 'Silver Bars'),
		'expected_category_path'=> array('Precious Metals', 'Silver', 'Silver Bars'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.silver.industrial_silver' => array(
		'code'                  => 'precious_metals.silver.industrial_silver',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Industrial Silver',
		'path'                  => array('Precious Metals', 'Silver', 'Industrial Silver'),
		'expected_category_path'=> array('Precious Metals', 'Silver', 'Industrial Silver'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.silver.silver_rounds' => array(
		'code'                  => 'precious_metals.silver.silver_rounds',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Silver Rounds',
		'path'                  => array('Precious Metals', 'Silver', 'Silver Rounds'),
		'expected_category_path'=> array('Precious Metals', 'Silver', 'Silver Rounds'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.platinum.platinum_bars_rounds' => array(
		'code'                  => 'precious_metals.platinum.platinum_bars_rounds',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Platinum Bars / Rounds',
		'path'                  => array('Precious Metals', 'Platinum', 'Platinum Bars / Rounds'),
		'expected_category_path'=> array('Precious Metals', 'Platinum', 'Platinum Bars / Rounds'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'precious_metals.platinum.industrial_platinum' => array(
		'code'                  => 'precious_metals.platinum.industrial_platinum',
		'domain'                => 'precious_metals',
		'domain_label'          => 'Precious Metals',
		'label'                 => 'Industrial Platinum',
		'path'                  => array('Precious Metals', 'Platinum', 'Industrial Platinum'),
		'expected_category_path'=> array('Precious Metals', 'Platinum', 'Industrial Platinum'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'fine_art.drawings_pastels' => array(
		'code'                  => 'fine_art.drawings_pastels',
		'domain'                => 'fine_art',
		'domain_label'          => 'Fine Art',
		'label'                 => 'Drawings / Pastels',
		'path'                  => array('Fine Art', 'Drawings / Pastels'),
		'expected_category_path'=> array('Fine Art', 'Drawings / Pastels'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'fine_art.paintings_mixed_media' => array(
		'code'                  => 'fine_art.paintings_mixed_media',
		'domain'                => 'fine_art',
		'domain_label'          => 'Fine Art',
		'label'                 => 'Paintings / Mixed Media',
		'path'                  => array('Fine Art', 'Paintings / Mixed Media'),
		'expected_category_path'=> array('Fine Art', 'Paintings / Mixed Media'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'fine_art.fine_art_photography' => array(
		'code'                  => 'fine_art.fine_art_photography',
		'domain'                => 'fine_art',
		'domain_label'          => 'Fine Art',
		'label'                 => 'Fine Art Photography',
		'path'                  => array('Fine Art', 'Fine Art Photography'),
		'expected_category_path'=> array('Fine Art', 'Fine Art Photography'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'fine_art.prints_multiples' => array(
		'code'                  => 'fine_art.prints_multiples',
		'domain'                => 'fine_art',
		'domain_label'          => 'Fine Art',
		'label'                 => 'Prints / Multiples',
		'path'                  => array('Fine Art', 'Prints / Multiples'),
		'expected_category_path'=> array('Fine Art', 'Prints / Multiples'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'fine_art.sculptures' => array(
		'code'                  => 'fine_art.sculptures',
		'domain'                => 'fine_art',
		'domain_label'          => 'Fine Art',
		'label'                 => 'Sculptures',
		'path'                  => array('Fine Art', 'Sculptures'),
		'expected_category_path'=> array('Fine Art', 'Sculptures'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'fine_art.watercolour_paintings' => array(
		'code'                  => 'fine_art.watercolour_paintings',
		'domain'                => 'fine_art',
		'domain_label'          => 'Fine Art',
		'label'                 => 'Watercolour Paintings',
		'path'                  => array('Fine Art', 'Watercolour Paintings'),
		'expected_category_path'=> array('Fine Art', 'Watercolour Paintings'),
		'supports_product_types'=> array('fixed_price', 'auction')
	),
	'jewellery.general' => array(
		'code'                  => 'jewellery.general',
		'domain'                => 'jewellery',
		'domain_label'          => 'Jewellery',
		'label'                 => 'Jewellery',
		'path'                  => array('Jewellery'),
		'expected_category_path'=> array('Jewellery'),
		'supports_product_types'=> array('fixed_price', 'auction')
	)
);

$_['kotlyars_catalog_attribute_definitions'] = array(
	'shape' => array(
		'code'         => 'shape',
		'label'        => 'Shape',
		'input_type'   => 'select',
		'storage'      => 'extension',
		'filterable'   => true,
		'options'      => array('round' => 'Round', 'oval' => 'Oval', 'cushion' => 'Cushion', 'emerald' => 'Emerald', 'pear' => 'Pear', 'princess' => 'Princess', 'marquise' => 'Marquise', 'heart' => 'Heart', 'radiant' => 'Radiant', 'asscher' => 'Asscher')
	),
	'carat' => array(
		'code'         => 'carat',
		'label'        => 'Carat',
		'input_type'   => 'decimal',
		'storage'      => 'extension',
		'filterable'   => true
	),
	'colour' => array(
		'code'         => 'colour',
		'label'        => 'Colour',
		'input_type'   => 'select',
		'storage'      => 'extension',
		'filterable'   => true,
		'options'      => array('d' => 'D', 'e' => 'E', 'f' => 'F', 'g' => 'G', 'h' => 'H', 'i' => 'I', 'j' => 'J', 'k' => 'K', 'fancy_yellow' => 'Fancy Yellow', 'fancy_pink' => 'Fancy Pink', 'fancy_blue' => 'Fancy Blue', 'fancy_green' => 'Fancy Green', 'other' => 'Other')
	),
	'clarity' => array(
		'code'         => 'clarity',
		'label'        => 'Clarity',
		'input_type'   => 'select',
		'storage'      => 'extension',
		'filterable'   => true,
		'options'      => array('if' => 'IF', 'vvs1' => 'VVS1', 'vvs2' => 'VVS2', 'vs1' => 'VS1', 'vs2' => 'VS2', 'si1' => 'SI1', 'si2' => 'SI2', 'i1' => 'I1', 'other' => 'Other')
	),
	'cut' => array(
		'code'         => 'cut',
		'label'        => 'Cut',
		'input_type'   => 'select',
		'storage'      => 'extension',
		'filterable'   => true,
		'options'      => array('ideal' => 'Ideal', 'excellent' => 'Excellent', 'very_good' => 'Very Good', 'good' => 'Good', 'fair' => 'Fair')
	),
	'certificate' => array(
		'code'         => 'certificate',
		'label'        => 'Certificate',
		'input_type'   => 'select',
		'storage'      => 'extension',
		'filterable'   => true,
		'options'      => array('gia' => 'GIA', 'igi' => 'IGI', 'hrd' => 'HRD', 'ssef' => 'SSEF', 'gubelin' => 'Gubelin', 'agl' => 'AGL', 'other' => 'Other')
	),
	'price' => array(
		'code'         => 'price',
		'label'        => 'Price',
		'input_type'   => 'decimal',
		'storage'      => 'core',
		'core_field'   => 'price',
		'core_label'   => 'Data > Price',
		'filterable'   => true
	),
	'intensity' => array(
		'code'         => 'intensity',
		'label'        => 'Intensity',
		'input_type'   => 'select',
		'storage'      => 'extension',
		'filterable'   => true,
		'options'      => array('light' => 'Light', 'fancy_light' => 'Fancy Light', 'fancy' => 'Fancy', 'fancy_intense' => 'Fancy Intense', 'fancy_vivid' => 'Fancy Vivid', 'other' => 'Other')
	),
	'brand' => array(
		'code'         => 'brand',
		'label'        => 'Brand',
		'input_type'   => 'select',
		'storage'      => 'core',
		'core_field'   => 'manufacturer_id',
		'core_label'   => 'Links > Manufacturer',
		'filterable'   => true
	),
	'weight' => array(
		'code'         => 'weight',
		'label'        => 'Weight',
		'input_type'   => 'decimal',
		'storage'      => 'core',
		'core_field'   => 'weight',
		'core_label'   => 'Data > Weight',
		'filterable'   => true
	),
	'quantity' => array(
		'code'         => 'quantity',
		'label'        => 'Quantity',
		'input_type'   => 'integer',
		'storage'      => 'core',
		'core_field'   => 'quantity',
		'core_label'   => 'Data > Quantity',
		'filterable'   => true
	),
	'location' => array(
		'code'         => 'location',
		'label'        => 'Location',
		'input_type'   => 'text',
		'storage'      => 'core',
		'core_field'   => 'location',
		'core_label'   => 'Data > Location',
		'filterable'   => true
	),
	'creator_brand' => array(
		'code'         => 'creator_brand',
		'label'        => 'Creator / Brand',
		'input_type'   => 'text',
		'storage'      => 'extension',
		'filterable'   => true
	),
	'item_type' => array(
		'code'         => 'item_type',
		'label'        => 'Item Type',
		'input_type'   => 'select',
		'storage'      => 'extension',
		'filterable'   => true,
		'options'      => array('bar' => 'Bar', 'round' => 'Round', 'industrial' => 'Industrial', 'vintage_bar' => 'Vintage Bar / Round', 'drawing' => 'Drawing', 'painting' => 'Painting', 'photography' => 'Photography', 'print' => 'Print / Multiple', 'sculpture' => 'Sculpture', 'watercolour' => 'Watercolour')
	)
);

$_['kotlyars_catalog_category_attribute_map'] = array(
	'diamonds.natural' => array(
		array('code' => 'shape', 'required' => true),
		array('code' => 'carat', 'required' => true),
		array('code' => 'clarity', 'required' => true),
		array('code' => 'cut', 'required' => true),
		array('code' => 'certificate', 'required' => false),
		array('code' => 'colour', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'diamonds.coloured' => array(
		array('code' => 'shape', 'required' => true),
		array('code' => 'carat', 'required' => true),
		array('code' => 'clarity', 'required' => true),
		array('code' => 'cut', 'required' => true),
		array('code' => 'certificate', 'required' => false),
		array('code' => 'colour', 'required' => true),
		array('code' => 'intensity', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'gemstones.ruby' => array(
		array('code' => 'shape', 'required' => true),
		array('code' => 'carat', 'required' => true),
		array('code' => 'clarity', 'required' => false),
		array('code' => 'intensity', 'required' => false),
		array('code' => 'certificate', 'required' => false),
		array('code' => 'price', 'required' => true)
	),
	'gemstones.sapphire' => array(
		array('code' => 'shape', 'required' => true),
		array('code' => 'carat', 'required' => true),
		array('code' => 'clarity', 'required' => false),
		array('code' => 'intensity', 'required' => false),
		array('code' => 'certificate', 'required' => false),
		array('code' => 'price', 'required' => true)
	),
	'gemstones.emerald' => array(
		array('code' => 'shape', 'required' => true),
		array('code' => 'carat', 'required' => true),
		array('code' => 'clarity', 'required' => false),
		array('code' => 'intensity', 'required' => false),
		array('code' => 'certificate', 'required' => false),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.gold.gold_by_weight' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.gold.gold_bars_rounds_by_brand' => array(
		array('code' => 'brand', 'required' => true),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.gold.industrial_gold' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.gold.vintage_gold_bars_rounds' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.silver.silver_bars' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.silver.industrial_silver' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.silver.silver_rounds' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.platinum.platinum_bars_rounds' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'precious_metals.platinum.industrial_platinum' => array(
		array('code' => 'brand', 'required' => false),
		array('code' => 'weight', 'required' => true),
		array('code' => 'quantity', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'fine_art.drawings_pastels' => array(
		array('code' => 'location', 'required' => false),
		array('code' => 'creator_brand', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'fine_art.paintings_mixed_media' => array(
		array('code' => 'location', 'required' => false),
		array('code' => 'creator_brand', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'fine_art.fine_art_photography' => array(
		array('code' => 'location', 'required' => false),
		array('code' => 'creator_brand', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'fine_art.prints_multiples' => array(
		array('code' => 'location', 'required' => false),
		array('code' => 'creator_brand', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'fine_art.sculptures' => array(
		array('code' => 'location', 'required' => false),
		array('code' => 'creator_brand', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'fine_art.watercolour_paintings' => array(
		array('code' => 'location', 'required' => false),
		array('code' => 'creator_brand', 'required' => true),
		array('code' => 'item_type', 'required' => true),
		array('code' => 'price', 'required' => true)
	),
	'jewellery.general' => array()
);

$_['kotlyars_catalog_filter_definitions'] = array(
	'product_type' => array('code' => 'product_type', 'label' => 'Product Type', 'filter_type' => 'select', 'source' => 'product_type'),
	'price' => array('code' => 'price', 'label' => 'Price', 'filter_type' => 'range', 'source' => 'attribute', 'attribute_code' => 'price'),
	'shape' => array('code' => 'shape', 'label' => 'Shape', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'shape'),
	'carat' => array('code' => 'carat', 'label' => 'Carat', 'filter_type' => 'range', 'source' => 'attribute', 'attribute_code' => 'carat'),
	'colour' => array('code' => 'colour', 'label' => 'Colour', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'colour'),
	'clarity' => array('code' => 'clarity', 'label' => 'Clarity', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'clarity'),
	'cut' => array('code' => 'cut', 'label' => 'Cut', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'cut'),
	'certificate' => array('code' => 'certificate', 'label' => 'Certificate', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'certificate'),
	'intensity' => array('code' => 'intensity', 'label' => 'Intensity', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'intensity'),
	'brand' => array('code' => 'brand', 'label' => 'Brand', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'brand'),
	'weight' => array('code' => 'weight', 'label' => 'Weight', 'filter_type' => 'range', 'source' => 'attribute', 'attribute_code' => 'weight'),
	'quantity' => array('code' => 'quantity', 'label' => 'Quantity', 'filter_type' => 'range', 'source' => 'attribute', 'attribute_code' => 'quantity'),
	'location' => array('code' => 'location', 'label' => 'Location', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'location'),
	'creator_brand' => array('code' => 'creator_brand', 'label' => 'Creator / Brand', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'creator_brand'),
	'item_type' => array('code' => 'item_type', 'label' => 'Item Type', 'filter_type' => 'select', 'source' => 'attribute', 'attribute_code' => 'item_type')
);

$_['kotlyars_catalog_category_filter_map'] = array(
	'diamonds.natural' => array('product_type', 'price', 'shape', 'carat', 'clarity', 'cut', 'certificate', 'colour'),
	'diamonds.coloured' => array('product_type', 'price', 'shape', 'carat', 'clarity', 'cut', 'certificate', 'colour', 'intensity'),
	'gemstones.ruby' => array('product_type', 'price', 'shape', 'carat', 'clarity', 'intensity', 'certificate'),
	'gemstones.sapphire' => array('product_type', 'price', 'shape', 'carat', 'clarity', 'intensity', 'certificate'),
	'gemstones.emerald' => array('product_type', 'price', 'shape', 'carat', 'clarity', 'intensity', 'certificate'),
	'precious_metals.gold.gold_by_weight' => array('product_type', 'price', 'brand', 'weight', 'quantity', 'item_type'),
	'precious_metals.gold.gold_bars_rounds_by_brand' => array('product_type', 'price', 'brand', 'weight', 'quantity', 'item_type'),
	'precious_metals.gold.industrial_gold' => array('product_type', 'price', 'weight', 'quantity', 'item_type'),
	'precious_metals.gold.vintage_gold_bars_rounds' => array('product_type', 'price', 'brand', 'weight', 'quantity', 'item_type'),
	'precious_metals.silver.silver_bars' => array('product_type', 'price', 'brand', 'weight', 'quantity', 'item_type'),
	'precious_metals.silver.industrial_silver' => array('product_type', 'price', 'weight', 'quantity', 'item_type'),
	'precious_metals.silver.silver_rounds' => array('product_type', 'price', 'brand', 'weight', 'quantity', 'item_type'),
	'precious_metals.platinum.platinum_bars_rounds' => array('product_type', 'price', 'brand', 'weight', 'quantity', 'item_type'),
	'precious_metals.platinum.industrial_platinum' => array('product_type', 'price', 'weight', 'quantity', 'item_type'),
	'fine_art.drawings_pastels' => array('product_type', 'price', 'location', 'creator_brand', 'item_type'),
	'fine_art.paintings_mixed_media' => array('product_type', 'price', 'location', 'creator_brand', 'item_type'),
	'fine_art.fine_art_photography' => array('product_type', 'price', 'location', 'creator_brand', 'item_type'),
	'fine_art.prints_multiples' => array('product_type', 'price', 'location', 'creator_brand', 'item_type'),
	'fine_art.sculptures' => array('product_type', 'price', 'location', 'creator_brand', 'item_type'),
	'fine_art.watercolour_paintings' => array('product_type', 'price', 'location', 'creator_brand', 'item_type'),
	'jewellery.general' => array('product_type')
);

$_['kotlyars_catalog_auction_placeholders'] = array(
	'current_bid'  => null,
	'start_price'  => null,
	'bid_increment'=> null,
	'end_time'     => null,
	'bid_history'  => null,
	'status'       => null
);
