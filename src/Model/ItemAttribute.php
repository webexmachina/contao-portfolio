<?php

declare(strict_types=1);

/**
 * Contao Portfolio for Contao Open Source CMS
 * Copyright (c) 2015-2020 Web ex Machina
 *
 * @category ContaoBundle
 * @package  Web-Ex-Machina/contao-portfolio
 * @author   Web ex Machina <contact@webexmachina.fr>
 * @link     https://github.com/Web-Ex-Machina/contao-portfolio/
 */

namespace WEM\PortfolioBundle\Model;

use Contao\Model;
use RuntimeException as Exception;

/**
 * Reads and writes item attributes.
 */
class ItemAttribute extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_wem_portfolio_item_attribute';

    /**
     * Find item attributes, depends on the arguments.
     *
     * @param array
     * @param int
     * @param int
     * @param array
     *
     * @return Collection
     */
    public static function findItems($arrConfig = [], $intLimit = 0, $intOffset = 0, array $arrOptions = [])
    {
        try {
            $t = static::$strTable;
            $arrColumns = static::formatColumns($arrConfig);

            if ($intLimit > 0) {
                $arrOptions['limit'] = $intLimit;
            }

            if ($intOffset > 0) {
                $arrOptions['offset'] = $intOffset;
            }

            if (!isset($arrOptions['order'])) {
                $arrOptions['order'] = "$t.attribute ASC";
            }

            if (empty($arrColumns)) {
                return static::findAll($arrOptions);
            }

            return static::findBy($arrColumns, null, $arrOptions);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Count item attributes, depends on the arguments.
     *
     * @param array
     * @param array
     *
     * @return int
     */
    public static function countItems($arrConfig = [], array $arrOptions = [])
    {
        try {
            $t = static::$strTable;
            $arrColumns = static::formatColumns($arrConfig);
            if (empty($arrColumns)) {
                return static::countAll($arrOptions);
            }

            return static::countBy($arrColumns, null, $arrOptions);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Format ItemAttributeModel columns.
     *
     * @param [Array] $arrConfig [Configuration to format]
     *
     * @return [Array] [The Model columns]
     */
    public static function formatColumns($arrConfig)
    {
        try {
            $t = static::$strTable;
            $arrColumns = [];

            if ($arrConfig['pid']) {
                $arrColumns[] = "$t.pid = ".$arrConfig['pid'];
            }

            if ($arrConfig['attribute']) {
                $arrColumns[] = "$t.attribute = ".$arrConfig['attribute'];
            }

            if (1 === $arrConfig['displayInFrontend']) {
                ++$i;
                $arrColumns[] = "$t.attribute IN(SELECT t".$i.'.id FROM tl_wem_portfolio_attribute AS t'.$i.' WHERE t'.$i.".displayInFrontend = '1')";
            } elseif (0 === $arrConfig['displayInFrontend']) {
                ++$i;
                $arrColumns[] = "$t.attribute IN(SELECT t".$i.'.id FROM tl_wem_portfolio_attribute AS t'.$i.' WHERE t'.$i.".displayInFrontend = '')";
            }

            if ($arrConfig['not']) {
                $arrColumns[] = $arrConfig['not'];
            }

            return $arrColumns;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function findAllGroupBy($strField)
    {
        try {
            $t = static::$strTable;
            $objRows = \Database::getInstance()->prepare("SELECT * FROM $t GROUP BY $strField")->execute();

            if (!$objRows) {
                return null;
            }

            return static::createCollectionFromDbResult($objRows, $t);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
