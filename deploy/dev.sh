#!/bin/bash
eval `ssh-agent -s`
ssh-add ~/.ssh/id_rsa
./vendor/bin/dep deploy develop
