<?php
/**
 * Core Model API
 *
 * @package WP_Schema
 * @subpackage Model
 */

//
// Model Type registration.
//

/**
 * Registers a model type.
 *
 * Note: Post type registrations should not be hooked before the
 * {@see 'init'} action. Also, any taxonomy connections should be
 * registered via the `$taxonomies` argument to ensure consistency
 * when hooks such as {@see 'parse_query'} or {@see 'pre_get_posts'}
 * are used.
 *
 * Post types can support any number of built-in core features such
 * as meta boxes, custom fields, post thumbnails, post statuses,
 * comments, and more. See the `$supports` argument for a complete
 * list of supported features.
 *
 * @return WP_Model_Type|WP_Error The registered model type object on success,
 *                               WP_Error object on failure.
 */
function register_model_type( $model_type, $args = array() ) {
    global $wp_model_types;

    if ( ! is_array( $wp_model_types ) ) {
        $wp_model_types = array();
    }

    // Sanitize post type name.
    $model_type = sanitize_key( $model_type );

    if ( empty( $model_type ) || strlen( $model_type ) > 20 ) {
        _doing_it_wrong( __FUNCTION__, __( 'Model type names must be between 1 and 20 characters in length.' ), '4.2.0' );
        return new WP_Error( 'post_type_length_invalid', __( 'Model type names must be between 1 and 20 characters in length.' ) );
    }
    

    $model_type_object = new WP_Model_Type( $model_type, $args );

    $model_type_object->add_supports();
    $model_type_object->add_rewrite_rules();
    $model_type_object->register_meta_boxes();

    $wp_model_types[ $model_type ] = $model_type_object;

    $model_type_object->add_hooks();
    $model_type_object->register_taxonomies();

    /**
     * Fires after a post type is registered.
     *
     * @since 3.3.0
     * @since 4.6.0 Converted the `$model_type` parameter to accept a `WP_Post_Type` object.
     *
     * @param string       $model_type        Post type.
     * @param WP_Post_Type $post_type_object Arguments used to register the post type.
     */
    do_action( 'registered_model_type', $model_type, $model_type_object );

    /**
     * Fires after a specific model type is registered.
     *
     * The dynamic portion of the filter name, `$model_type`, refers to the post type key.
     *
     * Possible hook names include:
     *
     *  - `registered_post_type_post`
     *  - `registered_post_type_page`
     *
     * @since 6.0.0
     *
     * @param string       $model_type        Model type.
     * @param WP_Post_Type $post_type_object Arguments used to register the post type.
     */
    do_action( "registered_model_type_{$model_type}", $model_type, $model_type_object );

    return $model_type_object;
}


/**
 * Unregisters a post type.
 *
 * Cannot be used to unregister built-in post types.
 *
 * @since 4.5.0
 *
 * @global array $wp_post_types List of post types.
 *
 * @param string $post_type Post type to unregister.
 * @return true|WP_Error True on success, WP_Error on failure or if the post type doesn't exist.
 */
function unregister_model_type( $model_type ) {
    global $wp_model_types;

    if ( ! post_type_exists( $post_type ) ) {
        return new WP_Error( 'invalid_post_type', __( 'Invalid post type.' ) );
    }

    $post_type_object = get_post_type_object( $post_type );

    // Do not allow unregistering internal post types.
    if ( $post_type_object->_builtin ) {
        return new WP_Error( 'invalid_post_type', __( 'Unregistering a built-in post type is not allowed' ) );
    }

    $post_type_object->remove_supports();
    $post_type_object->remove_rewrite_rules();
    $post_type_object->unregister_meta_boxes();
    $post_type_object->remove_hooks();
    $post_type_object->unregister_taxonomies();

    unset( $wp_post_types[ $post_type ] );

    /**
     * Fires after a post type was unregistered.
     *
     * @since 4.5.0
     *
     * @param string $post_type Post type key.
     */
    do_action( 'unregistered_post_type', $post_type );

    return true;
}

/**
 * Registers support of certain features for a post type.
 *
 * All core features are directly associated with a functional area of the edit
 * screen, such as the editor or a meta box. Features include: 'title', 'editor',
 * 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes',
 * 'thumbnail', 'custom-fields', and 'post-formats'.
 *
 * Additionally, the 'revisions' feature dictates whether the post type will
 * store revisions, the 'autosave' feature dictates whether the post type
 * will be autosaved, and the 'comments' feature dictates whether the comments
 * count will show on the edit screen.
 *
 * A third, optional parameter can also be passed along with a feature to provide
 * additional information about supporting that feature.
 *
 * Example usage:
 *
 *     add_post_type_support( 'my_post_type', 'comments' );
 *     add_post_type_support( 'my_post_type', array(
 *         'author', 'excerpt',
 *     ) );
 *     add_post_type_support( 'my_post_type', 'my_feature', array(
 *         'field' => 'value',
 *     ) );
 *
 * @since 3.0.0
 * @since 5.3.0 Formalized the existing and already documented `...$args` parameter
 *              by adding it to the function signature.
 *
 * @global array $_wp_post_type_features
 *
 * @param string       $post_type The post type for which to add the feature.
 * @param string|array $feature   The feature being added, accepts an array of
 *                                feature strings or a single string.
 * @param mixed        ...$args   Optional extra arguments to pass along with certain features.
 */
function add_model_type_support( $model_type, $feature, ...$args ) {
    global $_wp_model_type_features;

    $features = (array) $feature;
    foreach ( $features as $feature ) {
        if ( $args ) {
            $_wp_model_type_features[ $model_type ][ $feature ] = $args;
        } else {
            $_wp_model_type_features[ $model_type ][ $feature ] = true;
        }
    }
}