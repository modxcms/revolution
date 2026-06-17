# Contributing guidelines
Before submitting a new issue or opening a new PR, please search to see if the same issue/PR already exists. You can also look in the [changelog](https://github.com/modxcms/revolution/blob/develop/core/docs/changelog.txt).

Please note, the repository at [modxcms/revolution](https://github.com/modxcms/revolution/) is only for issues & PRs directly related to **MODX Revolution**. If you have discovered a **security vulnerability**, do not disclose details in a public issue. See [SECURITY.md](../SECURITY.md) for the responsible disclosure process.

When submitting new issues and PRs, **always** use the corresponding template.

### Translations
Only English lexicon changes are handled in GitHub. If you want to update anything in non-English lexicon files, please do so via [Crowdin](http://translate.modx.com).

### AI-assisted contributions
This project has an AI contribution policy. If you used AI tools in your contribution, please read [AI-POLICY.md](../AI-POLICY.md) before submitting.

### Other
 * Did you find an issue in xPDO? Please submit it in [xPDO repository](https://github.com/modxcms/xpdo).
 * Did you find an issue in a MODX Extra? Please submit it to that component's repository or contact the author.
 * Looking for advice or help? Please search the [MODX documentation](https://docs.modx.com/), the [MODX Community](https://community.modx.com/), or join the [MODX Community on Slack](https://modx.org/).

## Submit a bug report
[Clicking here will open a new issue with the bug report template](https://github.com/modxcms/revolution/issues/new?template=bug_report.yml&title=%5BBug%5D%20)

The template will ask for: a summary, steps to reproduce, expected and observed behavior, MODX version, PHP version, database type and version, server/browser environment, and any relevant logs.

## Submit a feature request
[Clicking here will open a new issue with the feature request template](https://github.com/modxcms/revolution/issues/new?template=feature_request.yml&title=%5BFeature%5D%20)

The template will ask for: a summary, why it is needed, your suggested solution, alternatives considered, and related issues or PRs.

## Submit a Pull Request
If this is your first PR, please create an account on the [MODX website](http://www.modx.com) and sign the [Contributors License Agreement](https://account.modx.com/cla). This is needed to ensure all code is licensed properly. We cannot merge pull requests without a signed CLA.

MODX requires PHP 8.1 or higher, so your *PRs must be compatible* with PHP 8.1+ unless they have a compelling reason to bump the minimum.

Please test your PR before submitting it! If something needs some special review/attention, please let us know.

#### Choosing the correct branch
Target the `3.x` branch for your contribution. This is the current default development branch for MODX Revolution.
