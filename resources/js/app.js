import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createPinia } from 'pinia';
import { InertiaProgress } from '@inertiajs/progress';
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(pinia);  
        app.use(ZiggyVue);
        app.mount(el);
    },
})

InertiaProgress.init();