<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersController
 *
 * @author uchilaka
 */
class UsersController extends AppController {
    public $components = array('Session');
    public $helpers = array('Form','Html','Session');
    public $paginate = array(
        'User'=>array(
            'limit' => 25,
            'order' => array('User.created'=>'desc')
        )
    );
    
    //put your code here
    function beforeFilter() {
        parent::beforeFilter();
        // define basic access actions
        $basicAccess = array('logout', 'login', 'forgot');
        $role_list = $this->User->Role->find('all', array(
            'conditions'=>array(
                'Role.active'=>'Y'
            ),
            'fields'=>array('Role.id', 'Role.name'),
            'recursive'=>-1
        ));
        $roles = array();
        foreach($role_list as $role):
            $roles[]=array($role['Role']['id']=>$role['Role']['name']);
        endforeach;
        $this->set('role_list', $roles);
        if($this->Auth->loggedIn()):
            $this->set('authUser', $this->Auth->user());
            // role based permissions
            $permissions = $this->getUserPermissions();
            $roleId = $this->Auth->user("role_id");
            if(!empty($roleId)):
                $permissions = $this->getPermissions($roleId);
            endif;
            if($permissions['isAdmin']):
                $this->Auth->allow(array_merge($basicAccess, array('index', 'manage')));
            else:
                $this->Auth->allow(array_merge($basicAccess, array('index')));
            endif;
            
        else:
            $this->Auth->allow($basicAccess);
        endif;
    }
        
    function login() {
        
        if($this->Auth->loggedIn()):
            $this->redirect(array("controller"=>"users", "action"=>"index"));
        endif;
        
        if($this->request->is("post")):
            if($this->Auth->login()):
                return $this->redirect($this->Auth->redirectUrl());
            else:
                $this->Session->setFlash(__('Username or password is incorrect'), 'flash_badnews');
            endif;
        endif;
    }
    
    function logout() {
        //$this->redirect($this->Auth->logout());
        $this->Auth->logout();
        $this->Session->setFlash("You have logged out successfully.", "flash_goodnews");
        $this->redirect(array("controller"=>"users", "action"=>"login"));
    }
    
    function manage() {
        // use paginate to retrieve user list
        $users = $this->paginate('User');
        $this->set('users', $users);
    }
    
    public function index() {
        // get data for index
    }
    
    public function location( $ip=null ) {
        if(!empty($ip)):
            $this->set('useip', $ip);
        endif;
    }
    
    public function edit($id = null) {
        $saved = false;
        $user = $this->User->findById($id);
        
        if(!empty($user)):
            $this->set('user_role', $user['User']['role_id']);
        endif;
        
        if (($this->request->is('post') || $this->request->is('put')) 
                && !empty($this->request->data)
                && !empty($user)) {
            
            try {
                
                $this->User->set("id", $id);
                if (empty($this->request->data['User']['password'])) {
                    unset($this->request->data['User']['password']);
                }
                $saved = $this->User->save($this->request->data, false);
                
            } catch (Exception $e) {
                //$saved = false;
            }
            
            if ($saved && !empty($this->request->data['User']['password'])) {
                
                // send notification about password change
                $email = new CakeEmail();
                $email->template('default', 'email_layout');
                $email->emailFormat('html');
                $email->to($user['User']['email']);
                $email->from('sentinel@websiteinapage.com','WIAP Support');
                $email->subject('Account change notification');
                // message
                $message = "<strong>Hello!</strong>
                    <p>Your account information ({$user['User']['email']}) was just changed.</p>
                    <p>If you did not request this change, please contact ". ADMIN_EMAIL . "</p>
                    Thank you for using " . APP_NAME ."!" ;
                $email->send($message);
                
                // check if admin
                if($this->Auth->user("role")===$this->RoleGroups[1]):
                    $this->Session->setFlash('The user was saved successfully an has been notified of a password change. Please login with your new password', 'flash_goodnews');
                    $this->redirect(array("controller"=>"users", "action"=>"manage"));
                else:
                    // log out any other user who isn't an admin
                    $this->Session->setFlash('You have been logged out after a password change. Please login with your new password', 'flash_goodnews');
                    $this->Auth->logout();
                    $this->redirect(array('controller'=>'users', 'action'=>'login'));
                endif;
            }
            else if ($saved) {
                $this->Session->setFlash(__('The user has been saved'), 'flash_goodnews');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'flash_badnews');
            }
        } 
        if(!empty($id)):
            $this->request->data = $this->User->findById($id);
        endif;
    }
    
    function forgot() {
        if ($this->request->is('post')) {
            $email = $this->request->data['User']['email'];
            // re-send access details
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.email' => $email
                )
            ));
            if (!empty($user)) {
                $newpw = $this->User->randomString(8);
                $data = array(
                    'User' => array(
                        'password' => $newpw
                    )
                );
                //print_r($user);
                $this->User->read(null, $user['User']['id']);
                $this->User->save($data, false, array('password'));
                $email = new CakeEmail();
                $email->template('default', 'email_layout');
                $email->emailFormat('html');
                $email->to($user['User']['email']);
                $email->from(ADMIN_EMAIL, APP_NAME);
                $email->subject('♻ Your requested account information.');
                // message
                $message = "<strong>Hello!</strong>
                    Your new access details are:
                    <strong>Email</strong> {$user['User']['email']}
                    <strong>Password</strong> $newpw
                
                    Thank you for using " . APP_NAME ."!
                    " . APP_EMAIL_SIGNATURE ;
                $email->send($message);
                $this->Session->setFlash("Your password has been reset. Please check your email address.","flash_goodnews");
                $this->redirect(array('action'=>'login'));
            } else {
                $this->Session->setFlash("No account found.", "flash_badnews");
            }
        } else {
            //$email->send('My message');
        }
    }
    
}

?>
