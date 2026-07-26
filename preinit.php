<?php

// Add {BXT} DB Prefix to reduce long table names in queries
// and allow for easy module rename in future versions

$database->addPrefix('{BXT}', TABLE_PREFIX.'mod_bakery');