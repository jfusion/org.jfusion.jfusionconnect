<?php

/**
 * This is file that creates URLs for the jfusion component
 *
 * PHP version 5
 *
 * @category  JFusion
 * @package   Router
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


//load params class
jimport( 'joomla.html.parameter');

/**
 * build the SEF URL
 *
 * @param array &$query query to build
 *
 * @return string URL
 */
function jfusionconnectBuildRoute(&$query)
{
    $segments = array();
    //make sure the url starts with the filename
    foreach ($query as $key => $value) {
        if ($key != 'option' && $key != 'Itemid') {
            if (is_array($value)) {
                foreach ($value as $array_key => $array_value) {
                    $segments[] = $key . '[' . $array_key . '],' . $array_value;
                    unset($query[$key]);
                }
            } else {
                $segments[] = $key . ',' . $value;
                unset($query[$key]);
            }
        }
    }
    if (count($segments)) {
        $segments[count($segments) - 1].= '/';
    }

    return $segments;
}

/**
 * reconstruct the SEF URL
 *
 * @param array $segments segments to parse
 *
 * @return string vars
 */
function jfusionconnectParseRoute($segments)
{
    //needed to force Joomla to use JDocumentHTML when adding a .html suffix is enabled
    JRequest::setVar('format', 'html');

    $vars = array();
    if (isset($segments[0])) {
        //parse all other segments
        if (!empty($segments)) {
            foreach ($segments as $segment) {
                $parts = explode(',', $segment);
                if (isset($parts[1])) {
                    //check for an array
                    if (strpos($parts[0], '[')) {
                        //prepare the variable
                        $array_parts = explode('[', $parts[0]);
                        $array_index = substr_replace($array_parts[1], "", -1);
                        //set the variable
                        if (empty($vars[$array_parts[0]])) {
                            $vars[$array_parts[0]] = array();
                        }
                        $vars[$array_parts[0]][$array_index] = $parts[1];
                    } else {
                        $vars[$parts[0]] = $parts[1];
                    }
                }
            }
        }
    }

    unset($segments);
    
    $menu = & JMenu::getInstance('site');
    $item = & $menu->getActive();
    $vars += $item->query;
    return $vars;
}
