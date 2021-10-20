<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockCategoryStorage\Storage;

use Generated\Shared\Transfer\CmsBlockRequestTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface;
use Spryker\Client\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface;
use Spryker\Shared\CmsBlockCategoryStorage\CmsBlockCategoryStorageConstants;

class CmsBlockCategoryStorageReader implements CmsBlockCategoryStorageReaderInterface
{
    /**
     * @var string
     */
    protected const OPTION_KEY_POSITION = 'position';

    /**
     * @var string
     */
    protected const KEY_CMS_BLOCK_CATEGORIES = 'cms_block_categories';

    /**
     * @var string
     */
    protected const KEY_BLOCK_KEYS = 'block_keys';

    /**
     * @var \Spryker\Client\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\CmsBlockCategoryStorage\Dependency\Client\CmsBlockCategoryStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        CmsBlockCategoryStorageToStorageClientInterface $storageClient,
        CmsBlockCategoryStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockRequestTransfer $cmsBlockRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\CmsBlockTransfer>
     */
    public function getCmsBlocksByOptions(CmsBlockRequestTransfer $cmsBlockRequestTransfer): array
    {
        if (!$cmsBlockRequestTransfer->getCategory()) {
            return [];
        }

        $position = $cmsBlockRequestTransfer->getPosition();
        $searchKey = $this->generateKey(
            (string)$cmsBlockRequestTransfer->getCategory(),
            CmsBlockCategoryStorageConstants::CMS_BLOCK_CATEGORY_RESOURCE_NAME,
        );

        $blocks = $this->storageClient->get($searchKey);

        if (!$blocks) {
            return [];
        }

        foreach ($blocks[static::KEY_CMS_BLOCK_CATEGORIES] as $blockData) {
            if ($position && $blockData[static::OPTION_KEY_POSITION] === $position) {
                return $this->mapBlockKeysArrayToCmsBlockTransfers($blockData[static::KEY_BLOCK_KEYS]);
            }
        }

        return [];
    }

    /**
     * @param string $reference
     * @param string $resourceName
     * @param string|null $localeName
     * @param string|null $storeName
     *
     * @return string
     */
    protected function generateKey(
        string $reference,
        string $resourceName,
        ?string $localeName = null,
        ?string $storeName = null
    ): string {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($storeName);
        $synchronizationDataTransfer->setLocale($localeName);
        $synchronizationDataTransfer->setReference($reference);

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param array<string> $blockKeys
     *
     * @return array<\Generated\Shared\Transfer\CmsBlockTransfer>
     */
    protected function mapBlockKeysArrayToCmsBlockTransfers(array $blockKeys): array
    {
        $cmsBlockTransfers = [];

        foreach ($blockKeys as $blockKey) {
            $cmsBlockTransfers[] = (new CmsBlockTransfer())->setKey($blockKey);
        }

        return $cmsBlockTransfers;
    }
}
