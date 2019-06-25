<?php
/**
 * Pavel Usachev <webcodekeeper@hotmail.com>
 * @copyright Copyright (c) 2019, Pavel Usachev
 */

namespace ALevel\Attributes\Model\Attribute\Frontend;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;

class Subscribe extends AbstractFrontend
{
    /** {@inheritDoc} */
    public function getValue(DataObject $object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());

        return sprintf("%s <sup>month</sup>", $value);
    }
}