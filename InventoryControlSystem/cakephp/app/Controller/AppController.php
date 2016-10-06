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
class AppController extends Controller {

// 	public $lockedPages = array('order','purchase');	//認証ユーザーだけに見せたい静的ページ
// 	public $helpers = array('Html' => array('configFile' => 'html5_tags'));	//html5に対応させる

// 	var $helpers = array('Table');

	public $helpers = array('Form', 'Html', 'Js', 'Time');

	public function beforeFilter() {

		$this->layout = "WebZksLayout";



// 		// pages controllerかどうかをチェック
// 		if ($this->name == 'Pages')
// 		{
// 			// pagesなら引数を受け取る
// 			$path = $this->passedArgs;

// 			// 認証ユーザーしか閲覧できないページか調べる
// 			if (isset($path[0]) && in_array($path[0], $this->lockedPages))
// 			{
// 				//ここでpagesのdisplayをロック
// 				$this->Auth->deny('display');
// 			}
// 		}


	}
	public $components = array(
			'DebugKit.Toolbar' => array(
					'panels' => array(
							'history' => false,
					),
			),
	);

}

