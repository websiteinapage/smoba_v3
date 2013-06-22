<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Role
 *
 * @author uchilaka
 */
class Role extends AppModel {
    
    // database configuration with User info
    var $useDbConfig = 'auth_db';	
    //put your code here
    public $hasMany = array(
        'User'=>array(
            'className'=>'User',
            'foreignKey'=>'role_id'
        )
    );
    
    public $displayField = 'name';
    public $primaryKey = 'id';
    
    public $validate = array(
        'name'=>array(
            'rule'=>'notEmpty'
        ),
        'groupmap'=>array(
            'rule'=>'/^((admin|manager|user|test)(\|)?)+$/',
            'required'=>true,
            'message'=>'You must enter a string that matches [rolemap]|[rolemap2]... Valid role maps are user, manager, test and admin'
        ),
        'active'=>array(
            'rule'=>'notEmpty'
        )
    );
}

?>
