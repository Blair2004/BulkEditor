import { defineConfig, loadEnv } from 'vite';

import { fileURLToPath } from 'node:url';
import laravel from 'laravel-vite-plugin';
import path from 'node:path';
import vuePlugin from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite'

const Vue = fileURLToPath(
	new URL(
		'vue',
		import.meta.url
	)
);

export default ({ mode }) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};

    return defineConfig({
        base: '/modules/bulkeditor/build/',
        plugins: [
            vuePlugin(),
            tailwindcss(),
            laravel({
                input: [
                    'Resources/js/main.js',
                ],
                refresh: [ 
                    'Resources/**', 
                ]
            })
        ],
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'Resources/js'),
            }
        },
        build: {
            outDir: 'Public/build',
            manifest: true,
            rollupOptions: {
                input: [
                    './Resources/js/main.js',
                ],
                output: {
                    // manualChunks(id) {
                    //     if ( id.includes( 'Resources/ts/scss/gastro.scss' ) ) {
                    //         return 'gastro-assets';
                    //     }
                    // }
                }
            }
        }        
    });
}