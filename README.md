# api 

```                                                                                                                                                                                   
                                     __                  ___                        __     ___            ___           __        
                __                  /\ \                /\_ \                      /\ \  /'___`\         /\_ \         /\ \       
   __    _____ /\_\       ___  __  _\ \ \____    __  _ _\//\ \      __      ___    \_\ \/\_\ /\ \      __\//\ \   __  _\ \ \____  
 /'__`\ /\ '__`\/\ \     /'___/\ \/\ \ \ '__`\ /'__`/\`'__\ \ \   /'__`\  /' _ `\  /'_` \/_/// /__    /'___\ \ \ /\ \/\ \ \ '__`\ 
/\ \L\.\\ \ \L\ \ \ \ __/\ \__\ \ \_\ \ \ \L\ /\  __\ \ \/ \_\ \_/\ \L\.\_/\ \/\ \/\ \L\ \ // /_\ \__/\ \__/\_\ \\ \ \_\ \ \ \L\ \
\ \__/.\_\ \ ,__/\ \_/\_\ \____\/`____ \ \_,__\ \____\ \_\ /\____\ \__/.\_\ \_\ \_\ \___,_/\______/\_\ \____/\____\ \____/\ \_,__/
 \/__/\/_/\ \ \/  \/_\/_/\/____/`/___/> \/___/ \/____/\/_/ \/____/\/__/\/_/\/_/\/_/\/__,_ \/_____/\/_/\/____\/____/\/___/  \/___/ 
           \ \_\                   /\___/                                                                                         
            \/_/                   \/__/                                                                                          

                                                                                                                                                         
 ```  

this is the /api/ microservice for api.cyberland2.club!  in /dev/


```
                  .
                .'|     .8
               .  |    .8:
              .   |   .8;:        .8
             .    |  .8;;:    |  .8;
            .     n .8;;;:    | .8;;;
           .      M.8;;;;;:   |,8;;;;;
          .    .,"n8;;;;;;:   |8;;;;;;
         .   .',  n;;;;;;;:   M;;;;;;;;
        .  ,' ,   n;;;;;;;;:  n;;;;;;;;;
       . ,'  ,    N;;;;;;;;:  n;;;;;;;;;
      . '   ,     N;;;;;;;;;: N;;;;;;;;;;
     .,'   .      N;;;;;;;;;: N;;;;;;;;;;
    ..    ,       N6666666666 N6666666666
    I    ,        M           M
   ---nnnnn_______M___________M______mmnnn
         "-.                          /
  __________"-_______________________/_________
  ```
  
# Accessing Secure Endpoints With JSON Web Tokens (JWT)
  > https://tools.ietf.org/html/rfc7519
  
### Attempt (and fail) to access secure endpoint
```
sailb@ ~/$ curl api.cyberland2.club/api/v1/treasure/ -v
*   Trying 178.128.47.103...
* TCP_NODELAY set
* Connected to api.cyberland2.club (178.128.47.103) port 80 (#0)
> GET /api/v1/treasure/ HTTP/1.1
> Host: api.cyberland2.club
> User-Agent: curl/7.64.1
> Accept: */*
> 
< HTTP/1.1 401 Unauthorized
< Server: nginx
< Date: Mon, 20 Apr 2020 03:03:24 GMT
< Content-Type: text/html; charset=UTF-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< Strict-Transport-Security: max-age=31536000; includeSubDomains
< 
* Connection #0 to host api.cyberland2.club left intact
* Closing connection 0
```

### Authenticating to get our authorization token
```
sailb@ ~/$ curl api.cyberland2.club/api/v1/auth -d 'username=sba&password=ycLPyE#cLL$wu8WrJ)R~86REecn' -v
*   Trying 178.128.47.103...
* TCP_NODELAY set
* Connected to api.cyberland2.club (178.128.47.103) port 80 (#0)
> POST /api/v1/auth HTTP/1.1
> Host: api.cyberland2.club
> User-Agent: curl/7.64.1
> Accept: */*
> Content-Length: 31
> Content-Type: application/x-www-form-urlencoded
> 
* upload completely sent off: 31 out of 31 bytes
< HTTP/1.1 200 OK
< Server: nginx
< Date: Mon, 20 Apr 2020 03:06:24 GMT
< Content-Type: application/json
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-XSS-Protection: 1; mode=block
< X-Frame-Options: SAMEORIGIN
< X-Content-Type-Options: nosniff
< Strict-Transport-Security: max-age=31536000; includeSubDomains
< Access-Control-Allow-Origin: *
< 
* Connection #0 to host api.cyberland2.club left intact
{"jwt":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1ODczNTE5ODQsImp0aSI6Im9hV0dCNlgzODE1Wkt6clErQllKRzJ5cTJ2QUVQd0JMXC94VEJ2aU8zNmJ3PSIsImlzcyI6IjE3OC4xMjguNDcuMTAzIiwibmJmIjoxNTg3MzUxOTk0LCJleHAiOjE1OTMzNTE5OTQsImRhdGEiOnsidXNlcklkIjoiMiIsInVzZXJOYW1lIjoic2JhIn19._ARv0NZPwve27bFOO1UnGf7PeuI6FCFO3q1IWw8keDj06mojKKTeolEf9WzRBvfF_XsFUDwz2rjmsk5iP8FjhA"}
* Closing connection 0
```
### Accessing the protected endpoint by sending ‘Authorization: Bearer ‘ header with jwt
```
sailb@ ~/$ curl api.cyberland2.club/api/v1/treasure -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1ODczNTE5ODQsImp0aSI6Im9hV0dCNlgzODE1Wkt6clErQllKRzJ5cTJ2QUVQd0JMXC94VEJ2aU8zNmJ3PSIsImlzcyI6IjE3OC4xMjguNDcuMTAzIiwibmJmIjoxNTg3MzUxOTk0LCJleHAiOjE1OTMzNTE5OTQsImRhdGEiOnsidXNlcklkIjoiMiIsInVzZXJOYW1lIjoic2JhIn19._ARv0NZPwve27bFOO1UnGf7PeuI6FCFO3q1IWw8keDj06mojKKTeolEf9WzRBvfF_XsFUDwz2rjmsk5iP8FjhA' -v
*   Trying 178.128.47.103...
* TCP_NODELAY set
* Connected to api.cyberland2.club (178.128.47.103) port 80 (#0)
> GET /api/v1/treasure HTTP/1.1
> Host: api.cyberland2.club
> User-Agent: curl/7.64.1
> Accept: */*
> Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1ODczNTE5ODQsImp0aSI6Im9hV0dCNlgzODE1Wkt6clErQllKRzJ5cTJ2QUVQd0JMXC94VEJ2aU8zNmJ3PSIsImlzcyI6IjE3OC4xMjguNDcuMTAzIiwibmJmIjoxNTg3MzUxOTk0LCJleHAiOjE1OTMzNTE5OTQsImRhdGEiOnsidXNlcklkIjoiMiIsInVzZXJOYW1lIjoic2JhIn19._ARv0NZPwve27bFOO1UnGf7PeuI6FCFO3q1IWw8keDj06mojKKTeolEf9WzRBvfF_XsFUDwz2rjmsk5iP8FjhA
> 
< HTTP/1.1 200 OK
< Server: nginx
< Date: Mon, 20 Apr 2020 03:13:33 GMT
< Content-Type: text/html; charset=UTF-8
< Transfer-Encoding: chunked
< Connection: keep-alive
< X-XSS-Protection: 1; mode=block
< X-Frame-Options: SAMEORIGIN
< X-Content-Type-Options: nosniff
< Strict-Transport-Security: max-age=31536000; includeSubDomains
< Access-Control-Allow-Origin: *
< 
```
```
you found the treasure!  you've successfully passed-back the JWT and you're accessing a protected endpoint.  fair winds!

				                  .
				                .'|     .8
				               .  |    .8:
				              .   |   .8;:        .8
				             .    |  .8;;:    |  .8;
				            .     n .8;;;:    | .8;;;
				           .      M.8;;;;;:   |,8;;;;;
				          .    .,"n8;;;;;;:   |8;;;;;;
				         .   .',  n;;;;;;;:   M;;;;;;;;
				        .  ,' ,   n;;;;;;;;:  n;;;;;;;;;
				       . ,'  ,    N;;;;;;;;:  n;;;;;;;;;
				      . '   ,     N;;;;;;;;;: N;;;;;;;;;;
				     .,'   .      N;;;;;;;;;: N;;;;;;;;;;
				    ..    ,       N6666666666 N6666666666
				    I    ,        M           M
				   ---nnnnn_______M___________M______mmnnn
				         "-.                          /
				  __________"-_______________________/_________
* Connection #0 to host api.cyberland2.club left intact
* Closing connection 0
sailb@ ~/$ 
```
