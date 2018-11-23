<?php


use GetResponse\Account\AccountSettingsRepository;
use GetResponse\CustomFields\DefaultCustomFields;
use GetResponse\Ecommerce\Ecommerce;
use GetResponse\Ecommerce\EcommerceRepository;
use GetResponse\Settings\Registration\RegistrationRepository;
use GetResponse\Settings\Registration\RegistrationSettings;
use GetResponse\WebForm\WebForm;
use GetResponse\WebForm\WebFormRepository;
use GetResponse\WebTracking\WebTracking;
use GetResponse\WebTracking\WebTrackingRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_16_4_0($object) {

    $idShop = Context::getContext()->shop->id;
    upgradeCustomsTable($idShop);
    upgradeEcommerceTable($idShop);
    upgradeSettingsTable($idShop);
    upgradeWebFormsTable($idShop);

    return true;
}

function upgradeCustomsTable($idShop) {

    Configuration::updateValue(ConfigurationSettings::CUSTOM_FIELDS, json_encode(DefaultCustomFields::DEFAULT_CUSTOM_FIELDS));

    $sql = "DROP TABLE "._DB_PREFIX_."getresponse_customs";
    DB::getInstance()->execute($sql);
}

function upgradeEcommerceTable($idShop) {
    $sql = "SELECT * FROM "._DB_PREFIX_."getresponse_ecommerce WHERE id_shop = " . $idShop;
    $result = Db::getInstance()->getRow($sql);

    $sql = "SELECT * FROM "._DB_PREFIX_."getresponse_settings WHERE id_shop = " . $idShop;
    $settings = Db::getInstance()->getRow($sql);

    if (!empty($result)) {
        $repository = new EcommerceRepository();
        $repository->updateEcommerceSubscription(
            new Ecommerce(
                Ecommerce::STATUS_ACTIVE,
                isset($result['gr_id_shop']) ? $result['gr_id_shop'] : null,
                isset($settings['campaign_id']) ? $settings['campaign_id'] : null
            )
        );
    }

    $sql = "DROP TABLE "._DB_PREFIX_."getresponse_ecommerce";
    DB::getInstance()->execute($sql);
}

function upgradeSettingsTable($idShop) {
    $sql = "SELECT * FROM "._DB_PREFIX_."getresponse_settings WHERE id_shop = " . $idShop;
    $result = Db::getInstance()->getRow($sql);

    if (!empty($result['api_key'])) {
        $accountRepository = new AccountSettingsRepository();
        $accountRepository->updateApiSettings($result['api_key'], $result['account_type'], $result['crypto']);

        $registrationRepository = new RegistrationRepository();
        $registrationRepository->updateSettings(RegistrationSettings::createFromOldDbTable($result));

        $webTrackingRepository = new WebTrackingRepository();
        $webTrackingRepository->saveTracking(
            new WebTracking(
            $result['active_tracking'] === 'yes' ? WebTracking::TRACKING_ACTIVE : WebTracking::TRACKING_INACTIVE,
            $result['tracking_snippet']
            )
        );

        if (isset($result['invalid_request_date'])) {
            Configuration::updateValue(ConfigurationSettings::INVALID_REQUEST, $result['invalid_request_date']);
        }
    }

    $sql = "DROP TABLE "._DB_PREFIX_."getresponse_settings";
    DB::getInstance()->execute($sql);
}

function upgradeWebFormsTable($idShop) {
    $sql = "SELECT * FROM "._DB_PREFIX_."getresponse_webform WHERE id_shop = " . $idShop;
    $result = Db::getInstance()->getRow($sql);

    if (!empty($result['webform_id'])) {
        $repository = new WebFormRepository();
        $repository->update(new WebForm(
            $result['webform_id'],
            $result['active_subscription'],
            $result['sidebar'],
            $result['style'],
            $result['url']
        ));
    }

    $sql = "DROP TABLE "._DB_PREFIX_."getresponse_webform";
    DB::getInstance()->execute($sql);
}
