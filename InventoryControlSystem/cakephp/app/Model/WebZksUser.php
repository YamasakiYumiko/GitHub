<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

class WebZksUser extends AppModel {

	public $name = 'WebZksUser';
	public $useTable = 'web_zks_users';

	public $validate = array(
        'user_cd' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => '社員CDを入力してください'
            )
        ),
        'user_pass' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'パスワードを入力してください'
            )
        )
    );
}
