<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusionConnect
 * @package    ViewsFront
 * @subpackage Frameless
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="jfusionconnect">
	<?php JFusionConnectUi::renderMenuBar();?>
	<br/>
	<?php echo JText::_('WELCOME'); ?> <b><?php echo $this->username; ?></b>
	<br/>
	<br/>
	<?php echo JText::_('YOUROPENIDIS'); ?>: <b><?php echo $this->openid; ?></b>
	<?php if (isset($this->openidselect)) : ?>
		<?php echo JText::_('OR'); ?> <b><?php echo $this->openidselect; ?></b>
	<?php endif ?>
	<br/>
	<br/>
	<?php echo $this->message; ?>
	<br/><br/>
	<a class="openid" href="http://openid.net/get-an-openid/what-is-openid/"><?php echo JText::_('WHATISOPENID'); ?></a><br/>
	<br/>
</div>