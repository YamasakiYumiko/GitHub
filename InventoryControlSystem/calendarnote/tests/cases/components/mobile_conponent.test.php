<?php 
App::import('Component', 'Mobile');

class MobileComponentTest extends CakeTestCase {
  function testGetUrl_url_array() {
    $params = array();
    $params['prefix'] = 'mobile';
    $url = array('controller' => 'users', 'action' => 'index');

    $expected = array('controller' => 'users', 'action' => 'index', 'mobile' =>true, '?' => array(session_name() => session_id()));
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));

    // no prefix
    $params = array();
    $url = array('controller' => 'users', 'action' => 'index');

    $expected = array('controller' => 'users', 'action' => 'index');
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));
  }

  function testGetUrl_url_string_absolute() {
    $params = array();
    $params['prefix'] = 'mobile';
    $url = 'http://hoge/m/';

    $expected = 'http://hoge/m/';
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));

    // no prefix
    $params = array();
    $url = 'http://hoge/m/';

    $expected = 'http://hoge/m/';
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));

  }

  function testGetUrl_url_string_relative() {
    $params = array();
    $params['prefix'] = 'mobile';
    $url = '/users/index';

    $expected = '/m/users/index?'.session_name().'='.session_id();
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));

    // no prefix
    $params = array();
    $url = '/users/index';

    $expected = '/users/index';
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));
  }

  function testGetUrl_url_string_relative_m() {
    $params = array();
    $params['prefix'] = 'mobile';
    $url = '/m/users/index';

    $expected = '/m/users/index?'.session_name().'='.session_id();
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));

    // no prefix
    $params = array();
    $url = '/m/users/index';

    $expected = '/m/users/index';
    $this->assertEqual($expected, MobileComponent::getUrl($params, $url));
  }
}
