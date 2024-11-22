import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  srcExclude: ['**/available_parsers.md'],
  head: [
    // Favicon
    //['link', { rel: 'icon', href: '/lawtex-favicon-black.png' }]
  ],
  title: "lawtex",
  description: "This is the documentation for lawtex",
  themeConfig: {
    siteTitle: false,
    search: {
      provider: 'local'
    },
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Docs', link: '/introduction/welcome' },
      { text: 'LaTeX commands', link: '/latex/overview' },
    ],

      editLink: {
          pattern: 'https://github.com/fabio1202/lawtex/edit/main/docs/:path',
          text: 'Edit this page on GitHub'
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
                { text: 'Quick Start', link: '/introduction/run-lawtex' },
                { text: 'Environment variables', link: '/introduction/environment' },
                { text: 'Volumes', link: '/introduction/volumes' },
            ]
        },
        {
            text: 'Use the app',
            items: [
                { text: 'Create Project', link: '/parsers' },
                { text: 'Add Laws', link: '/parsers' },
                { text: 'Export Project', link: '/parsers' },
            ]
        },
        {
            text: 'Manage the app',
            items: [
                { text: 'User Management', link: '/parsers' },
                { text: 'Roles', link: '/parsers' },
            ]
        },
        {
            text: 'LaTeX',
            items: [
                { text: 'Overview', link: '/parsers' },
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
