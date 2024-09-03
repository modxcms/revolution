module.exports = {
    env: {
        browser: true,
        es2021: true
    },
    extends: [
        'eslint:recommended',
        'airbnb-base'
    ],
    globals: {
        MODx: 'readonly',
        Ext: 'readonly',
        _: 'readonly'
    },
    ignorePatterns: [
        'manager/assets/ext3/**/*.js',
        'manager/assets/fileapi/**/*.js',
        'manager/assets/lib/**/*.js',
        'manager/assets/modext/modx.jsgrps-min.js',
        'setup/assets/js/ext-core.js',
        'setup/assets/js/ext-core-debug.js'
    ],
    overrides: [],
    parserOptions: {
        ecmaVersion: 'latest'
    },
    rules: {
        'arrow-parens': ['error', 'as-needed'],
        'comma-dangle': ['error', 'never'],
        'consistent-return': 0,
        curly: ['error', 'all'],
        eqeqeq: ['error', 'smart'],
        'func-names': ['warn', 'as-needed'],
        indent: ['error', 4, {
            VariableDeclarator: 'first',
            SwitchCase: 1
        }],
        'max-len': ['warn', {
            code: 140,
            ignoreComments: true
        }],
        'no-continue': 'warn',
        'no-new': 'warn',
        'no-param-reassign': 'warn',
        'no-plusplus': ['error', {
            allowForLoopAfterthoughts: true
        }],
        'no-underscore-dangle': 'warn',
        'no-unused-vars': ['error', { args: 'none' }],
        'no-use-before-define': ['error', 'nofunc'],
        'object-shorthand': ['error', 'consistent'],
        'one-var': ['error', 'consecutive'],
        'prefer-arrow-callback': 'warn',
        'prefer-rest-params': 'warn',
        'semi-spacing': ['warn', {
            before: false,
            after: true
        }],
        'semi-style': ['warn', 'last'],
        'space-before-function-paren': ['error', 'never']
    }
};
