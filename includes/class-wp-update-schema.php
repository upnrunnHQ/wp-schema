<?php
class WP_Update_Schema {

    private $table_name;
    private $schema;

    public function __construct($table_name, $schema) {
        global $wpdb;

        $this->table_name = $wpdb->prefix . $table_name; // Add the WordPress table prefix
        $this->schema = $schema;

        $this->update_table();
    }

    private function update_table() {
        global $wpdb;

        // Get existing table structure
        $existing_columns = $this->get_existing_columns();
        if (!$existing_columns) {
            error_log("Table `{$this->table_name}` does not exist. Skipping update.");
            return; // Exit if the table does not exist
        }

        $alter_queries = [];
        $is_schema_changed = false;

        // Check for new or updated columns
        foreach ($this->schema['columns'] as $column_name => $details) {
            if (isset($existing_columns[$column_name])) {
                // Check if the column needs to be modified
                $existing_column = $existing_columns[$column_name];
                $new_column_sql = $this->normalize_column_definition($details);

                // Compare the columns ignoring case
                if (strtoupper($existing_column) !== strtoupper($new_column_sql)) {
                    $alter_queries[] = "MODIFY COLUMN `{$column_name}` {$new_column_sql}";
                    $is_schema_changed = true;
                }
            } else {
                // Add new column
                $alter_queries[] = "ADD COLUMN `{$column_name}` {$this->normalize_column_definition($details)}";
                $is_schema_changed = true;
            }
        }

        // Check for removed columns
        foreach ($existing_columns as $column_name => $definition) {
            if (!isset($this->schema['columns'][$column_name])) {
                $alter_queries[] = "DROP COLUMN `{$column_name}`";
                $is_schema_changed = true;
            }
        }

        // Apply index changes
        if (!empty($this->schema['indexes'])) {
            foreach ($this->schema['indexes'] as $index_name => $index_details) {
                if (!$this->index_exists($index_name)) {
                    $index_columns = implode(', ', array_map(fn($col) => "`{$col}`", $index_details['columns']));
                    $alter_queries[] = "ADD INDEX `{$index_name}` ({$index_columns})";
                    $is_schema_changed = true;
                } else {
                    error_log("Index `{$index_name}` already exists on `{$this->table_name}`. Skipping.");
                }
            }
        }

        // Execute alter queries only if there is a change
        if ($is_schema_changed) {
            foreach ($alter_queries as $query) {
                $alter_query = "ALTER TABLE `{$this->table_name}` {$query}";
                $result = $wpdb->query($alter_query);
                if ($result === false) {
                    error_log("Failed to execute query: {$alter_query}");
                } else {
                    error_log("Executed: {$alter_query}");
                }
            }

            error_log("Table `{$this->table_name}` updated successfully.");
        } else {
            error_log("No schema changes detected for table `{$this->table_name}`.");
        }
    }

    private function get_existing_columns() {
        global $wpdb;

        $results = $wpdb->get_results("DESCRIBE `{$this->table_name}`", ARRAY_A);
        if (empty($results)) {
            return null;
        }

        $columns = [];
        foreach ($results as $column) {
            $columns[$column['Field']] = strtoupper("{$column['Type']} "
                . ($column['Null'] === 'NO' ? 'NOT NULL' : 'NULL')
                . ($column['Default'] !== null ? " DEFAULT '{$column['Default']}'" : '')
                . ($column['Extra'] ? " {$column['Extra']}" : ''));
        }

        return $columns;
    }

    private function normalize_column_definition($details) {
        // Normalize the column definition and convert to uppercase to ignore case differences
        return strtoupper("{$details['type']} {$details['attributes']}");
    }

    private function index_exists($index_name) {
        global $wpdb;

        $result = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(1) 
                 FROM INFORMATION_SCHEMA.STATISTICS 
                 WHERE TABLE_SCHEMA = %s 
                 AND TABLE_NAME = %s 
                 AND INDEX_NAME = %s",
                DB_NAME,
                $this->table_name,
                $index_name
            )
        );

        return $result > 0;
    }
}