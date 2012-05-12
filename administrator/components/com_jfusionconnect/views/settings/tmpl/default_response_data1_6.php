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
<?php foreach($this->lists['returndata'] as $name=>$plugin) : ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'RETURNDATA' ). ' - '.$name; ?>
			<span class="error hasTip" title="<?php echo JText::_( 'Warning' );?>::<?php echo JText::_( 'WARNPATHCHANGES' ); ?>">
				<?php //echo ConfigApplicationView::WarningIcon(); ?>
			</span>
		</legend>
		<ul>
			<?php foreach($plugin as $key=>$value) : ?>
				<li>
					<label title="<?php echo JText::_($key).'::'.JText::_( 'TIP'.$key ); ?>" class="hasTip"><?php echo JText::_($key); ?>
					</label>
					<fieldset class="radio">
						<?php echo $value; ?>
					</fieldset>
				</li>
			<?php endforeach; ?>
		</ul>	
	</fieldset>
<?php endforeach; ?>