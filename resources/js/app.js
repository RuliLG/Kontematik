require('./bootstrap');

require('alpinejs');

import AOS from 'aos';
import 'aos/dist/aos.css';

import 'tippy.js/dist/tippy.css';
import { delegate } from 'tippy.js';

var copy = require('clipboard-copy');

AOS.init();

delegate('.tooltip-parent', {
    target: '[data-tooltip]',
    content: (reference) => reference.getAttribute('data-tooltip'),
    hideOnClick: false,
});

window.copy = function (id, button) {
    const element = document.getElementById(id);
    if (element) {
        copy(element.textContent.trim());
        window.updateTooltip(button)
    }
}

window.updateTooltip = function (button, delay) {
    delay = delay || 1000
    if (button && button._tippy) {
        button._tippy.setContent(button.getAttribute('data-action-tooltip'));
        setTimeout(function () {
            button._tippy.setContent(button.getAttribute('data-tooltip'));
        }, 1000);
    }
}

window.trackGoal = function (goalId) {
    window.fathom.trackGoal(goalId, 0);
};

window.alpineFieldLength = function (fieldName, max) {
    return {
        max: max,
        text: document.getElementById(fieldName).textContent
    }
};
