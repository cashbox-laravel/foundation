#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)

function split()
{
    git remote add $2 "git@github.com:cashier-provider/$2.git" || true
    
    SHA1=`./bin/splitsh-lite --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
    
    git remote remove $2 || true
}

git pull origin $CURRENT_BRANCH

split 'src/Core' core
split 'src/Cash' cash
split 'src/SberAuth' sber-auth
split 'src/SberOnline' sber-online
split 'src/SberQrCode' sber-qr
split 'src/TinkoffAuth' tinkoff-auth
split 'src/TinkoffCredit' tinkoff-credit
split 'src/TinkoffOnline' tinkoff-online
split 'src/TinkoffQrCode' tinkoff-qr
split 'src/TemplateDriver' driver
split 'src/TemplateDriverAuth' driver-auth
