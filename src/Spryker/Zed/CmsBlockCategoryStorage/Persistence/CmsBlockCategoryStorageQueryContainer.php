<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryPositionTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStoragePersistenceFactory getFactory()
 */
class CmsBlockCategoryStorageQueryContainer extends AbstractQueryContainer implements CmsBlockCategoryStorageQueryContainerInterface
{
    /**
     * @var string
     */
    public const POSITION = 'position';

    /**
     * @var string
     */
    public const NAME = 'name';

    /**
     * @var string
     */
    protected const BLOCK_KEY = 'block_key';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorageQuery
     */
    public function queryCmsBlockCategoryStorageByIds(array $categoryIds)
    {
        return $this->getFactory()
            ->createSpyCmsBlockCategoryStorageQuery()
            ->filterByFkCategory_In($categoryIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategories(array $categoryIds)
    {
        $query = $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->innerJoinCmsBlockCategoryPosition()
            ->innerJoinCmsBlock()
            ->addJoin(
                [SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY_TEMPLATE],
                [SpyCategoryTableMap::COL_ID_CATEGORY, SpyCategoryTableMap::COL_FK_CATEGORY_TEMPLATE],
                Criteria::INNER_JOIN,
            )
            ->withColumn(SpyCmsBlockCategoryPositionTableMap::COL_NAME, static::POSITION)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME);

        if ($this->isCmsBlockKeyPropertyExists()) {
            $query->withColumn(SpyCmsBlockTableMap::COL_KEY, static::BLOCK_KEY);
        }

        return $query->filterByFkCategory_In($categoryIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer::queryCmsBlockCategoriesByCmsCategoryIds()} instead.
     *
     * @param array<int> $cmsBlockCategoriesIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoriesByIds(array $cmsBlockCategoriesIds): SpyCmsBlockCategoryConnectorQuery
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->innerJoinCmsBlockCategoryPosition()
            ->innerJoinCmsBlock()
            ->addJoin(
                [SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY_TEMPLATE],
                [SpyCategoryTableMap::COL_ID_CATEGORY, SpyCategoryTableMap::COL_FK_CATEGORY_TEMPLATE],
                Criteria::INNER_JOIN,
            )
            ->withColumn(SpyCmsBlockCategoryPositionTableMap::COL_NAME, static::POSITION)
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME)
            ->filterByIdCmsBlockCategoryConnector_In($cmsBlockCategoriesIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $cmsBlockCategoriesIds
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoriesByCmsCategoryIds(array $cmsBlockCategoriesIds): SpyCmsBlockCategoryConnectorQuery
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->filterByIdCmsBlockCategoryConnector_In($cmsBlockCategoriesIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idPositions
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryIdsByPositionIds(array $idPositions)
    {
        return $this->getFactory()
            ->getCmsBlockCategoryConnectorQuery()
            ->queryCmsBlockCategoryConnector()
            ->filterByFkCmsBlockCategoryPosition_In($idPositions)
            ->select([SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY]);
    }

    /**
     * This is added for BC reason to support previous versions of CmsBlock module.
     *
     * @return bool
     */
    protected function isCmsBlockKeyPropertyExists(): bool
    {
        return defined(SpyCmsBlockTableMap::class . '::COL_KEY');
    }
}
