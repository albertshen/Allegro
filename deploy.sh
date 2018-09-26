#!/bin/bash
set -x
set -e

PRO_SRV='share'
PRO_WWW='/data/sites/allegro'

rsync \
  --exclude=.DS_Store \
  --exclude=.git/ \
  --exclude=.gitignore \
  --exclude=.vagrant \
  --exclude=*.box\
  --exclude=*.bz2\
  --exclude=*.sql\
  --exclude=*.tgz\
  --exclude=*.log\
  --exclude=deploy.sh \
  --exclude=config/config.php \
  --delete \
  -arvP ./ $PRO_SRV:$PRO_WWW

