#!/usr/bin/env bash
set -euo pipefail

BASE_SHA="${1:?Base SHA is required}"
HEAD_SHA="${2:?Head SHA is required}"
PHPCS="${3:-core/vendor/bin/phpcs}"
STANDARD="${4:-phpcs.xml}"

if ! command -v jq >/dev/null 2>&1; then
    echo "jq is required to filter PHPCS results to PR diff lines."
    exit 1
fi

FILES=()
while IFS= read -r file; do
    [ -n "$file" ] && FILES+=("$file")
done < <(git diff --name-only --diff-filter=ACMRT "${BASE_SHA}...${HEAD_SHA}" | grep -E '\.php$' || true)

if [ ${#FILES[@]} -eq 0 ]; then
    echo "No PHP files changed in this PR."
    exit 0
fi

echo "PHP files in PR diff:"
printf '  %s\n' "${FILES[@]}"

get_changed_lines() {
    local file="$1"

    git diff -U0 "${BASE_SHA}...${HEAD_SHA}" -- "$file" | python3 -c '
import re
import sys

for line in sys.stdin:
    match = re.match(r"^@@ -\d+(?:,\d+)? \+(\d+)(?:,(\d+))? @@", line)
    if not match:
        continue
    start = int(match.group(1))
    count = int(match.group(2) or 1)
    for line_number in range(start, start + count):
        print(line_number)
'
}

line_is_changed() {
    local line="$1"
    shift

    for changed_line in "$@"; do
        if [ "$line" -eq "$changed_line" ]; then
            return 0
        fi
    done

    return 1
}

FAILED=0

for file in "${FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo "Skipping missing file: ${file}"
        continue
    fi

    CHANGED_LINES=()
    while IFS= read -r changed_line; do
        [ -n "$changed_line" ] && CHANGED_LINES+=("$changed_line")
    done < <(get_changed_lines "$file")

    if [ ${#CHANGED_LINES[@]} -eq 0 ]; then
        echo "No changed lines detected for ${file}; skipping."
        continue
    fi

    REPORT="$(mktemp)"
    set +e
    "$PHPCS" -q --standard="$STANDARD" --report=json "$file" >"$REPORT" 2>/dev/null
    set -e

    FILE_REPORT="$(jq -c --arg file "$file" '[.files | to_entries[] | select(.key | endswith("/" + $file) or . == $file) | .value][0] // empty' "$REPORT")"
    rm -f "$REPORT"

    if [ -z "$FILE_REPORT" ] || [ "$FILE_REPORT" = "null" ]; then
        continue
    fi

    MESSAGE_COUNT="$(jq -r '.messages | length' <<<"$FILE_REPORT")"
    if [ "$MESSAGE_COUNT" -eq 0 ]; then
        continue
    fi

    while IFS= read -r message; do
        [ -z "$message" ] && continue

        line="$(jq -r '.line' <<<"$message")"
        column="$(jq -r '.column' <<<"$message")"
        type="$(jq -r '.type' <<<"$message")"
        source="$(jq -r '.source' <<<"$message")"
        text="$(jq -r '.message' <<<"$message")"

        if line_is_changed "$line" "${CHANGED_LINES[@]}"; then
            echo "${file}:${line}:${column}: ${type} - ${text} (${source})"
            FAILED=1
        fi
    done < <(jq -c '.messages[]?' <<<"$FILE_REPORT")
done

if [ "$FAILED" -ne 0 ]; then
    echo "PHPCS found violations in PR-changed lines."
    exit 1
fi

echo "PHPCS passed for PR-changed lines."
