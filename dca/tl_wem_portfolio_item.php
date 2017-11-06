<?php

/**
 * Module Portfolio for Contao Open Source CMS
 *
 * Copyright (c) 2015-2017 Web ex Machina
 *
 * @author Web ex Machina <http://www.webexmachina.fr>
 */

/**
 * Table tl_wem_portfolio_item
 */
$GLOBALS['TL_DCA']['tl_wem_portfolio_item'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_wem_portfolio_category',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 6,
			'fields'                  => array('title'),
			'panelLayout'             => 'filter;search',
		),
		'label' => array
		(
			'fields'                  => array('title', 'created_on'),
			'format'                  => '%s <span style="color:#999;padding-left:3px">[%s]</span>',
			'label_callback'          => array('tl_wem_portfolio_item', 'addIcon')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['toggle'],
				'icon'                => 'visible.svg',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_wem_portfolio_item', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '
			{title_legend},title,alias,customer,date;
			{media_legend},picture,pictures;
			{details_legend},teaser,technologies,duration,url;
			{tags_legend},tags;
			{testimonials_legend},testimonials;
			{publish_legend},published,start,stop
		'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'label'                   => array('ID'),
			'search'                  => true,
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_wem_portfolio_category.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'created_on' => array
		(
			'default'				  => time(),
			'flag'					  => 8,
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['alias'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_wem_portfolio_item', 'generateAlias')
			),
			'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
		'picture' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['picture'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'fieldType'=>'radio'),
			'sql'                     => "binary(16) NULL"
		),
		'pictures' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['pictures'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes'], 'multiple' => true, 'fieldType'=>'checkbox'),
			'sql'                     => "blob NULL"
		),
		'teaser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['teaser'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		'technologies' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['technologies'],
			'exclude'                 => true,
			'inputType'               => 'listWizard',
			'sql'                     => "blob NULL"
		),
		'duration' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['duration'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['url'],
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'rgxp' => 'url', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'tags' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['tags'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_wem_portfolio_tag.title',
			'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'multiple'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "blob NULL",
		),
		'testimonials' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['testimonials'],
		    'inputType'             => 'dcaWizard',
		    'foreignTable'          => 'tl_wem_portfolio_item_testimonial',
		    'foreignField'          => 'pid',
		    'params'                  => array
		    (
		        'do'                  => 'wem_portfolio_item',
		    ),
		    'eval'                  => array
		    (
		        'fields' => array('title', 'author', 'created_on'),
		        'editButtonLabel' => $GLOBALS['TL_LANG']['tl_wem_portfolio_item']['edit_testiomnials'],
		        'applyButtonLabel' => $GLOBALS['TL_LANG']['tl_wem_portfolio_item']['apply_testiomnials'],
		        'orderField' => 'sorting',
		        'showOperations' => true,
		        'operations' => array('edit', 'delete'),
		        'tl_class'=>'clr',
		    ),
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_portfolio_item']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
	)
);


/**
 * Handle Portfolio Items DCA functions
 *
 * @author Web ex Machina <contact@webexmachina.fr>
 */
class tl_wem_portfolio_item extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Add an image to each item in the tree
	 *
	 * @param array         $row
	 * @param string        $label
	 * @param DataContainer $dc
	 * @param string        $imageAttribute
	 * @param boolean       $blnReturnImage
	 * @param boolean       $blnProtected
	 *
	 * @return string
	 */
	public function addIcon($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false)
	{
		return '<img src="assets/contao/images/iconJPG.svg" width="18" height="18" alt="image/jpeg" style="margin-right:3px"><span style="vertical-align:-1px">'.$label.'</span>';
	}

	/**
	 * Auto-generate an article alias if it has not been set yet
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;

		// Generate an alias if there is none
		if ($varValue == '')
		{
			$autoAlias = true;
			$varValue = StringUtil::generateAlias($dc->activeRecord->title);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_wem_portfolio_item WHERE id=? OR alias=?")
								   ->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1)
		{
			if (!$autoAlias)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}

	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_wem_portfolio_item::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.svg';
		}

		$objPage = $this->Database->prepare("SELECT * FROM tl_wem_portfolio_category WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		if (!$this->User->isAllowed(BackendUser::CAN_EDIT_ARTICLES, $objPage->row()))
		{
			if ($row['published'])
			{
				$icon = preg_replace('/\.svg$/i', '_.svg', $icon); // see #8126
			}

			return Image::getHtml($icon) . ' ';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
	}


	/**
	 * Disable/enable a user group
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 *
	 * @throws Contao\CoreBundle\Exception\AccessDeniedException
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Set the ID and action
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		if ($dc)
		{
			$dc->id = $intId; // see #8043
		}

		// Trigger the onload_callback
		if (is_array($GLOBALS['TL_DCA']['tl_wem_portfolio_item']['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_wem_portfolio_item']['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($dc);
				}
				elseif (is_callable($callback))
				{
					$callback($dc);
				}
			}
		}

		// Check the field access
		if (!$this->User->hasAccess('tl_wem_portfolio_item::published', 'alexf'))
		{
			throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to publish/unpublish article ID "' . $intId . '".');
		}

		// Set the current record
		if ($dc)
		{
			$objRow = $this->Database->prepare("SELECT * FROM tl_wem_portfolio_item WHERE id=?")
									 ->limit(1)
									 ->execute($intId);

			if ($objRow->numRows)
			{
				$dc->activeRecord = $objRow;
			}
		}

		$objVersions = new Versions('tl_wem_portfolio_item', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_wem_portfolio_item']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_wem_portfolio_item']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $dc);
				}
			}
		}

		$time = time();

		// Update the database
		$this->Database->prepare("UPDATE tl_wem_portfolio_item SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		if ($dc)
		{
			$dc->activeRecord->tstamp = $time;
			$dc->activeRecord->published = ($blnVisible ? '1' : '');
		}

		// Trigger the onsubmit_callback
		if (is_array($GLOBALS['TL_DCA']['tl_wem_portfolio_item']['config']['onsubmit_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_wem_portfolio_item']['config']['onsubmit_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->{$callback[0]}->{$callback[1]}($dc);
				}
				elseif (is_callable($callback))
				{
					$callback($dc);
				}
			}
		}

		$objVersions->create();
	}
}
