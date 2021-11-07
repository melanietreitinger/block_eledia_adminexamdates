// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block_eledia_adminexamdates
 * @copyright  2021 René Hansen <support@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import 'block_eledia_adminexamdates/datatables';

export const init = () => {

    var data = [
        [
            "Tiger Nixon",
            "System Architect",
        ],
        [
            "Garrett Winters",
            "Director",
        ]
    ];
    $(document).ready(function() {
        var table =$('#table1').DataTable({
            buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
            data: data
        });
        $('#btn-place').html(table.buttons().container());
        $("#table1").removeClass("dataTable");
    });
};


