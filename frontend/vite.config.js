import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    host: "localhost",
    //port: 5174,
    proxy: {
      "/graphql": {
        target: "http://localhost",
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
