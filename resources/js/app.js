import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from './tightenco/ziggy/dist/vue.m.js';
import store from "./store";
import 'v-calendar/dist/style.css';
import VCalendar from 'v-calendar';
// import { SetupCalendar, Calendar, DatePicker } from 'v-calendar';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(store)
            .use(VCalendar)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
