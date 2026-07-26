<?php

/*
  
  Copyright (C) 2007 - 2021, Christoph Marti
Copyleft 2021- Christian M. Stefan, Florian Meerwinck

  LICENCE TERMS:
  This module is free software. You can redistribute it and/or modify it 
  under the terms of the GNU General Public License - version 2 or later, 
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  DISCLAIMER:
  This module is distributed in the hope that it will be useful, 
  but WITHOUT ANY WARRANTY; without even the implied warranty of 
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
  GNU General Public License for more details.
*/


//Prevent this file from being accessed directly
defined('WB_PATH') or exit("Cannot access this file directly"); 

// ***************************************************************************
// SET DEFAULT VALUES OF SOME ADDITIONAL SETTINGS
// ***************************************************************************


// CART (FRONTEND)
// ****************

// Default cart thumb max. size (px)
global $cart_thumb_max_size;
$cart_thumb_max_size = 40;


// TEMPLATES (FRONTEND)
// ********************

// For item detail templates built with a table wrap selects in a table row
// Affects item options selects => [OPTION] placeholder
global $use_table;
$use_table = FALSE;

// On item detail pages chop long pagination links and add … (horizontal ellipsis)
// Number of allowed chars before chopping link text
global $link_length;
$link_length = 24;


// IMAGES AND THUMBNAILS (BACKEND)
// *******************************

// Name of the media subfolder that contains the bakery images and thumbs
// No more than a proper directory name - no leading nor trailing slash
global $img_dir;
$img_dir = 'bakery';

// Selectable thumbnail default sizes (modify page settings)
global $default_thumb_sizes;
$default_thumb_sizes['40']  = '40x40px';
$default_thumb_sizes['50']  = '50x50px';
$default_thumb_sizes['60']  = '60x60px';
$default_thumb_sizes['75']  = '75x75px';
$default_thumb_sizes['100'] = '100x100px';
$default_thumb_sizes['125'] = '125x125px';
$default_thumb_sizes['150'] = '150x150px';

// Accepted max lenght of image filenames (modify item)
global $filename_max_length;
$filename_max_length = 40;

// For item images set image resize default values (modify item)
global $fetch_item;
$fetch_item['imgresize'] = '';  // yes = selected by default
$fetch_item['quality']   = 75;
$fetch_item['maxwidth']  = 400;
$fetch_item['maxheight'] = 300;

/*
 * Default EU tax zone (2012) for general settings
 * =================================================================
 * 
 *  AT - Austria        BE - Belgium            BG - Bulgaria
 *  CY - Cyprus         CZ - Czech Republic     DK - Denmark
 *  EE - Estonia        FI - Finland            FR - France
 *  DE - Germany        GR - Greece             HU - Hungary
 *  IE - Ireland        IT - Italy              LV - Latvia
 *  LT - Lithuania      LU - Luxembourg         MT - Malta
 *  NL - Netherlands    PL - Poland             PT - Portugal
 *  RO - Romania        SK - Slovakia           SI - Slovenia
 *  ES - Spain          SE - Sweden             GB - United Kingdom	 
*/
// global $tax_group; // (we don't need it as global variable)
$cfg_tax_group = 'AT,BE,BG,CY,CZ,DK,EE,FI,FR,DE,GR,HU,IE,IT,LV,LT,LU,MT,NL,PL,PT,RO,SK,SI,ES,SE';