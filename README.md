# DTAG
Cria e manipula tags html através do PHP
## Instalação
```shell
composer require dob/dtag
```
## Utilização
Exemplo
```php
<?php
require __DIR__.'/../../../vendor/autoload.php';

use Dob\DynamicTag\DFile;
use Dob\DynamicTag\DForm;
use Dob\DynamicTag\DImg;
use Dob\DynamicTag\DInput;
use Dob\DynamicTag\DLink;
use Dob\DynamicTag\DTag;
use Dob\DynamicTag\DView;

Class HomeView extends DView {
	function __construct($params) {
		$css = true;
		$js = true;
		$headers = true;
		$globalCss = true;
		$globalJs = true;
		$this->pushCss('avulso.css');
		$this->pushJs('avulso.js');
		parent::__construct($css, $js, $headers, $globalCss, $globalJs);
		
		extract($params);
		$this->setLayout();
	}
	
	function setLayout() {
		$container_home = $this->getHtmlTag('container_home');
		$bt_login = $this->getHtmlTag('bt_login');
		//echo $this->htmlFile->show();
		$bt_login->onclick = "alert('login')";

		// BUTTON
		$bt_cancel = new DTag('button');
		$bt_cancel->setIn('Cancel');
		$bt_cancel->id = "bt_cancel";
		$bt_cancel->onclick = "alert('cancel')";

		// IMAGE
		$img = DTag::img('img_exemple', 'images/sample.png');
		$img->id = "img_example";
		$img->width = '100px';

		// INPUT
		$input = DTag::input('input_name', 'text');
		$input_date = DTag::input('input_date_name', 'date');

		// FILE
		$input_file = DTag::filepicker('input_file_name', 'images/sample.png');

		// SELECT
		$select = DTag::select('select_name');
		$options = array(
			'value 1',
			'value 2',
			'value 3',
			'value 4',
			'value 5',
		);
		foreach ($options as $key => $optValue) {
			$opt = DTag::create('option');
			$opt->setIn($optValue);
			$opt->value = $optValue;
			$select->setIn($opt);
		}
		
		// FORM
		$form = DTag::form('form_name', 'action', 'post');
		$form->id = 'form_id';
		$form->setIn($input);
		$form->setIn($input_date);
		$form->setIn($input_file);
		$form->setIn($select);

		// Link
		$link = new DLink('link', 'href');

		// TEXTAREA
		$txtArea = DTag::textarea('txa_name', 10, 50);
		
		// WYSIWYG
		$wysiwyg = DTag::wysiwyg('wys_name', 10, 100, 'content');

		// Mount view
		$container_home->setIn($bt_cancel);
		$container_home->setIn('<br><br>');
		$container_home->setIn($img);
		$container_home->setIn('<br><br>');
		$container_home->setIn($form);
		$container_home->setIn('<br><br>');
		$container_home->setIn($link);
		$container_home->setIn('<br><br>');
		$container_home->setIn($txtArea);
		$container_home->setIn('<br><br>');
		$container_home->setIn($wysiwyg);
		$container_home->setIn('<br><br>');
	}
}

$hv = new HomeView(array());
$hv->show();

?>

``` 
## Requisitos 
Necessário PHP >= 7.0