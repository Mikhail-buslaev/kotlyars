<?php

$_['kotlyars_catalog_leaf_registry'] = array(
	'diamonds.natural' => array(
		'code'         => 'diamonds.natural',
		'domain'       => 'diamonds',
		'domain_label' => 'Diamonds',
		'label'        => 'Natural',
		'path'         => array('Diamonds', 'Natural')
	),
	'diamonds.colour' => array(
		'code'         => 'diamonds.colour',
		'domain'       => 'diamonds',
		'domain_label' => 'Diamonds',
		'label'        => 'Colour',
		'path'         => array('Diamonds', 'Colour')
	),
	'gemstones.ruby' => array(
		'code'         => 'gemstones.ruby',
		'domain'       => 'gemstones',
		'domain_label' => 'Gemstones',
		'label'        => 'Ruby',
		'path'         => array('Gemstones', 'Ruby')
	),
	'gemstones.sapphire' => array(
		'code'         => 'gemstones.sapphire',
		'domain'       => 'gemstones',
		'domain_label' => 'Gemstones',
		'label'        => 'Sapphire',
		'path'         => array('Gemstones', 'Sapphire')
	),
	'gemstones.emerald' => array(
		'code'         => 'gemstones.emerald',
		'domain'       => 'gemstones',
		'domain_label' => 'Gemstones',
		'label'        => 'Emerald',
		'path'         => array('Gemstones', 'Emerald')
	),
	'precious_metals.gold.gold_by_weight' => array(
		'code'         => 'precious_metals.gold.gold_by_weight',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Gold By Weight',
		'path'         => array('Precious Metals', 'Gold', 'Gold By Weight')
	),
	'precious_metals.gold.gold_bars_rounds_by_brand' => array(
		'code'         => 'precious_metals.gold.gold_bars_rounds_by_brand',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Gold Bars & Rounds By Brand',
		'path'         => array('Precious Metals', 'Gold', 'Gold Bars & Rounds By Brand')
	),
	'precious_metals.gold.vintage_gold_bars_rounds' => array(
		'code'         => 'precious_metals.gold.vintage_gold_bars_rounds',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Vintage Gold Bars & Rounds',
		'path'         => array('Precious Metals', 'Gold', 'Vintage Gold Bars & Rounds')
	),
	'precious_metals.silver.silver_bars' => array(
		'code'         => 'precious_metals.silver.silver_bars',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Silver Bars',
		'path'         => array('Precious Metals', 'Silver', 'Silver Bars')
	),
	'precious_metals.silver.industrial_silver' => array(
		'code'         => 'precious_metals.silver.industrial_silver',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Industrial Silver',
		'path'         => array('Precious Metals', 'Silver', 'Industrial Silver')
	),
	'precious_metals.silver.silver_rounds' => array(
		'code'         => 'precious_metals.silver.silver_rounds',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Silver Rounds',
		'path'         => array('Precious Metals', 'Silver', 'Silver Rounds')
	),
	'precious_metals.platinum.platinum_bars_rounds' => array(
		'code'         => 'precious_metals.platinum.platinum_bars_rounds',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Platinum Bars & Rounds',
		'path'         => array('Precious Metals', 'Platinum', 'Platinum Bars & Rounds')
	),
	'precious_metals.platinum.industrial_platinum' => array(
		'code'         => 'precious_metals.platinum.industrial_platinum',
		'domain'       => 'precious_metals',
		'domain_label' => 'Precious Metals',
		'label'        => 'Industrial Platinum',
		'path'         => array('Precious Metals', 'Platinum', 'Industrial Platinum')
	),
	'fine_art.drawings_pastels' => array(
		'code'         => 'fine_art.drawings_pastels',
		'domain'       => 'fine_art',
		'domain_label' => 'Fine Art',
		'label'        => 'Drawings & Pastels',
		'path'         => array('Fine Art', 'Drawings & Pastels')
	),
	'fine_art.paintings_mixed_media' => array(
		'code'         => 'fine_art.paintings_mixed_media',
		'domain'       => 'fine_art',
		'domain_label' => 'Fine Art',
		'label'        => 'Paintings & Mixed Media',
		'path'         => array('Fine Art', 'Paintings & Mixed Media')
	),
	'fine_art.fine_art_photography' => array(
		'code'         => 'fine_art.fine_art_photography',
		'domain'       => 'fine_art',
		'domain_label' => 'Fine Art',
		'label'        => 'Fine Art Photography',
		'path'         => array('Fine Art', 'Fine Art Photography')
	),
	'fine_art.prints_multiples' => array(
		'code'         => 'fine_art.prints_multiples',
		'domain'       => 'fine_art',
		'domain_label' => 'Fine Art',
		'label'        => 'Prints & Multiples',
		'path'         => array('Fine Art', 'Prints & Multiples')
	),
	'fine_art.sculptures' => array(
		'code'         => 'fine_art.sculptures',
		'domain'       => 'fine_art',
		'domain_label' => 'Fine Art',
		'label'        => 'Sculptures',
		'path'         => array('Fine Art', 'Sculptures')
	)
);

