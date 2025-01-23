<?php
/**
 * Handles the table creation and schema setup.
 *
 * @since 1.0.0
 */
class WP_Create_Schema {

    /**
     * The name of the table.
     *
     * @var string
     */
    private $table_name;

    /**
     * The schema definition.
     *
     * @var array
     */
    private $schema;

    /**
     * Constructor.
     *
     * @param string $table_name The name of the table to create.
     * @param array  $schema The schema definition.
     */
    public function __construct($table_name, $schema) {
        global $wpdb;

        $this->table_name = $wpdb->prefix . $table_name; // Add the WordPress table prefix
        $this->schema = $schema;

        $this->create_table();
        $this->add_foreign_keys();
    }

    /**
     * Creates the table based on the schema.
     */
    private function create_table() {
        global $wpdb;

        // Check if the table already exists
        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") === $this->table_name) {
            error_log("Table `{$this->table_name}` already exists. Skipping creation.");
            return; // Exit early if the table exists
        }

        $columns_sql = [];
        foreach ($this->schema['columns'] as $column_name => $details) {
            $column_sql = "`{$column_name}` {$details['type']} {$details['attributes']}";
            $columns_sql[] = $column_sql;
        }

        // Add primary keys
        if (!empty($this->schema['primary_key'])) {
            $primary_keys = implode(', ', array_map(fn($col) => "`{$col}`", $this->schema['primary_key']));
            $columns_sql[] = "PRIMARY KEY ({$primary_keys})";
        }

        // Add indexes
        if (!empty($this->schema['indexes'])) {
            foreach ($this->schema['indexes'] as $index_name => $index_details) {
                $index_columns = implode(', ', array_map(function ($col) {
                    // Handle column length for specific cases
                    if (strpos($col, '$max_index_length') !== false) {
                        return str_replace('$max_index_length', '191', $col);
                    }
                    return "`{$col}`";
                }, $index_details['columns']));
                $columns_sql[] = "KEY `{$index_name}` ({$index_columns})";
            }
        }

        $columns_sql_string = implode(",\n", $columns_sql);
        $charset_collate = $wpdb->get_charset_collate();
        $create_table_query = "CREATE TABLE `{$this->table_name}` (\n{$columns_sql_string}\n) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($create_table_query);

        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") === $this->table_name) {
            error_log("Table `{$this->table_name}` created successfully.");
        } else {
            error_log("Failed to create table `{$this->table_name}`.");
        }
    }

    /**
     * Adds foreign keys to the table.
     */
    private function add_foreign_keys() {
	    global $wpdb;

	    if (!isset($this->schema['foreign_keys']) || empty($this->schema['foreign_keys'])) {
	        return; // No foreign keys to add
	    }

	    foreach ($this->schema['foreign_keys'] as $key_name => $foreign_key) {
	        // Check if the foreign key already exists
	        $check_query = $wpdb->prepare(
	            "SELECT CONSTRAINT_NAME 
	             FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
	             WHERE TABLE_NAME = %s 
	             AND CONSTRAINT_NAME = %s 
	             AND TABLE_SCHEMA = %s",
	            $this->table_name,
	            $key_name,
	            $wpdb->dbname
	        );

	        $existing_key = $wpdb->get_var($check_query);

	        if ($existing_key) {
	            error_log("Foreign key `{$key_name}` already exists. Skipping.");
	            continue; // Skip adding the foreign key
	        }

	        $columns = implode(', ', array_map(fn($col) => "`{$col}`", $foreign_key['columns']));
	        $referenced_columns = implode(', ', array_map(fn($col) => "`{$col}`", $foreign_key['referenced_columns']));
	        $query = "
	            ALTER TABLE `{$this->table_name}` 
	            ADD CONSTRAINT `{$key_name}` 
	            FOREIGN KEY ({$columns}) 
	            REFERENCES `{$wpdb->prefix}{$foreign_key['referenced_table']}` ({$referenced_columns}) 
	            ON DELETE {$foreign_key['on_delete']}
	        ";

	        $result = $wpdb->query($query);

	        if ($result === false) {
	            error_log("Failed to add foreign key `{$key_name}` to table `{$this->table_name}`.");
	        } else {
	            error_log("Foreign key `{$key_name}` added successfully to table `{$this->table_name}`.");
	        }
	    }
	}

}