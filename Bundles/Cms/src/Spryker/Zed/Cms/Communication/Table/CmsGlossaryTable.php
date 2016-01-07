<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Table;

use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsGlossaryTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const REQUEST_ID_MAPPING = 'id-mapping';
    const URL_CMS_GLOSSARY_EDIT = '/cms/glossary/edit/';
    const URL_CMS_GLOSSARY_DELETE = '/cms/glossary/delete/';

    /**
     * @var SpyCmsGlossaryKeyMappingQuery
     */
    protected $glossaryQuery;

    /**
     * @var array
     */
    protected $placeholders;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @var array
     */
    protected $searchArray;

    /**
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryQuery
     * @param int $idPage
     * @param array $placeholders
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryQuery
     * @param array $searchArray
     */
    public function __construct(SpyCmsGlossaryKeyMappingQuery $glossaryQuery, $idPage, array $placeholders = [], array $searchArray = [])
    {
        $this->glossaryQuery = $glossaryQuery;
        $this->idPage = $idPage;
        $this->placeholders = $placeholders;
        $this->searchArray = $searchArray;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => 'Id',
            SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => 'Placeholder',
            CmsQueryContainer::KEY => 'Glossary Key',
            CmsQueryContainer::TRANS => 'Glossary Value',
            self::ACTIONS => self::ACTIONS,
        ]);
        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
        ]);

        $config->setSearchable([
            SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING,
            SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER,
            CmsQueryContainer::KEY => SpyGlossaryKeyTableMap::COL_KEY,
            CmsQueryContainer::TRANS => SpyGlossaryTranslationTableMap::COL_VALUE,
        ]);

        $config->setUrl('table?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $this->idPage);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        if (!empty($this->searchArray['value'])) {
            $this->placeholders = $this->findPlaceholders($this->searchArray);
        }

        $query = $this->glossaryQuery;
        $queryResults = $this->runQuery($query, $config);

        $mappedPlaceholders = [];
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
                SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => $item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER],
                CmsQueryContainer::KEY => $item[CmsQueryContainer::KEY],
                CmsQueryContainer::TRANS => $item[CmsQueryContainer::TRANS],
                self::ACTIONS => implode(' ', $this->buildLinks($item)),
            ];
            $mappedPlaceholders[] = $item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER];
        }

        unset($queryResults);

        $results = $this->addExtractedPlaceholders($mappedPlaceholders, $results);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    private function buildLinks(array $item)
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(self::URL_CMS_GLOSSARY_EDIT, [
                CmsPageTable::REQUEST_ID_PAGE => $this->idPage,
                self::REQUEST_ID_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
            ]),
            'Edit'
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(self::URL_CMS_GLOSSARY_DELETE, [
                CmsPageTable::REQUEST_ID_PAGE => $this->idPage,
                self::REQUEST_ID_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
            ]),
            'Delete'
        );

        return $buttons;
    }

    /**
     * @param string $placeholder
     *
     * @return string
     */
    private function buildPlaceholderLinks($placeholder)
    {
        return '<a href="/cms/glossary/add/?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $this->idPage . '&placeholder=' . $placeholder . '" class="btn btn-xs btn-white">Add Glossary</a>';
    }

    /**
     * @param array $mappedPlaceholders
     * @param array $results
     *
     * @return array
     */
    protected function addExtractedPlaceholders(array $mappedPlaceholders, array $results)
    {
        foreach ($this->placeholders as $place) {
            if (!in_array($place, $mappedPlaceholders)) {
                $results[] = [
                    SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => null,
                    SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => $place,
                    CmsQueryContainer::KEY => null,
                    CmsQueryContainer::TRANS => null,
                    self::ACTIONS => $this->buildPlaceholderLinks($place),
                ];
            }
        }

        return $results;
    }

    /**
     * @param $searchItems
     *
     * @return array
     */
    private function findPlaceholders(array $searchItems)
    {
        $foundPlaceholders = [];
        foreach ($this->placeholders as $place) {
            if (stripos($place, $searchItems['value']) !== false) {
                $foundPlaceholders[] = $place;
            }
        }

        return $foundPlaceholders;
    }

}
