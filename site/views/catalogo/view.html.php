<?php

/**
 * @version        1
 * @package        webtv
 * @author        antonio
 * @author mail    tony@bslt.it
 * @link
 * @copyright    Copyright (C) 2011 antonio - All rights reserved.
 * @license        GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

jimport('joomla.application.component.helper');

require_once JPATH_COMPONENT . '/models/catalogo.php';

class gglmsViewCatalogo extends JViewLegacy
{

    protected $params;

    function display($tpl = null)
    {
        $box = JRequest::getVar('box');
        //$DOMINIO="UICuneoFAD.it";
        $DOMINIO="http://www.Assolombardaservizifad.it";
        //$DOMINIO=dirname(juri::base());
        $DOMINIO=explode("//",$DOMINIO)[1];
        $DOMINIO=str_replace("www.","",$DOMINIO);
        $this->catalogoModel = new gglmsModelCatalogo();
        $this->catalogo=$this->catalogoModel->getCatalogo($DOMINIO,$box);
       


        parent::display($tpl);
    }
}
    