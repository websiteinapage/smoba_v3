<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OpenidAuthenticate
 *
 * @author uchilaka
 */
App::uses('BaseAuthenticate', 'Controller/Component/Auth');
class OpenidAuthenticate extends BaseAuthenticate {
    
    public function authenticate(CakeRequest $request, CakeResponse $response) {
        // Do things for openid here.
        $user = $request->data['User'];
        if(!empty($request->data['Openid'])):
            $openid = $request->data['Openid'];
            if(empty($openid['current_access_token']) || empty($user['id'])):
                return false;
            endif;
            return $this->_findUser($user['id'], $openid['current_access_token']);
        else:
            return false;
        endif;
    }
    
}
?>
