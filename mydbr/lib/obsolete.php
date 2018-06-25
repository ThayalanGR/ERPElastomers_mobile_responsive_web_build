<?php

/**
 * A list of files and directories that need to be removed once update of myDBR is complete.
 * This list must contain all files that have been removed since previous versions.
 * 
 * Directories can only be removed if they are empty, therfore always put files before 
 * directories in the list below
 *
 * File and directory paths are relative to the myDBR installation folder
 */

$obsolete_files = array(
    'lib/external/editarea',
    'images/copytoclipboard.swf',
    'extensions/syntaxhighlighter/scripts/clipboard.swf'
);
