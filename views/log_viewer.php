<?php

/**
 * Log viewer view.
 *
 * @category   Apps
 * @package    Log_Viewer
 * @subpackage Views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->load->helper('number');
$this->lang->load('base');
$this->lang->load('log_viewer');

///////////////////////////////////////////////////////////////////////////////
// Settings form
///////////////////////////////////////////////////////////////////////////////

$buttons = array(
    form_submit_custom('display', lang('log_viewer_display')),
    anchor_cancel('/app/log_viewer', 'low')
);

echo form_open('log_viewer/index');
echo form_header(lang('base_settings'), array('id' => 'settings_form'));

echo field_dropdown('file', $log_file_options, $file, lang('log_viewer_file'), FALSE);
echo field_input('filter', $filter, lang('log_viewer_filter'), FALSE);
echo field_checkbox('full_line', $full_line, lang('log_viewer_show_full_line'), FALSE);
echo field_button_set($buttons);

echo form_footer();
echo form_close();

///////////////////////////////////////////////////////////////////////////////
// Summary table
///////////////////////////////////////////////////////////////////////////////

$buttons = array(
    form_submit_custom('export', lang('log_viewer_export_to_file')),
);

$headers = array(
    lang('log_viewer_entry')
);

$items = array();

foreach ($log_data as $id => $entry) {
    $item['title'] = $id;
    $item['action'] = NULL;
    $item['anchors'] = NULL;
    $item['details'] = array(
        '<span style=\'font-size: .8em; font-family: monospace;\'>' . htmlentities(($full_line ? $entry : substr($entry, 0, 120) . ' ...')) . '</span>'
    );

    $items[] = $item;
}

if ($is_truncated) {
    echo infobox_warning(
        lang('base_warning'),
        lang('log_viewer_result_too_big')
    );
}

// Table output
//-------------

echo form_open('log_viewer/export', array('target' => '_blank'));

$options['default_rows'] = 500;
$options['sort'] = FALSE;
$options['no_action'] = TRUE;

echo summary_table(
    lang('log_viewer_logs'),
    $buttons,
    $headers,
    $items,
    $options
);
echo "<input type='hidden' name='my_file' id='my_file' value='$file'>";
echo "<input type='hidden' name='my_filter' id='my_filter' value='$filter'>";
echo form_close();
