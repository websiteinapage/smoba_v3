<?php
// app/Model/User.php
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
    
    // database configuration with User info
    var $useDbConfig = 'auth_db';	

    public $hasMany = array(
        'Signup'=>array(
            'className'=>'Signup',
            'foreignKey'=>'user_id'
        )
    );
    
    public $hasOne = array(
        'Openid'=> array(
            'className'=>'Openid',
            'foreignKey'=>'user_id'
        )
    );
    
    public $belongsTo = array(
        'Role'=>array(
            'className'=>'Role',
            'foreignKey'=>'role_id'
        )
    );
    
    public $validate = array(
        'first_name'=> array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'A first name is required. Make sure there are no white spaces.'
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'required' => true,
                'message' => 'A valid email address is required',
                'unique' => true
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email address is already registered.'
            )
        ),
        'last_name' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'A last name is required. Make sure there are no white spaces.'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('between', 5, 15),
                'required' => true,
                'message' => 'A password is required'
            )
        ),
        'role' => array(
                    'valid' => array(
                        'rule' => array('inList', array('admin', 'user')),
                        'message' => 'Please enter a valid role',
                        'allowEmpty' => false
                    )
        )
    );

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }  
    
    public function doUpdateIfNeeded($id = null) {
    }
    
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
