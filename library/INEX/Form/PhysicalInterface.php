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

/**
 *
 * @package INEX_Form
 */
class INEX_Form_PhysicalInterface extends INEX_Form
{
    /**
     *
     *
     */
    public function __construct( $options = null, $isEdit = false, $cancelLocation )
    {
        parent::__construct( $options, $isEdit );

        
        $switch = $this->createElement( 'select', 'switch_id' );

        $maxSwitchId = $this->createSelectFromDatabaseTable( $switch, 'SwitchTable', 'id',
            array( 'name' ),
            'name'
        );

        $switch->setRegisterInArrayValidator( true )
            ->setRequired( true )
            ->setLabel( 'Switch' )
            ->addValidator( 'between', false, array( 1, $maxSwitchId ) )
            ->setErrorMessages( array( 'Please select a switch' ) );

        $this->addElement( $switch );

        
        
        
        $switchPorts = $this->createElement( 'select', 'switchportid' );

        $switchPorts->setRequired( true )
            ->setRegisterInArrayValidator( false )
            ->setLabel( 'Port' )
            ->addValidator( 'greaterThan', false, array( 'min' => 1 ) )
            ->setErrorMessages( array( 'Please select a switch port' ) );
            
        $this->addElement( $switchPorts );
        
        $virtualInterface = $this->createElement( 'hidden', 'virtualinterfaceid' );
        $this->addElement( $virtualInterface );


        $status = $this->createElement( 'select', 'status' );
        $status->setMultiOptions( Physicalinterface::$STATES_TEXT )
            ->setRegisterInArrayValidator( true )
            ->setLabel( 'Status' )
            ->setErrorMessages( array( 'Please set the status' ) );

        $this->addElement( $status );




        $speed = $this->createElement( 'select', 'speed' );
        $speed->setMultiOptions( Physicalinterface::$SPEED )
        ->setRegisterInArrayValidator( true )
        ->setLabel( 'Speed' )
        ->setErrorMessages( array( 'Please set the speed' ) );

        $this->addElement( $speed );


        $duplex = $this->createElement( 'select', 'duplex' );
        $duplex->setMultiOptions( Physicalinterface::$DUPLEX )
        ->setRegisterInArrayValidator( true )
        ->setLabel( 'Duplex' )
        ->setErrorMessages( array( 'Please set the duplex' ) );

        $this->addElement( $duplex );





        $monitorindex = $this->createElement( 'text', 'monitorindex' );
        $monitorindex->addValidator( 'int' )
        ->setLabel( 'Monitor Index' )
        ->addFilter( 'StringTrim' )
        ->addFilter( new INEX_Filter_StripSlashes() );
        $this->addElement( $monitorindex );




        $notes = $this->createElement( 'textarea', 'notes' );
        $notes->setLabel( 'Notes' )
        ->setRequired( false )
        ->addFilter( new INEX_Filter_StripSlashes() )
        ->setAttrib( 'cols', 60 )
        ->setAttrib( 'rows', 5 );
        $this->addElement( $notes );


        $this->addElement( 'button', 'cancel', array( 'label' => 'Cancel', 'onClick' => "parent.location='"
        . $cancelLocation . "'" ) );

        $this->addElement( 'submit', 'commit', array( 'label' => 'Add' ) );

        $preselectSwitchPort = $this->createElement( 'hidden', 'preselectSwitchPort' );
        $this->addElement( $preselectSwitchPort );
        
        $preselectPhysicalInterface = $this->createElement( 'hidden', 'preselectPhysicalInterface' );
        $this->addElement( $preselectPhysicalInterface );
        
    }

}

?>