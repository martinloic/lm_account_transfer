{*
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
*}
{extends file='customer/page.tpl'}

{block name='page_title'}
  {l s='Closing of the website' mod='lm_account_transfer'}
{/block}

{block name='page_content'}
  {assign var="new_website_url" value={Configuration key="LM_ACCOUNT_TRANSFER_WEBSITE_URL"}}
  <div class="container">
    <section class="page_content">
      <div class="col-xs-12">
        <p>
          {l s='We have made the decision to merge the this website with %s.' sprintf=[$new_website_url] mod='lm_account_transfer'}
          {l s='This merge will allow us to better serve you by offering you a wider choice of motors, remote controls and other related products, as well as an improved service overall.' mod='lm_account_transfer'}.
        </p>
        <p>
          {l s='We understand that this change may raise questions or concerns, which is why we want to provide you with all the information you need to make this transition as smooth as possible.' mod='lm_account_transfer'}
        </p>
        <p>
          {l s='Please note that you will need to collect your invoices from the site before closing. If you have not yet collected your invoices, or if you are having difficulty doing so, please do not hesitate to contact us with the contact form on the site or by email at : %s.' sprintf=[$shop.email] mod='lm_account_transfer'}
        </p>
        <p>
          {l s='For all orders in progress, your account will remain accessible until your order is closed.' mod='lm_account_transfer'}
        </p>
        <p>{l s=''}
        <p>
          {l s='If you would like your account to be merged, please start the transfer initialization by clicking the button below. This action must be done before the closure of the site %s' sprintf=[$urls.base_url] mod='lm_account_transfer'}.
        </p>
        <a class="btn btn-primary d-flex items-center mb-3" href="{$lm_account_transfer_launch_controller}">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 256 256">
            <path fill="currentColor"
              d="m213.66 181.66l-32 32a8 8 0 0 1-11.32-11.32L188.69 184H48a8 8 0 0 1 0-16h140.69l-18.35-18.34a8 8 0 0 1 11.32-11.32l32 32a8 8 0 0 1 0 11.32m-139.32-64a8 8 0 0 0 11.32-11.32L67.31 88H208a8 8 0 0 0 0-16H67.31l18.35-18.34a8 8 0 0 0-11.32-11.32l-32 32a8 8 0 0 0 0 11.32Z" />
          </svg>
          <span>{l s='Transfer my account now' mod='lm_account_transfer'}</span>
        </a>
        <p>
          {l s='We would like to sincerely thank you for your trust and continued support. We are impatient to welcome you to %s and to continue to offer you the best possible service.' sprintf=[$new_website_url] mod='lm_account_transfer'}
        </p>
      </div>
    </section>
  </div>
{/block}