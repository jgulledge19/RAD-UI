<?php
/**
 * MyComponent transport snippets
 * Copyright 2011 Your Name <you@yourdomain.com>
 * @author Your Name <you@yourdomain.com>
 * 1/1/11
 *
 * MyComponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * MyComponent is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * MyComponent; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package mycomponent
 */
/**
 * Description:  Array of snippet objects for MyComponent package
 * @package mycomponent
 * @subpackage build
 */

if (! function_exists('getSnippetContent')) {
    function getSnippetContent($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<?php','',$o);
        $o = str_replace('?>','',$o);
        $o = trim($o);
        return $o;
    }
}
$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1, // set this in order not the ID for the snippet
    'name' => 'RadUiCharts',
    'description' => 'Display Flot Chart',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.raduicharts.php'),
),'',true,true);
//$properties = include $sources['data'].'/properties/properties.mysnippet1.php';
//$snippets[1]->setProperties($properties);
//unset($properties);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2, // set this in order not the ID for the snippet
    'name' => 'RadUiGrid',
    'description' => 'Display a complete CRUD grid',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/raduigrid.snippet.php'),
),'',true,true);

$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 3, // set this in order not the ID for the snippet
    'name' => 'RadUiJson',
    'description' => 'Create the JSON for RadUiGrid and does all DB functions',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/raduijson.snippet.php'),
),'',true,true);

$snippets[4]= $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 4, // set this in order not the ID for the snippet
    'name' => 'RadUiTabs',
    'description' => 'Create JS tabs',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/raduitabs.snippet.php'),
),'',true,true);

return $snippets;