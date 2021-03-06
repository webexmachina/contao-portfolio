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

/**
 * Table tl_wem_portfolio_item_attribute.
 */
$GLOBALS['TL_DCA']['tl_wem_portfolio_item_attribute'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_wem_portfolio_item',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 4,
            'fields' => ['attribute DESC'],
            'headerFields' => ['title', 'pid', 'tstamp', 'date', 'published'],
            'panelLayout' => 'filter;sort,search,limit',
            'child_record_callback' => ['tl_wem_portfolio_item_attribute', 'listItemAttributes'],
            'child_record_class' => 'no_padding',
            'disableGrouping' => true,
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item_attribute']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item_attribute']['copy'],
                'href' => 'act=paste&amp;mode=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item_attribute']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item_attribute']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},attribute,value',
    ],

    // Fields
    'fields' => [
        'id' => [
            'label' => ['ID'],
            'search' => true,
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'foreignKey' => 'tl_wem_portfolio_item.title',
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'belongsTo', 'load' => 'lazy'],
        ],
        'createdAt' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item_attribute']['createdAt'],
            'default' => time(),
            'flag' => 8,
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],

        'attribute' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item_attribute']['attribute'],
            'exclude' => true,
            'filter' => true,
            'flag' => 11,
            'inputType' => 'select',
            'foreignKey' => 'tl_wem_portfolio_attribute.title',
            'eval' => ['chosen' => true, 'mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'hasOne', 'load' => 'eager'],
        ],
        'value' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item_attribute']['value'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
    ],
];

// Handle i18nl10n compatibility
$bundles = \System::getContainer()->getParameter('kernel.bundles');
if (\array_key_exists('VerstaerkerI18nl10nBundle', $bundles)) {
    $GLOBALS['TL_DCA']['tl_wem_portfolio_item_attribute']['fields']['attribute']['options_callback'] = ['tl_wem_portfolio_item_attribute', 'getOptionsByLanguage'];
}

/**
 * Handle Portfolio DCA functions.
 *
 * @author Web ex Machina <contact@webexmachina.fr>
 */
class tl_wem_portfolio_item_attribute extends Backend
{
    /**
     * Import the back end user object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Parse row.
     *
     * @param [Array] $arrRow
     *
     * @return [String]
     */
    public function listItemAttributes($arrRow)
    {
        $objAttribute = $this->Database->prepare('SELECT * FROM tl_wem_portfolio_attribute WHERE id = ?')->limit(1)->execute($arrRow['attribute']);

        return sprintf('%s -> %s', $objAttribute->title, $arrRow['value']);
    }

    /**
     * Limit attributes by their languages.
     *
     * @return [Array] [<description>]
     */
    public function getOptionsByLanguage($dc)
    {
        $objItem = \WEM\PortfolioBundle\Model\Item::findByPk($dc->activeRecord->pid);
        $objAttributes = \WEM\PortfolioBundle\Model\Attribute::findItems(['lang' => $objItem->i18nl10n_lang], 0, 0, ['title ASC']);

        if (!$objAttributes || 0 === $objAttributes->count()) {
            return [];
        }

        $r = [];
        while ($objAttributes->next()) {
            $r[$objAttributes->id] = $objAttributes->title;
        }

        return $r;
    }
}
