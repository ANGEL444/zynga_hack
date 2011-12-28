#!/bin/bash

echo "<msg t='sys'><body action='verChk' r='0'><ver v='140' /></body></msg>" | nc 216.151.149.120 9339
