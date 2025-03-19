import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    host: "testhost",
    port: 5174,
    proxy: {
      "/graphql": {
        target: "http://testhost:5174",
        changeOrigin: true,
        secure: false,
      },
    },
    watch: {
      usePolling: true,
    },
    open: true,
  },
});
