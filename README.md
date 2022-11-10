# DTAG
Cria e manipula tags html através do PHP

## Utilização
Exemplo
```php
require __DIR__.'/vendor/autoload.php';

use Dob\DynamicTag\DTag;

$div = new DTag('div');
$div->onclick = "alert(0);";
$br = new DTag('br');
$img = DTag::img('alt', 'src');
$texto = "texto de teste";
$div->setIn($texto.$br->toStr().$img->toStr());
$div->show(); 

``` 
## Requisitos 
Necessário PHP >= 7.0