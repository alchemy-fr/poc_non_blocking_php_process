<?php
$pingFrequency = 1;
$mainDuration = 10;;

pcntl_async_signals(true);

// alarm callback
pcntl_signal(SIGALRM, function () use ($pingFrequency) {
    echo "ping\n";
    // restart alarm
    pcntl_alarm($pingFrequency);
});



printf("\ni'm main, pinging every %d seconds for %d seconds\n\n", $pingFrequency, $mainDuration);

// fork & run the child process
if(($pid = pcntl_fork()) == 0) {
    $cmd =  "/usr/local/bin/php";
    $arg =  [ getcwd() . "/child.php" ];
    printf("running %s %s\n", $cmd, join(' ', $arg));
    pcntl_exec($cmd, $arg);
    // the child will only reach this point on exec failure,
    // because execution shifts to the pcntl_exec()ed command
    exit(0);
}

// here we are the main process, the child process should be running

// start first alarm
pcntl_alarm($pingFrequency);

// do nothing for ($mainDuration) seconds

$t = [
    'seconds' => $mainDuration,
    'nanoseconds' => 0
];
// time_nanosleep is stopped by pcntl_alarm, returning the remaining un-slept time
// we must loop.
do {
    // echo "sleeping\n" . var_export($t, true) . "\n";
    $t = time_nanosleep($t['seconds'], $t['nanoseconds']);
}
while(is_array($t));    // true or false at the end

echo "bye from main\n";

