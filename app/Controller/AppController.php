<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $components = array('Acl', 'Auth' => array('authorize' => 'Controller'), 'Session');
    
    public function beforeFilter()
    {
        // Definindo o algorítmo de hash para a senha (OPCIONAL)
        $this->Auth->authenticate = array('Blowfish' => array(
                'userModel' => 'User',
                'fields' => array('username' => 'email')
        ));
        
        // Informando controller/action para login
        $this->Auth->loginAction = array(
            'controller' => 'users',
            'action' => 'login'
        );
        
        // controller/action após realizar o login
        $this->Auth->loginRedirect = array(
            'controller' => 'pages',
            'action' => 'home');
        
        // controller/action após realizar o logout
        $this->Auth->logoutRedirect = array(
            'controller' => 'users',
            'action' => 'login'
        );
        
        // Actions habilitadas para usuários não logados
        $this->Auth->allow('login', 'display', 'home');
        
        // Definindo uma mensagem de erro do ACL
        $this->Auth->authError = 'Suas permissões não concedem acesso ao recurso solicitado.';
        
        if ($this->Auth->user()) {
            
            if( !$this->isAuthorized() ) {
                $this->Session->setFlash($this->Auth->authError);
                $this->redirect($this->Auth->redirectUrl());
            }
            
            $this->Auth->allow();
        }
        
        parent::beforeFilter();
    }
    
    protected function isAuthorized()
    {

        // verifica o recurso solicitado
        $aco = 'controllers/' . $this->params['controller'];

        //Informando qual é meu grupo
        $aro = $this->Auth->user('role_id');

        //Retornando a validação do privilégio solicitante - recurso/privilegio
        return $this->Acl->check($aro, $aco, $this->params['action']);
    }

}
