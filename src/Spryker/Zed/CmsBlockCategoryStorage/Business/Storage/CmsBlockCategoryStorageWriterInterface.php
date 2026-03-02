<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business\Storage;

interface CmsBlockCategoryStorageWriterInterface
{
    public function publish(array $categoryIds): void;

    public function refreshOrUnpublish(array $categoryIds): void;
}
