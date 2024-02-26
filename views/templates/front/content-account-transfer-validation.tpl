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
  {l s='Account transferred successfully' mod='lm_account_transfer'}
{/block}

{block name='page_content'}
  <div class="container">
    <section class="page_content">
      <div class="col-xs-12">
        <p>{l s='Your account has been created on the new website' mod='lm_account_transfer'} <a
            href="{Configuration key="LM_ACCOUNT_TRANSFER_WEBSITE_URL"}"><u
              class="new-website-url">{Configuration key="LM_ACCOUNT_TRANSFER_WEBSITE_URL"}</u></a> !
        </p>
        {if strlen($password) > 0}
          <p>
            {l s='For security reasons, a password has been automatically generated for your new account on the new website' mod='lm_account_transfer'}
          </p>
          <p>{l s='Here is your new password :' mod='lm_account_transfer'} <b>{$password}</b></p>
        {/if}
        <p>
          {l s='If you want to change the password you can use the forgotten password function or log in and modify it directly in the customer account.' mod='lm_account_transfer'}
        </p>
        <a class="btn btn-primary d-flex items-center" href="{Configuration key="LM_ACCOUNT_TRANSFER_WEBSITE_URL"}">
          <span>{l s='I log in to the new site' mod='lm_account_transfer'}</span>
        </a>
      </div>
    </section>
  </div>
{/block}