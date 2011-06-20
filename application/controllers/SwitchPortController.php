<?php

/*
 * Copyright (C) 2009-2011 Internet Neutral Exchange Association Limited.
 * All Rights Reserved.
 * 
 * This file is part of IXP Manager.
 * 
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 * 
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 * 
 * http://www.gnu.org/licenses/gpl-2.0.html
 */


/*
 *
 *
 * http://www.inex.ie/
 * (c) Internet Neutral Exchange Association Ltd
 */

class SwitchPortController extends INEX_Controller_FrontEnd
{
    public function init()
    {
        $this->frontend['defaultOrdering'] = 'id';
        $this->frontend['model']           = 'Switchport';
        $this->frontend['name']            = 'SwitchPort';
        $this->frontend['pageTitle']       = 'Switch Ports';

        $this->frontend['pagination']       = false;
        
        $this->frontend['columns'] = array(

            'displayColumns' => array( 'id', 'name', 'type', 'switchid' ),

            'viewPanelRows'  => array( 'name', 'type', 'switchid' ),
            'viewPanelTitle' => 'name',

            'sortDefaults' => array(
                'column' => 'id',
                'order'  => 'asc'
            ),

            'id' => array(
                'label' => 'ID',
                'hidden' => true
            ),

            'name' => array(
                'label' => 'Name',
                'sortable' => 'true',
            ),

            'type' => array(
                'label' => 'Type',
                'sortable' => true,
                'type' => 'xlate',
                'xlator' => Switchport::$TYPE_TEXT
            ),

            'switchid' => array(
                'type' => 'hasOne',
                'model' => 'SwitchTable',
                'controller' => 'switch',
                'field' => 'name',
                'label' => 'Switch',
                'sortable' => true
            )

        );

        parent::feInit();
    }

    
    protected function _preList( $dataQuery )
    {
        // load switch names
        $this->view->switches = Doctrine_Query::create()
            ->from( 'SwitchTable s' )
            ->orderBy( 's.name' )
            ->fetchArray();
            
        $this->view->switchid = $this->_getParam( 'switchid', null );
        
        // and limit to a single switch
        return $dataQuery->andWhere( 'x.switchid = ?', $this->_getParam( 'switchid', 0 ) );
    }
    
    
}

?>