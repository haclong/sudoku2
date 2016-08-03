<?php

// last value solving

$a[0][1] = 8 ;
$a[0][7] = 6 ;
$a[1][0] = 5 ;
$a[1][2] = 9 ;
$a[1][5] = 4 ;
$a[1][6] = 3 ;
$a[1][8] = 2 ;
$a[2][0] = 4 ;
$a[2][1] = 6 ;
$a[2][5] = 2 ;
$a[2][6] = 7 ;
$a[3][0] = 9 ;
$a[3][3] = 7 ;
$a[3][7] = 4 ;
$a[3][8] = 6 ;
$a[4][2] = 6 ;
$a[4][6] = 2 ;
$a[5][0] = 7 ;
$a[5][1] = 2 ;
$a[5][5] = 1 ;
$a[5][8] = 9 ;
$a[6][2] = 5 ;
$a[6][3] = 9 ;
$a[6][7] = 2 ;
$a[6][8] = 8 ;
$a[7][0] = 2 ;
$a[7][2] = 8 ;
$a[7][3] = 4 ;
$a[7][6] = 6 ;
$a[7][8] = 3 ;
$a[8][1] = 3 ;
$a[8][7] = 5 ;

return $a ;


/*
0.2 = 2 // only 2 in row 0
4.5 = 2 // only 2 in row 4
8.4 = 2 // only 2 in row 8
8.6 = 9 // only 9 in row 8
5.0 = 8 // only 8 in col 0
0.0 = 3 // only 3 in col 0
2.2 = 1 // last value 
1.1 = 7 // last value
3.2 = 3 // last value
5.2 = 4 // last value
8.2 = 7 // last value
2.8 = 5 // last value
2.7 = 9 // only 9 in col 7
4.8 = 7 // only 7 in col 8
1.7 = 8 // only 8 in reg 2
5.7 = 3 // last value
4.7 = 1 // last value
4.1 = 5 // last value
7.7 = 7 // last value
7.5 = 5 // last value
7.4 = 1 // last value
7.1 = 9 // last value
4.1 = 5 // last value
4.3 = 3 // last value
4.5 = 9 // last value
4.4 = 4 // last value
3.5 = 8 // last value
8.5 = 6 // last value
8.4 = 8 // last value
8.0 = 1 // last value
8.8 = 4 // last value
6.6 = 1 // last value
6.1 = 4 // last value
6.0 = 6 // last value
3.6 = 5 // last value
5.6 = 8 // last value
3.1 = 1 // last value
2.4 = 3 // last value
6.4 = 7 // last value
6.5 = 3 // last value
2.3 = 8 // last value
1.4 = 6 // last value
5.4 = 5 // last value
5.3 = 6 // last value
1.3 = 1 // last value
0.8 = 1 // last value
0.6 = 4
0.5 = 7
0.4 = 9
0.3 = 5



*/