$_['kotlyars_catalog_native_category_nodes'] = array(
	'diamonds' => array('code' => 'diamonds', 'label' => 'Diamonds', 'parent_code' => '', 'is_leaf' => false),
	'diamonds.natural' => array('code' => 'diamonds.natural', 'label' => 'Natural', 'parent_code' => 'diamonds', 'is_leaf' => true, 'leaf_code' => 'diamonds.natural'),
	'diamonds.colour' => array('code' => 'diamonds.colour', 'label' => 'Colour', 'parent_code' => 'diamonds', 'is_leaf' => true, 'leaf_code' => 'diamonds.colour'),
	'gemstones' => array('code' => 'gemstones', 'label' => 'Gemstones', 'parent_code' => '', 'is_leaf' => false),
	'gemstones.ruby' => array('code' => 'gemstones.ruby', 'label' => 'Ruby', 'parent_code' => 'gemstones', 'is_leaf' => true, 'leaf_code' => 'gemstones.ruby'),
	'gemstones.sapphire' => array('code' => 'gemstones.sapphire', 'label' => 'Sapphire', 'parent_code' => 'gemstones', 'is_leaf' => true, 'leaf_code' => 'gemstones.sapphire'),
	'gemstones.emerald' => array('code' => 'gemstones.emerald', 'label' => 'Emerald', 'parent_code' => 'gemstones', 'is_leaf' => true, 'leaf_code' => 'gemstones.emerald'),
	'precious_metals' => array('code' => 'precious_metals', 'label' => 'Precious Metals', 'parent_code' => '', 'is_leaf' => false),
	'precious_metals.gold' => array('code' => 'precious_metals.gold', 'label' => 'Gold', 'parent_code' => 'precious_metals', 'is_leaf' => false),
	'precious_metals.gold.gold_by_weight' => array('code' => 'precious_metals.gold.gold_by_weight', 'label' => 'Gold By Weight', 'parent_code' => 'precious_metals.gold', 'is_leaf' => true, 'leaf_code' => 'precious_metals.gold.gold_by_weight'),
	'precious_metals.gold.gold_bars_rounds_by_brand' => array('code' => 'precious_metals.gold.gold_bars_rounds_by_brand', 'label' => 'Gold Bars & Rounds By Brand', 'parent_code' => 'precious_metals.gold', 'is_leaf' => true, 'leaf_code' => 'precious_metals.gold.gold_bars_rounds_by_brand'),
	'precious_metals.gold.vintage_gold_bars_rounds' => array('code' => 'precious_metals.gold.vintage_gold_bars_rounds', 'label' => 'Vintage Gold Bars & Rounds', 'parent_code' => 'precious_metals.gold', 'is_leaf' => true, 'leaf_code' => 'precious_metals.gold.vintage_gold_bars_rounds'),
	'precious_metals.silver' => array('code' => 'precious_metals.silver', 'label' => 'Silver', 'parent_code' => 'precious_metals', 'is_leaf' => false),
	'precious_metals.silver.silver_bars' => array('code' => 'precious_metals.silver.silver_bars', 'label' => 'Silver Bars', 'parent_code' => 'precious_metals.silver', 'is_leaf' => true, 'leaf_code' => 'precious_metals.silver.silver_bars'),
	'precious_metals.silver.industrial_silver' => array('code' => 'precious_metals.silver.industrial_silver', 'label' => 'Industrial Silver', 'parent_code' => 'precious_metals.silver', 'is_leaf' => true, 'leaf_code' => 'precious_metals.silver.industrial_silver'),
	'precious_metals.silver.silver_rounds' => array('code' => 'precious_metals.silver.silver_rounds', 'label' => 'Silver Rounds', 'parent_code' => 'precious_metals.silver', 'is_leaf' => true, 'leaf_code' => 'precious_metals.silver.silver_rounds'),
	'precious_metals.platinum' => array('code' => 'precious_metals.platinum', 'label' => 'Platinum', 'parent_code' => 'precious_metals', 'is_leaf' => false),
	'precious_metals.platinum.platinum_bars_rounds' => array('code' => 'precious_metals.platinum.platinum_bars_rounds', 'label' => 'Platinum Bars & Rounds', 'parent_code' => 'precious_metals.platinum', 'is_leaf' => true, 'leaf_code' => 'precious_metals.platinum.platinum_bars_rounds'),
	'precious_metals.platinum.industrial_platinum' => array('code' => 'precious_metals.platinum.industrial_platinum', 'label' => 'Industrial Platinum', 'parent_code' => 'precious_metals.platinum', 'is_leaf' => true, 'leaf_code' => 'precious_metals.platinum.industrial_platinum'),
	'fine_art' => array('code' => 'fine_art', 'label' => 'Fine Art', 'parent_code' => '', 'is_leaf' => false),
	'fine_art.drawings_pastels' => array('code' => 'fine_art.drawings_pastels', 'label' => 'Drawings & Pastels', 'parent_code' => 'fine_art', 'is_leaf' => true, 'leaf_code' => 'fine_art.drawings_pastels'),
	'fine_art.paintings_mixed_media' => array('code' => 'fine_art.paintings_mixed_media', 'label' => 'Paintings & Mixed Media', 'parent_code' => 'fine_art', 'is_leaf' => true, 'leaf_code' => 'fine_art.paintings_mixed_media'),
	'fine_art.fine_art_photography' => array('code' => 'fine_art.fine_art_photography', 'label' => 'Fine Art Photography', 'parent_code' => 'fine_art', 'is_leaf' => true, 'leaf_code' => 'fine_art.fine_art_photography'),
	'fine_art.prints_multiples' => array('code' => 'fine_art.prints_multiples', 'label' => 'Prints & Multiples', 'parent_code' => 'fine_art', 'is_leaf' => true, 'leaf_code' => 'fine_art.prints_multiples'),
	'fine_art.sculptures' => array('code' => 'fine_art.sculptures', 'label' => 'Sculptures', 'parent_code' => 'fine_art', 'is_leaf' => true, 'leaf_code' => 'fine_art.sculptures')
);

