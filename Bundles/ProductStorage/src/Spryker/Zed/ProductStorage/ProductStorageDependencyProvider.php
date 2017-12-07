<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductBridge;
use Spryker\Zed\ProductStorage\Dependency\QueryContainer\ProductStorageToProductQueryContainerBridge;
use Spryker\Zed\ProductStorage\Dependency\Service\ProductStorageToUtilSynchronizationBridge;

class ProductStorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SYNCHRONIZATION] = function (Container $container) {
            return new ProductStorageToUtilSynchronizationBridge($container->getLocator()->utilSynchronization()->service());
        };

        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductStorageToProductBridge($container->getLocator()->product()->facade());
        };

        $container[static::STORE] = function (Container $container) {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }

}