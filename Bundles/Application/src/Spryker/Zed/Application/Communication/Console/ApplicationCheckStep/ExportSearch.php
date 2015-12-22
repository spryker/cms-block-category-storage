<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

use Spryker\Zed\Application\Business\ApplicationFacade;

/**
 * @method ApplicationFacade getFacade()
 */
class ExportSearch extends AbstractApplicationCheckStep
{

    /**
     * @return void
     */
    public function run()
    {
        $this->getFacade()->runCheckStepExportSearch($this->logger);
    }

}