<?php

/**
 * Log_Viewer Settings View.
 *
 * @category   Apps
 * @package    Log_Viewer
 * @subpackage Views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('log_viewer');

///////////////////////////////////////////////////////////////////////////////
// Form handler
///////////////////////////////////////////////////////////////////////////////

$buttons = array(
    form_submit_custom('display', lang('log_viewer_display')),
    anchor_cancel('/app/log_viewer', 'low')
);

///////////////////////////////////////////////////////////////////////////////
// Form
///////////////////////////////////////////////////////////////////////////////

echo form_open('log_viewer/view');
echo form_header(lang('base_settings'), array('id' => 'settings_form'));

echo field_dropdown('file', $log_file_options, $file, lang('log_viewer_file'), FALSE);
echo field_input('filter', $filter, lang('log_viewer_filter'), FALSE);
echo field_checkbox('full_line', $full_line, lang('log_viewer_show_full_line'), FALSE);
echo field_button_set($buttons);

echo form_footer();
echo form_close();
