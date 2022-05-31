<?php 
function handleAdditionalSettings($additionalDSHClassName){
    
            global $wpdb;
            $table_name = $wpdb->prefix.'AdditionalDSH';
            $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name");
            //!empty($additionalDSHClassName) ? : die('ERROR: Name is required yolo');

          

            if($count > 0) {
                global $wpdb;
                $table_name = $wpdb->prefix.'AdditionalDSH';
                $format = array('%s','%s');
                $where = [ 'As_id' => '1' ];

                if(!isset($_POST['enableAdditionalDynamicStickyHeaderSettings'])) {
                    $data = array(
                        'Additional_Header_On_Off' => "false",
                        'Additional_Header_Value' => "");
                        $wpdb->update($table_name,$data,$where,$format);
                } else {
                    $data = array(
                        'Additional_Header_On_Off' => "true",
                        'Additional_Header_Value' => "$additionalDSHClassName");
                    $wpdb->update($table_name,$data,$where,$format);
                }
             
                      
            } 
            
                 
            if($count < 1) {
                global $wpdb;
                $table_name = $wpdb->prefix.'AdditionalDSH';
                $format = array('%d','%s','%s');
                $where = [ 'As_id' => '1' ];

                if(!isset($_POST['enableAdditionalDynamicStickyHeaderSettings'])) {
                    $data = array(
                        'Additional_Header_On_Off' => "false",
                        'Additional_Header_Value' => "");
                        $wpdb->insert($table_name,$data,$where,$format);
                } else {

                    
                $data = array(
                    'As_id' => '1',
                    'Additional_Header_On_Off' => "true",
                    'Additional_Header_Value' => "$additionalDSHClassName");
                    $wpdb->insert($table_name,$data,$format);  


                }

            }
    
}

?>