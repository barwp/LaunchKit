#!/usr/bin/env bash

set -euo pipefail

BASE_URL="${1:-http://127.0.0.1:8000}"

echo "==> Checking ${BASE_URL}"

check() {
  local path="$1"
  local code
  code="$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}${path}")"
  echo "${path} -> ${code}"
  if [[ "${code}" != "200" ]]; then
    echo "Route check failed for ${path}"
    exit 1
  fi
}

check "/"
check "/login"
check "/register"
check "/health"

echo "==> All public route checks passed"

