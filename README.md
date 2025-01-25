# wp-schema (WIP)
WP Schema: Register different Models in the WordPress Database. 

Client Code\
+++++++++++++++++++++++\
register_model_type( 'book', $args );\
register_component_type( 'faq', $args );

Core class used to implement the Content object like WP_Post object.\
+++++++++++++++++++++++\
WP_Model() {}\
WP_Component() {}

Core class used for interacting with Schema like WP_Post_Type.\
+++++++++++++++++++++++\
WP_Model_Type() {}\
WP_Component_Type() {}\
WP_Create_Schema() {}

Core class used for interacting with Schema like WP_Query.\
+++++++++++++++++++++++\
WP_Model_Query

=========================
# Custom Post Type: Book

This WordPress plugin registers a custom post type called **Book**, along with its associated schema and features. The schema defines a custom database table structure for managing book data effectively.

## Features

- Custom post type: **Book**.
- Custom schema for database table creation with primary key, indexes, and foreign key constraints.
- Supports WordPress features such as titles, editors, authors, thumbnails, excerpts, and comments.
- Fully integrated with WordPress UI, including admin menus and toolbars.

## Usage

To use this custom post type, add the following code to your WordPress plugin or theme's `functions.php` file.

### Code

```php
/**
 * Register a custom post type called "Book".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_book_init() {
    $labels = array(
        'name'                  => _x( 'Books', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Book', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Books', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Book', 'textdomain' ),
        'new_item'              => __( 'New Book', 'textdomain' ),
        'edit_item'             => __( 'Edit Book', 'textdomain' ),
        'view_item'             => __( 'View Book', 'textdomain' ),
        'all_items'             => __( 'All Books', 'textdomain' ),
        'search_items'          => __( 'Search Books', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
        'not_found'             => __( 'No books found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No books found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase', 'textdomain' ),
        'archives'              => _x( 'Book archives', 'The post type archive label', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post” phrase', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post” phrase', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter books list', 'Screen reader text', 'textdomain' ),
        'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text', 'textdomain' ),
        'items_list'            => _x( 'Books list', 'Screen reader text', 'textdomain' ),
    );

    $schema = [
        'columns' => [
            'ID' => [
                'type' => 'BIGINT(20) UNSIGNED',
                'attributes' => 'NOT NULL AUTO_INCREMENT',
                'primary' => true,
            ],
            'model_author' => [
                'type' => 'BIGINT(20) UNSIGNED',
                'attributes' => "NOT NULL DEFAULT '0'",
            ],
            'model_date' => [
                'type' => 'DATETIME',
                'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
            ],
            'model_content' => [
                'type' => 'LONGTEXT',
                'attributes' => 'NOT NULL',
            ],
            'model_title' => [
                'type' => 'TEXT',
                'attributes' => 'NOT NULL',
            ],
        ],
        'primary_key' => ['ID'],
        'indexes' => [
            'model_author_index' => [
                'columns' => ['model_author'],
            ],
            'model_date_index' => [
                'columns' => ['model_date'],
            ],
        ],
    ];
    
    $args = array(
        'labels'             => $labels,
        'schema'             => $schema,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'book' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );

    register_model_type( 'book', $args );
}

add_action( 'init', 'wpdocs_codex_book_init' );


// Set up the query arguments for multiple model types.
$args = [
    'model_type'     => [ 'movie', 'book' ],  // multiple model types
    'posts_per_page' => 10,
    'offset'         => 0,
];

// The Query.
$the_query = new WP_Model_Query( $args );

// The Loop.
if ( $the_query->have_models() ) {
    echo '<ul>';
    while ( $model = $the_query->the_model() ) {
        // Assuming each model has a 'title' property
        echo '<li>' . esc_html( $model->model_title ) . '</li>';
		echo "<pre>";
		print_r($model); 
		echo "</pre>";
    }
    echo '</ul>';
} else {
    esc_html_e( 'Sorry, no models matched your criteria.' ); // if no data added. Please add some data through PHPMYADMIN
}

// Reset model data after the loop.
$the_query->reset_modeldata();
