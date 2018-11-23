<?php
namespace GetResponse\ContactList;

use GrApiException;
use GrShareCode\ContactList\Command\AddContactListCommand;
use GrShareCode\ContactList\AutorespondersCollection;
use GrShareCode\ContactList\ContactListCollection;
use GrShareCode\ContactList\ContactListService as GrContactListService;
use GrShareCode\ContactList\FromFieldsCollection;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationBodyCollection;
use GrShareCode\ContactList\SubscriptionConfirmation\SubscriptionConfirmationSubjectCollection;
use GrShareCode\Api\Exception\GetresponseApiException;

/**
 * Class ContactListService
 * @package GetResponse\ContactList
 */
class ContactListService
{
    /** @var GrContactListService */
    private $grContactListService;

    /**
     * @param GrContactListService $grContactListService
     */
    public function __construct(GrContactListService $grContactListService) {
        $this->grContactListService = $grContactListService;
    }

    /**
     * @return SubscriptionConfirmationSubjectCollection
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationSubject()
    {
        return $this->grContactListService->getSubscriptionConfirmationSubjects();
    }

    /**
     * @return SubscriptionConfirmationBodyCollection
     * @throws GetresponseApiException
     */
    public function getSubscriptionConfirmationBody()
    {
        return $this->grContactListService->getSubscriptionConfirmationsBody();
    }

    /**
     * @return FromFieldsCollection
     * @throws GetresponseApiException
     */
    public function getFromFields()
    {
        return $this->grContactListService->getFromFields();
    }

    /**
     * @return ContactListCollection
     * @throws GetresponseApiException
     */
    public function getContactLists()
    {
        return $this->grContactListService->getAllContactLists();
    }

    /**
     * @return AutorespondersCollection
     * @throws GetresponseApiException
     */
    public function getAutoresponders()
    {
        return $this->grContactListService->getAutoresponders();
    }

    /**
     * @param AddContactListDto $addContactListDto
     * @param string $languageCode
     * @throws GrApiException
     */
    public function createContactList(AddContactListDto $addContactListDto, $languageCode)
    {
        try {
            $this->grContactListService->createContactList(
                new AddContactListCommand(
                    $addContactListDto->getContactListName(),
                    $addContactListDto->getFromField(),
                    $addContactListDto->getReplyTo(),
                    $addContactListDto->getBodyId(),
                    $addContactListDto->getSubjectId(),
                    $languageCode
                )
            );
        } catch (GetresponseApiException $e) {
            throw GrApiException::createForCampaignNotAddedException($e);
        }
    }

}
