
/**
 * This file is part of the "cashbox/foundation" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://cashbox.city
 */

import dotenv from 'dotenv'

import { defaultTheme, viteBundler } from 'vuepress'
import { docsearchPlugin } from '@vuepress/plugin-docsearch'
import { containerPlugin } from '@vuepress/plugin-container'
import { githubLinkifyPlugin } from 'vuepress-plugin-github-linkify'

dotenv.config()

const hostname = 'cashbox.com'

module.exports = {
    lang: 'en-US',
    title: 'Cashbox for Laravel',
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

        repo: 'https://github.com/cashbox-laravel/foundation',
        repoLabel: 'GitHub',
        docsRepo: 'https://github.com/cashbox-laravel/foundation',
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
