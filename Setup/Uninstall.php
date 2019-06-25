<?php
/**
 * Pavel Usachev <webcodekeeper@hotmail.com>
 * @copyright Copyright (c) 2019, Pavel Usachev
 */

namespace ALevel\Attributes\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Eav\Setup\EavSetupFactory;


class Uninstall implements UninstallInterface
{
    /** @var EavSetupFactory  */
    private $eavSetupFactory;

    /**
     * Uninstall constructor.
     * @param EavSetupFactory $eavSetupFactory\
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /** {@inheritDoc} */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $removeAttributeCode = [
            'subscribe',
            'clothing_material',
            'test_attribute',
            'test_swatch_attribute',
        ];

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        foreach ($removeAttributeCode as $attributeCode) {
            $eavSetup->removeAttribute(Product::ENTITY, $attributeCode);
        }
    }
}
