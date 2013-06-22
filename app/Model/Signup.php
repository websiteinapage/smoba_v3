<?php
class Signup extends AppModel {
    // database configuration with User info
    var $useDbConfig = 'auth_db';	
    
    public $belongsTo = array(
        'User'=>array(
            'className'=>'User',
            'foreignKey'=>'user_id'
        ),
        'Application'=>array(
            'className'=>'Application',
            'foreignKey'=>'application_id'
        )
    );
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
