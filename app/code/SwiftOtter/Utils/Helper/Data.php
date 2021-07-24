<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 6/10/21
 * @website https://swiftotter.com
 **/
namespace SwiftOtter\Utils\Helper;

class Data
{
    public function niceDebugBacktrace(string $title = ''): string
    {
        $d = debug_backtrace();
        array_shift($d);
        $out = $title . ' -- ';
        $c1width = strlen((string)(count($d) + 1));
        $c2width = 0;
        foreach ($d as &$f) {
            if (!isset($f['file'])) $f['file'] = '';
            if (!isset($f['line'])) $f['line'] = '';
            if (!isset($f['class'])) $f['class'] = '';
            if (!isset($f['type'])) $f['type'] = '';
            $f['file_rel'] = str_replace(BP . '/', '', $f['file']);
            $thisLen = strlen((string)$f['file_rel'] . ':' . $f['line']);
            if ($c2width < $thisLen) $c2width = $thisLen;
        }
        foreach ($d as $i => $f) {
            $args = '';
            if (isset($f['args'])) {
                $args = array();
                foreach ($f['args'] as $arg) {
                    if (is_object($arg)) {
                        $str = get_class($arg);
                    } elseif (is_array($arg)) {
                        $str = 'Array';
                    } elseif (is_numeric($arg)) {
                        $str = $arg;
                    } else {
                        $str = "'$arg'";
                    }
                    $args[] = $str;
                }
                $args = implode(', ', $args);
            }
            $out .= sprintf(
                "[%{$c1width}s] %-{$c2width}s %s%s%s(%s)\n",
                $i,
                $f['file_rel'] . ':' . $f['line'],
                $f['class'],
                $f['type'],
                $f['function'],
                $args
            );
        }
        return $out;
    }
}
