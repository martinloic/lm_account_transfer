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

require_once _PS_MODULE_DIR_.'lm_account_transfer/classes/PSWebServiceLibrary.php';

class lm_account_transfermigrateModuleFrontController extends ModuleFrontController {
  public function initContent() {
    $ps_new_shop_path = Configuration::get('LM_ACCOUNT_TRANSFER_WEBSITE_URL');
    $ps_new_shop_webservice_key = Configuration::get('LM_ACCOUNT_TRANSFER_WEBSERVICE_TOKEN');
    $accountFound = false;

    $customer = Context::getContext()->customer;
    $secure_key = sha1($customer->secure_key);
    $token = Tools::getValue('transfer_token');
    if ($customer->isLogged() === false || !isset($token) || $token != $secure_key) {
      Tools::redirect('index.php');
    }

    parent::initContent();

    try {
      $webService = new PrestashopWebservice($ps_new_shop_path, $ps_new_shop_webservice_key, false);
      $xml = $webService->get([
          'resource' => 'customers',
          'filter[email]'  => '['.$customer->email.']' // Here we use hard coded value but of course you could get this ID from a request parameter or anywhere else
      ]);

      // Vérifiez s'il y a des résultats
      if (isset($xml->customers->customer)) {
        $accountFound = true;
        // Des résultats sont présents, vous pouvez les afficher
        // echo 'Résultats trouvés:';
      } else {
        // Aucun résultat trouvé
        // echo 'Aucun résultat trouvé.';
      }

    } catch (PrestaShopWebserviceException $ex) {
      // Shows a message related to the error
      // echo 'Other error: <br />' . $ex->getMessage();
    }

    $newPassword = '';
    
    if($accountFound === false) {
      $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $pass = array(); 
      $combLen = strlen($comb) - 1; 
      for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $combLen);
        $pass[] = $comb[$n];
      }

      $newPassword = implode($pass);

      try {
        $webService = new PrestashopWebservice($ps_new_shop_path, $ps_new_shop_webservice_key, false);
        $blankXml = $webService->get(['resource' => 'customers?schema=blank']);

        $customerFields = $blankXml->customer->children();
        $customerFields->id_default_group = $customer->id_default_group;
        $customerFields->associations->groups->group->id = $customer->id_default_group;
        $customerFields->id_gender = $customer->id_gender;
        $customerFields->firstname = $customer->firstname;
        $customerFields->lastname = $customer->lastname;
        $customerFields->email = $customer->email;
        $customerFields->company = $customer->company;
        $customerFields->siret = $customer->siret;
        $customerFields->ape = $customer->ape;
        $customerFields->vatNumber = $customer->vatNumber;
        $customerFields->passwd = $newPassword;
        $customerFields->active = 1;
        $customerFields->newsletter = 1;

        $createdXml = $webService->add([
          'resource' => 'customers',
          'postXml' => $blankXml->asXML(),
        ]);
        $newCustomerFields = $createdXml->customer->children();
        // echo 'Customer created with ID ' . $newCustomerFields->id . PHP_EOL;

      } catch (PrestaShopWebserviceException $ex) {
        // echo 'Other error: <br />' . $ex->getMessage();
      }
    }

    $this->context->smarty->assign(array(
      'password' => $newPassword,
    ));

    $this->setTemplate('module:lm_account_transfer/views/templates/front/content-account-transfer-validation.tpl');
  }
}