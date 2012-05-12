<?php
/**
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   Models
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
defined('_JEXEC') or die('Restricted access');

class JFusionConnectAbstractPlugin
{
	var $name = null;
	var $fields = array('nameperson',
						'nameperson/first',
						'nameperson/middle',
						'nameperson/middle',
						'nameperson/last',
						'nameperson/friendly',
						'contact/email',
						'contact/country/home',
						'pref/language',
						'pref/timezone');

	function getConfig() {
		if ($this->name) {
        	$params = &JComponentHelper::getParams('com_jfusionconnect');
			$config = array();
			foreach ($this->fields as $field) {
				$fieldName = $this->name.$field;
				$config[$field] = JHTML::_('select.booleanlist', 'settings['.$fieldName.']', 'class="inputbox"', $params->get($fieldName,1));
			}
			return $config;
		}
		return array();
	}
	function isEnabled($key) {
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		return $params->get($this->name.$key,0);
	}

	function get($user,$parameter) {
		$parameter = strtolower($parameter);
		switch ($parameter) {
			case 'fullname':
			case 'nameperson':
				if($this->isEnabled('nameperson')) {
					return $user->name;
				}
				break;
			case 'nickname':
			case 'nameperson/friendly':
				if($this->isEnabled('nameperson/friendly')) {
					return $user->username;
				}
				break;
			case 'email':
			case 'contact/email':
				if($this->isEnabled('contact/email')) {
					return $user->email;
				}
				break;
			case 'country':
			case 'contact/country/home':
				if($this->isEnabled('contact/country/home')) {
					$language = $user->getParam('language');
					if ($language) {
						list($language,$country) = explode('-', $user->getParam('language'));
						return $country;
					}
				}
				break;
			case 'language':
			case 'pref/language':
				if($this->isEnabled('pref/language')) {
					$language = $user->getParam('language');
					if ($language) {
						list($language,$country) = explode('-', $user->getParam('language'));
						return $language;
					}
				}
				break;				
			case 'timezone':
			case 'pref/timezone':
				if($this->isEnabled('pref/timezone')) {
					$timezone = $user->getParam('timezone');
					if ($timezone) {
						return JFusionConnect::getTimezone($timezone);
					}
				}
				break;
			case 'nameperson/first':
				if($this->isEnabled('nameperson/first')) {
					list($firstname) = explode(' ', $user->name);
					return $firstname;
				}
				break;
			case 'nameperson/middle':
				if($this->isEnabled('nameperson/middle')) {
					$name = explode(' ', $user->name);
					if (count($name) > 2) {
						unset($name[count($name)-1]);
						unset($name[0]);
						return implode(' ',$name);
					}
				}
				break;
			case 'nameperson/last':
				if($this->isEnabled('nameperson/las')) {
					$name = explode(' ', $user->name);
					if (count($name) > 1) {
						return $name[count($name)-1];
					}
				}
				break;		
			default:
				break;
		}
		return null;
		
		/*
			// name stuff
			$ax_response->addValue('http://axschema.org/namePerson/prefix', ...);
			$ax_response->addValue('http://axschema.org/namePerson/suffix', ...);
			
			// Work stuff
			$ax_response->addValue('http://axschema.org/company/name', ...);
			$ax_response->addValue('http://axschema.org/company/title', ...);
			$ax_response->addValue('http://axschema.org/namePerson/friendly', ...);
			
			// Date of Birth
			$ax_response->addValue('http://axschema.org/birthDate/birthYear', ...);
			$ax_response->addValue('http://axschema.org/birthDate/birthMonth', ...);
			$ax_response->addValue('http://axschema.org/birthDate/birthday', ...);
			
			//Telephone
			$ax_response->addValue('http://axschema.org/contact/phone/default', ...);
			$ax_response->addValue('http://axschema.org/contact/phone/home', ...);
			$ax_response->addValue('http://axschema.org/contact/phone/business', ...);
			$ax_response->addValue('http://axschema.org/contact/phone/cell', ...);
			$ax_response->addValue('http://axschema.org/contact/phone/fax', ...);
			
			//Address
			$ax_response->addValue('http://axschema.org/contact/postalAddress/home', ...);
			$ax_response->addValue('http://axschema.org/contact/postalAddressAdditional/home', ...);
			$ax_response->addValue('http://axschema.org/contact/city/home', ...);
			$ax_response->addValue('http://axschema.org/contact/state/home', ...);
			$ax_response->addValue('http://axschema.org/contact/country/home', ...);
			$ax_response->addValue('http://axschema.org/contact/postalCode/home', ...);
			$ax_response->addValue('http://axschema.org/contact/postalAddress/business', ...);
			$ax_response->addValue('http://axschema.org/contact/postalAddressAdditional/business', ...);
			$ax_response->addValue('http://axschema.org/contact/city/business', ...);
			$ax_response->addValue('http://axschema.org/contact/state/business', ...);
			$ax_response->addValue('http://axschema.org/contact/country/business', ...);
			$ax_response->addValue('http://axschema.org/contact/postalCode/business', ...);
			
			//Instant Messaging
			$ax_response->addValue('http://axschema.org/contact/IM/AIM', ...);
			$ax_response->addValue('http://axschema.org/contact/IM/ICQ', ...);
			$ax_response->addValue('http://axschema.org/contact/IM/MSN', ...);
			$ax_response->addValue('http://axschema.org/contact/IM/Yahoo', ...);
			$ax_response->addValue('http://axschema.org/contact/IM/Jabber', ...);
			$ax_response->addValue('http://axschema.org/contact/IM/Skype', ...);
			
			//Web Sites
			$ax_response->addValue('http://axschema.org/contact/web/default', ...);
			$ax_response->addValue('http://axschema.org/contact/web/blog', ...);
			$ax_response->addValue('http://axschema.org/contact/web/Linkedin', ...);
			$ax_response->addValue('http://axschema.org/contact/web/Amazon', ...);
			$ax_response->addValue('http://axschema.org/contact/web/Flickr', ...);
			$ax_response->addValue('http://axschema.org/contact/web/Delicious', ...);
			
			//Audio/Video Greetings
			$ax_response->addValue('http://axschema.org/media/spokenname', ...);
			$ax_response->addValue('http://axschema.org/media/greeting/audio', ...);
			$ax_response->addValue('http://axschema.org/media/greeting/video', ...);
			
			//Images
			$ax_response->addValue('http://axschema.org/media/image/default', ...);
			$ax_response->addValue('http://axschema.org/media/image/aspect11', ...);
			$ax_response->addValue('http://axschema.org/media/image/aspect43', ...);
			$ax_response->addValue('http://axschema.org/media/image/aspect34', ...);
			$ax_response->addValue('http://axschema.org/media/image/favicon', ...);
			
			//Other Personal Details/Preferences
			$ax_response->addValue('http://axschema.org/media/biography', ...);
		*/
	}
}