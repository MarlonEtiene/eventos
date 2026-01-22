import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import { faPlus, faPencil, faEye, faReply, faFloppyDisk, faTimesCircle, faCheckCircle,
    faPrint, faSort, faSortUp, faSortDown, faPaperPlane, faCircle, faSquareXmark, faUserDoctor,
    faEnvelopeCircleCheck, faFileLines, faBan, faFingerprint, faCircleChevronLeft, faCircleChevronRight } from '@fortawesome/free-solid-svg-icons'

library.add(faPlus, faPencil, faEye, faReply, faFloppyDisk, faTimesCircle, faCheckCircle,
    faPrint, faSort, faSortUp, faSortDown, faPaperPlane, faCircle, faSquareXmark, faUserDoctor,
    faEnvelopeCircleCheck, faFileLines, faBan, faFingerprint, faCircleChevronLeft, faCircleChevronRight);

const appName = import.meta.env.VITE_APP_NAME || 'Eventos';

void createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('fai', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
