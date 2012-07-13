<?php
/**
 * MyComponent transport chunks
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
 * Description: Array of chunk objects for MyComponent package
 * @package mycomponent
 * @subpackage build
 */

$chunks = array();
$x=0;


$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'RadUiChartHead',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.raduicharthead.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'RadUiChartHeadInstance.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.raduichartheadinstance.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'RadUiCharts.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.raduicharts.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'RadUiTabContainer.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.raduitabcontainer.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'RadUitabContent.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.raduitabcontent.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'RadUiTabJs.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.raduitabjs.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'RadUiTabName.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.raduitabname.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'SlickGridCss',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.slickgridcss.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'SlickGridGrid',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.slickgridgrid.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'SlickGridJs.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.slickgridjs.html'),
    'properties' => '',
),'',true,true);
            
// RadUiBuild Chunks:
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'BuildSlickGridJson.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/build/chunk.buildslickgridjson.html'),
    'properties' => '',
),'',true,true);
            
$chunks[++$x]= $modx->newObject('modChunk');
$chunks[$x]->fromArray(array(
    'id' => $x,
    'name' => 'BuildSlickGridJsonHead.',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/build/chunk.buildslickgridjsonhead.html'),
    'properties' => '',
),'',true,true);
            

            

return $chunks;