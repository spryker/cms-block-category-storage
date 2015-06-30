<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductOption\ProductOptionDependencyProvider;
use SprykerFeature\Zed\ProductOption\ProductOptionConfig;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionBusiness;
use SprykerFeature\Zed\ProductOption\Business\Model\DataImportWriterInterface;
use SprykerFeature\Zed\ProductOption\Business\Model\ProductOptionReaderInterface;

/**
 * @method ProductOptionBusiness getFactory()
 * @method ProductOptionConfig getConfig()
 */
class ProductOptionDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return DataImportWriterInterface
     */
    public function getDataImportWriterModel()
    {
        return $this->getFactory()->createModelDataImportWriter(
            $this->getQueryContainer(),
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_PRODUCT),
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE)
        );
    }

    /**
     * @return ProductOptionReaderInterface
     */
    public function getProductOptionReaderModel()
    {
        return $this->getFactory()->createModelProductOptionReader(
            $this->getQueryContainer()
        );
    }
}