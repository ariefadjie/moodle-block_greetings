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
 * Webservices for the plugin.
 *
 * @package     block_greetings
 * @copyright   2022 Your name <your@email>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_greetings\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_description;
use external_function_parameters;
use external_single_structure;
use external_value;
use external_warnings;
use stdClass;
use context_system;

/**
 * Add greeting
 *
 * @package     block_greetings
 * @copyright   2022 Your name <your@email>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class fetch_greetings extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters(
                array(
                        'userid' => new external_value(PARAM_INT, 'Id of the user'),
                )
        );
    }

    /**
     * Add message to database.
     *
     * @param  int $userid Id of user.
     * @return array Result as defined in execute_returns.
     */
    public static function execute($userid) {
        global $DB;

        $warnings = [];

        $context = context_system::instance();

        $params = self::validate_parameters(self::execute_parameters(), array('userid' => $userid));

        $result = json_encode([]);
        if (has_capability('block/greetings:viewmessages', $context)) {
            $sql = "SELECT m.id, m.message, m.timecreated, m.userid, u.firstname
            FROM {block_greetings_messages} m
            LEFT JOIN {user} u ON u.id = m.userid
            ORDER BY timecreated DESC";

            $messages = $DB->get_records_sql($sql);

            $result = json_encode(array_values($messages));
        }

        // Send a response.
        return ['warnings' => [], 'result' => $result];
    }


    /**
     * Returns the description of the webservice response.
     *
     * @return external_description
     */
    public static function execute_returns(): external_description {
        return new external_single_structure([
            'warnings' => new external_warnings(),
            'result' => new external_value(PARAM_RAW, 'result output'),
        ]);
    }
}