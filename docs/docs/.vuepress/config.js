import dotenv from 'dotenv'

import { defaultTheme, viteBundler } from 'vuepress'
import { docsearchPlugin } from '@vuepress/plugin-docsearch'
import { containerPlugin } from '@vuepress/plugin-container'
import { githubLinkifyPlugin } from 'vuepress-plugin-github-linkify'

dotenv.config()

const hostname = 'cashier-provider.com'

module.exports = {
    lang: 'en-US',
    title: 'Cashier Provider',
    description: 'Cashier provides an expressive, fluent interface to manage billing services',

    head: [
        ['link', { rel: 'icon', href: `https://${ hostname }/images/logo.svg` }],
        ['meta', { name: 'twitter:image', content: `https://${ hostname }/images/social-logo.png` }]
    ],

    bundler: viteBundler(),

    theme: defaultTheme({
        hostname,
        base: '/',

        logo: `https://${ hostname }/images/logo.svg`,

        repo: 'https://github.com/cashier-provider/foundation',
        repoLabel: 'GitHub',
        docsRepo: 'https://github.com/cashier-provider/foundation',
        docsBranch: 'main',
        docsDir: 'docs',

        contributors: false,
        editLink: true,

        navbar: [
            '/index.md'
        ],

        sidebarDepth: 1,

        sidebar: [
            {
                text: 'License',
                link: '/license.md'
            }
        ]
    }),

    plugins: [
        docsearchPlugin({
            appId: process.env.VITE_APP_ALGOLIA_APP_ID,
            apiKey: process.env.VITE_APP_ALGOLIA_API_KEY,
            indexName: process.env.VITE_APP_ALGOLIA_INDEX_NAME
        }),

        containerPlugin({
            type: 'tip'
        }),

        githubLinkifyPlugin({
            repo: 'Laravel-Lang/common'
        })
    ]
}
