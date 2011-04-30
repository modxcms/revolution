<?php
/**
 * The xPDORevisionControl class provides utilities for content versioning.
 * 
 * @package xpdo
 * @subpackage revision
 */

/**
 * Utility class for creating, merging, and managing diffs for versioning.
 * 
 * @package xpdo
 * @subpackage revision
 * 
  BASED ON:
  Implementation of a GNU diff alike function from scratch.
  Copyright (C) 2003  Nils Knappmeier <nk@knappi.org>

  Permission is hereby granted, free of charge, to any person obtaining 
  a copy of this software and associated documentation files (the 
  "Software"), to deal in the Software without restriction, including 
  without limitation the rights to use, copy, modify, merge, publish, 
  distribute, sublicense, and/or sell copies of the Software, and to 
  permit persons to whom the Software is furnished to do so, subject to 
  the following conditions:

  The above copyright notice and this permission notice shall be 
  included in all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
  NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS 
  BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN 
  ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN 
  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
  SOFTWARE.
 **/

class xPDORevisionControl {
    /**
     * Computes the difference between two string linewise. 
     * The output is the same format
     * as the GNU diff command without any parameters.
     * Note: Since GNU diff starts counting with 1, and arrays start counting with
     * 0, you have to subtract one from each line number to get the real one.
     */
    function diff($text1, $text2) {
        $array1= $this->_split($text1);
        $array2= $this->_split($text2);

        /* Build up array with the line as key and a list of line numbers as value */
        foreach ($array1 as $nr => $line) {
            $r_array1[$line][]= $nr;
        }
        foreach ($array2 as $nr => $line) {
            $r_array2[$line][]= $nr;
        }

        $result= "";

        $a[1]= 0; /* counter for array1 */
        $a[2]= 0; /* counter for array2 */
        $actions= array ();
        while ($a[1] < sizeof($array1) && $a[2] < sizeof($array2)) {
            #       print "$a[1]:$a[2]\n";
            if ($array1[$a[1]] == $array2[$a[2]]) {
                $a[1]++;
                $a[2]++;
                $actions[]= 'copy';
            } else {
                /*
                 * $tmp1/2 is the next line in $array1/2 that equals
                 * $array2/1[$a2/1+1];
                 */

                $best[1]= count($array1);
                $best[2]= count($array2);
                $scan= $a;

                #       echo "distBest = ".dist($a, $best)."\n";
                #       echo "distScan = ".dist($a, $scan)."\n";
                while ($this->dist($a, $scan) < $this->dist($a, $best)) {
                    #       echo "A    $a[1]:$a[2]\t";
                    #       echo "Scan $scan[1]:$scan[2]  ";
                    $tmp[1]= $this->nextOccurence($array2[$scan[2]], $r_array1, $scan[1]);
                    $tmp[2]= $scan[2];
                    if ($tmp[1] && $this->dist($a, $tmp) < $this->dist($a, $best))
                        $best= $tmp;
                    #       echo "Tmp1 $tmp[1]:$tmp[2]\t";;

                    $tmp[1]= $scan[1];
                    $tmp[2]= $this->nextOccurence($array1[$scan[1]], $r_array2, $scan[2]);
                    if ($tmp[2] && $this->dist($a, $tmp) < $this->dist($a, $best))
                        $best= $tmp;
                    #       echo "Tmp2 $tmp[1]:$tmp[2]\t";

                    #       echo "Best $best[1]:$best[2]\n";
                    $scan[1]++;
                    $scan[2]++;
                }

                for ($i= $a[1]; $i < $best[1]; $i++) {
                    $actions[]= 'del';
                }

                for ($i= $a[2]; $i < $best[2]; $i++) {
                    $actions[]= 'add';
                }

                $a= $best;
            }
        }

        # Here, we may still not be at the bottom left corner.
        # So we have to get there. (This case happens, when we just append something to the page)   
        for ($i= $a[1]; $i < sizeof($array1); $i++) {
            $actions[]= 'del';
        }

        for ($i= $a[2]; $i < sizeof($array2); $i++) {
            $actions[]= 'add';
        }

        $actions[]= 'finish';

        /* Now follow the way back and report connected (unequal) pieces */
        $x= $xold= 0;
        $y= $yold= 0;
        $realAction= ""; /* the current action */
        foreach ($actions as $action) {

            if ($action == 'del') {
                if ($realAction == "" || $realAction == "d") {
                    $realAction= "d";
                } else {
                    $realAction= "c";
                }
                $x++;
            }
            if ($action == 'add') {
                if ($realAction == "" || $realAction == "a") {
                    $realAction= "a";
                } else {
                    $realAction= "c";
                }
                $y++;
            }
            if ($action == 'copy' || $action == 'finish') {
                /* Prepare header for diff entry */
                if ($xold +1 == $x) {
                    $xstr= $x;
                } else {
                    $xstr= ($xold +1) . ",$x";
                }
                if ($yold +1 == $y) {
                    $ystr= $y;
                } else {
                    $ystr= ($yold +1) . ",$y";
                }

                /* "Print" entry to result */
                if ($realAction == "a") {
                    $result .= ($x) . "a$ystr\n";
                    for ($i= $yold; $i < $y; $i++) {
                        $result .= "> " . $array2[$i];
                    }
                } else
                    if ($realAction == "d") {
                        $result .= ($xstr) . "d" . ($y) . "\n";
                        for ($i= $xold; $i < $x; $i++) {
                            $result .= "< " . $array1[$i];
                        }
                    } else
                        if ($realAction == "c") {
                            $result .= "$xstr$realAction$ystr\n";
                            for ($i= $xold; $i < $x; $i++) {
                                $result .= "< " . $array1[$i];
                            }
                            $result .= "---\n";
                            for ($i= $yold; $i < $y; $i++) {
                                $result .= "> " . $array2[$i];
                            }
                        }
                $x++;
                $y++;
                $realAction= "";
                $xold= $x;
                $yold= $y;

            }
        }
        return $result;
    }

