<?php

class Incite_View_Helper_InciteHelper extends Zend_View_Helper_Abstract
{
    public function inciteHelper()
    {
        return get_option('text_test');
    }
}
