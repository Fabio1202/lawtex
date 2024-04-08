import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "lawtex",
  description: "This is the documentation for lawtex",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/introduction' }
    ],

    sidebar: [
        {
            text: 'Introduction',
            items: [
                { text: 'Parsers', link: '/parsers' },
                { text: 'Parsers', link: '/parsers' },
                { text: 'Parsers', link: '/parsers' },
            ]
        },
        {
            text: 'Development',
            items: [
                { text: 'Parsers', link: '/parsers' },
            ]
        }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/fabio1202/lawtex' }
    ]
  }
})
