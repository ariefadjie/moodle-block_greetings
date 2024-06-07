<?php
// This file is part of the Allocation form plugin
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
//

/**
 * Setup the webservices for the plugin.
 *
 * @package     block_greetings
 * @copyright   2022 Your name <your@email>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'block_greetings_add_greeting' => array(
        'classname' => 'block_greetings\external\add_greeting',
        'methodname' => 'execute',
        'classpath' => 'block/greetings/classes/external/add_greeting.php',
        'description' => "Adds a greetings message.",
        'type' => 'write',
        'ajax' => true,
        'capabilities'  => 'block/greetings:postmessages',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE, 'block_mobile'),
    ),
    'block_greetings_fetch_greetings' => array(
        'classname' => 'block_greetings\external\fetch_greetings',
        'methodname' => 'execute',
        'classpath' => 'block/greetings/classes/external/fetch_greetings.php',
        'description' => "Fetch greetings message.",
        'type' => 'read',
        'ajax' => true,
        'capabilities'  => 'block/greetings:viewmessages',
        'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE, 'block_mobile'),
    ),
);