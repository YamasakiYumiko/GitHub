<?php
App::uses('AppController', 'Controller');

/**
 * WebZksUser Controller
 *
 * @property WebZksUser $WebZksUser
 */
class WebZksUserController extends AppController {

	public $name = 'WebZksUser';
	public $uses = array('WebZksUser'); //モデルを利用

	public function beforeFilter() {
	    parent::beforeFilter();

	    //ユーザー自身によるログアウトを許可する
	   	$this->Auth->allow('logout');
	}

	public $components = array(
			'Flash',
			'Session',
			'Auth' => array(
					// 認証時の設定
					'authenticate' => array(
							'Form' => array(
									// 認証に利用するモデルの変更
									'userModel' => 'WebZksUser',
									// 認証に利用するモデルのフィードを変更
									'fields' => array('username' => 'user_cd', 'password' => 'user_pass'),
									//パスワード暗号化を無効
									'passwordHasher' => array('className' => 'None')
							)
					),
					//未ログイン時のメッセージ
					'authError' => '社員CDとパスワードを入力して下さい。',
					//ログインエラー時のメッセージ
					'loginError' => '社員CDとパスワードを正しく入力してください。',
					// 認証成功後の追加処理を行う場所を指定
					'authorize' =>'controller',
					// ログインに使用するアクションを指定
					'loginAction' => array('controller'=>'WebZksUser','action' => 'login')
			)
	);

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {

				$this->Session->write('login', true);
				return $this->redirect(array('action' => 'index'));

			} else {
				$this->Flash->error(__('社員CDまたはパスワードが間違っています。'));
			}
		}
	}


	public function isAuthorized($user) {
		return true;
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

    public function index() {

    	if (($this->Session->read('Auth.User.status_flg')==='00') || ($this->Session->read('Auth.User.status_flg')==='01')){
    		$this->redirect(array('action' => 'menu01'));
    	} elseif(($this->Session->read('Auth.User.status_flg')==='10') || ($this->Session->read('Auth.User.status_flg')==='11')){
    		$this->redirect(array('action' => 'menu02'));
    	}else{
    		$this->redirect(array('action' => 'login'));	// ログイン画面に戻る
    	}
    }


    public function menu01() {}	//店舗用メニュー画面
    public function menu02() {}	//商品部用メニュー画面

}

