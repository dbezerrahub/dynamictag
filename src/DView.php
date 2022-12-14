<?php

namespace Dob\DynamicTag;
use ReflectionClass;
use UnexpectedValueException;
use Exception;

include_once "simple_html_dom.php";

function searchDirView($view, $dir) {
    try {
        $d = new \DirectoryIterator($dir);
        $directories = null;
        $rs = false;
        $dirView = false;
        foreach ($d as $item) {
            if ($item->isDot()) {
                continue;
            }
            if ($item->isFile()) {
                if (strpos($item->getFilename(), 'View.php') !== false) {
                    $nomeClasse = str_replace('.php', '', $item);
                    if ($nomeClasse == $view) {
                        $dirView = str_replace(resource_path() . '\views', '', $item->getPath());
                        //echo $dirView.'/'.$nomeClasse.'<br />';
                        return $dirView;
                    }
                }
            } else {
                $directories[] = $item->getPath() . '/' . $item;
                //echo 'registrou '.$item->getPath().'/'.$item.'<br />';
            }
        }

        if (!is_null($directories)) {
            foreach ($directories as $dir) {
                //echo 'entrou em '.$item->getPath().'/'.$item.'<br />';
                $dirView = searchDirView($view, $dir);
                if ($dirView) {
                    //return $dirView;
                    break;
                }
            }
        } else {
            
        }
        return $dirView;
    } catch (UnexpectedValueException $e) {
        echo 'Erro: ' . $e->getMessage() . '<br />';
    }
}

/**
 * Busca o diretório de um arquivo (Classe)
 */
function get_resource_path($context) {
     $reflector = new ReflectionClass(get_class($context));
     return dirname($reflector->getFileName());
}

