<?php
/**
 * Pavel Usachev <webcodekeeper@hotmail.com>
 * @copyright Copyright (c) 2019, Pavel Usachev
 */

namespace ALevel\Attributes\Model\Attribute\Backend;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

/**
 * Class Material
 * @package ALevel\Attributes\Model\Attribute\Backend
 */
class Material extends AbstractBackend
{
    /** {@inheritDoc} */
    public function validate(DataObject $object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());

        if ($value > 12 or $value < 1) {
            throw new LocalizedException(__('Please select correct month number'));
        }

        return true;
    }
}
