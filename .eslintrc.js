module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    extends: [
        'eslint:recommended',
        'airbnb-base',
    ],
    globals: {
        MODx: 'readonly',
        Ext: 'readonly',
        _: 'readonly',
    },
    ignorePatterns: [
        'manager/assets/modext/workspace/workspace.panel.js',
        'manager/assets/ext3/**/*.js',
        'manager/assets/fileapi/**/*.js',
        'manager/assets/lib/**/*.js',
        'manager/assets/modext/modx.jsgrps-min.js',

        'setup/assets/js/ext-core.js',
        'setup/assets/js/ext-core-debug.js',
    ],
    overrides: [
    ],
    parserOptions: {
        ecmaVersion: 'latest',
    },
    rules: {
        // TODO Enable rules gradually
        indent: 0,
        quotes: ['error', 'single'],
        semi: 0,
        'space-before-function-paren': 0,
        'comma-dangle': 0,
        'prefer-arrow-callback': 0,
        'space-before-blocks': 0,
        'object-shorthand': 0,
    },
}
