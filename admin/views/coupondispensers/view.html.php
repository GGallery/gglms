<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once (JPATH_COMPONENT_ADMINISTRATOR.'/models/unita.php');

class gglmsViewcoupondispensers extends JViewLegacy {

    function display($tpl = null) {

        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');


        $this->searchterms = $this->state->get('filter.search');


        $this->sidebar = JHtmlSidebar::render();
        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    protected function addToolBar() {

        JToolBarHelper::title(JText::_('COM_GGLMS_MANAGER_UNITAS'), 'unitas');
        JToolBarHelper::deleteList(JText::_('COM_GGLMS_FILES_SICUROELIMINARE'), 'unita.delete');
        JToolBarHelper::editList('coupondispenser.edit');
        JToolBarHelper::addNew('coupondispenser.add');
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_GGLMS_ADMINISTRATION'));
    }

}
