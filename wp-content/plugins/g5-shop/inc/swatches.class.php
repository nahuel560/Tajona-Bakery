<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Shop_Swatches' ) ) {
	class G5Shop_Swatches {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_filter( 'product_attributes_type_selector', array( $this, 'product_attributes_type_selector' ) );
			add_filter( 'gsf_term_meta_config', array( $this, 'define_term_meta' ) );


			$attribute_taxonomies = wc_get_attribute_taxonomies();
			foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
				add_filter( "manage_edit-pa_{$attribute_taxonomy->attribute_name}_columns", array(
					$this,
					'custom_columns'
				) );
				add_filter( "manage_pa_{$attribute_taxonomy->attribute_name}_custom_column", array(
					$this,
					'custom_columns_content'
				), 10, 3 );
			}

			add_filter( 'woocommerce_layered_nav_term_html', array( $this, 'layered_nav_term_html' ), 10, 4 );


			remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );

			add_action( 'woocommerce_variable_add_to_cart', array( $this, 'swatches_single' ) );
			add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'swatches_loop' ), 30 );

		}

		public function product_attributes_type_selector( $attribute_types ) {
			global $pagenow;
			if ( ( $pagenow === 'post-new.php' )
			     || ( $pagenow === 'post.php' )
			     || ( defined( 'DOING_AJAX' ) && DOING_AJAX) ) {
				return $attribute_types;
			}
			$attribute_types['text']  = esc_html__( 'Text', 'g5-shop' );
			$attribute_types['color'] = esc_html__( 'Color', 'g5-shop' );
			$attribute_types['image'] = esc_html__( 'Image', 'g5-shop' );

			return $attribute_types;
		}

		public function get_attribute_taxonomies() {
			$taxonomies = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attribute_taxonomies = wc_get_attribute_taxonomies();
				if ( ! empty( $attribute_taxonomies ) ) {
					foreach ( $attribute_taxonomies as $tax ) {
						if ( wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
							$taxonomies[ 'pa_' . $tax->attribute_name ] = $tax->attribute_type;
						}
					}
				}
			}

			return $taxonomies;
		}

		public function define_term_meta( $configs ) {
			$prefix               = G5SHOP()->meta_prefix;
			$attribute_taxonomies = $this->get_attribute_taxonomies();
			foreach ( $attribute_taxonomies as $k => $v ) {
				if ( in_array($v,array('color','text','image'))) {
					$configs["g5shop_{$k}_meta"] = array(
						'name'     => esc_attr__( 'Additional Fields', 'g5-shop' ),
						'layout'   => 'inline',
						'taxonomy' => array( $k ),
						'fields'   => array(
							array(
								'title'   => ucfirst( $v ),
								'id'      => "{$prefix}product_taxonomy_" . $v,
								'type'    => $v,
								'default' => ''
							)
						)
					);
				}
			}
			return $configs;
		}

		public function custom_columns( $columns ) {
			$columns['swatches_value'] = esc_html__( 'Swatches Value', 'g5-shop' );

			return $columns;
		}

		public function custom_columns_content( $columns, $column, $term_id ) {
			if ( $column == 'swatches_value' ) {
				$prefix    = G5SHOP()->meta_prefix;
				$term      = get_term( $term_id );
				$attr_id   = wc_attribute_taxonomy_id_by_name( $term->taxonomy );
				$attr_info = wc_get_attribute( $attr_id );
				switch ( $attr_info->type ) {
					case 'image':
						$val      = get_term_meta( $term_id, "{$prefix}product_taxonomy_image", true );
						$image_id = isset( $val['id'] ) ? $val['id'] : 0;
						if ( ! empty( $image_id ) ) {
							echo '<img style="display: inline-block; width: 40px; height: 40px; background-color: #eee; box-sizing: border-box; border: 1px solid #eee;" src="' . esc_url( wp_get_attachment_thumb_url( $image_id ) ) . '"/>';
						}
						break;
					case 'color':
						$val = get_term_meta( $term_id, "{$prefix}product_taxonomy_color", true );
						echo '<span style="display: inline-block; width: 40px; height: 40px; background-color: ' . esc_attr( $val ) . '; box-sizing: border-box; border: 1px solid #eee;"></span>';
						break;
					case 'text':
						$val = get_term_meta( $term_id, "{$prefix}product_taxonomy_text", true );
						echo '<span style="display: inline-block; height: 40px; line-height: 40px; padding: 0 15px; border: 1px solid #eee; background-color: #fff; min-width: 44px; box-sizing: border-box;">' . esc_html( $val ) . '</span>';
						break;
				}
			}
		}

		public function layered_nav_term_html( $term_html, $term, $link, $count ) {
			$attribute_type  = $this->get_attribute_type( $term->taxonomy );
			$prefix          = G5SHOP()->meta_prefix;
			$span_attributes = array();
			$item_classes    = array( 'g5shop__layered-nav-item' );
			if ( $attribute_type === 'color' ) {
				$color = get_term_meta( $term->term_id, "{$prefix}product_taxonomy_color", true );
				if ( ! empty( $color ) ) {
					$span_attributes[] = sprintf( 'style="background-color:%s"', esc_attr( $color ) );
					$item_classes[]    = 'layered-nav-item-color';
				}
			}

			$item_class = implode( ' ', $item_classes );

			if ( $link ) {
				$term_html = '<a class="' . esc_attr( $item_class ) . '" rel="nofollow" href="' . esc_url( $link ) . '"><span ' . join( ' ', $span_attributes ) . '></span>' . esc_html( $term->name ) . '</a>';
			} else {
				$term_html = '<span class="' . esc_attr( $item_class ) . '"><span ' . join( ' ', $span_attributes ) . '></span>' . esc_html( $term->name ) . '</span>';
			}
			$term_html .= ' ' . apply_filters( 'g5shop_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );

			return $term_html;
		}

		public function get_attribute_type( $taxonomy ) {
			$attribute_id   = wc_attribute_taxonomy_id_by_name( $taxonomy );
			$attribute_info = wc_get_attribute( $attribute_id );
			if ( $attribute_info != null ) {
				return $attribute_info->type;
			}

			return '';
		}

		public function utf8_urldecode( $str ) {
			$str = preg_replace( "/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode( $str ) );

			return html_entity_decode( $str, null, 'UTF-8' );
		}

		public function get_term_html( $term, $type ) {
			$attributes   = array();
			$label        = '';
			$prefix       = G5SHOP()->meta_prefix;
			$attributes[] = sprintf( 'data-term="%s"', esc_attr( $term->slug ) );
			$attributes[] = sprintf( 'title="%s"', esc_attr( $term->name ) );
			$attributes[] = 'data-toggle="tooltip"';
			switch ( $type ) {
				case 'color':
					$color = get_term_meta( $term->term_id, "{$prefix}product_taxonomy_color", true );
					if ( ! empty( $color ) ) {
						$attributes[] = sprintf( 'style="background-color:%s"', esc_attr( $color ) );
					}
					break;
				case 'text':
					$label = get_term_meta( $term->term_id, "{$prefix}product_taxonomy_text", true );
					if ( empty( $label ) ) {
						$label = $term->name;
					}
					break;
				case 'image':
					$image    = get_term_meta( $term->term_id, "{$prefix}product_taxonomy_image", true );
					$image_id = isset( $image['id'] ) ? $image['id'] : 0;
					if ( ! empty( $image_id ) ) {
						$attributes[] = sprintf( 'style="background-image:url(%s)"', esc_url( wp_get_attachment_url( $image_id ) ) );
					}
					break;
			}

			?>
			<span class="g5shop__swatches-item" <?php echo implode( ' ', $attributes ) ?>>
                <?php echo esc_html( $label ); ?>
            </span>
			<?php


		}

		public function get_swatches_enable() {
			$prefix                 = G5SHOP()->meta_prefix;
			$swatches_enable        = G5SHOP()->options()->get_option( 'swatches_enable' );
			$custom_swatches_enable = get_post_meta( get_the_ID(), "{$prefix}swatches_enable", true );
			if ( $custom_swatches_enable !== '' ) {
				$swatches_enable = $custom_swatches_enable;
			}

			return $swatches_enable === 'on';
		}

		public function swatches_loop() {
			$swatches_enable = $this->get_swatches_enable();
			if ( ! $swatches_enable ) {
				return;
			}
			global $product;
			if ( ! $product->is_type( 'variable' ) ) {
				return;
			}
			$available_variations = $product->get_available_variations();
			$variation_attributes = $product->get_variation_attributes();
			$args                 = array(
				'available_variations' => $available_variations,
				'variation_attributes' => $variation_attributes,
			);
			G5SHOP()->get_template( 'loop/swatches.php', $args );
		}

		public function swatches_single() {
			$swatches_enable = $this->get_swatches_enable();
			if ( ! $swatches_enable ) {
				woocommerce_variable_add_to_cart();
			} else {
				global $product;

				// Enqueue variation scripts.
				wp_enqueue_script( 'wc-add-to-cart-variation' );

				// Get Available variations?
				$get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );


				G5SHOP()->get_template( 'single/swatches.php', array(
					'available_variations' => $get_variations ? $product->get_available_variations() : false,
					'attributes'           => $product->get_variation_attributes(),
					'selected_attributes'  => $product->get_default_attributes(),
				) );
			}
		}

	}
}