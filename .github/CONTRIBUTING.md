# Contributing guidelines
Before submitting a new issue or opening a new PR, please search to see if the same issue/PR already exists. You can also look in the [changelog](https://github.com/modxcms/revolution/blob/develop/core/docs/changelog.txt).

Please note, the repository at [modxcms/revolution](https://github.com/modxcms/revolution/) is only for issues & PRs directly related to **MODX Revolution**. Also, if you have discovered a **security vulnerability** of any kind, please report the details to security@modx.com, instead of disclosing details in a public issue.

When submitting new issues and PRs, **always** use the corresponding template.

### Translations
Only English lexicon changes are handled in GitHub. If you want to update anything in non-English lexicon files, please do so via [Crowdin](http://translate.modx.com).

### AI-assisted contributions

This project has an AI contribution policy. If you used AI tools in your contribution, please read [AI-POLICY.md](https://github.com/modxcms/revolution/blob/3.x/AI-POLICY.md) before submitting.

### Other
 * Did you find an issue in xPDO? Please submit it in [xPDO repository](https://github.com/modxcms/xpdo).
 * Did you find an issue in a MODX Extra? Please submit it to that component's repository or contact the author.
 * Looking for advice or help? Please search the [MODX documentation](https://docs.modx.com/), the [MODX Community](https://community.modx.com/), join to #modx or #xpdo room on IRC FreeNode server (irc.freenode.net) or join the [MODX Community on Slack](https://modx.org/).

## Submit a bug report

[Clicking here will open a new issue with the bug report template](https://github.com/modxcms/revolution/issues/new?template=bug_report.yml&title=%5BBug%5D%20)

The template will ask for: a summary, steps to reproduce, expected and observed behavior, MODX version, PHP version, database type and version, server/browser environment, and any relevant logs.

## Submit a feature request

[Clicking here will open a new issue with the feature request template](https://github.com/modxcms/revolution/issues/new?template=feature_request.yml&title=%5BFeature%5D%20)

The template will ask for: a summary, why it is needed, your suggested solution, alternatives considered, and related issues or PRs.

## Submit a Pull Request
If this is your first PR, please create an account on the [MODX website](http://www.modx.com) and sign the [Contributors License Agreement](https://modx.com/community/cla/). This is needed to ensure all code is licensed properly. We cannot merge pull requests without a signed CLA.

MODX supports PHP from v5.3, so your PRs have to work on PHP 5.3+. **PRs must be backwards compatible.**

Please test your PR before submitting it! If something needs some special review/attention, please let us know.

#### Choosing the correct branch
We try to follow [Semantic Versioning](http://semver.org/) and we maintain major-version-specific "development" branches. All new features that do not break backwards-compatibility should be committed to the development branch for the version in question. For instance, target the `2.x` branch to have it considered for the next minor release of version 2, so if current stable release is 2.4.2, then 2.5 is next minor. Any features that break backwards-compatibility should target a development branch for the next major release, e.g. `3.x`. Bug fixes should target the current stable minor "master" branch. If current stable release is 2.4.2, then this "master" branch will be the `2.4.x` branch.

* `2.x` - development branch for next minor release (2.5.0, 2.6.0, 2.7.0, etc.)
* `2.4.x` - master for current stable minor version; contains bug fixes for the next patch release
* `3.x` - development branch for next major version

#### Template

The PR template will ask for: what changed and why, how to test, related issues or PRs, compatibility notes, a breaking change assessment, test coverage, and contributors to acknowledge.
