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
 * TODO describe module greetings
 *
 * @module     block_greetings/greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Selectors from 'block_greetings/block/greetings/selectors';
import * as Repository from 'block_greetings/block/greetings/repository';
import { get_string as getString } from 'core/str';
import DynamicForm from 'core_form/dynamicform';

/**
 *
 * @param {Number} userid
 */
export const init = (userid) => {
    registerEventListeners(userid);
};

/**
 *
 * @param {Number} userid
 */
const registerEventListeners = (userid) => {
    document.addEventListener('click', e =>  {
        if (e.target.closest(Selectors.actions.showGreetingButton)) {
            const greetingBlock = document.querySelector(Selectors.regions.greetingBlock);

            Repository.getUser(userid)
            .then(function(response) {
                window.console.log("Country: " + response[0].country);
            });

            if (greetingBlock) {
                const nameField = document.querySelector(Selectors.regions.inputField);
                const msg = document.createElement("h2");

                userGreeting(nameField.value)
                .then((greetingStr) => {
                    msg.append(greetingStr);
                    greetingBlock.append(msg);
                })
                .catch();
            }
        }
        if (e.target.closest(Selectors.actions.resetButton)) {
            const nameField = document.querySelector(Selectors.regions.inputField);
            nameField.value = '';

            const greetingBlock = document.querySelector(Selectors.regions.greetingBlock);
            greetingBlock.innerHTML  = '';
        }
    });
};

/**
 * Return a personalised greeting.
 *
 * @param   {String} name The name of the person to greet
 * @returns {Promise}
 */
const userGreeting = (name) => getString('greetinguserau', 'block_greetings', name);

export const addMessage = (selector, formClass) => {
    const form = new DynamicForm(document.querySelector(selector), formClass);

    form.addEventListener(form.events.FORM_SUBMITTED, (e) => {
        e.preventDefault();
        const response = e.detail;
        const frm = document.querySelector(selector);
        frm.querySelector('[name=message]').value = '';

        //form.load({userid: response.userid, message: response.message});
        window.console.log('Form submitted: ' + JSON.stringify(response));
        Repository.addMessage(response.userid, response.message)
        .then(function(res) {
            window.console.log(JSON.stringify(res));
            fetchMessages(response.userid);
        });
    });
};

export const fetchMessages = (userid) => {
    const messages = document.querySelector('[id=greeting-messages]');

    Repository.fetchMessages(userid).then(res => {
        let html = `<table class="table table-bordered"><tbody>`;
        JSON.parse(res.result).forEach(data => {
            html += `<tr>`;
            html += `<td>${data.id}</td>`;
            html += `<td>${data.message}</td>`;
            html += `<td>${data.timecreated}</td>`;
            html += `<td>${data.firstname}</td>`;
            html += `</tr>`;
        });
        html += `</tbody></table>`;

        messages.innerHTML = html;
    });
};
