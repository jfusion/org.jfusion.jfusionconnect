<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusionConnect
 * @package    ViewsAdmin
 * @subpackage Cpanel
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
//display the paypal donation button
?>
<table class="adminform"><tr><td width="55%" valign="top">
	<tr>
		<td>	
			<div id="cpanel">
			    <div style="float:left;">
			            <div class="icon">
			                <a href="index.php?option=com_jfusionconnect&view=settings" >
								<img src="components/com_jfusionconnect/images/manager.png" height="50px" width="50px">                
				                <span><?php echo JText::_('SETTINGS'); ?></span>
			                </a>
			            </div>
			    </div>
			    <div style="float:left;">
			            <div class="icon">
			                <a href="index.php?option=com_jfusionconnect&view=sites" >
								<img src="components/com_jfusionconnect/images/log.png" height="50px" width="50px">                
				                <span><?php echo JText::_('SITES'); ?></span>
			                </a>
			            </div>
			    </div>
			    <div style="float:left;">
			            <div class="icon">
			                <a href="index.php?option=com_jfusionconnect&view=userlog" >
								<img src="components/com_jfusionconnect/images/log.png" height="50px" width="50px">                
				                <span><?php echo JText::_('LOG'); ?></span>
			                </a>
			            </div>
			    </div>
			    <div style="float:left;">
			            <div class="icon">
			                <a href="index.php?option=com_jfusionconnect&view=servertest" >
								<img src="components/com_jfusionconnect/images/versioncheck.png" height="50px" width="50px">                
				                <span><?php echo JText::_('SERVERTEST'); ?></span>
			                </a>
			            </div>
			    </div>
			</div>
		</td>		
		<td width="45%" valign="top">
		</td>
	</tr>
</table>