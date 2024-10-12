# wp-schema
WP Schema: Register different Models/DB in WordPress

Client Code\
+++++++++++++++++++++++\
register_model_type( 'book', $args );\
register_component_type( 'faq', $args );\

Core class used to implement the Content object like WP_Post object.\
+++++++++++++++++++++++\
WP_Model_Content() {}\
WP_Component_Content() {}\

Core class used for interacting with Schema like WP_Post_Type.\
+++++++++++++++++++++++\
WP_Model_Type() {}\
WP_Component_Type() {}\
WP_Database_Table() {}\

