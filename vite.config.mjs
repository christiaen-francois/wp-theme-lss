import { defineConfig } from "vite";
import path from "path";

// Change ceci selon ton nom de domaine Local
const LOCAL_DOMAIN = "lion-select-safaris.lunivers";

export default defineConfig({
  root: path.resolve(__dirname),
  base: "",
  build: {
    outDir: "dist",
    manifest: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, "assets/js/main.js"),
      },
    },
  },
  server: {
    host: LOCAL_DOMAIN,
    port: 5174, // Port spécifique pour éviter les conflits avec d'autres projets
    strictPort: true, // Forcer l'utilisation de ce port
    cors: {
      origin: `http://${LOCAL_DOMAIN}`,
      credentials: true,
    },
    hmr: {
      host: LOCAL_DOMAIN, // Important pour Local by Flywheel
      // protocol: "ws",
    },
  },
});
