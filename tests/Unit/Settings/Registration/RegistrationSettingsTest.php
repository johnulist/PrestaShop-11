<?php
/**
 * 2007-2019 PrestaShop
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
 * @author     Getresponse <grintegrations@getresponse.com>
 * @copyright 2007-2019 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace GetResponse\Tests\Unit\Settings\Registration;

use GetResponse\Settings\Registration\RegistrationSettings;
use GetResponse\Tests\Unit\BaseTestCase;

/**
 * Class RegistrationSettingsTest
 * @package GetResponse\Tests\Unit\Settings\Registration
 */
class RegistrationSettingsTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldCreateFromConfigurationArray()
    {
        $customs = [
            [
                'customer_property_name' => 'property1',
                'gr_custom_id' => 'gr_id1',
            ],
            [
                'customer_property_name' => 'property2',
                'gr_custom_id' => 'gr_id2',
            ],
        ];

        $configuration = [
            'active_subscription' => true,
            'active_newsletter_subscription' => true,
            'campaign_id' => 'cid',
            'cycle_day' => null,
        ];

        $registrationSettings = RegistrationSettings::createFromConfiguration($configuration, $customs);

        self::assertEquals(2, $registrationSettings->getCustomFieldMappingCollection()->count());
        self::assertTrue($registrationSettings->isActive());
        self::assertTrue($registrationSettings->isNewsletterActive());
        self::assertEquals('cid', $registrationSettings->getListId());
        self::assertSame(null, $registrationSettings->getCycleDay());
    }
}
