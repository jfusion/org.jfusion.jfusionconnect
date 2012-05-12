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

/**
 * Renders the main admin screen that shows the configuration overview of all integrations
 *
 * @category   JFusionConnect
 * @package    ViewsAdmin
 * @subpackage Cpanel
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
class jfusionconnectViewsettings extends JView
{
     /**
     * displays the view
     *
     * @param string $tpl template name
     * 
     * @return string html output of view
     */       
    function display($tpl = null)
    {
		// Load tooltips behavior
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.switcher');
		$document =& JFactory::getDocument();
		if(JFusionConnect::isJoomlaVersion('1.6')) {
			$version = '1_6';
		} else {
			$version = '';
		}
		$document->setBuffer($this->loadTemplate('navigation'.$version), 'modules', 'submenu');		

		$params = &JComponentHelper::getParams('com_jfusionconnect');

		// set value
		$lists['enabled'] = JHTML::_('select.booleanlist', 'settings[enabled]', 'class="inputbox"', $params->get('enabled',false));
		$lists['https'] = JHTML::_('select.booleanlist', 'settings[https]', 'class="inputbox"', $params->get('https',false));

		$lists['serverurl'] = JFusionConnect::getServerURL();
		$lists['joomlaurl'] = JFusionConnect::getJoomlaURL();
		
		$lists['openidsite'] = JFusionConnect::getOpenIDSiteURL();
		$lists['openidprefix'] = $params->get('openidprefix',null);
		
		$user =& JFactory::getUser();
		$openid[] = JHTML::_('select.option',  0, JFusionConnect::userToURL($user,0). ' - '.JText::_( 'OPENIDUSERNAME' ));
		$openid[] = JHTML::_('select.option',  1, JFusionConnect::userToURL($user,1). ' - '.JText::_( 'OPENIDUSERID' ));
		$openid[] = JHTML::_('select.option',  2, JFusionConnect::userToURL($user,2). ' - '.JText::_( 'OPENIDUSERNAME' ));
		$openid[] = JHTML::_('select.option',  3, JFusionConnect::userToURL($user,3). ' - '.JText::_( 'OPENIDUSERID' ));		
		$lists['openid'] = JHTML::_('select.genericlist', $openid, 'settings[openid]', 'class="inputbox" size="1"', 'value', 'text', $params->get('openid',0) );		
		
		$lists['allowopenidselect'] = JHTML::_('select.booleanlist', 'settings[allowopenidselect]', 'class="inputbox"', $params->get('allowopenidselect',true));
		
		$lists['storepath'] = $params->get('storepath',JPATH_ROOT.DS.'tmp'.DS.'openid_store');
		$lists['loginitemid'] = $params->get('loginitemid');

		$lists['favicon'] = $params->get('favicon',null);
		
		$lists['pape_enabled'] = JHTML::_('select.booleanlist', 'settings[pape_enabled]', 'class="inputbox"', $params->get('pape_enabled',1));		
		$lists['pape_phishing-resistant'] = JHTML::_('select.booleanlist', 'settings[pape_phishing-resistant]', 'class="inputbox"', $params->get('pape_phishing-resistant',0));		
		$lists['pape_multi-factor'] = JHTML::_('select.booleanlist', 'settings[pape_multi-factor]', 'class="inputbox"', $params->get('pape_multi-factor',0));
		$lists['pape_multi-factor-physical'] = JHTML::_('select.booleanlist', 'settings[pape_multi-factor-physical]', 'class="inputbox" disabled', $params->get('pape_multi-factor-physical',0));		
		
		$lists['openid_listtype'] = JHTML::_('select.booleanlist', 'settings[openid_listtype]', 'class="inputbox"', $params->get('openid_listtype',1),JText::_( 'BLACKLIST' ),JText::_( 'WHITELIST' ));		
		
		// sreg
		$lists['sreg_enabled'] = JHTML::_('select.booleanlist', 'settings[sreg_enabled]', 'class="inputbox"', $params->get('sreg_enabled',1));
		$lists['ax_enabled'] = JHTML::_('select.booleanlist', 'settings[ax_enabled]', 'class="inputbox"', $params->get('ax_enabled',1));
		
		$lists['returndata']['nameperson'] = JHTML::_('select.booleanlist', 'settings[nameperson]', 'class="inputbox"', $params->get('nameperson',1));
		$lists['returndata']['nameperson/first'] = JHTML::_('select.booleanlist', 'settings[nameperson/first]', 'class="inputbox"', $params->get('nameperson/first',1));
		$lists['returndata']['nameperson/middle'] = JHTML::_('select.booleanlist', 'settings[nameperson/middle]', 'class="inputbox"', $params->get('nameperson/middle',1));
		$lists['returndata']['nameperson/last'] = JHTML::_('select.booleanlist', 'settings[nameperson/last]', 'class="inputbox"', $params->get('nameperson/last',1));						
		$lists['returndata']['nameperson/friendly'] = JHTML::_('select.booleanlist', 'settings[nameperson/friendly]', 'class="inputbox"', $params->get('nameperson/friendly',1));
		$lists['returndata']['contact/email'] = JHTML::_('select.booleanlist', 'settings[contact/email]', 'class="inputbox"', $params->get('contact/email',1));		
		$lists['returndata']['contact/country/home'] = JHTML::_('select.booleanlist', 'settings[contact/country/home]', 'class="inputbox"', $params->get('contact/country/home',1));
		$lists['returndata']['pref/language'] = JHTML::_('select.booleanlist', 'settings[pref/language]', 'class="inputbox"', $params->get('pref/language',1));
		$lists['returndata']['pref/timezone'] = JHTML::_('select.booleanlist', 'settings[pref/timezone]', 'class="inputbox"', $params->get('pref/timezone',1));

		$plugin = JFusionConnectFactory::getPlugin();
		$lists['returndata'] = $plugin->getConfig() ;
		
		// usersettings
		$lists['user_allowautoconfirm'] = JHTML::_('select.booleanlist', 'settings[user_allowautoconfirm]', 'class="inputbox"', $params->get('user_allowautoconfirm',1));
		$lists['user_redirectinvalidrequest'] = JHTML::_('select.booleanlist', 'settings[user_redirectinvalidrequest]', 'class="inputbox"', $params->get('user_redirectinvalidrequest',0));		
		
		$lists['user_recaptchaenabled'] = JHTML::_('select.booleanlist', 'settings[user_recaptchaenabled]', 'class="inputbox"', $params->get('user_recaptchaenabled',0));		
		$lists['user_recaptchaalways'] = JHTML::_('select.booleanlist', 'settings[user_recaptchaalways]', 'class="inputbox"', $params->get('user_recaptchaalways',0));
		$lists['user_recaptchaprivatekey'] = $params->get('user_recaptchaprivatekey','');
		$lists['user_recaptchapublickey'] = $params->get('user_recaptchapublickey','');
		
		$recaptchatheame[] = JHTML::_('select.option',  0, '- '. JText::_( 'Select Theame' ) .' -');
		$recaptchatheame[] = JHTML::_('select.option',  'red', JText::_( 'RED' ));
		$recaptchatheame[] = JHTML::_('select.option',  'white', JText::_( 'WHITE' ) );
		$recaptchatheame[] = JHTML::_('select.option',  'blackglass', JText::_( 'BLACKGLASS' ));
		$recaptchatheame[] = JHTML::_('select.option',  'clean', JText::_( 'CLEAN' ) );
					
		$lists['user_recaptchatheme'] = JHTML::_('select.genericlist', $recaptchatheame, 'settings[user_recaptchatheme]', 'class="inputbox" size="1"', 'value', 'text', $params->get('user_recaptchatheme',0) );		

		$this->assignRef('lists', $lists);
		$this->assignRef('params', $params);
		$this->assignRef('version', $version);
		
		JToolBarHelper::title(JText::_('SETTINGS'), 'config.png');
		JToolBarHelper::save('savesettings');
		JToolBarHelper::apply('applysettings');
		JToolBarHelper::cancel('cancelsettings', 'Cancel');
        parent::display($tpl);
    }
}
