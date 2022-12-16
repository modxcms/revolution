module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    extends: 'eslint:recommended',
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
    },
}
