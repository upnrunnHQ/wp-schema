# wp-schema
WP Schema: Register different Models/DB in WordPress

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
    $max_index_length = 191;

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
            'id' => [
                'type' => 'BIGINT(20) UNSIGNED',
                'attributes' => 'NOT NULL AUTO_INCREMENT',
                'primary' => true,
            ],
            'isbn' => [
                'type' => 'TINYTEXT',
                'attributes' => 'NOT NULL',
            ],
            'title' => [
                'type' => 'MEDIUMTEXT',
                'attributes' => 'NOT NULL',
            ],
            'author' => [
                'type' => 'MEDIUMTEXT',
                'attributes' => 'NOT NULL',
            ],
            'date_created' => [
                'type' => 'DATETIME',
                'attributes' => 'NOT NULL',
            ],
            'date_published' => [
                'type' => 'DATETIME',
                'attributes' => 'NOT NULL',
            ],
        ],
        'primary_key' => ['id'],
        'indexes' => [
            'isbn_index' => [
                'columns' => ['isbn'],
                'unique' => true,
            ],
        ],
        'foreign_keys' => [
            'fk_example' => [
                'columns' => ['author'],
                'referenced_table' => 'authors',
                'referenced_columns' => ['id'],
                'on_delete' => 'CASCADE',
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
