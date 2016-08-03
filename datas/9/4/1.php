<?php

// last value solving

$a[0][3] = 9 ;
$a[0][7] = 1 ;
$a[0][8] = 4 ;
$a[1][0] = 2 ;
$a[1][5] = 3 ;
$a[1][8] = 8 ;
$a[2][0] = 3 ;
$a[2][4] = 6 ;
$a[2][7] = 9 ;
$a[2][8] = 5 ;
$a[3][2] = 1 ;
$a[3][4] = 7 ;
$a[3][6] = 8 ;
$a[4][1] = 2 ;
$a[4][3] = 8 ;
$a[4][4] = 1 ;
$a[4][5] = 4 ;
$a[4][7] = 3 ;
$a[5][2] = 6 ;
$a[5][4] = 2 ;
$a[5][6] = 4 ;
$a[6][0] = 9 ;
$a[6][1] = 8 ;
$a[6][4] = 3 ;
$a[6][8] = 6 ;
$a[7][0] = 6 ;
$a[7][3] = 2 ;
$a[7][8] = 3 ;
$a[8][0] = 1 ;
$a[8][1] = 5 ;
$a[8][5] = 6 ;

return $a ;


/*
0.6 = 3 // only 3 in row 0
4.6 = 6 // only 6 in row 4
1.6 = 7 // last value
2.6 = 2 // last value
8.6 = 9 // last value
1.7 = 6 // last value
3.0 = 4 // only 4 in row 3
3.3 = 6 // only 6 in row 3
3.1 = 3 // only 3 in row 3
5.8 = 1 // only 1 in row 5
5.3 = 3 // only 3 in row 5
5.0 = 8 // only 8 in row 5
8.2 = 3 // only 3 in row 8
0.1 = 6 // only 6 in col 1
6.2 = 2 // only 2 in col 2
7.4 = 9 // only 9 in col 4
0.5 = 2 // only 2 in col 5
0.0 = 5 // hypothese - discarded
0.0 = 7 // last value
4.0 = 5 // last value
0.2 = 5 // hypothese - discarded
0.2 = 8 // last value
2.2 = 4 // last value
7.2 = 7 // last vlaue
7.1 = 4 // last value
4.2 = 9 // last value
5.1 = 7 // last value
5.7 = 5 // last value
7.7 = 8 // last value
5.5 = 9 // last value
4.8 = 7 // last value
8.8 = 2 // last value
3.8 = 9 // last value
3.7 = 2 // last value
3.5 = 5 // last value
7.5 = 1 // last value
7.6 = 5 // last value
6.6 = 1 // last value
6.5 = 7 // last value
8.3 = 4 // last value
8.7 = 7 // last value
8.4 = 8 // last value
6.7 = 4 // last value
6.3 = 5 // last value
2.5 = 8 
2.1 = 1 
2.3 = 7
1.3 = 1 
1.2 = 5
1.4 = 4 
1.1 = 9
0.4 = 5

*/
