<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\CmsBlockCategoryConnectorEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorEntityStoragePublishListener}
 *   and {@link \Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Event\Listener\CmsBlockCategoryConnectorEntityStorageUnpublishListener} instead.
 *
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Communication\CmsBlockCategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 */
class CmsBlockCategoryConnectorStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $idCategories = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventEntityTransfers, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY);

        if ($eventName === CmsBlockCategoryConnectorEvents::ENTITY_SPY_CMS_BLOCK_CATEGORY_CONNECTOR_DELETE) {
            $this->getFacade()->refreshOrUnpublish($idCategories);

            return;
        }

        $this->getFacade()->publish($idCategories);
    }
}
