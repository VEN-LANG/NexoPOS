<?php

namespace App\Settings;

use App\Classes\FormInput;
use App\Classes\SettingForm;
use App\Crud\TransactionAccountCrud;
use App\Models\TransactionAccount;
use App\Services\SettingsPage;
use Illuminate\Support\Facades\Auth;

class AccountingSettings extends SettingsPage
{
    const IDENTIFIER = 'accounting';

    const AUTOLOAD = true;

    public function __construct()
    {
        $debitAccounts  =   TransactionAccount::debit()->get()->map( function ( $account ) {
            return [
                'label' => $account->name,
                'value' => $account->id,
            ];
        } );

        $creditAccount  =   TransactionAccount::credit()->get()->map( function ( $account ) {
            return [
                'label' => $account->name,
                'value' => $account->id,
            ];
        } );

        $callBackUrl = ns()->option->get('ns_accounting_mpesa_callback_url');
        if (empty($callBackUrl)) {
            $callBackUrl = env("APP_URL") . "/mpesa/callback";
            ns()->option->set('ns_accounting_mpesa_callback_url', $callBackUrl);
        }

        $found_admin = false;

        // Check if the user has an 'admin' role
        foreach (Auth::user()->roles as $role_) {
            if ($role_->namespace == 'admin') {
                $found_admin = true;
                break;
            }
        }

        $this->form = [
            'title' => __( 'Accounting' ),
            'description' => __( 'Configure the accounting feature' ),
            'tabs' => SettingForm::tabs(
                SettingForm::tab(
                    identifier: 'general',
                    label: __( 'General' ),
                    fields: include ( dirname( __FILE__ ) . '/accounting/general.php' ),
                ),
                SettingForm::tab(
                    identifier: 'cash-registers',
                    label: __( 'Cash Register' ),
                    fields: SettingForm::fields(
                        FormInput::multiselect(
                            label: __( 'Allowed Cash In Account' ),
                            name: 'ns_accounting_cashin_accounts',
                            description: __( 'Define on which accounts cashin transactions are allowed' ),
                            options: $creditAccount,
                            value: ns()->option->get( 'ns_accounting_cashin_accounts' ),
                        ),
                        FormInput::multiselect(
                            label: __( 'Allowed Cash Out Account' ),
                            name: 'ns_accounting_cashout_accounts',
                            description: __( 'Define on which accounts cashout transactions are allowed' ),
                            options: $debitAccounts,
                            value: ns()->option->get( 'ns_accounting_cashout_accounts' ),
                        ),
                        FormInput::searchSelect(
                            label: __( 'Opening Float Account' ),
                            name: 'ns_accounting_opening_float_account',
                            description: __( 'Select the account from which the opening float will be taken' ),
                            options: $debitAccounts,
                            component: 'nsCrudForm',
                            props: TransactionAccountCrud::getFormConfig(),
                            value: ns()->option->get( 'ns_accounting_opening_float_account' ),
                        ),
                        FormInput::searchSelect(
                            label: __( 'Closing Float Account' ),
                            name: 'ns_accounting_closing_float_account',
                            description: __( 'Select the account from which the closing float will be taken' ),
                            options: $creditAccount,
                            component: 'nsCrudForm',
                            props: TransactionAccountCrud::getFormConfig(),
                            value: ns()->option->get( 'ns_accounting_closing_float_account' ),
                        )
                    ),
                ),
                SettingForm::tab(
                    identifier: 'mpesa',
                    label: __( 'Mpesa' ),
                    fields: SettingForm::fields(
                        FormInput::text(
                            label: __( 'Mpesa API Key' ),
                            name: 'ns_accounting_mpesa_api_key',
                            description: __( 'Enter the Mpesa API Key' ),
                            value: ns()->option->get( 'ns_accounting_mpesa_api_key' ),
                        ),
                        FormInput::text(
                            label: __( 'Mpesa API Secret' ),
                            name: 'ns_accounting_mpesa_api_secret',
                            description: __( 'Enter the Mpesa API Secret' ),
                            value: ns()->option->get( 'ns_accounting_mpesa_api_secret' ),
                        ),
                        FormInput::text(
                            label: __( 'Mpesa Shortcode' ),
                            name: 'ns_accounting_mpesa_shortcode',
                            description: __( 'Enter the Mpesa Shortcode' ),
                            value: ns()->option->get( 'ns_accounting_mpesa_shortcode' ),
                        ),
                        FormInput::text(
                            label: __( 'Mpesa Callback URL' ),
                            name: 'ns_accounting_mpesa_callback_url',
                            description: __( 'Enter the Mpesa Callback URL' ),
                            value: $callBackUrl,
                            disabled: !$found_admin,
                        ),
                        FormInput::text(
                            label: __( 'Mpesa Pass Key' ),
                            name: 'ns_accounting_mpesa_pass_key',
                            description: __( 'Enter the Mpesa Pass Key' ),
                            value: ns()->option->get( 'ns_accounting_mpesa_pass_key')
                        ),
                        FormInput::text(
                            label: __( 'Business Code' ),
                            name: 'ns_accounting_mpesa_business_code',
                            description: __( 'Enter the Mpesa Business Code' ),
                            value: ns()->option->get('ns_accounting_mpesa_business_code'),
                        ),
                        FormInput::searchSelect(
                            label: __( 'Allowed Mpesa In Account' ),
                            name: 'ns_accounting_mpesain_accounts',
                            description: __( 'Define on which accounts Mpesa in transactions are allowed' ),
                            options: $creditAccount,
                            component: 'nsCrudForm',
                            props: TransactionAccountCrud::getFormConfig(),
                            value: ns()->option->get( 'ns_accounting_mpesain_accounts' ),
                        ),
                        FormInput::searchSelect(
                            label: __( 'Allowed Mpesa Out Account' ),
                            name: 'ns_accounting_mpesaout_accounts',
                            description: __( 'Define on which accounts mpesaout transactions are allowed' ),
                            options: $debitAccounts,
                            component: 'nsCrudForm',
                            props: TransactionAccountCrud::getFormConfig(),
                            value: ns()->option->get( 'ns_accounting_mpesaout_accounts' ),
                        ),
                        FormInput::select(
                            label: __( 'Allow Mpesa Transactions' ),
                            name: 'ns_accounting_mpesaallowed_accounts',
                            description: __( 'Allowed Mpesa Transactions' ),
                            options: [['label' => __('Yes'), 'value'=> __('yes')], ['label' => __('No'), 'value'=> __('no')],],
                            value: ns()->option->get( 'ns_accounting_mpesaallowed_accounts' ),
                        ),
                        FormInput::select(
                            label: __('Mpesa Transaction Gateway'),
                            name: 'ns_accounting_mpesa_transaction_gateway',
                            description: __( 'Select transaction Gateway' ),
                            options: [['label' => __('Safaricom Till'), 'value'=> __('safaricom_till')], ['label' => __('Kopo Kopo Till'), 'value'=> __('kopo_kopo_till')],],
                            value: ns()->option->get( 'ns_accounting_mpesa_transaction_gateway' ),
                        )
                    )
                )
            ),
        ];
    }
}
