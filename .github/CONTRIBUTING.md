# Contributing guidelines
Before submitting a new issue or opening a new PR, please search to see if the same issue/PR already exists. You can also look in the [changelog](https://github.com/modxcms/revolution/blob/develop/core/docs/changelog.txt).

Please note, the repository at [modxcms/revolution](https://github.com/modxcms/revolution/) is only for issues & PRs directly related to **MODX Revolution**. Also, if you have discovered a **security vulnerability** of any kind, please report the details to security@modx.com, instead of disclosing details in a public issue.

When submitting new issues and PRs, **always** use the corresponding template.

### Translations
Only English lexicon changes are handled in GitHub. If you want to update anything in non-English lexicon files, please do so via [Crowdin](http://translate.modx.com).

### Other
 * Did you find an issue in xPDO? Please submit it in [xPDO repository](https://github.com/modxcms/xpdo).
 * Did you find an issue in a MODX Extra? Please submit it to that component's repository or contact the author.
 * Looking for advise or help? Please search the [MODX documentation](http://rtfm.modx.com/), the [MODX forums](http://forums.modx.com/) or join to #modx or #xpdo room on IRC FreeNode server (irc.freenode.net).

## Submit a bug report

[Clicking here will open a new issue which will have the below template prefilled](https://github.com/modxcms/revolution/issues/new?title=%5BBug%5D%20&?template=bug_report.md)

#### Template

    ## Bug report
    ### Summary
    Quick summary what's this issue about.
    
    ### Step to reproduce
    How to reproduce the issue, including custom code if needed.
    
    ### Observed behavior
    How it behaved after following steps above.
    
    ### Expected behavior
    How it should behave after following steps above.
    
    ### Environment
    MODX version, apache/nginx and version, mysql version, browser, etc. Any relevant information.

## Submit a feature request

[Clicking here will open a new issue which will have the below template prefilled](https://github.com/modxcms/revolution/issues/new?title=%5BFeature%20request%5D%20&?template=feature_report.md)

#### Template

    ## Feature request
    ### Summary
    Quick summary what's this feature request about.
    
    ### Why is it needed?
    A clear and concise description of what the problem is. Ex. I'm always frustrated when [...]
    
    ### Suggested solution(s)
    A clear and concise description of what you want to happen.
    
    ### Related issue(s)/PR(s)
    Let us know if this is related to any issue/pull request.

## Submit a Pull Request
If this is your first PR, please create an account on the [MODX website](http://www.modx.com) and sign the [Contributors License Agreement](http://develop.modx.com/contribute/cla/). This is needed to ensure all code is licensed properly. We cannot merge pull requests without a signed CLA.

MODX supports PHP from v5.3, so your PRs have to work on PHP 5.3+. **PRs must be backwards compatible.**

Please test your PR before submitting it! If something needs some special review/attention, please let us know.

#### Choosing the correct branch
We try to follow [Semantic Versioning](http://semver.org/) and we maintain major-version-specific "development" branches. All new features that do not break backwards-compatibility should be committed to the development branch for the version in question. For instance, target the `2.x` branch to have it considered for the next minor release of version 2, so if current stable release is 2.4.2, then 2.5 is next minor. Any features that break backwards-compatibility should target a development branch for the next major release, e.g. `3.x`. Bug fixes should target the current stable minor "master" branch. If current stable release is 2.4.2, then this "master" branch will be the `2.4.x` branch.

* `2.x` - development branch for next minor release (2.5.0, 2.6.0, 2.7.0, etc.)
* `2.4.x` - master for current stable minor version; contains bug fixes for the next patch release
* `3.x` - development branch for next major version

#### Template

    ### What does it do ?
    Describe the technical changes you did.

    ### Why is it needed ?
    Describe the issue you are solving.

    ### Related issue(s)/PR(s)
    Let us know if this is related to any issue/pull request (see https://github.com/blog/1506-closing-issues-via-pull-requests)
