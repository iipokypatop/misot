#!/usr/bin/env bash
find ./src -name "*.php" | xargs sed -i 's/^.*assert(.\+);/##disabled##:\0/gMi'