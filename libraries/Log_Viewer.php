<?php

/**
 * Log_Viewer class.
 *
 * @category   Apps
 * @package    Log_Viewer
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/log_viewer/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\log_viewer;

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('log_viewer');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Engine as Engine;
use \clearos\apps\base\File as File;
use \clearos\apps\base\Folder as Folder;

clearos_load_library('base/Engine');
clearos_load_library('base/File');
clearos_load_library('base/Folder');

// Exceptions
//-----------

use \clearos\apps\base\Validation_Exception as Validation_Exception;

clearos_load_library('base/Validation_Exception');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Log_Viewer class.
 *
 * @category   Apps
 * @package    Log_Viewer
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/log_viewer/
 */

class Log_Viewer extends Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const FOLDER_LOG_FILES = '/var/log';
    const MAX_BYTES = 512000;

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Log_Viewer constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);
    }

    /**
     * Get Log files.
     *
     * @return array
     * @throws Engine_Exception
     */

    public function get_log_files()
    {
        clearos_profile(__METHOD__, __LINE__);

        $folder = new Folder(self::FOLDER_LOG_FILES, TRUE);
        $options['follow_symlinks'] = TRUE;
        $files = $folder->get_recursive_listing($options);

        $list = array();

        foreach ($files as $file) {
            if (preg_match("/(Console)|(anaconda)|(btmp)|(cores)|(old)|(sa\/)|(ssl_)|(snort\/)|(ksyms)|(lastlog)|(rpmpkgs)|(wtmp)|(Xorg)|(gz$)/", $file))
                continue;

            $pathregex = preg_quote(self::FOLDER_LOG_FILES, "/");
            $filevalue = preg_replace("/$pathregex\//", "", $file);
            $list[$filevalue] = $filevalue;
        }

        return $list;
    }

    /**
     * Get Log file entries.
     *
     * @param String $log_file log file contents
     * @param String $filter   filter
     * @param int    $max      maximum number of bytes to fetch
     *
     * @return array
     * @throws Engine_Exception
     */

    public function get_log_entries($log_file, $filter = ".*", $max = self::MAX_BYTES)
    {
        clearos_profile(__METHOD__, __LINE__);

        Validation_Exception::is_valid($this->validate_log_file($log_file));

        $target = new File(self::FOLDER_LOG_FILES . "/" . $log_file, TRUE);
        $result = $target->get_search_results($filter, $max);

        return $result;
    }

    ///////////////////////////////////////////////////////////////////////////////
    // V A L I D A T I O N
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Validation routine for log file.
     *
     * @param string $log_file log file
     *
     * @return string error message if log file is invalid
     */

    public function validate_log_file($log_file)
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($log_file == NULL ||  $log_file == '')
            return lang('log_viewer_invalid_log_file');
        if (preg_match("/\.\./", $log_file) || preg_match("/^\//", $log_file))
            return lang('log_viewer_invalid_log_file');
    }
}
