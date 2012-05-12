<?php

/**
 * First file that gets called for accessing jfusion in the administrator panel
 *
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   ControllerFront
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Require the base controller
 */
require_once JPATH_COMPONENT . DS . 'controllers' . DS . 'controller.jfusionconnect.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.connect.php';

// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path)) {
        include_once $path;
    } else {
        $controller = '';
    }
}
// Create the controller
$classname = 'JFusionconnectControllerFrontEnd' . $controller;
$controller = new $classname();
//load the views
$controller->addViewPath(JPATH_COMPONENT . DS . 'views');
// Perform the Request task

$params = &JComponentHelper::getParams('com_jfusionconnect');
$document = JFactory::getDocument();
$favicon = $params->get('favicon',null);
if ($favicon) {
	
$pos = strpos($mystring, $findme);
// Note our use of ===.  Simply == would not work as expected
// because the position of 'a' was the 0th (first) character.
	if (strpos($favicon, 'http://') === 0 || strpos($favicon, 'https://') === 0) {
	    echo "The string '$findme' was not found in the string '$mystring'";
	}
	
	$document->addFavicon( JURI::root().'components/com_jfusionconnect/images/jfusionconnect.png' );
	$document->addCustomTag('<link href="'.JURI::root().'components/com_jfusionconnect/images/jfusionconnect.png" rel="shortcut icon" type="image/x-icon" />');
}
$document->addStyleSheet(JURI::root().'components/com_jfusionconnect/css/jfusionconnect.css');

$task = JRequest::getVar('task');
$view = JRequest::getVar('view');

if (!$view) {
	JRequest::setVar('view','login');
    $view = 'login';
}

//load the view
$v = & $controller->getView($view, 'html');
//render the view
$v->addTemplatePath(JPATH_COMPONENT . DS . 'view' . DS . $view . DS . 'tmpl');

$controller->execute($task);
// Redirect if set by the controller
$controller->redirect();