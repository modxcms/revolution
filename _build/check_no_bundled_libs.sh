#!/usr/bin/env bash
#
# MAB-01 hygiene: fail if phpmailer, Smarty, or AWS SDK are re-bundled under
# core/ outside Composer-managed core/vendor/.
#
# Checks tree fingerprints (historical 3.0.0 cleanup paths + library signature
# files). Does not search for the words "phpmailer"/"smarty"/"aws" — those hit
# MODX wrappers, lexicons, and docs.
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

failed=0

# Paths removed by setup/includes/upgrades/common/3.0.0-cleanup-files.php
forbidden_paths=(
    'core/model/aws'
    'core/model/smarty'
    'core/model/modx/mail'
    'core/model/modx/smarty'
    'core/model/phpmailer'
    'core/model/PHPMailer'
)

for path in "${forbidden_paths[@]}"; do
    if [[ -e "$path" ]]; then
        echo "FAIL: bundled library path exists outside core/vendor: ${path}"
        failed=1
    fi
done

# Signature files that only belong to the Composer packages
while IFS= read -r -d '' file; do
    echo "FAIL: library signature file outside core/vendor: ${file}"
    failed=1
done < <(
    find core \
        \( -path 'core/vendor' -o -path 'core/cache' -o -path 'core/packages' \) -prune -o \
        -type f \( \
            -name 'PHPMailer.php' \
            -o -name 'class.phpmailer.php' \
            -o -name 'Smarty.class.php' \
            -o -path '*/sysplugins/smarty_internal_templatebase.php' \
            -o -path '*/Aws/Sdk.php' \
            -o -path '*/Aws/S3/S3Client.php' \
        \) -print0 2>/dev/null || true
)

if [[ "$failed" -ne 0 ]]; then
    echo
    echo 'MAB-01: keep phpmailer, Smarty, and AWS SDK only via Composer in core/vendor.'
    echo 'Remove the paths above or move the dependency to composer.json.'
    exit 1
fi

echo 'OK: no bundled phpmailer/Smarty/AWS SDK outside core/vendor'
