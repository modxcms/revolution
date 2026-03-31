# Security Policy

MODX takes the security of its software seriously. If you believe you have
found a vulnerability in MODX Revolution, please follow the responsible
disclosure process described below.

---

## Supported Versions

The following release lines currently receive security patches:

| Version | Supported                        |
| ------- | -------------------------------- |
| 3.x     | Yes                              |
| 2.x     | Critical vulnerabilities only    |

Only the versions marked **Yes** or **Critical vulnerabilities only** are in
scope for security reports against this repository. The 2.x line does not
receive new features or general bug fixes — only patches for critical
security vulnerabilities. Reports against unsupported versions are
appreciated but may not result in a patch.

---

## Reporting a Vulnerability

**Do not open a public GitHub issue for security vulnerabilities.**
Public disclosure before a patch is available puts all MODX users at risk.

To report a vulnerability, use one of the following channels:

- **Email:** security@modx.com
- **Disclosure policy and submission form:** https://modx.com/community/responsible-security-disclosure

We aim to respond to all reports within **24–48 hours** of receipt.

---

## What to Include in Your Report

To help us triage and reproduce the issue quickly, please include as many
of the following as are applicable:

- The specific vulnerability type (e.g., XSS, SQL injection, RCE, SSRF)
- The exact affected version(s) of MODX Revolution
- Step-by-step reproduction instructions or a proof-of-concept
- The security impact and potential consequences
- Suggested remediation, if you have one
- Screenshots, HTTP request/response captures, or log excerpts

Incomplete reports may slow the investigation. The more detail you provide,
the faster we can assess and address the issue.

---

## Response Timeline

| Stage                        | Target                            |
| ---------------------------- | --------------------------------- |
| Initial acknowledgment       | Within 24–48 hours of receipt     |
| Vulnerability assessment     | 3–14 days after acknowledgment    |
| Patch development            | Varies by severity and complexity |
| Public disclosure            | After patch is released and deployed |

We commit to a maximum embargo period of **90 days** from the date of
initial acknowledgment, absent exceptional circumstances (e.g., a patch
requires a coordinated release across multiple dependencies). If we
anticipate exceeding this window, we will notify you and agree on a
revised timeline.

If you have not received an acknowledgment within 48 hours, follow up at
security@modx.com.

---

## Disclosure Policy

MODX follows a **coordinated responsible disclosure** process:

1. You report the vulnerability privately to the MODX Security Team.
2. We investigate, confirm, and develop a fix.
3. We release a patched version.
4. We publish a public security advisory or announcement after the fix is
   available for users to install.

**We ask that you:**

- Allow us a reasonable window to investigate and release a fix before
  publicly disclosing the issue.
- Avoid exploiting the vulnerability beyond what is necessary to
  demonstrate it.
- Avoid accessing, modifying, or deleting data that does not belong to you.

Please review the full disclosure policy at:
https://modx.com/community/responsible-security-disclosure

---

## Scope

### In Scope

The following are in scope for security reports submitted via this repository:

- MODX Revolution 3.x (current supported release line)
- MODX Revolution 2.x (critical vulnerabilities only)

The following are in scope for reports to security@modx.com but are not
managed through this repository:

- modx.com and its subdomains
- dashboard.modxcloud.com

### Out of Scope

The following are explicitly out of scope and will not be accepted as
valid vulnerability reports:

- **Third-party Extras** — MODX is not responsible for security issues in
  packages distributed through the MODX Extras repository or any other
  third-party channel. Report those issues directly to the Extra's author.
- Self-XSS (attacks that require you to execute code in your own browser)
- Vulnerabilities that require administrator-level access to exploit
  (administrator access is a trusted role in MODX; features available to
  administrators are intentional)
- Results from automated scanners submitted without manual verification
- Denial-of-service attacks or brute-force rate limiting
- Social engineering tactics
- Server misconfigurations in the deploying user's environment
- Development or staging domains: `*.modx.dev`, `*.paas`,
  `audit.modx.com`, `status.modx.com`, `support.modx.com`,
  `status.modxcloud.com`

**A note on manager-executed scripts:** Scripts entered into the MODX
manager (by a user with permission to do so) executing on the front-end is
intentional product behavior, not a vulnerability.

---

## Recognition

MODX does not offer monetary compensation, bug bounties, or swag for
security reports.

For verified, substantive vulnerability reports, MODX commits to:

- Publicly acknowledging your contribution in the security advisory or
  release notes at the time the fix is published.
- Crediting you by name (or alias, if you prefer anonymity).

If you would like to be credited under a specific name or prefer to remain
anonymous, please indicate this in your report.

---

## Researcher Protections

MODX commits to the following for reporters acting in good faith:

- We will not initiate legal action against you for discovering and
  reporting a vulnerability in accordance with this policy.
- We will treat your report fairly and evaluate it on its technical merits.
- We will keep you informed of the status of your report with reasonable
  updates throughout the process.
- We will not publicly disclose your identity without your consent.

Good faith means: you did not exploit the vulnerability beyond what was
necessary to demonstrate it, you did not access or modify data outside a
test environment you control, and you followed the disclosure process
described in this document.

---

_This security policy is maintained by the MODX Security Team. For
general questions about MODX security practices, see the
[responsible disclosure policy](https://modx.com/community/responsible-security-disclosure)
on modx.com._
