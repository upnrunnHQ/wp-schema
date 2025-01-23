<?php
/**
 * Query API: WP_Model_Query class
 *
 * @package WordPress
 * @subpackage Query
 * @since 4.7.0
 */

/**
 * The WordPress Query class.
 *
 * @link https://developer.wordpress.org/reference/classes/wp_query/
 *
 * @since 1.5.0
 * @since 4.5.0 Removed the `$comments_popup` property.
 */
#[AllowDynamicProperties]
class WP_Model_Query {

    // Query args
    private $args = [];

    // Query results (models)
    public $models = [];

    // Current model index
    private $current_model_index = 0;

    // Total models found
    public $found_models = 0;

    // Query flags
    private $is_404 = false;

    // Queried object
    private $queried_object = null;

    /**
     * Constructor for the WP_Model_Query class.
     *
     * @param array $args Query arguments.
     */
    public function __construct( $args = [] ) {
        $this->init();
        $this->query( $args );
    }

    /**
     * Initialize the query.
     */
    public function init() {
        $this->models = [];
        $this->current_model_index = 0;
        $this->found_models = 0;
        $this->is_404 = false;
    }

    /**
     * Parse query vars and fill defaults.
     *
     * @param array $query_vars Query variables.
     * @return array Parsed query variables.
     */
    public function parse_query_vars( $query_vars ) {
        $defaults = [
            'model_type'     => [], // Specify the model type(s)
            'posts_per_page' => 10,
            'offset'         => 0,
            'where'          => '',
            'orderby'        => 'id',
            'order'          => 'ASC',
        ];
        return wp_parse_args( $query_vars, $defaults );
    }

    /**
     * Fill query vars with default values.
     *
     * @param array $query_vars Query variables.
     * @return array
     */
    public function fill_query_vars( $query_vars ) {
        return $this->parse_query_vars( $query_vars );
    }

    /**
     * Parse the query and execute it.
     *
     * @param array|string $query Query args or SQL.
     * @return $this
     */
    public function query( $query ) {
        $this->args = is_array( $query ) ? $this->fill_query_vars( $query ) : [];
        $this->get_models();
        return $this;
    }

    /**
     * Fetch models based on the query arguments.
     */
    public function get_models() {
        global $wpdb;

        $model_types = (array) $this->args['model_type'];
        if ( empty( $model_types ) ) {
            $this->is_404 = true;
            return;
        }

        foreach ( $model_types as $model_type ) {
            $table_name = $wpdb->prefix . $model_type . 's'; // Assuming plural table names.
            $where_clause = $this->args['where'] ? 'WHERE ' . $this->args['where'] : '';
            $orderby_clause = "ORDER BY {$this->args['orderby']} {$this->args['order']}";
            $limit_clause = "LIMIT {$this->args['posts_per_page']} OFFSET {$this->args['offset']}";

            // SQL Query
            $query = "SELECT * FROM {$table_name} {$where_clause} {$orderby_clause} {$limit_clause}";

            $results = $wpdb->get_results( $query );
            if ( ! empty( $results ) ) {
                $this->models = array_merge( $this->models, $results );
            }
        }

        $this->found_models = count( $this->models );
        $this->is_404 = $this->found_models === 0;
    }

    /**
     * Check if there are more models to loop through.
     *
     * @return bool
     */
    public function have_models() {
        return $this->current_model_index < $this->found_models;
    }

    /**
     * Move to the next model in the loop.
     *
     * @return object|false The current model or false if none.
     */
    public function the_model() {
        if ( $this->have_models() ) {
            $model = $this->models[ $this->current_model_index ];
            $this->current_model_index++;
            return $model;
        }
        return false;
    }

    /**
     * Rewind models to the beginning.
     */
    public function rewind_models() {
        $this->current_model_index = 0;
    }

    /**
     * Reset the query.
     */
    public function reset_modeldata() {
        $this->init();
    }

    /**
     * Get a query variable.
     *
     * @param string $query_var Query variable name.
     * @param mixed  $default_value Default value.
     * @return mixed
     */
    public function get( $query_var, $default_value = '' ) {
        return isset( $this->args[ $query_var ] ) ? $this->args[ $query_var ] : $default_value;
    }

    /**
     * Set a query variable.
     *
     * @param string $query_var Query variable name.
     * @param mixed  $value Value to set.
     */
    public function set( $query_var, $value ) {
        $this->args[ $query_var ] = $value;
    }

    /**
     * Get the queried object.
     *
     * @return mixed The queried object.
     */
    public function get_queried_object() {
        return $this->queried_object;
    }

    /**
     * Get the ID of the queried object.
     *
     * @return int|null The ID or null if not set.
     */
    public function get_queried_object_id() {
        return isset( $this->queried_object->id ) ? $this->queried_object->id : null;
    }

    /**
     * Check if the query resulted in a 404.
     *
     * @return bool True if 404, false otherwise.
     */
    public function is_404() {
        return $this->is_404;
    }
}