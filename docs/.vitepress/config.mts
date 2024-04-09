import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  srcExclude: ['**/available_parsers.md'],
  title: " ",
  description: "This is the documentation for lawtex",
  themeConfig: {
    search: {
      provider: 'local'
    },
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/introduction/welcome' }
    ],

      editLink: {
          pattern: 'https://github.com/fabio1202/lawtex/edit/main/docs/:path'
      },

      logo: {
        light: '/logo-black.svg',
        dark: '/logo-no-background.svg'
      },


    sidebar: [
        {
            text: 'Introduction',
            items: [
                { text: 'Welcome!', link: '/introduction/welcome' },
                { text: 'Run lawtex', link: '/introduction/run-lawtex' },
                { text: 'Environment variables', link: '/introduction/environment' },
                { text: 'Volumes', link: '/parsers' },
            ]
        },
        {
            text: 'Web App',
            items: [
                { text: 'Create Project', link: '/parsers' },
                { text: 'Add Laws', link: '/parsers' },
                { text: 'Export Project', link: '/parsers' },
            ]
        },
        {
            text: 'LaTeX',
            items: [
                { text: 'Show Law', link: '/parsers' },
                { text: 'Table of laws', link: '/parsers' },
            ]
        },
        {
            text: 'Development',
            collapsed: false,
            items: [
                { text: 'Dev Environment', link: '/parsers' },
                { text: 'Parsers', link: '/parsers' },
            ]
        }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/fabio1202/lawtex' }
    ]
  }
})
