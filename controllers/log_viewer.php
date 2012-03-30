<?php

/**
 * Log_Viewer class.
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

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Log_Viewer controller.
 *
 * @category   Apps
 * @package    Log_Viewer
 * @subpackage Controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/log_viewer/
 */

class Log_Viewer extends ClearOS_Controller
{
    /**
     * Log_Viewer default controller
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

        // Defaults
        $data = array(
            'log_file_options' => $this->log_viewer->get_log_files(),
            'filter' => '.*',
            'full_line' => FALSE
        );

        $this->page->view_form('settings', $data, lang('log_viewer_app_name'));
    }

    /**
     * Log_Viewer default controller
     *
     * @return view
     */

    function view()
    {
        
        clearos_profile(__METHOD__, __LINE__);

        $this->lang->load('log_viewer');

        $views = array(
            'log_viewer/settings',
            'log_viewer/logs'
        );

        $this->page->view_forms($views, lang('log_viewer_app_name'));
    }

    /**
     * Log_Viewer log export controller
     *
     * @return view
     */

    function export()
    {
        
        clearos_profile(__METHOD__, __LINE__);

        $log_file = $this->input->post('my_file');
        $filter  = $this->input->post('my_filter');

        header('Content-type: application/txt');
        header('Content-Disposition: attachment; filename=' . $log_file);
        header('Content-Disposition: inline; filename=' . $log_file);
        header('Pragma: no-cache');
        header('Expires: 0');

        // Load dependencies
        //------------------

        $this->load->library('log_viewer/Log_Viewer');
        try {
            $lines = $this->log_viewer->get_log_entries($log_file, $filter, -1);
        } catch (Exception $e) {
            $this->page->set_message(clearos_exception_message($e));
            redirect('log_viewer');
            return;
        }
        ob_start();
        foreach ($lines as $line)
            echo $line . "\n";
        ob_flush();
    }
}
