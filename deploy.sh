#!/bin/sh
set -e

vendor/bin/phpunit

(git push) || true

git checkout production
git merge main

git push origin production
git push mirror production

git checkout main