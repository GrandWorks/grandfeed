<?php
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
class GrandFeedDatabase {
    // Table Names
    private  $table_name = "grand_feed";
    private $id = array("name"=> "id", "type"=>"mediumint (255)" );
    private $json_data = array("name"=> "json_data", "type"=>"TEXT");
    private static $instance = null;
    private function __construct(){}

    public static function instantiate()
    {
        if(self::$instance == null)
        {
            self::$instance = new GrandFeedDatabase();
        }

        return self::$instance;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_fields()
    {
        return [$this->id,$this->json_data];
    }

    public function build_create_query()
    {   
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint (255) NOT NULL AUTO_INCREMENT,
            json_data TEXT NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate";
        return $sql;
    }

    public function insert_feeds($feeds)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();
        $result = $wpdb->insert(
            $table_name,
            array(
                'json_data' => json_encode($feeds),
            )
        );
    }

    public function fetch_feeds()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();
            $result = $wpdb->get_row( "select json_data from $table_name order by id DESC limit 1" );
        
        return $result->json_data;
    }

    public function initiate_table()
    {
        global $wpdb;
        $query = $this->build_create_query();
        $result = dbDelta($query);
    }

    public function delete_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->get_table_name();
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);
    }
    
}