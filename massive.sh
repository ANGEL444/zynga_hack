#!/bin/bash

  for p in "$@"
    do
      php socket.php $p &
    done
  
  sleep 5
  killall php
