<?php

function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}

	return $classes;
}

add_filter( 'body_class', 'add_slug_body_class' );

function le( $selector, $post = '' ) {
	if ( $post == '' ) {
		$post = get_the_id();
	}
	if ( function_exists( 'live_edit' ) ) {
		live_edit( $selector, $post );
	}
}

function tf( $selector, $post = '' ) {
	if ( $post == '' ) {
		$post = get_the_id();
	}
	if ( function_exists( 'the_field' ) ) {
		the_field( $selector, $post );
	}
}

function gf( $selector, $post = '' ) {
	if ( $post == '' ) {
		$post = get_the_id();
	}
	if ( function_exists( 'get_field' ) ) {
		return ( get_field( $selector, $post ) );
	}
}

function theme( $echo = true ) {
	$dir = get_template_directory_uri();
	if ( $echo ) {
		echo $dir;
	}

	return $dir;
}

function js_variables() {
	$post_friendly = [ 272, 273, 274, 275 ];
	$variables     = [
		'admin_ajax_url' => admin_url( 'admin-ajax.php' ),
		'page_id'        => get_the_id()
	];
	if ( in_array( get_the_id(), $post_friendly ) ) {
		$variables['post'] = $_POST ?: [];
	}
	echo '<script type="text/javascript">window.wp_data = ' . json_encode( $variables ) . ';</script>';
}

add_action( 'wp_head', 'js_variables' );

function pre( $input ) {
	echo( '<pre>' );
	print_r( $input );
	echo( '</pre>' );
}

function get_client_ip() {
	if ( getenv( 'HTTP_CLIENT_IP' ) ) {
		$ip = getenv( 'HTTP_CLIENT_IP' );
	} else if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
		$ip = getenv( 'HTTP_X_FORWARDED_FOR' );
	} else if ( getenv( 'HTTP_X_FORWARDED' ) ) {
		$ip = getenv( 'HTTP_X_FORWARDED' );
	} else if ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
		$ip = getenv( 'HTTP_FORWARDED_FOR' );
	} else if ( getenv( 'HTTP_FORWARDED' ) ) {
		$ip = getenv( 'HTTP_FORWARDED' );
	} else if ( getenv( 'REMOTE_ADDR' ) ) {
		$ip = getenv( 'REMOTE_ADDR' );
	} else {
		$ip = 'UNKNOWN';
	}

	return $ip;
}

function va_get_template_part( $file, $template_args = [], $return = false, $cache_args = [] ) {
	$template_args = wp_parse_args( $template_args );
	$cache_args    = wp_parse_args( $cache_args );
	if ( $cache_args ) {
		foreach ( $template_args as $key => $value ) {
			if ( is_scalar( $value ) || is_array( $value ) ) {
				$cache_args[ $key ] = $value;
			} else if ( is_object( $value ) && method_exists( $value, 'get_id' ) ) {
				$cache_args[ $key ] = call_user_func( 'get_id', $value );
			}
		}
		if ( ( $cache = wp_cache_get( $file, serialize( $cache_args ) ) ) !== false ) {
			if ( $return ) {
				return $cache;
			}
			echo $cache;

			return $cache;
		}
	}

	if ( file_exists( get_stylesheet_directory() . '/' . $file . '.php' ) ) {
		$file = get_stylesheet_directory() . '/' . $file . '.php';
	} elseif ( file_exists( get_template_directory() . '/' . $file . '.php' ) ) {
		$file = get_template_directory() . '/' . $file . '.php';
	}
	ob_start();
	{
		extract( $template_args );
		$require = require( $file );
	}
	$data = ob_get_clean();

	if ( $cache_args ) {
		wp_cache_set( $file, $data, serialize( $cache_args ), 3600 );
	}
	if ( $return ) {
		if ( $require === false ) {
			return false;
		} else {
			return $data;
		}
	}
	echo $data;

	return $data;
}

function va_validate_card( $number ) {
	$len     = strlen( $number );
	$mul     = 0;
	$prodArr = [ [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ], [ 0, 2, 4, 6, 8, 1, 3, 5, 7, 9 ] ];
	$sum     = 0;

	while ( $len -- ) {
		$sum += $prodArr[ $mul ][ (int) $number[ $len ] ];
		$mul ^= 1;
	}

	return $sum % 10 === 0 && $sum > 0;
}

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page();
}