class DView {

public $title;
public $css;
public $js;
public $htmlContentFile;
public $cssContentFile;
public $jsContentFile;
public $cssLinkFile;
public $jsLinkFile;
public $trackGoogle = false;
public $headers;
public $globalCss;
public $pushCss = array();
public $pushJs = array();
public $dirView;

/**
 * Constroi e define classes de Views
 * @param type $css
 * @param type $js
 * @param type Boolean $headers
 */
function __construct($css = true, $js = false, $headers = false, $globalCss = false, $globalJs = false, $title = '') {
    @header('Content-Type: text/html; charset=utf-8');
    $this->view_directory = get_resource_path($this);
    $this->relative_view_directory = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', get_resource_path($this));
    $this->css = $css;
    $this->js = $js;
    $this->headers = $headers;
    $this->globalCss = $globalCss;
    $this->globalJs = $globalJs;
    $this->cssContentFile = '';
    $this->jsContentFile = '';
    $this->cssLinkFile = '';
    $this->jsLinkFile = '';
    $this->name = get_class($this);

    if(!file_exists($this->view_directory . '/html/' . lcfirst($this->name) . '.html')) {
        throw new Exception("
            O Arquivo ". lcfirst($this->name).".html não existe para a view ". ucfirst($this->name).".php
            Verifique se o arquivo .html correspondente existe no diretório $this->view_directory\html"
        , 1);
    }
    if($css == true) {
        if(!file_exists($this->view_directory . '/css/' . lcfirst($this->name) . '.css')) {
        throw new Exception("
            O Arquivo ". lcfirst($this->name).".css não existe para a view ". ucfirst($this->name).".php
            Verifique se o arquivo .css correspondente existe no diretório $this->view_directory\css"
        , 1);
        } else {
            $this->cssLinkFile = "<link rel='stylesheet' href = '$this->relative_view_directory/css/".lcfirst($this->name).".css'>";
            if(!$headers) {
                $this->cssContentFile = file_get_html($this->relative_view_directory . '/css/' . lcfirst($this->name) . '.css');
            }
        }
    }
    
    if($js == true) {
        if(!file_exists($this->view_directory . '/js/' . lcfirst($this->name) . '.js')) {
        throw new Exception("
            O Arquivo ". lcfirst($this->name).".js não existe para a view ". ucfirst($this->name).".php
            Verifique se o arquivo .js correspondente existe no diretório $this->view_directory\js"
        , 1);
        } else {
            $this->jsLinkFile = "<script src='$this->relative_view_directory/js/".lcfirst($this->name).".js'></script>";
            if(!$headers) {
                $this->jsContentFile = file_get_html($this->relative_view_directory . '/js/' . lcfirst($this->name) . '.js');
            }
        }
    }
    
    $this->htmlContentFile = file_get_html($this->view_directory . '/html/' . lcfirst($this->name) . '.html');
    
    //$htmlView = $this->htmlContentFile->show();
    //echo $htmlView;
    $this->title = $title;
}

/**
 * Retorna a instância de uma View
 */
static function view($className, $params = null) {
    //$dirView = searchDirView($className, public_path() . '/views');
    $dirView = $_SERVER['DOCUMENT_ROOT']. $className . '.php';
    #use
    include $dirView;
    $className = str_replace('.php', '', substr(strrchr($dirView, '/'), 1));
//die($className);
    if (is_null($params)) {
        return new $className();
    } else {
        return new $className($params);
    }
}

function setTitle($title) {
    $this->title = $title;
}

/**
 * 
 * @param $url => route ou url
 * @param $container => Container em que a view será carregada
 * @param $type => get ou post
 * @param string $loadMsg => mensagem de load
 * @param string $postExecute => código js que será executado após o submit (opcional)
 * @return string
 */
static function redirect($url, $container = false, $type = 'get', $loadMsg = '', $postExecute = '') {
    if (!$container) {
        return "dtag_redirect('$url')";
    } else {
        return "dtag_redirect('$url', '$container', '$type', '$loadMsg', function() { $postExecute }, '" . url('/') . "')";
    }
}

static function redirectWithJs($url) {
    echo "<script language = 'javascript type= 'text/javascript'>window.location.replace('$url');</script>";
}

/**
 *
 * @param $url => route ou url
 * @param $container => Container em que a view será carregada
 * @param $form => nome do form
 * @param string $loadMsg => mensagem de load (opcional)
 * @param string $postExecute => código js que será executado após o submit (opcional)
 * @return string
 */
static function submit($url, $container, $form, $loadMsg = '', $postExecute = '') {
    return "dtag_submit('$url', '$container', '$form', '$loadMsg', function() { $postExecute }, '" . url('/') . "')";
}

/**
 * Define um arquivo css específico para a view
 */
function pushCss($css) {
    $this->pushCss[] = $css;
}

/**
 * Define um arquivo js específico para a view
 */
function pushJs($js) {
    $this->pushJs[] = $js;
}

/**
 * Carrega o css da view
 */
function getCss() {
    if ($this->css) {
        if($this->headers) {
            $cssView = $this->cssLinkFile;
        } else {
            $cssView = '<style>' . $this->cssContentFile->show() . '</style>';
        }
    }
    return $cssView;
}

/**
 * Carrega os CSSs avulsos
 */
function getPushCss() {
    $cssView = '';
    foreach ($this->pushCss as $pcss) {
        if($this->headers) {
            $cssView .= "<link rel='stylesheet' href='$pcss'>\n";
        } else {
            $cssContentFile = file_get_html("$pcss");
            $cssView .= '<style>' . $cssContentFile->show() . "</style>\n";
            $cssContentFile->clear();
        }
    }
    return $cssView;
}

/**
 * Carrega os CSSs globais
 * @return string
 */
function getGlobalCss() {
    $return = '';
    if($this->globalCss != false) {
        $dir = $this->globalCss;
        $scan = scandir($dir);
        $return = '';
        if($this->headers) {
            foreach($scan as $file) {
                if (!is_dir($dir."/$file")) {
                    $return .= "<link rel='stylesheet' href='$dir/$file'>\n";
                }
            }
        } else {
            foreach($scan as $file) {
                if (!is_dir($dir."/$file")) {
                    $globalCssContentFile = file_get_html("../../global/css/$file");
                    $return .= '<style>' . $globalCssContentFile->show() . "</style>\n";
                    $globalCssContentFile->clear();
                }
            }
        }
    }
    return $return;
}

function getLevel() {
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
    $path_level = substr_count($_SERVER['REQUEST_URI'], '/')-1;
    $level = '';
    for ($i=0; $i < $path_level; $i++) { 
        $level.='../';
    }
    die("<link rel='stylesheet' href='http://localhost/dtag/src/global/css/global.css'>");

}

/**
 * Carrega o js da view
 */
function getJs() {
    $jsView = '';
    if ($this->js) {
        if($this->headers) {
            $jsView = $this->jsLinkFile;
        } else {
            $jsView = '<script type="text/javascript">' . $this->jsContentFile->show() . "</script>\n";
        }
    }
    return $jsView;
}

/**
 * Carrega os JSs avulsos
 */
function getPushJs() {
    $jsView = '';
    foreach ($this->pushJs as $pjs) {
        if($this->headers) {
            $jsView .= "<script src='$pjs'></script>\n";
        } else {
            $jsContentFile = file_get_html("$pjs");
            $jsView .= '<script>' . $jsContentFile->show() . "</script>\n";
            $jsContentFile->clear();
        }
    }
    return $jsView;
}

/**
 * Insere na view o arquivo global
 * @return string
 */
function getGlobalJs() {
    $return = '';
    if($this->globalJs != false) {
        $dir = $this->globalJs;
        $scan = scandir($dir);
        $return = '';
        
        if($this->headers) {
            foreach($scan as $file) {
                if (!is_dir($dir."/$file")) {
                    $return .= "<script src='$dir/$file'></script>\n";
                }
            }
        } else {
            foreach($scan as $file) {
                if (!is_dir($dir."/$file")) {
                    $globalCssContentFile = file_get_html("../../global/js/$file");
                    $return .= '<script>' . $globalCssContentFile->show() . "</script>\n";
                    $globalCssContentFile->clear();
                }
            }
            $globalCssContentFile = file_get_html('../../global/js/global.js');
            
            $globalCssContentFile->clear();
        }
    }
    return $return;
}

/**
 * Busca uma tag de um html carregado pelo simple_html_dom pelo id
 * @param (string) $id
 */
function getHtmlTag($id) {
    return $this->htmlContentFile->find("[id=$id]", 0);
}

/**
 * Busca uma tag de um html carregado pelo simple_html_dom pelo id
 * @param (string) $id
 */
function getHtmlTagByAtt($tag, $att, $value) {
    return $this->htmlContentFile->find($tag . "[$att=$value]", 0);
}

/**
 * Imprime a estrutura HTML
 * @param ajax: Se verdadeiro imprime o código sem cabeçalho
 */
function show() {
    $show = '';
    // Busca o html
    $htmlView = '';
    if ($this->htmlContentFile) {
        $htmlView = $this->htmlContentFile->show();
    }

    $cssView = '';
    // Carrega Css Global
    $cssView = $this->getGlobalCss();
    // Carrega CSSs avulsos
    $cssView .= $this->getPushCss();
    // Carrega css
    $cssView .= $this->getCss();
    


    $jsView = '';
    // Js Global
    $jsView = $this->getGlobaljs();
    // Carrega JSs avulsos
    $jsView .=$this->getPushJs();
    // Carrega o Js
    $jsView .= $this->getJs();
    




    if($this->headers) {
        $show = "<!DOCTYPE html>\n<html>\n<head>\n";
        $show .= "<meta charset='utf-8'>\n<meta http-equiv='X-UA-Compatible' content='IE=edge'>\n<meta name='viewport' content='width=device-width, initial-scale=1'>\n";
        $show .= "<title>$this->title</title>\n";
    }
    
    $show .= $cssView . "\n";
    $show .= $jsView . "\n";
   

    // Body
    if($this->headers) {
        $show .= "</head>\n<body>\n";
        $show .= $htmlView;
        $show .= "\n</body>\n";
        $show .= "</html>";
    } else {
        $show .= $htmlView;
    }
    
    // Libera a memória do carregamento do html
    if ($this->htmlContentFile) {
        $this->htmlContentFile->clear();
        if($this->css and !$this->headers) {
            $this->cssContentFile->clear();
        }
        if($this->js and !$this->headers) {
            $this->jsContentFile->clear();
        }
    }
    echo($show);
} // End show()

} // End Class DView
