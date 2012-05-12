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
<form id="adminForm" name="adminForm" action="" method="post">
	<input type="hidden" name="site[id]" value="<?php echo $this->id; ?>"/>
	<table>
		<tr>
			<td>
				<span class="hasTip" title="<?php echo JText::_( 'SITE' ); ?>::<?php echo JText::_( 'TIPSITE' ); ?>">			
					<?php echo JText::_('SITE'); ?>
				</span>
			</td>
			<td>
				<input size="100" type="text" name="site[realm]" value="<?php echo $this->realm; ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<span class="hasTip" title="<?php echo JText::_( 'LANGUAGE' ); ?>::<?php echo JText::_( 'TIPLANGUAGE' ); ?>">			
					<?php echo JText::_('LANGUAGE'); ?>
				</span>
			</td>
			<td>
				<?php echo $this->language; ?>
			</td>
		</tr>
		<tr>
			<td>
				<span class="hasTip" title="<?php echo JText::_( 'SITETEXT' ); ?>::<?php echo JText::_( 'TIPSITETEXT' ); ?>">			
					<?php echo JText::_('SITETEXT'); ?>
				</span>
			</td>
			<td>
				<?php echo $this->text; ?>
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" value=""/>
</form>	