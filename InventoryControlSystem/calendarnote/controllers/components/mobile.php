<?php
class MobileComponent extends Object {
	function beforeRender(&$controller) {
  }

	function beforeRedirect(&$controller, $url, $status = null, $exit = true) {
    return MobileComponent::getUrl($controller->params, $url); 
  }

  /**
   * @static
   */
  function getUrl($params, $url) {
    if (!empty($params['prefix'])) {
      $sessionName = session_name();
      $sessionId = session_id();

      if (is_array($url)) {
          $url[$params['prefix']] = true;
          $url['?'] = array($sessionName => $sessionId);
      } else if (is_string($url)) {
        if (!preg_match("#^http[s]?://#", $url)) {
          $prefix = preg_match("#^/m/#", $url) ? '' : '/m';
          $url = sprintf("%s%s?%s=%s", $prefix, $url, $sessionName, $sessionId);
        }
      }
    }

    return $url;
  }
}
