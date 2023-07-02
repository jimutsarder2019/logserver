<?php

// Geeting free CPU info

$cpuUsage = shell_exec("top -bn 2 -d 0.01 | grep '^%Cpu' | tail -n 1");
preg_match_all('/[0-9.]+/', $cpuUsage, $cpuUsageArray);
$cpuUtilization = $cpuUsageArray[0][0];


// Geeting free RAM info

$totalMemory = shell_exec('cat /proc/meminfo | grep "MemTotal" | awk \'{print $2}\'');
$freeMemory = shell_exec('cat /proc/meminfo | grep "MemFree" | awk \'{print $2}\'');
$usedMemory = $totalMemory - $freeMemory;
$ramUtilization = ($usedMemory / $totalMemory) * 100;



// Geeting free Space info

$rootTotalSpace = disk_total_space("/");
$rootFreeSpace = disk_free_space("/");
$rootUtilization = ($rootTotalSpace - $rootFreeSpace) / $rootTotalSpace * 100;


echo "Current CPU utilization: {$cpuUtilization}%<br>";
echo "Current RAM utilization: " . round($ramUtilization, 2) . "%<br>";
echo "Current disk space utilization: " . round($rootUtilization, 2) . "%<br>";


// print uptime

$uptime = shell_exec('uptime');
preg_match('/up\s+(.*?),\s+(.*?)\s+/', $uptime, $matches);
$days = str_replace(',', '', $matches[1]);
$time = rtrim($matches[2], ',');
$uptimeString = $days . ' ' . $time;

echo "System Uptime: " . $uptimeString;

phpinfo();

?>
