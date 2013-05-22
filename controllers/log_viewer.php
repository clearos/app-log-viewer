<?php

/**
 * Log_Viewer controller.
 *
 * @category   apps
 * @package    log-viewer
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011-2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/log_viewer/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Log_Viewer controller.
 *
 * @category   apps
 * @package    log-viewer
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011-2013 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/log_viewer/
 */

class Log_Viewer extends ClearOS_Controller
{
    /**
     * Log Viewer default controller.
     *
     * @return view
     */

    function index()
    {
        // Load dependencies
        //------------------

        $this->load->library('log_viewer/Log_Viewer');
        $this->lang->load('log_viewer');

        // Handle form submit
        //-------------------

        if ($this->input->post('display')) {
            try {
                $log_entries = $this->log_viewer->get_log_entries($this->input->post('file'), $this->input->post('filter'));
                $status = array_shift($log_entries);

                $data['log_data'] = $log_entries;
                $data['is_truncated'] = ($status === \clearos\apps\log_viewer\Log_Viewer::SEARCH_TRUNCATED) ? TRUE : FALSE;
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load view data
        //---------------

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

        // Load views
        //-----------

        $options['type'] = MY_Page::TYPE_WIDE_CONFIGURATION;

        $this->page->view_form('log_viewer', $data, lang('log_viewer_app_name'), $options);
    }

    /**
     * Log_Viewer log export controller
     *
     * @return view
     */

    function export()
    {
        // Load dependencies
        //------------------

        $this->load->library('log_viewer/Log_Viewer');

        // Export data
        //------------

        $log_file = $this->input->post('my_file');
        $filter  = $this->input->post('my_filter');

        header('Content-type: application/txt');
        header('Content-Disposition: attachment; filename=' . $log_file);
        header('Content-Disposition: inline; filename=' . $log_file);
        header('Pragma: no-cache');
        header('Expires: 0');

        try {
            $lines = $this->log_viewer->get_log_entries($log_file, $filter);
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
