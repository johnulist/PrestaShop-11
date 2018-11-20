<?php
namespace GetResponse\Ecommerce;

use GetResponse\Settings\Registration\RegistrationRepository;
use Translate;

/**
 * Class EcommerceValidator
 * @package GetResponse\Ecommerce
 */
class EcommerceValidator
{
    /** @var array */
    private $errors;

    /** @var EcommerceDto */
    private $ecommerceDto;

    /** @var RegistrationRepository */
    private $registrationRepository;

    /**
     * @param EcommerceDto $ecommerceDto
     * @param RegistrationRepository $registrationRepository
     */
    public function __construct(EcommerceDto $ecommerceDto, RegistrationRepository $registrationRepository)
    {
        $this->ecommerceDto = $ecommerceDto;
        $this->registrationRepository = $registrationRepository;
        $this->errors = [];
        $this->validate();
    }

    private function validate()
    {
        $registrationSettings = $this->registrationRepository->getSettings();

        if ($this->ecommerceDto->isEnabled() && empty($this->ecommerceDto->getShopId())) {
            $this->errors[] = Translate::getAdminTranslation('You need to select store');

            return;
        }

        if ($this->ecommerceDto->isEnabled() && !$registrationSettings->isActive()) {
            $this->errors[] = Translate::getAdminTranslation(
                'You need to enable adding contacts during registrations to enable ecommerce'
            );

            return;
        }

    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
