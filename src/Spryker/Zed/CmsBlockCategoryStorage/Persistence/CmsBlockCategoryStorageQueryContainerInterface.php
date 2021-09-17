<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Persistence;

use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CmsBlockCategoryStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery
     */
    public function queryCmsBlockCategoryStorageByIds(array $categoryIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategories(array $categoryIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface::queryCmsBlockCategoriesByCmsCategoryIds()} instead.
     *
     * @param array<int> $cmsBlockCategoriesIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoriesByIds(array $cmsBlockCategoriesIds): SpyCmsBlockCategoryConnectorQuery;

    /**
     * Specification:
     * - Returns a a query for the table `spy_cms_block_category_connector` filtered by cms categories ids.
     *
     * @api
     *
     * @param array<int> $cmsBlockCategoriesIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoriesByCmsCategoryIds(array $cmsBlockCategoriesIds): SpyCmsBlockCategoryConnectorQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $idPositions
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryIdsByPositionIds(array $idPositions);
}