$_['kotlyars_catalog_attribute_definitions'] = array(
	'shape' => array(
		'code'       => 'shape',
		'label'      => 'Shape',
		'input_type' => 'select'
	),
	'colour' => array(
		'code'       => 'colour',
		'label'      => 'Colour',
		'input_type' => 'select'
	),
	'clarity' => array(
		'code'       => 'clarity',
		'label'      => 'Clarity',
		'input_type' => 'select'
	),
	'cut' => array(
		'code'       => 'cut',
		'label'      => 'Cut',
		'input_type' => 'select'
	),
	'certificate' => array(
		'code'       => 'certificate',
		'label'      => 'Certificate',
		'input_type' => 'select'
	),
	'intensity' => array(
		'code'       => 'intensity',
		'label'      => 'Intensity',
		'input_type' => 'select'
	),
	'brand' => array(
		'code'       => 'brand',
		'label'      => 'Brand',
		'input_type' => 'select'
	),
	'type' => array(
		'code'       => 'type',
		'label'      => 'Type',
		'input_type' => 'select'
	),
	'location' => array(
		'code'       => 'location',
		'label'      => 'Location',
		'input_type' => 'select'
	),
	'creator_brand' => array(
		'code'       => 'creator_brand',
		'label'      => 'Creator / Brand',
		'input_type' => 'select'
	),
	'item_type' => array(
		'code'       => 'item_type',
		'label'      => 'Item Type',
		'input_type' => 'select'
	)
);

