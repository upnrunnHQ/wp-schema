<?php
// Example

/**
 * Register a custom post type called "book".
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
		'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
		'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
		'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
		'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
	);

	$schema = [
	    'columns' => [
	        'id' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => 'NOT NULL AUTO_INCREMENT',
	            'primary' => true,  // Primary Key
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
	    'primary_key' => ['id'], // Define primary key columns
	    'indexes' => [
	        'isbn_index' => [
	            'columns' => ['isbn'],
	            'unique' => true,  // Optional: Set if it's a unique index
	        ]
	    ],
	    'foreign_keys' => [
	        'fk_example' => [
	            'columns' => ['author'],  // Column(s) in this table
	            'referenced_table' => 'authors',  // Referenced table
	            'referenced_columns' => ['id'],  // Referenced column(s) in the referenced table
	            'on_delete' => 'CASCADE',  // On delete behavior (CASCADE, RESTRICT, etc.)
	        ],
	    ],
	];

	$schema = [
	    'columns' => [
	        'ID' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => 'NOT NULL AUTO_INCREMENT',
	            'primary' => true,
	        ],
	        'post_author' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	        'post_date' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_date_gmt' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_content' => [
	            'type' => 'LONGTEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_title' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_excerpt' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_status' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'publish'",
	        ],
	        'comment_status' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'open'",
	        ],
	        'ping_status' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'open'",
	        ],
	        'post_password' => [
	            'type' => 'VARCHAR(255)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'post_name' => [
	            'type' => 'VARCHAR(200)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'to_ping' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'pinged' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_modified' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_modified_gmt' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_content_filtered' => [
	            'type' => 'LONGTEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_parent' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	        'guid' => [
	            'type' => 'VARCHAR(255)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'menu_order' => [
	            'type' => 'INT(11)',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	        'post_type' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'post'",
	        ],
	        'post_mime_type' => [
	            'type' => 'VARCHAR(100)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'comment_count' => [
	            'type' => 'BIGINT(20)',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	    ],
	    'primary_key' => ['ID'],
	    'indexes' => [
	        'post_name_index' => [
	            'columns' => ['post_name($max_index_length)'],
	        ],
	        'type_status_date_index' => [
	            'columns' => ['post_type', 'post_status', 'post_date', 'ID'],
	        ],
	        'post_parent_index' => [
	            'columns' => ['post_parent'],
	        ],
	        'post_author_index' => [
	            'columns' => ['post_author'],
	        ],
	    ],
	];

	$args = array(
		'labels'             => $labels,
		'schema'			 => $schema,
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



/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_job_init() {
	$max_index_length = 191;
	$labels = array(
		'name'                  => _x( 'Jobs', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Job', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Jobs', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Job', 'Add New on Toolbar', 'textdomain' ),
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
		'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
		'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
		'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
		'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
		'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
		'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
	);

	$schema = [
	    'columns' => [
	        'id' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => 'NOT NULL AUTO_INCREMENT',
	            'primary' => true,  // Primary Key
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
	    'primary_key' => ['id'], // Define primary key columns
	    'indexes' => [
	        'isbn_index' => [
	            'columns' => ['isbn'],
	            'unique' => true,  // Optional: Set if it's a unique index
	        ]
	    ],
	    'foreign_keys' => [
	        'fk_example' => [
	            'columns' => ['author'],  // Column(s) in this table
	            'referenced_table' => 'authors',  // Referenced table
	            'referenced_columns' => ['id'],  // Referenced column(s) in the referenced table
	            'on_delete' => 'CASCADE',  // On delete behavior (CASCADE, RESTRICT, etc.)
	        ],
	    ],
	];

	$schema = [
	    'columns' => [
	        'ID' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => 'NOT NULL AUTO_INCREMENT',
	            'primary' => true,
	        ],
	        'post_author' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	        'post_date' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_date_gmt' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_content' => [
	            'type' => 'LONGTEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_title' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_excerpt' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_status' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'publish'",
	        ],
	        'comment_status' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'open'",
	        ],
	        'ping_status' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'open'",
	        ],
	        'post_password' => [
	            'type' => 'VARCHAR(255)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'post_name' => [
	            'type' => 'VARCHAR(200)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'to_ping' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'pinged' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_modified' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_modified_gmt' => [
	            'type' => 'DATETIME',
	            'attributes' => "NOT NULL DEFAULT '0000-00-00 00:00:00'",
	        ],
	        'post_content_filtered' => [
	            'type' => 'LONGTEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'post_parent' => [
	            'type' => 'BIGINT(20) UNSIGNED',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	        'guid' => [
	            'type' => 'VARCHAR(255)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'menu_order' => [
	            'type' => 'INT(11)',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	        'post_type' => [
	            'type' => 'VARCHAR(20)',
	            'attributes' => "NOT NULL DEFAULT 'post'",
	        ],
	        'post_mime_type' => [
	            'type' => 'VARCHAR(100)',
	            'attributes' => "NOT NULL DEFAULT ''",
	        ],
	        'comment_count' => [
	            'type' => 'BIGINT(20)',
	            'attributes' => "NOT NULL DEFAULT '0'",
	        ],
	    ],
	    'primary_key' => ['ID'],
	    'indexes' => [
	        'post_name_index' => [
	            'columns' => ['post_name($max_index_length)'],
	        ],
	        'type_status_date_index' => [
	            'columns' => ['post_type', 'post_status', 'post_date', 'ID'],
	        ],
	        'post_parent_index' => [
	            'columns' => ['post_parent'],
	        ],
	        'post_author_index' => [
	            'columns' => ['post_author'],
	        ],
	    ],
	];

	$args = array(
		'labels'             => $labels,
		'schema'			 => $schema,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'job' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_model_type( 'job', $args );
}

add_action( 'init', 'wpdocs_codex_job_init' );



/**
 * Register a custom post type called "Book".
 *
 * @see get_post_type_labels() for label keys.
 */
function wpdocs_codex_movie_init() {
    $labels = array(
        'name'                  => _x( 'Movies', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Movie', 'Post type singular name', 'textdomain' ),
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
	        'model_title' => [
	            'type' => 'TEXT',
	            'attributes' => 'NOT NULL',
	        ],
	        'model_content' => [
	            'type' => 'LONGTEXT',
	            'attributes' => 'NOT NULL',
	        ],

	    ],
	    'primary_key' => ['ID'],
	    'indexes' => [
	        'model_author_index' => [
	            'columns' => ['model_author'],
	        ],
	        // 'model_date_index' => [
	        //     'columns' => ['model_date'],
	        // ],
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
        'rewrite'            => array( 'slug' => 'movie' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );

    register_model_type( 'movie', $args );
}

add_action( 'init', 'wpdocs_codex_movie_init' );



// Set up the query arguments for multiple model types
$args = [
    'model_type'     => [ 'movie', 'book' ],  // multiple model types
    'posts_per_page' => 10,
    'offset'         => 0,
    //'where'          => 'status = "active"',
];

// The Query.
$the_query = new WP_Model_Query( $args );

// The Loop.
if ( $the_query->have_models() ) {
    echo '<ul>';
    while ( $model = $the_query->the_model() ) {
        // Assuming each model has a 'title' property
        //echo '<li>' . esc_html( $model->model_title ) . '</li>';
		echo "<pre>";
		print_r($model); 
		echo "</pre>";
    }
    echo '</ul>';
} else {
    esc_html_e( 'Sorry, no models matched your criteria.' );
}

// Reset model data after the loop.
$the_query->reset_modeldata();