    function cut_head(& $str, $key, $prefix) {
        if (strpos($str, $prefix) === 0) {
            $str= substr($str, strlen($prefix));
        } else {
            print "Something is wrong in the patch: ";
            print "'$str' should begin with '$prefix'\n";
            exit;
        }
    }

    function restore($revisions= array (), $restore= 0) {
        reset($revisions);
        $restored= current($revisions);
        if ($restore && next($revisions)) {
            while (list($k, $v)= each($revisions)) {
                if ($k > $restore)
                    break;
                $restored= $this->patch($restored, $v);
            }
        }
        return $restored;
    }

    function patch($text, $patch) {
        $array= $this->_split($text);

        /* Modify patch to an array so that it 
         * is compatible to the modification */

        if ($patch == "")
            return $text;
        if (substr($patch, -1) == "\n")
            $patch= substr($patch, 0, strlen($patch) - 1);

        $patch_array= explode("\n", $patch);

        for ($i= 0; $i < count($patch_array); $i++) {
            $patch_array[$i]= $patch_array[$i] . "\n";
        }

        $i= 0;
        $nlIndex= array_search("\\ No newline at end of file\n", $patch_array);
        while ($nlIndex != false && $i < 2) {
            /* This shouldn't be happening more than two times in a valid patch */
            $newEntry= $patch_array[$nlIndex -1] . $patch_array[$nlIndex];
            array_splice($patch_array, $nlIndex -1, 2, $newEntry);
            $nlIndex= array_search("\\ No newline at end of file\n", $patch_array);
            $i++;
        }

        /* Start computing */
        $current= 0;
        do {
            if (preg_match("/^([\d,]+)([adc])([\d,]+)$/", $patch_array[$current], $matches) == 0) {
                print "<pre>Error in line $current: " . $patch_array[$current] . " not a command\n" . sizeof($patch_array);
                print "</pre>";
                exit;
            }
            list ($full, $left, $action, $right)= $matches;

            /* Compute start and end of each side */
            list ($left_start, $left_end)= explode(",", $left);
            list ($right_start, $right_end)= explode(",", $right);
            if ($left_end == "") {
                $left_end= $left_start;
            }
            if ($right_end == "") {
                $right_end= $right_start;
            }

            /* Perform action and switch to next patch */
            if ($action == "a") {
                $replace= array_slice($patch_array, $current +1, $right_end - $right_start +1);

                array_walk($replace, array ($this, 'cut_head'), '> ');

                array_splice($array, $right_start -1, 0, $replace);
                $current += $right_end - $right_start +2;
            } else
                if ($action == "d") {
                    /* Check whether lines in patch are like in file */
                    $should= array_slice($patch_array, $current +1, $left_end - $left_start +1);
                    array_walk($should, array ($this, 'cut_head'), '< ');

                    $is= array_splice($array, $right_start, $left_end - $left_start +1);
                    if ($should !== $is) {
                        print "<pre>According to the patch, in lines $left_start to ";
                        print "$left_end there should be a\n";
                        print urlencode(implode("", $should)) . "\n";
                        print "but I only find a\n";
                        print urlencode(implode("", $is)) . "\n</pre>";

                    }
                    $current += $left_end - $left_start +2;

                } else
                    if ($action == "c") {
                        $replace= array_slice($patch_array, $current +1 + $left_end - $left_start +2, $right_end - $right_start +1);

                        array_walk($replace, array ($this, 'cut_head'), '> ');
                        $is= array_splice($array, $right_start -1, $left_end - $left_start +1, $replace);

                        /* Check whether lines in patch are like in text */
                        $should= array_slice($patch_array, $current +1, $left_end - $left_start +1);
                        array_walk($should, array ($this, 'cut_head'), '< ');

                        if ($should !== $is) {
                            print "<pre>According to the patch, in lines $left_start to";
                            print "$left_end there should be a\n";
                            print implode("", $should);
                            print "but I only find a\n";
                            print implode("", $is) . "</pre>";

                        }
                        $current += 1 + $left_end - $left_start +1 + 1 + $right_end - $right_start +1;
                    }

        } while ($current < count($patch_array));
        $result= implode("", $array);
        $suffix= "\n\\ No newline at end of file\n";

        if (substr($result, -strlen($suffix)) == $suffix) {
            $result= substr($result, 0, strlen($result) - strlen($suffix));
        }
        return $result;
    }

    /**
     * Checks, if there is a line number-entry in $r_array for $line,
     * that is behind $where.
     * $where will be assigned the line-number, if true
     */
    function nextOccurence($line, & $r_array, $where) {
        $tmp= $r_array[$line];
        if (!$tmp)
            return false;
        foreach ($tmp as $nr) {
            if ($where <= $nr) {
                $where= $nr;
                return $nr;
            }
        }
        return false;
    }

    /**
     * Compute the manhatten distance of two points
     */
    function dist($a, $b) {
        $d1= $b[1] - $a[1];
        $d2= $b[2] - $a[2];
        return $d2 + $d1;
    }

    function _split($text) {
        $array= explode("\n", $text);
        for ($i= 0; $i < count($array); $i++) {
            $array[$i]= $array[$i] . "\n";
        }
        if ($array[count($array) - 1] == "\n") {
            array_pop($array);
        } else {
            $array[count($array) - 1]= $array[count($array) - 1] . "\\ No newline at end of file\n";
        }
        return $array;
    }

}
