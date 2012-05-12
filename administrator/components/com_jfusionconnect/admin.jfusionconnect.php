<?php

/**
 * First file that gets called for accessing jfusion in the administrator panel
 *
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   ControllerAdmin
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.connect.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.log.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.request.php';

/**
 * Require the base controller
 */
require_once JPATH_COMPONENT . DS . 'controllers' . DS . 'controller.jfusionconnect.php';
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
$classname = 'JFusionconnectController' . $controller;
$controller = new $classname();
// Perform the Request task
$task = JRequest::getVar('task');
if (!$task) {
    $task = 'cpanel';
}
$view = JRequest::getVar('view',$task);

$tasklist = $controller->getTasks();
if (in_array($task, $tasklist)) {
    //excute the task
    $controller->execute($task);
} else {
    //run the task as a view
    JRequest::setVar('view', $view);
    $controller->display();
}
// Redirect if set by the controller
$controller->redirect();
?>
