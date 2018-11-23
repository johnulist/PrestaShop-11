<?php
namespace GetResponse\CustomFieldsMapping;

use GrShareCode\TypedCollection;

/**
 * Class CustomFieldMappingCollection
 * @package GetResponse\CustomFieldsMapping
 */
class CustomFieldMappingCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType(CustomFieldMapping::class);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        /** @var CustomFieldMapping $customFieldMapping */
        foreach ($this->getIterator() as $customFieldMapping) {
            $result[] = $customFieldMapping->toArray();
        }
        return $result;
    }
}
