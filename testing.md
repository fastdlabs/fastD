#框架压力测试

###硬件说明：

```
CPU: 4核
内存: 8G
```

#＃Dobee v1.2.0-bate

`ab -c 200 -n 20000 -k http://127.0.0.1:1680/`

```
Server Hostname:        127.0.0.1
Server Port:            1680

Document Path:          /
Document Length:        11 bytes

Concurrency Level:      200
Time taken for tests:   2.527 seconds
Complete requests:      20000
Failed requests:        0
Write errors:           0
Keep-Alive requests:    20000
Total transferred:      3280000 bytes
HTML transferred:       220000 bytes
Requests per second:    7915.14 [#/sec] (mean)
Time per request:       25.268 [ms] (mean)
Time per request:       0.126 [ms] (mean, across all concurrent requests)
Transfer rate:          1267.66 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   1.6      0      20
Processing:     0   25  25.7     18     115
Waiting:        0   25  25.7     18     115
Total:          0   25  25.8     18     118

Percentage of the requests served within a certain time (ms)
  50%     18
  66%     26
  75%     31
  80%     35
  90%     71
  95%     89
  98%     94
  99%     98
 100%    118 (longest request)
```

#＃ThinkPHP v3.2.3-stable

`ab -c 200 -n 20000 -k http://127.0.0.1/thinkphp/index.php/`

```
Server Software:        Tengine
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /thinkphp/index.php/
Document Length:        11 bytes

Concurrency Level:      200
Time taken for tests:   73.171 seconds
Complete requests:      20000
Failed requests:        0
Write errors:           0
Keep-Alive requests:    0
Total transferred:      6140000 bytes
HTML transferred:       220000 bytes
Requests per second:    273.33 [#/sec] (mean)
Time per request:       731.706 [ms] (mean)
Time per request:       3.659 [ms] (mean, across all concurrent requests)
Transfer rate:          81.95 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1  11.8      0     158
Processing:    89  718 397.0    619    7634
Waiting:       88  718 396.9    619    7634
Total:        162  719 396.9    620    7634

Percentage of the requests served within a certain time (ms)
  50%    620
  66%    638
  75%    651
  80%    661
  90%    713
  95%   1614
  98%   1664
  99%   2175
 100%   7634 (longest request)

```

`webbench -t 60 -c 20000 http://127.0.0.1/thinkphp/index.php/`

```
Webbench - Simple Web Benchmark 1.5
Copyright (c) Radim Kolar 1997-2004, GPL Open Source Software.

Benchmarking: GET http://127.0.0.1/thinkphp/index.php/
20000 clients, running 60 sec.

Speed=19365 pages/min, 118042 bytes/sec.
Requests: 18779 susceed, 586 failed.
```




