import axios from 'axios';
import React from 'react';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import AppLayout from './Pages/Layouts/AppLayout';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const pages = import.meta.glob('./Pages/**/*.jsx')

createInertiaApp({
    resolve: async (name) => {
        const pageImport = pages[`./Pages/${name}.jsx`]

        if (!pageImport) {
            throw new Error(`Page not found: ${name}`)
        }

        const module = await pageImport()
        const page = module.default

        // 👉 GLOBAL DEFAULT LAYOUT
        page.layout ??= (page) => <AppLayout>{page}</AppLayout>

        return page
    },

    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },
})
