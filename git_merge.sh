#!/usr/bin/env bash
git checkout develop
git pull origin develop
git checkout master
git merge develop
git push origin master