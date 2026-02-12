import vue from 'eslint-plugin-vue'
import vueParser from 'vue-eslint-parser'
import tsParser from '@typescript-eslint/parser'
import tsPlugin from '@typescript-eslint/eslint-plugin'

export default [
    {
        files: ['**/*.vue'],
        languageOptions: {
            parser: vueParser,
            parserOptions: {
                parser: tsParser,
                ecmaVersion: 'latest',
                sourceType: 'module',
            },
        },
        plugins: {
            vue,
            '@typescript-eslint': tsPlugin,
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            'vue/no-unused-vars': 'error',
            '@typescript-eslint/no-unused-vars': 'error',
        },
    },
]
