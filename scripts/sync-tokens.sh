#!/usr/bin/env bash
set -euo pipefail

project_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
token_source="${TROMAY_TOKEN_SOURCE:-/home/sergui/platform/design-system/tromay/build/tokens.css}"
token_target="${project_dir}/public/assets/css/tromay-tokens.css"

cp "${token_source}" "${token_target}"
