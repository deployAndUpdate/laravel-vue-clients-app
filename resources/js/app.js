import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import "../css/app.css";
createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];

        page.default.layout = page.default.layout || "";
        return page;
    },


    setup({ el, App, props }) {
        createApp({ render: () => h(App, props) }).mount(el)
    },
})
