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
<form action="index.php" method="post" id="adminForm" name="adminForm" autocomplete="off">
	<div id="config-document">
		<div id="page-site" class="tab">
			<table class="noshow">
				<tr>
					<td width="100%">
						<?php echo $this->loadTemplate('settings'.$this->version) ?>
						<?php echo $this->loadTemplate('pape'.$this->version) ?>
						<?php echo $this->loadTemplate('site_config'.$this->version) ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="page-system" class="tab">
			<table class="noshow">
				<tr>
					<td width="100%">
						<?php echo $this->loadTemplate('response_config'.$this->version) ?>
						<?php echo $this->loadTemplate('response_data'.$this->version) ?>
					</td>
				</tr>
			</table>
		</div>		
		<div id="page-user" class="tab">
			<table class="noshow">
				<tr>
					<td width="100%">
						<?php echo $this->loadTemplate('usersettings_general'.$this->version) ?>
						<?php echo $this->loadTemplate('usersettings_recaptha'.$this->version) ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="clr">
	</div>
	<input type="hidden" name="option" value="com_jfusionconnect" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>