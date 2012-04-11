<?php

/**
 * Log_Viewer settings controller.
 *
 * @category   Apps
 * @package    Log_Viewer
 * @subpackage Controllers
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

use \clearos\apps\log_viewer\Log_Viewer as Log_Viewer;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Log Viewer settings controller.
 *
 * @category   Apps
 * @package    Logs
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/log_viewer/
 */

class Settings extends ClearOS_Controller
{
    /**
     * Log Viewer settings settings default controller
     *
     * @return view
     */

    function index()
    {
        clearos_profile(__METHOD__, __LINE__);

        // Load dependencies
        //------------------

        $this->load->library('log_viewer/Log_Viewer');
        $this->lang->load('log_viewer');

        // Set validation rules
        //---------------------

        // Handle form submit
        //-------------------

        if ($this->input->post('display')) {
            try {
                $data['log_data'] = $this->log_viewer->get_log_entries($this->input->post('file'), $this->input->post('filter'));
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        $data['log_file_options'] = $this->log_viewer->get_log_files();

        if ($this->input->post('filter') == FALSE)
            $data['filter'] = '.*';
        else
            $data['filter'] = $this->input->post('filter');

        if ($this->input->post('file'))
            $data['file'] = $this->input->post('file');
        else
            $data['file'] = 'system';

        if ($this->input->post('full_line'))
            $data['full_line'] = $this->input->post('full_line');

        $data['max_bytes'] = Log_Viewer::MAX_BYTES;

        // Load views
        //-----------

        $this->page->view_form('settings', $data, lang('log_viewer_app_name'));
    }
}
