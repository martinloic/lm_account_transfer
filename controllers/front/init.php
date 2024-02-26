<?php
/**
* 2007-2018 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2018 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class lm_account_transferinitModuleFrontController extends ModuleFrontController {
  
  public function initContent() {
      $this->display_column_right = false;
      $this->display_column_left = false;
      $context = Context::getContext();
      if (empty($context->customer->id)) {
          Tools::redirect('index.php');
      }

      parent::initContent();

      $ps_version = (bool)version_compare(_PS_VERSION_, '1.7', '>=');

      $params = array(
          'transfer_token' => sha1($context->customer->secure_key),
      );

      $this->context->smarty->assign(array(
        'lm_account_transfer_launch_controller' => Context::getContext()->link->getModuleLink('lm_account_transfer', 'migrate', $params, true),
        'lm_account_transfer_id_customer' => Context::getContext()->customer->id,
      ));

      $this->setTemplate('module:lm_account_transfer/views/templates/front/content-init-account-transfer.tpl');
  }

  public function getBreadcrumbLinks()
  {
      $breadcrumb = parent::getBreadcrumbLinks();
      $breadcrumb['links'][] = $this->addMyAccountToBreadcrumb();
      return $breadcrumb;
  }

}