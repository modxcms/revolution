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

        // "matter of taste" rules
        'comma-dangle': 0,
        'comma-spacing': 0,
        'comma-style': 0,
        'consistent-return': 0,
        'func-names': 0,
        indent: 0,
        'key-spacing': 0,
        'keyword-spacing': 0,
        'max-len': ['warn', {
            code: 140,
            ignoreComments: true
        }],
        'no-floating-decimal': 0,
        'no-multiple-empty-lines': 0,
        'no-tabs': 0,
        'object-curly-newline': 0,
        'object-curly-spacing': 0,
        'object-property-newline': 0,
        'object-shorthand': 0,
        'one-var': 0,
        'operator-linebreak': 0,
        'prefer-arrow-callback': 0,
        'prefer-template': 0,
        quotes: ['error', 'single'],
        'quote-props': 0,
        semi: 0,
        'spaced-comment': 0,
        'no-mixed-spaces-and-tabs': 0,
        'space-before-blocks': 0,
        'space-before-function-paren': 0,
        'space-in-parens': 0,
        'space-infix-ops': 0,
        'dot-notation': 0,
        'no-multi-spaces': 0,

        // temporarly disabled rules because of priority
        'block-scoped-var': 0,
        eqeqeq: 0,
        'no-bitwise': 0,
        'no-param-reassign': 0,
        'no-plusplus': 0,
        'no-redeclare': 0,
        'radix': 0,
        'vars-on-top': 0,
        'wrap-iife': 0,
        'prefer-destructuring': 0,
    },
}
