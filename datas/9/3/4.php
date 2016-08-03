<?php

// last value solving

$a[0][1] = 7 ;
$a[0][2] = 5 ;
$a[0][3] = 4 ;
$a[0][5] = 6 ;
$a[0][6] = 8 ;
$a[0][8] = 2 ;
$a[1][0] = 8 ;
$a[1][3] = 7 ;
$a[1][6] = 1 ;
$a[2][1] = 4 ;
$a[2][4] = 9 ;
$a[2][7] = 7 ;
$a[3][2] = 1 ;
$a[3][3] = 9 ;
$a[3][5] = 4 ;
$a[4][4] = 8 ;
$a[5][3] = 5 ;
$a[5][5] = 3 ;
$a[5][6] = 6 ;
$a[6][1] = 1 ;
$a[6][4] = 4 ;
$a[6][7] = 8 ;
$a[7][2] = 4 ;
$a[7][5] = 9 ;
$a[7][8] = 1 ;
$a[8][0] = 2 ;
$a[8][2] = 7 ;
$a[8][3] = 3 ;
$a[8][5] = 1 ;
$a[8][6] = 9 ;
$a[8][7] = 5 ;

return $a ;

/*
8.4 = 6
8.8 = 4
8.1 = 8 // row 8 completed
6.3 = 2
7.3 = 8
2.3 = 1
4.3 = 6 // col 3 completed
0.4 = 3
0.7 = 9
0.0 = 1 // row 0 completed

1.7 = 4 // last value in row 1
2.5 = 8 // last value in row 2
3.8 = 8 // last value in row 3
4.7 = 1 // last value in row 4
5.7 = 2
5.1 = 9
5.8 = 7
5.4 = 1
5.2 = 8
5.0 = 4 // row 5 completed
3.7 = 3
7.7 = 6
6.8 = 3
6.6 = 7
7.6 = 2 // region 8 completed
6.5 = 5
7.4 = 7 // region 7 completed
3.6 = 5
4.8 = 9
4.6 = 4 // region 5 completed
3.4 = 2
4.5 = 7 // region 4 completed
3.1 = 6
3.0 = 7 // row 3 completed
2.6 = 3
2.0 = 6
6.0 = 9
6.2 = 6 // row 6 completed
2.8 = 5
2.2 = 2 // row 2 completed
4.2 = 3
4.0 = 5
7.0 = 3 // col 0 completed
7.1 = 5 // row 7 completed
4.1 = 2 // row 4 completed
1.8 = 6
1.5 = 2
1.4 = 5
1.2 = 9
1.1 = 3

*/

