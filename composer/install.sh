#!/usr/bin/env bash
# Installs the Composer-managed dependencies for app/ and adminpanel/.
# Run this once after a fresh clone (and again whenever composer.json here changes).
#
# Why this layout: these manifests live outside vendor/, and each one pins
# "config.vendor-dir" to point straight into vendor/excel or vendor/phpmailer,
# so the app code's existing `require 'vendor/excel/autoload.php'` and
# `require 'vendor/phpmailer/autoload.php'` calls keep working unchanged.
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")/.."

for d in composer/excel composer/phpmailer adminpanel/composer/excel adminpanel/composer/phpmailer; do
    echo "==> composer install ($d)"
    (cd "$d" && composer install --no-interaction --no-dev)
done

echo "Done. Populated:"
echo "  vendor/excel, vendor/phpmailer"
echo "  adminpanel/vendor/excel, adminpanel/vendor/phpmailer"
