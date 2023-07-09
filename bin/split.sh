#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH="4.x"

function split()
{
    SHA1=`./bin/splitsh-lite --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f

#    TMP_DIR="./tmp/cashier-split"
#    REMOTE_URL="git@github.com:cashier-provider/$2.git"
#
#    rm -rf $TMP_DIR;
#    mkdir $TMP_DIR;
#
#    (
#        cd $TMP_DIR;
#
#        git clone $REMOTE_URL .
#        git checkout "$RELEASE_BRANCH";
#
#        git tag $VERSION
#        git push origin --tags
#    )
}

function remote()
{
    git remote add $1 $2 || true
}

#git pull origin $CURRENT_BRANCH

#remote core git@github.com:cashier-provider/core.git
remote cash git@github.com:cashier-provider/cash.git
#remote sber-auth git@github.com:cashier-provider/sber-auth.git
#remote sber-online git@github.com:cashier-provider/sber-online.git
#remote sber-qr git@github.com:cashier-provider/sber-qr.git
#remote tinkoff-auth git@github.com:cashier-provider/tinkoff-auth.git
#remote tinkoff-credit git@github.com:cashier-provider/tinkoff-credit.git
#remote tinkoff-online git@github.com:cashier-provider/tinkoff-online.git
#remote tinkoff-qr git@github.com:cashier-provider/tinkoff-qr.git
#remote driver git@github.com:cashier-provider/driver.git
#remote driver-auth git@github.com:cashier-provider/driver-auth.git

#split 'src/Core' core
split 'src/Cash' cash
#split 'src/SberAuth' sber-auth
#split 'src/SberOnline' sber-online
#split 'src/SberQrCode' sber-qr
#split 'src/TinkoffAuth' tinkoff-auth
#split 'src/TinkoffCredit' tinkoff-credit
#split 'src/TinkoffOnline' tinkoff-online
#split 'src/TinkoffQrCode' tinkoff-qr
#split 'src/TemplateDriver' driver
#split 'src/TemplateDriverAuth' driver-auth
