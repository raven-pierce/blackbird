import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { asset } from "@codinglabs/laravel-asset";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Blackbird";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin({ methods: { route, asset } })
            .mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });
