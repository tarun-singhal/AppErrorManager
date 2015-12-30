# AppErrorManager
AppErrorManager is used to capture the API error and exception on fly.
It is very usefull to manage the single place to capture the error message and error code.


* Steps to Enable and usage of AppErrorMAnager Module

Step 1 :To enable the Module in ZF2, Include the module name inside application.config.php file
```php
return array(
  ...,
  ...,
  'ErrorManager'
);
```
Step 2: now if you want to capture the api exception then change in ApiProblem i.e. new ApiProblem(200, '1001');
where, '1001' : is the error code, 

Step 3: Open the config/Error.ini file, please the error code inside that like as below:
```php
[test.rest.respapiname]
1001 = api problem found

``` 
Step 4: Now you can manage the api code and message at one place.

I hope it will help you to handle exception/error and to identify the actual debug happening in the API Calls.


