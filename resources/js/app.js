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

window.alpineLanguageSelector = function (initialLanguage) {
    return {
        language: initialLanguage,
        detectLanguage: function () {
            const form = document.getElementById('copy-form');
            const select = document.getElementById('language-select');
            if (!select) {
                return;
            }

            const inputs = Array.prototype.slice.call(form.querySelectorAll('input, textarea'));
            const text = inputs
                .map(input => input.value || input.textContent || '')
                .filter(value => value.length > 0)
                .join('\n');
            window.axios.post('/api/v1/ai/language-detection', { text: text})
                .then(response => {
                    const language = response.data.language;
                    const options = Array.prototype.slice.call(select.querySelectorAll('option'));
                    options.forEach(function (option) {
                        if (language && language === option.value) {
                            option.click();
                            return false;
                        }
                    })
                })
        }
    }
}