$_['kotlyars_catalog_native_attribute_group_registry'] = array(
	'vendor_store' => array(
		'code' => 'vendor_store',
		'label' => 'Kotlyars Vendor Store',
		'sort_order' => 0
	)
);

$_['kotlyars_catalog_native_attribute_registry'] = array(
	'shape' => array('code' => 'shape', 'label' => 'Shape', 'group_code' => 'vendor_store', 'sort_order' => 10),
	'colour' => array('code' => 'colour', 'label' => 'Colour', 'group_code' => 'vendor_store', 'sort_order' => 20),
	'clarity' => array('code' => 'clarity', 'label' => 'Clarity', 'group_code' => 'vendor_store', 'sort_order' => 30),
	'cut' => array('code' => 'cut', 'label' => 'Cut', 'group_code' => 'vendor_store', 'sort_order' => 40),
	'certificate' => array('code' => 'certificate', 'label' => 'Certificate', 'group_code' => 'vendor_store', 'sort_order' => 50),
	'intensity' => array('code' => 'intensity', 'label' => 'Intensity', 'group_code' => 'vendor_store', 'sort_order' => 60),
	'brand' => array('code' => 'brand', 'label' => 'Brand', 'group_code' => 'vendor_store', 'sort_order' => 70),
	'type' => array('code' => 'type', 'label' => 'Type', 'group_code' => 'vendor_store', 'sort_order' => 80),
	'location' => array('code' => 'location', 'label' => 'Location', 'group_code' => 'vendor_store', 'sort_order' => 90),
	'creator_brand' => array('code' => 'creator_brand', 'label' => 'Creator / Brand', 'group_code' => 'vendor_store', 'sort_order' => 100),
	'item_type' => array('code' => 'item_type', 'label' => 'Item Type', 'group_code' => 'vendor_store', 'sort_order' => 110),
	'carat' => array('code' => 'carat', 'label' => 'Carat', 'group_code' => 'vendor_store', 'sort_order' => 120)
);

$_['kotlyars_catalog_range_definitions'] = array(
	'carat' => array(
		'code'       => 'carat',
		'label'      => 'Carat',
		'input_type' => 'decimal',
		'storage'    => 'extension'
	),
	'price' => array(
		'code'       => 'price',
		'label'      => 'Price',
		'input_type' => 'decimal',
		'storage'    => 'core',
		'core_field' => 'price',
		'core_label' => 'Data > Price'
	),
	'weight' => array(
		'code'       => 'weight',
		'label'      => 'Weight',
		'input_type' => 'decimal',
		'storage'    => 'core',
		'core_field' => 'weight',
		'core_label' => 'Data > Weight'
	)
);

