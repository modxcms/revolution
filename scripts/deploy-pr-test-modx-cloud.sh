#!/usr/bin/env bash
# Deploy feat/manager-dark-theme (PR #16863) to MODX Cloud for manual QA.
# Based on: https://modx.com/blog/help-test-prs-to-accelerate-the-revolution-3-release
#
# Usage:
#   export MODX_CLOUD_SSH_PASS='...'
#   export MODX_CLOUD_DB_PASS='...'
#   ./scripts/deploy-pr-test-modx-cloud.sh
#
# Optional: PR_NUMBER=16863 BRANCH=feat/manager-dark-theme ./scripts/deploy-pr-test-modx-cloud.sh

set -euo pipefail

HOST="${MODX_CLOUD_HOST:-aybex27y.modx.dev}"
SSH_USER="${MODX_CLOUD_SSH_USER:-c0091}"
DB_NAME="${MODX_CLOUD_DB_NAME:-instance_c0091}"
DB_USER="${MODX_CLOUD_DB_USER:-c0091}"
DB_PASS="${MODX_CLOUD_DB_PASS:?Set MODX_CLOUD_DB_PASS}"
SSH_PASS="${MODX_CLOUD_SSH_PASS:?Set MODX_CLOUD_SSH_PASS}"
PR_NUMBER="${PR_NUMBER:-16863}"
BRANCH="${BRANCH:-feat/manager-dark-theme}"
FORK="${FORK:-Ibochkarev/revolution}"

SSH_OPTS=(
  -o StrictHostKeyChecking=no
  -o PreferredAuthentications=password
  -o PubkeyAuthentication=no
  -o IdentitiesOnly=yes
  -o NumberOfPasswordPrompts=1
)

echo "== MODX Cloud PR test deploy =="
echo "Host:   ${HOST}"
echo "PR:     #${PR_NUMBER} (${BRANCH})"
echo

sshpass -p "$SSH_PASS" ssh "${SSH_OPTS[@]}" "${SSH_USER}@${HOST}" \
  "PR_NUMBER=${PR_NUMBER} BRANCH=${BRANCH} FORK=${FORK} sh -s" <<'REMOTE'
set -eu
export PATH="$HOME/.bin:$HOME/.config/composer/vendor/bin:$PATH"
export COMPOSER_NO_DEV=0

cd "$HOME/www"

if [ ! -d revolution/.git ]; then
  echo "-- Installing MODX Revolution 3.x-dev via Composer --"
  COMPOSER_NO_DEV=0 composer create-project --keep-vcs modx/revolution revolution 3.x-dev
  cd revolution
  git checkout 3.x
else
  echo "-- Existing revolution/ repo found --"
  cd revolution
fi

echo "-- Checking out PR #${PR_NUMBER} (${BRANCH}) --"
if command -v gh >/dev/null 2>&1 && gh auth status >/dev/null 2>&1; then
  git fetch origin 3.x
  gh pr checkout "${PR_NUMBER}"
else
  echo "(gh not available; fetching fork branch ${BRANCH})"
  if git remote get-url fork >/dev/null 2>&1; then
    git remote set-url fork "https://github.com/${FORK}.git"
  else
    git remote add fork "https://github.com/${FORK}.git"
  fi
  git fetch fork "${BRANCH}" || GIT_SSL_NO_VERIFY=true git fetch fork "${BRANCH}"
  git checkout -B "${BRANCH}" "fork/${BRANCH}"
fi

echo "-- Composer install --"
composer install --no-interaction

if [ -f _build/build.properties.sample ] && [ ! -f _build/build.properties ]; then
  cp _build/build.properties.sample _build/build.properties
fi

echo "-- Build manager CSS (index.css from SCSS) --"
if command -v npm >/dev/null 2>&1; then
  cd _build/templates/default
  if [ ! -d node_modules ]; then
    npm ci 2>/dev/null || npm install
  fi
  npx grunt style
  cd "$HOME/www/revolution"
else
  echo "WARNING: npm not found; index.css will be stale until grunt style is run"
fi

echo "-- Core transport build (if package missing) --"
if ! ls core/packages/core/modx-core-*.transport.zip >/dev/null 2>&1; then
  php _build/transport.core.php 2>/dev/null || php transport.core.php 2>/dev/null || true
fi

echo "-- Symlink web root (MODX Cloud docroot is ~/www, code in revolution/) --"
cd "$HOME/www"
for item in index.php setup manager core connectors ht.access; do
  if [ ! -e "$item" ]; then
    ln -s "revolution/$item" "$item"
  fi
done

echo "-- Clear MODX cache --"
if [ -f core/config/config.inc.php ]; then
  "$HOME/.bin/modx" cache:clear 2>/dev/null || rm -rf core/cache/* 2>/dev/null || true
fi

echo
echo "Deployed branch: $(git -C "$HOME/www/revolution" branch --show-current)"
echo "Latest commit:   $(git -C "$HOME/www/revolution" log -1 --oneline)"
REMOTE

echo
echo "Site URL: https://${HOST}/"
echo "Setup:    https://${HOST}/setup/"
echo "Manager:  https://${HOST}/manager/"
