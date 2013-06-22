<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OpenidsController
 *
 * @author uchilaka
 */

class OpenidsController extends AppController {
    //put your code here
    public $helpers = array('Html', 'Form', 'Session', 'Facebook');
    public $components = array('Session', 'Gatekeeper', 'Directory');  
    
    public function beforeFilter() {
        parent::beforeFilter();
        // allow anyone to use this page
        $this->Auth->allow();
    }
    
    private function getState() {
        $fb_state = $this->Session->read(FB_STATE_VARIABLE);
        if(empty($fb_state)):
            // create state
            $this->Session->write(FB_STATE_VARIABLE, $this->Openid->randomString(8));
        endif;
        return $this->Session->read(FB_STATE_VARIABLE);
    }
    
    public function verify($openId=null, $auth_agent=null, $encryptedToken=null) {
        // process arguments from zeus
        $user; $openid; $output = array();
        
        if(!empty($openId) && !empty($encryptedToken) && !empty($auth_agent)):
            if($this->Auth->loggedIn()):
                // log user out if they are logged in
                $this->Auth->logout();
            endif;
            
            // get open id
            $openid = $this->Openid->findById($openId);
            
            // compare tokens 
            $verified = $openid['Openid']['current_access_token'] === $this->Gatekeeper->decode($encryptedToken);
            $output['Verified'] = $verified;
            //$output['decodedToken'] = $this->Gatekeeper->decode($encryptedToken);
            $output["Openid Info"] = $openid;
        
            if(!empty($openid)):
                $user = $this->Openid->User->find('first', array(
                    'conditions'=>array(
                        'User.id'=>$openid['Openid']['user_id']
                    ),
                    'recursive'=>-1
                   ));
                if(!empty($user)):
                    // login user
                    $openid['Openid']['auth_agent'] = $auth_agent;
                    $user['Openid'] = $openid['Openid'];
                    $this->request->data = $user;
                    $this->Auth->login($user['User']);
                    $output['LoggedIn'] = $this->Auth->loggedIn();
                    if($this->Auth->loggedIn()):
                        //$output['LoggedinUser'] = $this->Auth->user();
                        $this->redirect(array("controller"=>"users", "action"=>"index"));
                    endif;
                endif;
            endif;
        endif;
        
        if(empty($user)):
            // make sure user is logged out in failure
            $this->Auth->logout();
            $this->Session->setFlash("Login failed", "flash_badnews");
            $this->redirect(APP_BASE);
        endif;
        
        $this->set("data", json_encode($output));
    }
    
}

?>
