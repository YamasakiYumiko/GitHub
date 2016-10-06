<?php
App::import('Helper', 'Form');
class AppFormHelper extends FormHelper {
	var $helpers = array('Html', 'Javascript');
	/**
	 * @example of use
	 * 		echo $appForm->input('title', array());
	 * @example of output
	 * 		<tr>
	 *			<th>Title</th>
	 *			<td>
	 *				<div class="input text required">
	 *					<input name="data[Schedule][title]" type="text" maxlength="100" id="ScheduleTitle" />
	 *				</div>
	 *			</td>
	 *		</tr>
	 *
	 */
	function input($fieldName, $options = array()) {
		$label = null;
		if(!empty($options['label'])) {
			$label = $options['label'];
		}
		$options['label'] = false;
		$view =& ClassRegistry::getObject('view');
		$this->setEntity($fieldName);
		$model =& ClassRegistry::getObject($this->model());
		$type = $model->getColumnType($this->field());
		if($type == 'datetime') {
			$options = $this->setDatetimeOptions($options);
		}
		$tr_class = '';
		if (in_array($this->field(), $this->fieldset['validates'])) {
			$tr_class = ' class="required"';
		}

		$out = '';
		$out .= '<tr'.$tr_class.'>';
		$out .= '<th>';
		$out .= parent::label($fieldName, $label);
		$out .= '</th>';
		$out .= '<td>';
		$out .= parent::input($fieldName, $options);
		$out .= '</td>';
		$out .= '</tr>';

		return $out;
	}

	/**
	 * @example of use
	 * 		echo $appForm->create('Schedule');
	 * @example of output
	 * 		<form >
	 *			<tabel class="form">
	 *
	 */
	function create($model = null, $options = array()) {
		$out = parent::create($model, $options);
		$out .= '<table class="form">';
		return $out;
	}
	/**
	 * @example of use
	 * 		echo $appForm->end();
	 * @example of output
	 * 			</table>
	 *		</form>
	 *
	 */
	function end($options = null) {
		$out = '</table>';
		$out .= parent::end($options);
		return $out;
	}

	function setDatetimeOptions($options) {
		$locale = Configure::read('Config.language');
		switch($locale) {
		case 'ja' :
			$options = array_merge(array(
					'dateFormat'=>'YMD', // 年月日順
					'timeFormat'=>24, 	 // 24時間方式
					'separator'=>'',	 // 日付の区切り文字
					'interval'=>10		 // 10分間隔
				)
				,$options);
			break;
		}
		return $options;
	}
	function select($fieldName, $options = array(), $selected = null, $attributes = array(), $showEmpty = '') {
		$locale = Configure::read('Config.language');
		switch($locale) {
		case 'ja' :
			$type = split('\\.', $fieldName);
			switch(array_pop($type)) {
			case 'year' :
				foreach($options as $key=>$value) {
					$options[$key] = $value.'年';
				}
				break;
			case 'day' :
				foreach($options as $key=>$value) {
					$options[$key] = $value.'日';
				}
				break;
			}
			break;
		}
		return parent::select($fieldName, $options, $selected, $attributes, $showEmpty);
	}
	function delete_button($url) {
		$out = $this->button(__('Delete',true), 
			array('onclick'=>'javascript:delete_submit(\''.__('Are you sure you want to delete',true).'\',\''.$url.'\');'));
		$script = '
			function delete_submit(confirm, url) {
				if(window.confirm(confirm)) {
					location.href = url;
				}
			} 
		';
		$this->Javascript->codeBlock($script,array('inline'=>false));
		return $out;
	}
	function printDatetime($dt) {
		$time = mktime($dt['hour'], $dt['min'], 0, $dt['month'], $dt['day'], $dt['year']);
		return date(__("D, M jS Y, H:i",true), $time);
	}
	function multipleSelectBoxes($fieldName, $options=array()) {
		$label = null;
		if(!empty($options['label'])) {
			$label = $options['label'];
		}
		$options['label'] = false;
		$model = $this->model();
		$view =& ClassRegistry::getObject('view');
		$this->setEntity($fieldName);

		$tr_class = '';
		if (in_array($this->field(), $this->fieldset['validates'])) {
			$tr_class = ' class="required"';
		}
		$out = '';
		$out .= '<tr'.$tr_class.'>';
		$out .= '<th>';
		$out .= parent::label($fieldName, $label);
		$out .= '</th>';
		$out .= '<td>';
		$options['div'] = false;
		$varName = Inflector::variable(
			Inflector::pluralize(preg_replace('/_id$/', '', $this->field()))
		);
		$users = $view->getVar($varName);
		$selects = array();
		$attributes = $this->value($options);
		if(!empty($attributes['value'])) {
			foreach($attributes['value'] as $selected) {
				$selects[$selected] = $users[$selected];
				unset($users[$selected]);
			}
		}
		if(!empty($options['options'])) {
			$selects = $options['options'];
			$key = key($options['options']);
			unset($users[$key]);
			unset($options['options']);
		}
		$out .= parent::input($fieldName, array_merge($options, array('options'=>$selects, 'error'=>false)));
		$out .= parent::input('all', array_merge($options, array('type'=>'select','options'=>$users, 'error'=>false)));
		$out .= parent::error($fieldName);
		$script = 'createMovableOptions("'.$model.'All","'.$fieldName.$fieldName.'",500,300,"'.__($fieldName, true).'","'.$label.'");';
		$out .= $this->Javascript->codeBlock($script,array('inline'=>true));
		$this->Javascript->link('multipleselectboxes.js', false);
		$this->Html->css('multipleselectboxes.css', null, array(), false);
		$out .= '</td>';
		$out .= '</tr>';

		return $out;
	}
	function permissionSelect($acos=array(), $aros=array()) {
		$data = array();
		$script = '';
		$out = '';
		$out .= '<tr><th>';
		$out .= __('Permission', true);
		$out .= '</th><td class="permission">';
		foreach($acos as $controller) {
			if(in_array($controller['Aco']['id'], $aros)) {
				$data[] = $controller['Aco']['alias'];
			}
			$actions = array($controller['Aco']['alias'] => __('/*',true));
			$name = $controller['Aco']['alias'];
			$id = $this->model().'Permission'.Inflector::camelize($name);
			$script .= "
				var $name = document.getElementById('$id');
				$name.onclick = select_$id;
				function select_$id() {
					var disabled = false;
					if($name.checked) {
						disabled = 'disabled';
					}
			";
			foreach($controller['children'] as $action) {
				if(in_array($action['Aco']['id'], $aros)) {
					$data[] = $controller['Aco']['alias'].'/'.$action['Aco']['alias'];
				}
				$actions[$controller['Aco']['alias'].'/'.$action['Aco']['alias']] = __('/'.Inflector::camelize($action['Aco']['alias']), true);
				$act_name = $action['Aco']['alias'];
				$script .= "document.getElementById('$id/$act_name').disabled = disabled;";
			}
			$alias = __(Inflector::camelize($controller['Aco']['alias']), true);
			$options[$alias] = $actions;
			$script .= "}";
			$script .= "select_$id();";
		}
		if(!empty($aros)) {
			$this->data['Group']['Permission'] = $data;
		}
		$out .= parent::input('Permission', array('type'=>'select', 'div'=>false, 
					'label'=>false, 'multiple'=>'checkbox', 'options'=>$options));
		$out .= $this->Javascript->codeBlock($script,array('inline'=>true));
		$out .= '</td></tr>';
		return $out;
	}
}
?>