$_['kotlyars_catalog_leaf_attribute_map'] = array(
	'diamonds.natural' => array(
		array(
			'code' => 'shape',
			'options' => array(
				'round' => 'Round',
				'princess' => 'Princess',
				'cushion' => 'Cushion',
				'oval' => 'Oval',
				'pear' => 'Pear',
				'emerald' => 'Emerald',
				'heart' => 'Heart',
				'radiant' => 'Radiant',
				'asscher' => 'Asscher',
				'marquise' => 'Marquise',
				'kites_shields' => 'Kites & Shields',
				'old_cuts' => 'Old Cuts',
				'triangulars' => 'Triangulars'
			)
		),
		array(
			'code' => 'colour',
			'options' => array(
				'l' => 'L',
				'k' => 'K',
				'j' => 'J',
				'i' => 'I',
				'h' => 'H',
				'g' => 'G',
				'f' => 'F',
				'e' => 'E',
				'd' => 'D'
			)
		),
		array(
			'code' => 'clarity',
			'options' => array(
				'si2' => 'SI2',
				'si1' => 'SI1',
				'vs2' => 'VS2',
				'vs1' => 'VS1',
				'vvs2' => 'VVS2',
				'vvs1' => 'VVS1',
				'if' => 'IF',
				'fl' => 'FL'
			)
		),
		array(
			'code' => 'cut',
			'options' => array(
				'good' => 'Good',
				'very_good' => 'Very Good',
				'excellent' => 'Excellent',
				'cupids_ideal' => 'Cupid\'s Ideal'
			)
		),
		array(
			'code' => 'certificate',
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		)
	),
	'diamonds.colour' => array(
		array(
			'code' => 'shape',
			'options' => array(
				'round' => 'Round',
				'princess' => 'Princess',
				'cushion' => 'Cushion',
				'oval' => 'Oval',
				'pear' => 'Pear',
				'emerald' => 'Emerald',
				'heart' => 'Heart',
				'radiant' => 'Radiant',
				'asscher' => 'Asscher',
				'marquise' => 'Marquise',
				'kites_shields' => 'Kites & Shields',
				'old_cuts' => 'Old Cuts',
				'triangulars' => 'Triangulars'
			)
		),
		array(
			'code' => 'colour',
			'options' => array(
				'yellow' => 'Yellow',
				'pink' => 'Pink',
				'blue' => 'Blue',
				'grey' => 'Grey'
			)
		),
		array(
			'code' => 'intensity',
			'options' => array(
				'f_light' => 'F.Light',
				'fancy' => 'Fancy',
				'intense' => 'Intense',
				'vivid' => 'Vivid',
				'deep' => 'Deep'
			)
		),
		array(
			'code' => 'clarity',
			'options' => array(
				'si2' => 'SI2',
				'si1' => 'SI1',
				'vs2' => 'VS2',
				'vs1' => 'VS1',
				'vvs2' => 'VVS2',
				'vvs1' => 'VVS1',
				'if' => 'IF',
				'fl' => 'FL'
			)
		),
		array(
			'code' => 'cut',
			'options' => array(
				'good' => 'Good',
				'very_good' => 'Very Good',
				'excellent' => 'Excellent',
				'cupids_ideal' => 'Cupid\'s Ideal'
			)
		),
		array(
			'code' => 'certificate',
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		)
	),
	'gemstones.ruby' => array(
		array(
			'code' => 'shape',
			'options' => array(
				'round' => 'Round',
				'oval' => 'Oval',
				'pear' => 'Pear',
				'cushion' => 'Cushion',
				'heart' => 'Heart',
				'rectangle' => 'Rectangle',
				'marquise' => 'Marquise',
				'square' => 'Square',
				'other' => 'Other'
			)
		),
		array(
			'code' => 'clarity',
			'options' => array(
				'included' => 'Included',
				'visible_inclusions' => 'Visible Inclusions',
				'eye_clean' => 'Eye Clean'
			)
		),
		array(
			'code' => 'intensity',
			'options' => array(
				'medium_intense' => 'Medium Intense',
				'intense' => 'Intense',
				'vivid' => 'Vivid',
				'deep' => 'Deep',
				'dark' => 'Dark'
			)
		),
		array(
			'code' => 'certificate',
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		)
	),
	'gemstones.sapphire' => array(
		array(
			'code' => 'shape',
			'options' => array(
				'round' => 'Round',
				'oval' => 'Oval',
				'pear' => 'Pear',
				'cushion' => 'Cushion',
				'heart' => 'Heart',
				'rectangle' => 'Rectangle',
				'marquise' => 'Marquise',
				'square' => 'Square',
				'other' => 'Other'
			)
		),
		array(
			'code' => 'clarity',
			'options' => array(
				'included' => 'Included',
				'visible_inclusions' => 'Visible Inclusions',
				'eye_clean' => 'Eye Clean'
			)
		),
		array(
			'code' => 'intensity',
			'options' => array(
				'medium_intense' => 'Medium Intense',
				'intense' => 'Intense',
				'vivid' => 'Vivid',
				'deep' => 'Deep',
				'very_light' => 'Very Light',
				'light' => 'Light'
			)
		),
		array(
			'code' => 'certificate',
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		)
	),
	'gemstones.emerald' => array(
		array(
			'code' => 'shape',
			'options' => array(
				'round' => 'Round',
				'oval' => 'Oval',
				'pear' => 'Pear',
				'cushion' => 'Cushion',
				'heart' => 'Heart',
				'rectangle' => 'Rectangle',
				'marquise' => 'Marquise',
				'square' => 'Square',
				'other' => 'Other'
			)
		),
		array(
			'code' => 'clarity',
			'options' => array(
				'included' => 'Included',
				'visible_inclusions' => 'Visible Inclusions',
				'eye_clean' => 'Eye Clean'
			)
		),
		array(
			'code' => 'intensity',
			'options' => array(
				'medium_intense' => 'Medium Intense',
				'intense' => 'Intense',
				'vivid' => 'Vivid',
				'deep' => 'Deep',
				'light' => 'Light'
			)
		),
		array(
			'code' => 'certificate',
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		)
	),
	'precious_metals.gold.gold_by_weight' => array(
		array(
			'code' => 'brand',
			'options' => array(
				'apmex' => 'APMEX',
				'austrian_mint' => 'Austrian Mint',
				'call_of_duty' => 'Call of Duty',
				'credit_suisse' => 'Credit Suisse',
				'geiger' => 'Geiger',
				'johnson_matthey' => 'Johnson Matthey',
				'perth_mint' => 'Perth Mint'
			)
		)
	),
	'precious_metals.gold.gold_bars_rounds_by_brand' => array(
		array(
			'code' => 'brand',
			'options' => array(
				'apmex' => 'APMEX',
				'austrian_mint' => 'Austrian Mint',
				'call_of_duty' => 'Call of Duty',
				'credit_suisse' => 'Credit Suisse',
				'geiger' => 'Geiger',
				'johnson_matthey' => 'Johnson Matthey',
				'perth_mint' => 'Perth Mint'
			)
		)
	),
	'precious_metals.gold.vintage_gold_bars_rounds' => array(
		array(
			'code' => 'brand',
			'options' => array(
				'apmex' => 'APMEX',
				'austrian_mint' => 'Austrian Mint',
				'call_of_duty' => 'Call of Duty',
				'credit_suisse' => 'Credit Suisse',
				'geiger' => 'Geiger',
				'johnson_matthey' => 'Johnson Matthey',
				'perth_mint' => 'Perth Mint'
			)
		)
	),
	'precious_metals.silver.silver_bars' => array(
		array(
			'code' => 'brand',
			'options' => array(
				'ninefine_mint_silver_bars' => '9Fine Mint Silver Bars',
				'apmex_silver_bars' => 'APMEX Silver Bars',
				'pamp_silver_bars' => 'PAMP Silver Bars',
				'engelhard_silver_bars' => 'Engelhard Silver Bars',
				'johnson_matthey_silver_bars' => 'Johnson Matthey Silver Bars'
			)
		)
	),
	'precious_metals.silver.industrial_silver' => array(
		array(
			'code' => 'type',
			'options' => array(
				'bar' => 'Bar',
				'coin' => 'Coin',
				'supplie' => 'Supplie',
				'other' => 'Other'
			)
		)
	),
	'precious_metals.silver.silver_rounds' => array(
		array(
			'code' => 'brand',
			'options' => array(
				'american_reserve' => 'American Reserve',
				'auburn_university' => 'Auburn University',
				'bitpay' => 'Bitpay',
				'engelhard' => 'Engelhard',
				'germania_mint' => 'Germania Mint',
				'garfield' => 'Garfield',
				'hoffman_and_hoffman' => 'Hoffman and Hoffman'
			)
		)
	),
	'precious_metals.platinum.platinum_bars_rounds' => array(
		array(
			'code' => 'brand',
			'options' => array(
				'apmex' => 'APMEX',
				'credit_suisse' => 'Credit Suisse',
				'pamp_suisse' => 'PAMP Suisse',
				'argor_heraeus' => 'Argor-Heraeus',
				'engelhard' => 'Engelhard'
			)
		)
	),
	'precious_metals.platinum.industrial_platinum' => array(
		array(
			'code' => 'brand',
			'options' => array(
				'baird_and_co' => 'Baird and Co',
				'credit_suisse' => 'Credit Suisse',
				'pamp_suisse' => 'PAMP Suisse',
				'valcambi' => 'Valcambi'
			)
		)
	),
	'fine_art.drawings_pastels' => array(
		array(
			'code' => 'location',
			'options' => array(
				'united_kingdom' => 'United Kingdom',
				'united_states' => 'United States',
				'ireland' => 'Ireland',
				'germany' => 'Germany'
			)
		),
		array(
			'code' => 'creator_brand',
			'options' => array(
				'rudolf' => 'Rudolf',
				'oskar_kokoschka' => 'Oskar Kokoschka'
			)
		),
		array(
			'code' => 'item_type',
			'options' => array(
				'modern_impressionist_art' => 'Modern & Impressionist Art',
				'art_19th_21st_century' => '19th-21st Century Art'
			)
		)
	),
	'fine_art.paintings_mixed_media' => array(
		array(
			'code' => 'location',
			'options' => array(
				'united_kingdom' => 'United Kingdom',
				'united_states' => 'United States',
				'ireland' => 'Ireland',
				'germany' => 'Germany'
			)
		),
		array(
			'code' => 'creator_brand',
			'options' => array(
				'trevor_bell' => 'Trevor Bell',
				'english_school' => 'English School'
			)
		),
		array(
			'code' => 'item_type',
			'options' => array(
				'oil_painting' => 'Oil Painting',
				'oil_on_canvas' => 'Oil on Canvas',
				'landscape' => 'Landscape',
				'landscape_painting' => 'Landscape Painting',
				'portrait' => 'Portrait'
			)
		)
	),
	'fine_art.fine_art_photography' => array(
		array(
			'code' => 'location',
			'options' => array(
				'united_kingdom' => 'United Kingdom',
				'united_states' => 'United States',
				'ireland' => 'Ireland',
				'germany' => 'Germany'
			)
		),
		array(
			'code' => 'creator_brand',
			'options' => array(
				'francis_frith' => 'Francis Frith',
				'francis_bedford' => 'Francis Bedford'
			)
		),
		array(
			'code' => 'item_type',
			'options' => array(
				'photograph' => 'Photograph',
				'fine_art_portrait_photography' => 'Fine Art Portrait Photography',
				'portrait' => 'Portrait'
			)
		)
	),
	'fine_art.prints_multiples' => array(
		array(
			'code' => 'location',
			'options' => array(
				'united_kingdom' => 'United Kingdom',
				'united_states' => 'United States',
				'ireland' => 'Ireland',
				'germany' => 'Germany'
			)
		),
		array(
			'code' => 'creator_brand',
			'options' => array(
				'pablo_picasso' => 'Pablo Picasso',
				'andy_warhol' => 'Andy Warhol'
			)
		),
		array(
			'code' => 'item_type',
			'options' => array(
				'modern_impressionist_art' => 'Modern & Impressionist Art',
				'lithograph' => 'Lithograph',
				'etching' => 'Etching',
				'engraving' => 'Engraving'
			)
		)
	),
	'fine_art.sculptures' => array(
		array(
			'code' => 'location',
			'options' => array(
				'united_kingdom' => 'United Kingdom',
				'united_states' => 'United States',
				'ireland' => 'Ireland',
				'germany' => 'Germany'
			)
		),
		array(
			'code' => 'creator_brand',
			'options' => array(
				'banksy' => 'Banksy',
				'fritz_wotruba' => 'Fritz Wotruba',
				'rodin' => 'Rodin'
			)
		),
		array(
			'code' => 'item_type',
			'options' => array(
				'sculpture' => 'Sculpture',
				'bust' => 'Bust',
				'modern_impressionist_art' => 'Modern & Impressionist Art',
				'contemporary_art' => 'Contemporary Art',
				'bronze_sculpture' => 'Bronze Sculpture'
			)
		)
	)
);

