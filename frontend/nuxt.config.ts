// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },

  // Add Tailwind CSS and Pinia
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt'
  ]
})
