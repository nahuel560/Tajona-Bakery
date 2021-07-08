<?php
add_filter( 'g5core_theme_font_default', 'porus_font_default' );
function porus_font_default() {
	return array(
		array(
			'family'   => 'Lora',
			'kind'     => 'webfonts#webfont',
			'variants' => array(
				"400italic",
				"400",
				"700italic",
				"700",
			),
		),
		array(
			'family'   => 'Berkshire Swash',
			'kind'     => 'webfonts#webfont',
			'variants' => array(
				"400",
			),
		),
	);
}


if ( ! class_exists( 'PORUS_SETUP_DATA' ) ) {
	class PORUS_SETUP_DATA {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_filter( 'g5core_options_header_style', array( $this, 'change_options_header_style' ) );

			add_filter( 'g5core_header_options', array( $this, 'change_g5core_header_options_config' ), 20 );

			add_filter( 'g5core_default_options_g5core_header_options', array(
				$this,
				'change_default_options_g5core_header_options'
			) );

			add_filter( 'g5core_default_options_g5core_color_options', array(
				$this,
				'change_default_options_g5core_color_options'
			) );

			add_filter( 'g5core_default_options_g5core_typography_options', array(
				$this,
				'change_default_options_g5core_typography_options'
			) );

			add_filter( 'g5core_default_options_g5core_layout_options', array(
				$this,
				'change_default_options_g5core_layout_options'
			) );
		}

		public function change_options_header_style( $header ) {
			return wp_parse_args( array(
				'porus-27' => array(
					'label' => esc_html__( 'Header 27', 'porus' ),
					'img'   => get_theme_file_uri( 'assets/images/header-01.png' )
				)
			), $header );
		}

		public function change_g5core_header_options_config( $options_config ) {
			$options_config['section_color']['fields']['menu_color_group']['fields']['submenu_scheme']['preset'] = array(
				array(
					'op'     => '=',
					'value'  => 'light',
					'fields' => array(
						array( 'submenu_background_color', '#fff' ),
						array( 'submenu_heading_color', '#222' ),
						array( 'submenu_text_color', '#666' ),
						array( 'submenu_item_bg_hover_color', '#fafafa' ),
						array( 'submenu_text_hover_color', '#d7aa82' ),
						array( 'submenu_border_color', '#fff' ),
					)
				),
				array(
					'op'     => '=',
					'value'  => 'dark',
					'fields' => array(
						array( 'submenu_background_color', '#00' ),
						array( 'submenu_heading_color', '#fff' ),
						array( 'submenu_text_color', '#fff' ),
						array( 'submenu_item_bg_hover_color', '#242424' ),
						array( 'submenu_text_hover_color', '#999' ),
						array( 'submenu_border_color', '#000' ),
					)
				),
			);

			$options_config['section_color']['fields']['navigation_scheme']['fields']['submenu_scheme']['preset'] = array(
				array(
					'op'     => '=',
					'value'  => 'light',
					'fields' => array(
						array( 'navigation_background_color', '#fff' ),
						array( 'navigation_text_color', '#1b1b1b' ),
						array( 'navigation_text_hover_color', '#999' ),
						array( 'navigation_border_color', '#eee' ),
						array( 'navigation_disable_color', '#888' ),
					)
				),
				array(
					'op'     => '=',
					'value'  => 'dark',
					'fields' => array(
						array( 'navigation_background_color', '#1b1b1b' ),
						array( 'navigation_text_color', '#fff' ),
						array( 'navigation_text_hover_color', '#999' ),
						array( 'navigation_border_color', '#343434' ),
						array( 'navigation_disable_color', '#aaa' ),
					)
				),
			);

			return $options_config;
		}

		public function change_default_options_g5core_header_options( $defaults ) {

			$defaults = wp_parse_args( array(
				'logo_font'     => wp_parse_args(
					array(
						'font_family' => 'Lora',
					), $defaults['logo_font'] ),
				'top_bar_font'  => wp_parse_args(
					array(
						'font_family' => 'Lora',
					), $defaults['top_bar_font'] ),
				'menu_font'     => wp_parse_args(
					array(
						'font_family'    => 'Lora',
						'font_size'      => '14px',
						'font_weight'    => '700',
						'letter_spacing' => '0.05',
						'transform'      => 'uppercase'
					), $defaults['menu_font'] ),
				'sub_menu_font' => wp_parse_args(
					array(
						'font_family'    => 'Lora',
						'font_weight'    => '400',
						'font_size'      => '16px',
						'letter_spacing' => '0',
						'transform'      => 'none'
					), $defaults['sub_menu_font'] ),

				'header_background_color' => '#fff',
				'header_text_color'       => '#000000',
				'header_text_hover_color' => '#e0a45e',
				'header_border_color'     => '#eee',
				'header_disable_color'    => '#888',

				'header_sticky_background_color' => '#fff',
				'header_sticky_text_color'       => '#000000',
				'header_sticky_text_hover_color' => '#e0a45e',
				'header_sticky_border_color'     => '#eee',
				'header_sticky_disable_color'    => '#888',

				'top_bar_text_hover_color' => '#e0a45e',

				'navigation_background_color' => '#fff',
				'navigation_text_color'       => '#000000',
				'navigation_text_hover_color' => '#e0a45e',
				'navigation_border_color'     => '#eee',
				'navigation_disable_color'    => '#888',

				'submenu_scheme'              => 'light',
				'submenu_background_color'    => '#fff',
				'submenu_heading_color'       => '#000',
				'submenu_text_color'          => '#4a221a',
				'submenu_item_bg_hover_color' => 'transparent',
				'submenu_text_hover_color'    => '#e0a45e',
				'submenu_border_color'        => '#eee',

				'header_mobile_top_bar_background_color' => '#f6f6f6',
				'header_mobile_top_bar_text_color'       => '#000',
				'header_mobile_top_bar_text_hover_color' => '#e0a45e',
				'header_mobile_top_bar_border_color'     => '#eee',

				'header_mobile_background_color' => '#fff',
				'header_mobile_text_color'       => '#000',
				'header_mobile_text_hover_color' => '#e0a45e',
				'header_mobile_border_color'     => '#eee',

				'header_mobile_sticky_background_color' => '#fff',
				'header_mobile_sticky_text_color'       => '#000',
				'header_mobile_sticky_text_hover_color' => '#e0a45e',
				'header_mobile_sticky_border_color'     => '#eee',

			), $defaults );


			return $defaults;
		}

		public function change_default_options_g5core_color_options( $defaults ) {
			return wp_parse_args( array(
				'site_text_color'   => '#636363',
				'accent_color'      => '#764c24',
				'link_color'        => '#764c24',
				'border_color'      => '#ebebeb',
				'heading_color'     => '#000',
				'caption_color'     => '#ababab',
				'placeholder_color' => '#b6b6b6',
				'primary_color'     => '#e0a45e',
				'secondary_color'   => '#4a221a',
				'dark_color'        => '#222',
				'light_color'       => '#fafafa',
				'gray_color'        => '#a1a1a1',
			), $defaults );
		}

		public function change_default_options_g5core_typography_options( $defaults ) {

			return wp_parse_args( array(
				'body_font'    => array(
					'font_family' => 'Lora',
					'font_size'   => '18px'
				),
				'primary_font' => array(
					'font_family' => 'Berkshire Swash'
				),
				'h1_font'      => array(
					'font_family' => 'Lora',
					'font_weight' => '700',
					'font_size'   => '48px'
				),
				'h2_font'      => array(
					'font_family' => 'Lora',
					'font_weight' => '700',
					'font_size'   => '36px'
				),
				'h3_font'      => array(
					'font_family' => 'Lora',
					'font_weight' => '700',
					'font_size'   => '30px'
				),
				'h4_font'      => array(
					'font_family' => 'Lora',
					'font_weight' => '700',
					'font_size'   => '24px'
				),
				'h5_font'      => array(
					'font_family' => 'Lora',
					'font_weight' => '700',
					'font_size'   => '20px'
				),
				'h6_font'      => array(
					'font_family' => 'Lora',
					'font_weight' => '700',
					'font_size'   => '18px'
				),
				'display_1'    => array(
					'font_family' => 'Lora'
				),
				'display_2'    => array(
					'font_family' => 'Lora'
				),
				'display_3'    => array(
					'font_family' => 'Lora'
				),
				'display_4'    => array(
					'font_family' => 'Lora'
				),
			), $defaults );

		}

		public function change_default_options_g5core_layout_options( $defaults ) {
			return wp_parse_args( array(
				'content_padding' =>
					array(
						'left'   => '',
						'right'  => '',
						'top'    => 70,
						'bottom' => 120,
					),
			), $defaults );
		}

	}

	function PORUS_SETUP_DATA() {
		return PORUS_SETUP_DATA::getInstance();
	}

	PORUS_SETUP_DATA()->init();
}

