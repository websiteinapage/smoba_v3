<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public $components = array(
        // Include Debug Kit Toolbar
        // 'DebugKit.Toolbar',
        'Session',
        'Gatekeeper',
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'User'=>'Member',
                    'fields'=> array(
                        'username' => 'email'
                    )
                ),
                'Openid'=>array(
                    'User'=>'Member',
                    'fields'=>array(
                        'username'=>'id',
                        'password'=>'verified_token'
                    )
                )
            ),
            //'loginRedirect' => array('controller' => 'pages', 'action' => 'display', 'welcome'),
            'loginRedirect' => array('controller' => 'users', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'index'),
            'authorize' => array('Controller') // Added this line
        )
    );
    
    /** Define Role Lists **/
    public $allRoles = array(
        "admin"=>"System Administrator", 
        "vendor"=>"Product Vendor", 
        "manager"=>"Application Manager",
        "user"=>"User (Default)",
        "testeradmin"=>"Testing (Admin)",
        "testermgr"=>"Testing (Manager)",
        "testerusr"=>"Testing (User)"
        );
    public $adminRoles = array("admin","testeradmin");
    //public $managerRoles = array_merge($this->adminRoles, array("vendor","manager"));
    public $managerRoles = array("admin", "vendor", "manager", "testermgr", "testeradmin");
    public $testerAdminRoles = array("testeradmin");
    public $testerManagerRoles = array("testermgr");
    public $multiColorOpts = array("#99FFCC", "#CCCCFF", "#FFCC99", "#CCFFFF", "#CCFFCC", "#FFFF99", "#FFCCFF", "#CCFF99", "#FFFFCC");
    
    public $RoleGroups = array(
        'user','admin','manager'
    );
    private $adminPermissions = array(
                "isAdmin"=>true,
                "isManager"=>true,
                "isUser"=>true
            );
    private $managerPermissions = array(
                "isAdmin"=>false,
                "isManager"=>true,
                "isUser"=>true
            );
    private $userPermissions = array(
                "isAdmin"=>false,
                "isManager"=>false,
                "isUser"=>true
            );
    
    function getUserPermissions() {
        return $this->userPermissions;
    }
    
    /** @uses Authorization array - DO NOT use `role` on the `users` table **/
    function getPermissions( $roleId ) {
        $admin = $this->adminPermissions;
        $manager = $this->managerPermissions;
        $user = $this->userPermissions;
        switch($roleId):
            case 1:
                return $admin;
                break;
            case 2:
                return $manager;
                break;
            case 3:
                return $manager;
                break;
            case 5: 
                return array_merge($admin, array("isTester"=>true));
                break;
            case 6:
                return array_merge($manager, array("isTester"=>true));
                break;
            case 7:
                return array_merge($user, array("isTester"=>true));
                break;
        endswitch;
    }
    
    function beforeFilter() {
        parent::beforeFilter();
        $user = $this->Auth->user();
        $this->set("authUser", $user);
        $permissions = null;
        if(!empty($user['role_id'])):
            $permissions = $this->getPermissions($user['role_id']);
        endif;
        $this->set("permissions", $permissions);
        //print_r($user);
    }    
    
    public function getFileRoot() {
        return "files/";
    }
    
    public function getMapExportRoot() {
        return self::getFileRoot() . "export/";
    }
    
    public function checkIfWeekend() {
        $dow = date("w");
        if(!in_array($dow, array(1,2,3,4,5))):
            return WEEKEND_MESSAGE;
        else:
            return "";
        endif;
    }

    /** @TODO improve for checking authorization against role part **/
    public function isAuthorized($user) {

        $this->set('authUser', $this->Auth->user());
        
        // Admin can access every action
        if (isset($user['role']) && $user['role']==="admin") {
            return true;
        } 
        
        // Default deny
        return false;
    }
    
    public function getAppPath() {
        return "http://" . $_SERVER['HTTP_HOST'] . $this->webroot;
    }

    public function rgb2hex($rgb) {
       if (empty($rgb) || count($rgb)<3) {
           $rgb = array(0,0,0);
       }
       $hex = "#";
       $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
       $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
       $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

       return $hex; // returns the hex value including the number sign (#)
    }

    public function hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       //return implode(",", $rgb); // returns the rgb values separated by commas
       return $rgb; // returns an array with the rgb values
    }    
    
    public function getTextColor($rgb) {
        if ($rgb[0]>100 && $rgb[2]>100):
            $textcolor="#000";
        else:
            $textcolor="#fff";
        endif;
        return $textcolor;
    }
    
    public function clean( $data ) {
        // TODO Take actions to prevent injection here
        $data = addslashes($data);
        return str_replace("'", "''", $data);
    }

//    public function rand_str($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
//        // Length of character list
//        $chars_length = (strlen($chars) - 1);
//
//        // Start our string
//        $string = $chars{rand(0, $chars_length)};
//
//        // Generate random string
//        for ($i = 1; $i < $length; $i = strlen($string))
//        {
//            // Grab a random character from our list
//            $r = $chars{rand(0, $chars_length)};
//
//            // Make sure the same two characters don't appear next to each other
//            if ($r != $string{$i - 1}) $string .=  $r;
//        }
//
//        // Return the string
//        return $string;
//    }    
    
    
}
