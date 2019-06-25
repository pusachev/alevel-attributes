<?php
/**
 * Pavel Usachev <webcodekeeper@hotmail.com>
 * @copyright Copyright (c) 2019, Pavel Usachev
 */

namespace ALevel\Attributes\Setup;

use ALevel\Attributes\Model\Attribute\Backend\Subscribe;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\StateException;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Downloadable\Model\Product\Type as DownloadableType;

use ALevel\Attributes\Model\Attribute\Source\Material as MaterialSourceModel;
use ALevel\Attributes\Model\Attribute\Frontend\Material as MaterialFrontendModel;
use ALevel\Attributes\Model\Attribute\Frontend\Test as TestFrontendModel;
use ALevel\Attributes\Model\Attribute\Frontend\Subscribe as SubscribeFrontendModel;
use ALevel\Attributes\Model\Attribute\Backend\Material as MaterialBackendModel;
use ALevel\Attributes\Model\Attribute\Backend\Test as TestBackendModel;
use ALevel\Attributes\Model\Attribute\Backend\Subscribe as SubscribeBackendModel;

/**
 * Class InstallData
 * @package ALevel\Attributes\Setup
 */
class InstallData implements InstallDataInterface
{
    /** @var EavSetupFactory  */
    private $eavSetupFactory;

    /** @var EavSetup */
    private $eavSetup;

    /** @var EavConfig */
    private $eavConfig;

    /** @var OptionCollectionFactory */
    private $attrOptionCollectionFactory;

    /** @var AttributeRepositoryInterface */
    private $attributeRepositoryInterface;

