#!/bin/bash

rm var/out_sorted.csv 2> /dev/null
bin/console app:lock-alice-and-bob 1> out_a.csv &
bin/console app:lock-bob-and-alice 1> out_b.csv &
wait
cat out_a.csv out_b.csv > out.csv
sort --field-separator=";" --key=1 out.csv > out_sorted.csv
rm out_a.csv out_b.csv out.csv
mv out_sorted.csv var/out_sorted.csv
echo "Command log in a chronological order, lines numbered in sequence:"
nl var/out_sorted.csv
