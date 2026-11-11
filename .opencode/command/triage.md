---
description: Triage modxcms/revolution issues. Research is read-only. Resolved or not applicable gets one short paragraph and the issue is closed with it. A live bug gets a longer comment with a fix direction and stays open. Confirm before posting the comment.
agent: issue-triage
---

Triage the following GitHub issue(s) of modxcms/revolution: $ARGUMENTS

Two phases. Do not post during the first.

1. Research. Follow `.opencode/agent/issue-research.md`: full thread, CodeGraph then code on `3.x`, `git log` for `#<N>`, timeline plus open PRs (`Fixes` / `Closes` / `Resolves` / cross-ref). Return the JSON verdict. Open fix PRs are `needs_fix`, not `fixed`. For `fixed`, `duplicate`, `expected`, and `unsupported`, run the falsify pass before any "resolved" wording. If that pass finds a reason to stay open, switch to `needs_fix`.

2. Comment, per `.opencode/agent/issue-triage.md`. If the verdict is `fixed`, `duplicate`, `expected`, or `unsupported` and falsify found nothing, post the short comment and then close the issue (`completed` for `fixed`, `not planned` otherwise). Leave every other verdict open.
   - Resolved or not applicable (`fixed`, `duplicate`, `expected`, `unsupported`, falsify clean): one paragraph, about 80 words. Verdict, the commit or PR or file, and what it means for the reporter. Shape from jaygilmore on modxcms/revolution#12945 (comment 6196702261). No `###` sections, no "Verdict:", no thread recap.
   - Still relevant (`needs_fix`, `feature_open`): no 180-word cap. Where it breaks, the fix direction (not a full patch), open fix PRs first if any. Say you have not run MODX unless you did.
   - `needs_info` or `not_reproducible`: one concrete question.

Line 1 of every comment is `<!-- issue-triage -->`. End with the collapsed `<details>Triage run</details>` block. Skip issues that already have the marker, and skip closed issues.

Labels: if any need to change, add/remove them immediately from the taxonomy in the triage agent. Do not wait for confirmation. An empty diff means leave labels alone.

Plan gate: print the verdict and the full draft, then stop before the comment. You cannot press Tab. Post the comment only after the user confirms, or when this same message already says to post. Labels are not part of that gate.
