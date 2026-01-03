import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig({
  plugins: [react()],
  base: "./",
  build: {
    outDir: path.resolve(__dirname, "../assets/dist"),
    emptyOutDir: true,
    manifest: "manifest.json",
    rollupOptions: {
      input: path.resolve(__dirname, "index.html"),
    },
  },
});