    /**
     * InstallData constructor.
     *
     * @param EavSetupFactory $eavSetupFactory\
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        EavConfig $eavConfig,
        AttributeRepositoryInterface $attributeRepository,
        OptionCollectionFactory $attrOptionCollectionFactory
    ) {
        $this->eavSetupFactory              = $eavSetupFactory;
        $this->eavConfig                    = $eavConfig;
        $this->attributeRepositoryInterface = $attributeRepository;
        $this->attrOptionCollectionFactory  = $attrOptionCollectionFactory;
    }

    /** {@inheritDoc} */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createSimpleTextAttribute($setup);
        $this->createClothingMaterialAttribute($setup);
        $this->createdSwatchAttribute($setup);


        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function createSampleAttributeForVirtualProducts(ModuleDataSetupInterface $setup)
    {
        $this->getEavSetup($setup)
             ->addAttribute(
                Product::ENTITY,
                'subscribe',
                [
                    'group' => 'General', //Means that we add an attribute to the attribute group “General”, which is present in all attribute sets.
                    'type' => 'int', //varchar means that the values will be stored in the catalog_eav_varchar table.
                    'label' => 'Subscribe', //A label of the attribute (that is, how it will be rendered in the backend and on the frontend).
                    'input' => 'text',
                    'source' => '', // provides a list of options
                    'frontend' => SubscribeFrontendModel::class, //defines how it should be rendered on the frontend
                    'backend' => SubscribeBackendModel::class, //allows you to perform certain actions when an attribute is loaded or saved. In our example, it will be validation.
                    'required' => false,
                    'sort_order' => 50,
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL, // defines the scope of its values (global, website, or store)
                    'is_used_in_grid' => true, //is used in admin product grid
                    'is_visible_in_grid' => true, // is visibile column in admin product grid
                    'is_filterable_in_grid' => true, // is used for filter in admin product grid
                    'visible' => true, //A flag that defines whether an attribute should be shown on the “More Information” tab on the frontend
                    'is_html_allowed_on_front' => true, //Defines whether an attribute value may contain HTML
                    'visible_on_front' => true, // A flag that defines whether an attribute should be shown on product listing
                    'apply_to' => [Type::TYPE_VIRTUAL, DownloadableType::TYPE_DOWNLOADABLE]
                ]
            );
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function createClothingMaterialAttribute(ModuleDataSetupInterface $setup)
    {

        $this->getEavSetup($setup)
             ->addAttribute(
            Product::ENTITY,
            'clothing_material',
            [
                'group' => 'General', //Means that we add an attribute to the attribute group “General”, which is present in all attribute sets.
                'type' => 'varchar', //varchar means that the values will be stored in the catalog_eav_varchar table.
                'label' => 'Clothing Material', //A label of the attribute (that is, how it will be rendered in the backend and on the frontend).
                'input' => 'select',
                'source' => MaterialSourceModel::class, // provides a list of options
                'frontend' => MaterialFrontendModel::class, //defines how it should be rendered on the frontend
                'backend' => MaterialBackendModel::class, //allows you to perform certain actions when an attribute is loaded or saved. In our example, it will be validation.
                'required' => false,
                'sort_order' => 50,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL, // defines the scope of its values (global, website, or store)
                'is_used_in_grid' => true, //is used in admin product grid
                'is_visible_in_grid' => true, // is visibile column in admin product grid
                'is_filterable_in_grid' => true, // is used for filter in admin product grid
                'visible' => true, //A flag that defines whether an attribute should be shown on the “More Information” tab on the frontend
                'is_html_allowed_on_front' => true, //Defines whether an attribute value may contain HTML
                'visible_on_front' => true // A flag that defines whether an attribute should be shown on product listing
            ]
        );

    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function createSimpleTextAttribute(ModuleDataSetupInterface $setup)
    {
        $this->getEavSetup($setup)
             ->addAttribute(
            Product::ENTITY,
            'test_attribute',
            [
                'group' => 'General', //Means that we add an attribute to the attribute group “General”, which is present in all attribute sets.
                'type' => 'varchar', //varchar means that the values will be stored in the catalog_eav_varchar table.
                'label' => 'Test Attribute', //A label of the attribute (that is, how it will be rendered in the backend and on the frontend).
                'input' => 'text',
                'source' => '',
                'frontend' => TestFrontendModel::class, //defines how it should be rendered on the frontend
                'backend' => TestBackendModel::class, //allows you to perform certain actions when an attribute is loaded or saved. In our example, it will be validation.
                'required' => false,
                'sort_order' => 30,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL, // defines the scope of its values (global, website, or store)
                'is_used_in_grid' => true, //is used in admin product grid
                'is_visible_in_grid' => true, // is visibile column in admin product grid
                'is_filterable_in_grid' => true, // is used for filter in admin product grid
                'visible' => true, //A flag that defines whether an attribute should be shown on the “More Information” tab on the frontend
                'is_html_allowed_on_front' => true, //Defines whether an attribute value may contain HTML
                'visible_on_front' => true // A flag that defines whether an attribute should be shown on product listing
            ]
        );
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @throws LocalizedException
     * @throws StateException
     */
    private function createdSwatchAttribute(ModuleDataSetupInterface $setup)
    {
        $this->getEavSetup($setup)
            ->addAttribute(
            Product::ENTITY,
            'test_swatch_attribute',
            [
                'type' => 'int',
                'label' => 'Test Swatch Attribute',
                'input' => 'select',
                'required' => false,
                'user_defined' => true,
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_in_advanced_search' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'General',
                'option' => [
                    'values' => [
                        'Black',
                        'Blue',
                        'Brown',
                        'Gray',
                        'Green',
                        'Lavender',
                        'Multi',
                        'Orange',
                        'Purple',
                        'Red',
                        'White',
                        'Yellow'
                    ]
                ]
            ]
        );
        $this->eavConfig->clear();
        $attribute = $this->eavConfig->getAttribute(Product::ENTITY, 'test_swatch_attribute');
        if (!$attribute) {
            return;
        }
        $attributeData['option'] = $this->addExistingOptions($attribute);
        $attributeData['frontend_input'] = 'select';
        $attributeData['is_filterable_in_search'] = 1;
        $attributeData['swatch_input_type'] = 'visual';
        $attributeData['update_product_preview_image'] = 1;
        $attributeData['use_product_image_for_swatch'] = 0;
        $attributeData['optionvisual'] = $this->getOptionSwatch($attributeData);
        $attributeData['defaultvisual'] = $this->getOptionDefaultVisual($attributeData);
        $attributeData['swatchvisual'] = $this->getOptionSwatchVisual($attributeData);
        $attribute->addData($attributeData);

        $this->attributeRepositoryInterface->save($attribute);

    }

    /**
     * @param array $attributeData
     * @return array
     */
    private function getOptionSwatch(array $attributeData)
    {
        $optionSwatch = ['order' => [], 'value' => [], 'delete' => []];
        $i = 0;
        foreach ($attributeData['option'] as $optionKey => $optionValue) {
            $optionSwatch['delete'][$optionKey] = '';
            $optionSwatch['order'][$optionKey] = (string)$i++;
            $optionSwatch['value'][$optionKey] = [$optionValue, ''];
        }
        return $optionSwatch;
    }

    /**
     * @param array $attributeData
     * @return array
     */
    private function getOptionSwatchVisual(array $attributeData)
    {
        $optionSwatch = ['value' => []];
        foreach ($attributeData['option'] as $optionKey => $optionValue) {
            if (substr($optionValue, 0, 1) == '#' && strlen($optionValue) == 7) {
                $optionSwatch['value'][$optionKey] = $optionValue;
            } elseif ($this->colorMap[$optionValue]) {
                $optionSwatch['value'][$optionKey] = $this->colorMap[$optionValue];
            } else {
                $optionSwatch['value'][$optionKey] = $this->colorMap['White'];
            }
        }
        return $optionSwatch;
    }

    /**
     * @param array $attributeData
     * @return array
     */
    private function getOptionDefaultVisual(array $attributeData)
    {
        $optionSwatch = $this->getOptionSwatchVisual($attributeData);
        if (isset(array_keys($optionSwatch['value'])[0])) {
            return [array_keys($optionSwatch['value'])[0]];
        } else {
            return [''];
        }
    }

    /**
     * @param eavAttribute $attribute
     * @return array
     */
    private function addExistingOptions(eavAttribute $attribute)
    {
        $options = [];
        $attributeId = $attribute->getId();
        if ($attributeId) {
            $this->loadOptionCollection($attributeId);
            foreach ($this->optionCollection[$attributeId] as $option) {
                $options[$option->getId()] = $option->getValue();
            }
        }
        return $options;
    }

    /**
     * @param $attributeId
     */
    private function loadOptionCollection($attributeId)
    {
        if (empty($this->optionCollection[$attributeId])) {
            $this->optionCollection[$attributeId] = $this->attrOptionCollectionFactory->create()
                ->setAttributeFilter($attributeId)
                ->setPositionOrder('asc', true)
                ->load();
        }
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @return EavSetup
     */
    private function getEavSetup(ModuleDataSetupInterface $setup)
    {
        if (null === $this->eavSetup) {
            $this->eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        }

        return $this->eavSetup;
    }
}