$_['kotlyars_catalog_leaf_range_map'] = array(
	'diamonds.natural' => array(
		array('code' => 'carat', 'min' => 0.18, 'max' => 30.00, 'unit' => 'ct'),
		array('code' => 'price', 'min' => 134.00, 'max' => 6000000.00, 'currency' => 'USD')
	),
	'diamonds.colour' => array(
		array('code' => 'carat', 'min' => 0.18, 'max' => 30.00, 'unit' => 'ct'),
		array('code' => 'price', 'min' => 134.00, 'max' => 6000000.00, 'currency' => 'USD')
	),
	'gemstones.ruby' => array(
		array('code' => 'carat', 'min' => 0.18, 'max' => 30.00, 'unit' => 'ct'),
		array('code' => 'price', 'min' => 134.00, 'max' => 6000000.00, 'currency' => 'USD')
	),
	'gemstones.sapphire' => array(
		array('code' => 'carat', 'min' => 0.18, 'max' => 30.00, 'unit' => 'ct'),
		array('code' => 'price', 'min' => 134.00, 'max' => 6000000.00, 'currency' => 'USD')
	),
	'gemstones.emerald' => array(
		array('code' => 'carat', 'min' => 0.18, 'max' => 30.00, 'unit' => 'ct'),
		array('code' => 'price', 'min' => 134.00, 'max' => 6000000.00, 'currency' => 'USD')
	),
	'precious_metals.gold.gold_by_weight' => array(
		array('code' => 'weight', 'min' => 1.00, 'max' => 1000.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'precious_metals.gold.gold_bars_rounds_by_brand' => array(
		array('code' => 'weight', 'min' => 1.00, 'max' => 1000.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'precious_metals.gold.vintage_gold_bars_rounds' => array(
		array('code' => 'weight', 'min' => 1.00, 'max' => 250.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'precious_metals.silver.silver_bars' => array(
		array('code' => 'weight', 'min' => 1.00, 'max' => 5000.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'precious_metals.silver.industrial_silver' => array(
		array('code' => 'weight', 'min' => 1.00, 'max' => 1000.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'precious_metals.silver.silver_rounds' => array(
		array('code' => 'weight', 'min' => 100.00, 'max' => 1000.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'precious_metals.platinum.platinum_bars_rounds' => array(
		array('code' => 'weight', 'min' => 1.00, 'max' => 1000.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'precious_metals.platinum.industrial_platinum' => array(
		array('code' => 'weight', 'min' => 1.00, 'max' => 1000.00, 'unit' => 'gram'),
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'fine_art.drawings_pastels' => array(
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'fine_art.paintings_mixed_media' => array(
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'fine_art.fine_art_photography' => array(
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'fine_art.prints_multiples' => array(
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	),
	'fine_art.sculptures' => array(
		array('code' => 'price', 'min' => 1.00, 'max' => 1000000.00, 'currency' => 'USD')
	)
);
