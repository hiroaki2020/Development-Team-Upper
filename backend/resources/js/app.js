require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import AppLayout from '@/Layouts/AppLayout';

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .component('AppLayout', AppLayout)
    .mixin({
        methods: {
            route,
            /**
             * keyの値を翻訳します。
             */
            __(key)
            {
                key = String(key);
                var translation = this.$page.props.language[key]
                ? this.$page.props.language[key]
                : key;
                
                return translation;
            },
         },   
    })
    .use(InertiaPlugin)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
