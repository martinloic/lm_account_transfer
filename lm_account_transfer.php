<?php
/**
* 2007-2024 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2024 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
  exit;
}

class Lm_Account_Transfer extends Module {
  const HOOKS = [
    'header',
    'displayCustomerAccount'
  ];

  public function __construct() {
    $this->name = 'lm_account_transfer';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'Loïc MARTIN';
    $this->need_instance = 0;
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Account Transfer');
    $this->description = $this->l('Allow the customer to transfert his account to another prestashop');
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module ?');
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '8.0');
  }

  public function install() {
      // include(dirname(__FILE__).'/sql/install.php');

      return parent::install() && $this->registerHook(static::HOOKS);
  }

  public function uninstall() {
      // include(dirname(__FILE__).'/sql/uninstall.php');

      return parent::uninstall();
  }

    /**
   * Load the configuration form
   */
  public function getContent() {
      /**
       * If values have been submitted in the form, process.
       */
      if (((bool)Tools::isSubmit('submitLm_account_transferModule')) == true) {
          $this->postProcess();
      }

      $this->context->smarty->assign('module_dir', $this->_path);

      $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

      return $output.$this->renderForm();
  }

     /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm() {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitLm_account_transferModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        
        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm() {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Url of the new website'),
                        'name' => 'LM_ACCOUNT_TRANSFER_WEBSITE_URL',
                        'label' => $this->l('Website URL'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Webservice token of the new website'),
                        'name' => 'LM_ACCOUNT_TRANSFER_WEBSERVICE_TOKEN',
                        'label' => $this->l('Website Webservice Token'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues() {
        return array(
            'LM_ACCOUNT_TRANSFER_WEBSITE_URL' => Configuration::get('LM_ACCOUNT_TRANSFER_WEBSITE_URL', 'www.new-website-url.com'),
            'LM_ACCOUNT_TRANSFER_WEBSERVICE_TOKEN' => Configuration::get('LM_ACCOUNT_TRANSFER_WEBSERVICE_TOKEN', 'new website webservice token'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess() {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }


  public function hookHeader() {
    $this->context->controller->addJS($this->_path.'/views/js/front.js');
    $this->context->controller->addCSS($this->_path.'/views/css/front.css');
  }

  public function hookDisplayCustomerAccount() {
    $context = Context::getContext();
    $id_customer = $context->customer->id;
    $url = Context::getContext()->link->getModuleLink($this->name, 'init', array(), true);

    $this->context->smarty->assign(array(
      'front_controller' => $url,
      'id_customer' => $id_customer
    ));

    return $this->display(dirname(__FILE__), '/views/templates/front/side-menu-account-transfer.tpl');
  }
}