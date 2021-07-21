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
        text: document.getElementById(fieldName).getAttribute('data-initial-value') || ''
    }
};

window.addEventListener('load', function () {
    const apiKeyMeta = document.querySelector('meta[name="api_token"]');
    if (apiKeyMeta) {
        setTimeout(() => {
            if (!window.kontematikExtension) {
                const isChromeBased = !!window.chrome;
                const $alert = document.querySelector('#kontematik-extension-alert');
                if ($alert && isChromeBased && !sessionStorage.getItem('hasDisplayedExtensionAlert')) {
                    sessionStorage.setItem('hasDisplayedExtensionAlert', 1);
                    $alert.classList.remove('hidden');
                    setTimeout(() => {
                        $alert.classList.remove('opacity-0', 'transform');
                    }, 500);

                    const $closeButton = $alert.querySelector('.close');
                    $closeButton.onclick = () => {
                        $alert.classList.add('transform', 'opacity-0');
                        setTimeout(() => {
                            $alert.parentNode.removeChild($alert);
                        }, 300);
                    };
                }
            }
        }, 5000);
    }
});
