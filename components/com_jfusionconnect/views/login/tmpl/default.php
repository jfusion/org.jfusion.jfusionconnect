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
	<table>
		<tr>
			<td class="loginform">
				<form action="<?php echo $this->form_url ?>" method="post">
					<b><?php echo $this->trust_root ?></b> <?php echo JText::_('REQUEST_LOGIN');?><br/><br/>
					<?php if ($this->idselect) : ?>
						<span class="username">
							<input type="text" onfocus="if(this.value=='<?php echo JText::_('USERNAME');?>') this.value='';" onblur="if(this.value=='') this.value='<?php echo JText::_('USERNAME');?>';" value="<?php echo JText::_('USERNAME');?>" alt="<?php echo JText::_('USERNAME');?>" size="18" name="username">
						</span>
					<?php else : ?>
						<span class="username">
							<?php echo $this->username; ?>
						</span>
						<input type="hidden" name="username" value="<?php echo $this->username; ?>"/>
					<?php endif; ?>

					<span class="password">
						<input type="text" onfocus="if(this.value=='<?php echo JText::_('PASSWORD');?>') this.value=''; this.type='password';" onblur="if(this.value=='') { this.value='<?php echo JText::_('PASSWORD');?>'; this.type='text'; }" value="<?php echo JText::_('PASSWORD');?>" alt="<?php echo JText::_('PASSWORD');?>" size="18" name="password">
					</span>
					
					<?php if ($this->changeuser) : ?>
						<a href="<?php echo $this->changeuser; ?>"><?php echo JText::_('CHANGE_USER');?></a>
					<?php endif; ?>
					
					<br/>
				    <?php if (count($this->request_info)) : ?>
				        <br/><?php echo JText::_('REQUESTING_FOLLOWING_INFORMATION'); ?>: <br/>
				        <?php $fields = ''; ?>
		        
				        <?php foreach($this->request_info as $name) : ?>
							<?php if ($this->username && $this->user->username == $this->username) : ?>
								<?php $value = JFusionConnect::getValue($this->user,$name); ?>							
								<?php if ($value !== null) : ?>								
									<?php $fields .= JText::_($name) .' : <b>'. JFusionConnect::getValue($this->user,$name) . '</b><br/>'; ?>
								<?php endif; ?>
							<?php  else : ?>
								<?php if ($fields) : ?>
			               			<?php $fields .= ', '.JText::_($name); ?>
			             	   <?php else : ?>
			                		<?php $fields = JText::_($name); ?>
								<?php endif; ?>
							<?php endif; ?>
				        <?php endforeach; ?>
						<?php echo $fields; ?>
					<?php endif; ?>
					<?php if ($this->policy_url) : ?>
						<br/><br/><a href="<?php echo $this->policy_url; ?>" target="_blank"><?php echo JText::_('PRIVACY_POLICY');?> </a><br/>
						<?php echo JText::_('PRIVACY_POLICY_INFO');?>
					<?php endif; ?>
					<br/><br/>
					<?php echo $this->recaptcha; ?>
					<?php if ($this->user_allowautoconfirm) : ?>
						<input type="checkbox" name="remember"> <?php echo JText::_('AUTO_CONDIRM'); ?>
					<?php endif; ?>
					<?php echo JHTML::_('form.token'); ?>
					<button type="submit" name="task" value="login">
						<div class="submit">
							<?php echo JText::_('SUBMIT'); ?>
						</div>
					</button>
					<button name="task" value="cancel">
						<div class="cancel">
							<?php echo JText::_('CANCEL'); ?>
						</div>
					</button>
				</form>
			</td>
			<td valign="top">
				<?php if ($this->site->getValue('text',null)) : ?>
					<?php echo $this->site->getValue('text'); ?>
				<?php else : ?>
					<img class="sitelogo">
				<?php endif; ?>
			</td>
		</tr>
	</table>	
</div>


