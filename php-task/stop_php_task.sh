#!/bin/bash
# -*- coding: utf-8 -*-

key='WorkerMan'

echo "关键词：${key}"

arr=`ps -ef | grep ${key} | awk '{print $2}'`

for i in ${arr[@]}
do
    kill -9  "${i}"
done
