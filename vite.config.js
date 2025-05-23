import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  resolve: {
      alias: {
          '@': '/resources/js',
          'ziggy-js': path.resolve('vendor/tightenco/ziggy/dist/vue.es.js'),
      },
  },
  server: {
    host: '0.0.0.0',
    hmr: {
        host: '0.0.0.0',
    },    
  }
})
