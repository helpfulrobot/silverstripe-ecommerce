<?php
/**
 * describes any dataobject (apart from pages)
 * that is editable in the CMS
 *
 *
 * @authors: Nicolaas [at] Sunny Side Up .co.nz
 * @package: ecommerce
 **/

interface EditableEcommerceObject
{

    /**
     * returns the link to edit the object
     * @param String | Null $action
     * @return String
     */
    public function CMSEditLink($action = null);
}
