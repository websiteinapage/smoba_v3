<?php
class Openid extends AppModel {
    // database configuration with User info
    var $useDbConfig = 'auth_db';	
    
    public $belongsTo = array(
        'User'=>array(
            'className'=>'User',
            'foreignKey'=>'user_id'
        )
    );
        
}

?>
