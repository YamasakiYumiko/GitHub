<?php 
App::import('Helper', 'Html');
App::import('Helper', 'Javascript');
App::import('Helper', 'AppForm');
App::import('Controller', 'Schedules');

class AppFormHelperTest extends CakeTestCase {
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);
	var $AppForm = null;

	function start() {
		parent::start();
		Router::reload();

		Configure::write('Acl.database', 'test_suite');
		$this->AppForm =& new AppFormHelper();
		$this->AppForm->Html =& new HtmlHelper();
		$this->AppForm->Javascript =& new JavascriptHelper();
		$this->Controller =& new SchedulesController();
		$this->Controller->constructClasses();
		$this->View =& new View($this->Controller);
		ClassRegistry::addObject('view', $view);
	}

	function testFormCreate() {
		$result = $this->AppForm->create('Schedule', array('url' => '/schedules/add'));
		$expected = array(
			'form' => array('method' => 'post', 'action' => '/schedules/add'),
			'fieldset' => array('style' => 'display:none;'),
			array('input' => array('type' => 'hidden', 'name' => '_method', 'value' => 'POST')),
			'/fieldset',
			'table' => array('class'=>'form')
		);
		$this->assertTags($result, $expected,true);
	}
	function testFormEnd() {
		$result = $this->AppForm->end();
		$expected = array(
			'/table',
			'/form'
		);
		$this->assertTags($result, $expected);
	}
	function testFormTextInput() {
		$locale = Configure::write('Config.language', 'en');
		$this->AppForm->create('Schedule', array('url' => '/schedules/add'));

		$result = $this->AppForm->input('title');
		$expected = array(
			array('tr' => array(
				'class' => 'required'
			)),
			'<th',
			'label' => array('for' => 'ScheduleTitle'),
			'Title',
			'/label',
			'/th',
			'<td',
			'div' => array('class' => 'input text required'),
			array('input' => array(
				'type' => 'text', 'name' => 'data[Schedule][title]',
				'value' => '', 'id' => 'ScheduleTitle', 'maxlength'=>'100'
			)),
			'/div',
			'/td',
			'/tr'
		);
		$this->assertTags($result, $expected, true);
	}
	function testFormEnDatetimeInput() {
		$locale = Configure::write('Config.language', 'en');
		$this->AppForm->create('Schedule', array('url' => '/schedules/add'));

		$result = $this->AppForm->input('from');
		$expected = array(
			array('tr' => array(
				'class' => 'required'
			)),
			'<th',
			'label' => array('for' => 'ScheduleFrom'),
			'From',
			'/label',
			'/th',
			'<td',
			'div' => array('class' => 'input datetime required'),
			array('select' => array(
				'name' => 'data[Schedule][from][month]',
				'id' => 'ScheduleFromMonth'
			)),
			'*/select',
			'-',
			array('select' => array(
				'name' => 'data[Schedule][from][day]',
				'id' => 'ScheduleFromDay'
			)),
			'*/select',
			'-',
			array('select' => array(
				'name' => 'data[Schedule][from][year]',
				'id' => 'ScheduleFromYear'
			)),
			'*/select',
			array('select' => array(
				'name' => 'data[Schedule][from][hour]',
				'id' => 'ScheduleFromHour'
			)),
			'*/select',
			':',
			array('select' => array(
				'name' => 'data[Schedule][from][min]',
				'id' => 'ScheduleFromMin'
			)),
			'*/select',
			array('select' => array(
				'name' => 'data[Schedule][from][meridian]',
				'id' => 'ScheduleFromMeridian'
			)),
			'*/select',
			'/div',
			'/td',
			'/tr'
		);
		$this->assertTags($result, $expected);
	}
	function testFormJaDatetimeInput() {
		$locale = Configure::write('Config.language', 'ja');
		$this->AppForm->create('Schedule', array('url' => '/schedules/add'));

		$result = $this->AppForm->input('from');
		$expected = array(
			array('tr' => array(
				'class' => 'required'
			)),
			'<th',
			'label' => array('for' => 'ScheduleFrom'),
			'*/label',
			'/th',
			'<td',
			'div' => array('class' => 'input datetime required'),
			array('select' => array(
				'name' => 'data[Schedule][from][year]',
				'id' => 'ScheduleFromYear'
			)),
			'*/select',
			array('select' => array(
				'name' => 'data[Schedule][from][month]',
				'id' => 'ScheduleFromMonth'
			)),
			'*/select',
			array('select' => array(
				'name' => 'data[Schedule][from][day]',
				'id' => 'ScheduleFromDay'
			)),
			'*/select',
			array('select' => array(
				'name' => 'data[Schedule][from][hour]',
				'id' => 'ScheduleFromHour'
			)),
			'*/select',
			':',
			array('select' => array(
				'name' => 'data[Schedule][from][min]',
				'id' => 'ScheduleFromMin'
			)),
			'*/select',
			'/div',
			'/td',
			'/tr'
		);
		$this->assertTags($result, $expected);
//var_dump(h($result));
	}
	function testDeleteButton() {
		$locale = Configure::write('Config.language', 'en');
		$result = $this->AppForm->delete_button('/schedules/delete/1');
		$expected = '<input type="button" value="Delete" onclick="javascript:delete_submit(&#039;Are you sure you want to delete&#039;,&#039;/schedules/delete/1&#039;);" />';
		$this->assertEqual($result, $expected);

		$view =& ClassRegistry::getObject('view');
		$this->assertEqual('<script type="text/javascript">'."\n".'//<![CDATA['."\n".'
			function delete_submit(confirm, url) {
				if(window.confirm(confirm)) {
					location.href = url;
				}
			} 
		'."\n" . '//]]>' . "\n".'</script>',
			$view->__scripts[0]);

	}
	function testEnPrintDatetime() {
		$locale = Configure::write('Config.language', 'en');
		$result = $this->AppForm->printDatetime(array('year'=>2009,'month'=>1,'day'=>24,'hour'=>21,'min'=>46));
		$this->assertEqual('Sat, Jan 24th 2009, 21:46', $result);
	}
	function testFormMultipleSelectBoxes() {
		$locale = Configure::write('Config.language', 'en');
		$view =& ClassRegistry::getObject('view');
		$view->viewVars['users'] = array('1' => 'Lorem ipsum dolor sit amet', '2' => 'Hidetoshi Nakata');

		$this->AppForm->create('Schedule', array('url' => '/schedules/add'));

		$options = array('2' => 'Hidetoshi Nakata');
		$result = $this->AppForm->multipleSelectBoxes('User', array('label'=>__('Participant',true), 'multiple'=>'multiple', 'options'=>$options));
		$expected = array(
			'<tr',
			'<th',
			'label' => array('for' => 'UserUser'),
			'Participant',
			'/label',
			'/th',
			'<td',
			array('input' => array(
				'type'=>"hidden",
				'name'=>"data[User][User]",
				'value'=>""
			)),
			array('select' => array(
				'name' => 'data[User][User][]',
				'id' => 'UserUser',
				'multiple'=>"multiple",
			)),
			array('option' => array(
				'value' => '2',
			)),
			'Hidetoshi Nakata',
			'/option',
			'/select',
			array('input' => array(
				'type'=>"hidden",
				'name'=>"data[Schedule][all]",
				'value'=>""
			)),
			array('select' => array(
				'name' => 'data[Schedule][all][]',
				'id' => 'ScheduleAll',
				'multiple'=>"multiple",
			)),
			array('option' => array(
				'value' => '1',
			)),
			'Lorem ipsum dolor sit amet',
			'/option',
			'/select',
			array('script' => array(
				'type'=>"text/javascript",
			)),
			'*/script',
			'/td',
			'/tr'
		);
		$this->assertTags($result, $expected,true);
	}
	function testPermissionSelectAdd() {
		$locale = Configure::write('Config.language', 'en');
		$this->AppForm->create('Group', array('url' => '/groups/add'));
		$acos = array(
			array('Aco' => array('id'=>1, 'alias'=>'groups'),	'children' => array(
				array('Aco' => array('id'=>2, 'alias'=>'index')),
				array('Aco' => array('id'=>3, 'alias'=>'view'))
			)),
			array('Aco' => array('id'=>4, 'alias'=>'users',),	'children' => array(
				array('Aco' => array('id'=>5, 'alias'=>'index')),
				array('Aco' => array('id'=>6, 'alias'=>'view'))
			)),
		);

		$result = $this->AppForm->permissionSelect($acos);
		$expected = array(
			'<tr',
			'<th',
			'Permission',
			'/th',
			array('td' => array('class'=>'permission')),
			array('input' => array(
				'type'=>"hidden",
				'name'=>"data[Group][Permission]",
				'value'=>""
			)),
			'<fieldset',
			'<legend',
			'Groups',
			'/legend',
			array('div' => array('class' => 'checkbox')),
			array('input' => array(
				'type'=>"checkbox",
				'name'=>"data[Group][Permission][]",
				'value'=>"groups",
				'id'=>"GroupPermissionGroups" 
			)),
			array('label'=>array('for'=>"GroupPermissionGroups")),
			'preg:/\/\*/',
			'/label',
			'/div',
			array('div' => array('class' => 'checkbox')),
			array('input' => array(
				'type'=>"checkbox",
				'name'=>"data[Group][Permission][]",
				'value'=>"groups/index",
				'id'=>"GroupPermissionGroups/index" 
			)),
			array('label'=>array('for'=>"GroupPermissionGroups/index")),
			'preg:/\/\Index/',
			'/label',
			'/div',
			array('div' => array('class' => 'checkbox')),
			array('input' => array(
				'type'=>"checkbox",
				'name'=>"data[Group][Permission][]",
				'value'=>"groups/view",
				'id'=>"GroupPermissionGroups/view" 
			)),
			array('label'=>array('for'=>"GroupPermissionGroups/view")),
			'preg:/\/\View/',
			'/label',
			'/div',
			'/fieldset',
			'<fieldset',
			'<legend',
			'Users',
			'/legend',
			array('div' => array('class' => 'checkbox')),
			array('input' => array(
				'type'=>"checkbox",
				'name'=>"data[Group][Permission][]",
				'value'=>"users",
				'id'=>"GroupPermissionUsers" 
			)),
			array('label'=>array('for'=>"GroupPermissionUsers")),
			'preg:/\/\*/',
			'/label',
			'/div',
			array('div' => array('class' => 'checkbox')),
			array('input' => array(
				'type'=>"checkbox",
				'name'=>"data[Group][Permission][]",
				'value'=>"users/index",
				'id'=>"GroupPermissionUsers/index" 
			)),
			array('label'=>array('for'=>"GroupPermissionUsers/index")),
			'preg:/\/\Index/',
			'/label',
			'/div',
			array('div' => array('class' => 'checkbox')),
			array('input' => array(
				'type'=>"checkbox",
				'name'=>"data[Group][Permission][]",
				'value'=>"users/view",
				'id'=>"GroupPermissionUsers/view" 
			)),
			array('label'=>array('for'=>"GroupPermissionUsers/view")),
			'preg:/\/\View/',
			'/label',
			'/div',
			'/fieldset',
			array('script' => array(
				'type'=>"text/javascript",
			)),
			'*/script',
			'/td',
			'/tr'
		);
		$this->assertTags($result, $expected,true);
	}	
}
?>
