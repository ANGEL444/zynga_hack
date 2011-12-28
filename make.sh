#!/bin/bash
flex poker.l &&
gcc lex.yy.c -o poker -lfl
