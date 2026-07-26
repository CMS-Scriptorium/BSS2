<?php

// Add {BXT} DB Prefix to reduce long table names in queries
// and allow for easy module rename in future versions

$database->addPrefix('{BXT}', TABLE_PREFIX.'mod_bakery');

/**
 * [1] Just to make sure the WBCE autoloader will find the module classes
 *     (As this one line is missing in the "inizialize" file of the root.)
 */
WbAuto::AddDir(WB_PATH."/modules/");

/**
 * [1.1] Just to make sure the WBCE autoloader will find the frontend-template
 *       classes and also the theme ones.
 *       (As this one line is also missing in the "inizialize" file of the root.)
 */
WbAuto::AddDir(WB_PATH."/templates/");

/**
 * [2] Backwards for the "L_" processTranslation by Stefek.
 */
if (!defined('TWIG_SHOW_MISSING_LANG_STRINGS'))
{
    define('TWIG_SHOW_MISSING_LANG_STRINGS', false);
}
