<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Glossary\Business\Exception\KeyExistsException;
use Spryker\Zed\Glossary\Business\Exception\MissingKeyException;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;
use Spryker\Zed\Glossary\Business\Exception\TranslationExistsException;

interface CmsToGlossaryInterface
{

    /**
     * @param int $idKey
     * @param array $data
     *
     * @throws MissingTranslationException
     *
     * @return string
     */
    public function translateByKeyId($idKey, array $data = []);

    /**
     * @param string $keyName
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return TranslationTransfer
     */
    public function createTranslationForCurrentLocale($keyName, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return TranslationTransfer
     */
    public function createTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     *
     * @throws KeyExistsException
     *
     * @return int
     */
    public function createKey($keyName);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getKeyIdentifier($keyName);

    /**
     * @param int $idKey
     *
     * @return void
     */
    public function touchCurrentTranslationForKeyId($idKey);

    /**
     * @param string $keyName
     *
     * @return int
     */
    public function getOrCreateKey($keyName);

    /**
     * @param KeyTranslationTransfer $keyTranslationTransfer
     *
     * @return bool
     */
    public function saveGlossaryKeyTranslations(KeyTranslationTransfer $keyTranslationTransfer);

}