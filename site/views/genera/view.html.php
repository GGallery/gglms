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

require_once JPATH_COMPONENT . '/controllers/generacoupon.php';


class gglmsViewGenera extends JViewLegacy
{

    protected $params;
    public $lista_corsi;
    public $societa_venditrici;
    public $check_coupon_attestato;

    function display($tpl = null)
    {

        $couponCtrl = new gglmsControllerGeneraCoupon();
        $this->lista_corsi = $couponCtrl->generaCoupon->lista_corsi;
        $this->societa_venditrici = $couponCtrl->generaCoupon->societa_venditrici;

        // leggo parametro config.check_coupon_attestato
        // se == 1 mostra la checkbox
        // se == 0 nascondi la checkbox e creali tutti abilitati
        $_config = new gglmsModelConfig();
        $this->check_coupon_attestato = $_config->getConfigValue('check_coupon_attestato');


        // Display the view
        parent::display($tpl);

    }
}
