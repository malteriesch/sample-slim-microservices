# Test Results

## Run application
```
make up

```

then run requests:

```

curl -X POST http://localhost/api/create -H "Content-Type: application/json" -d '{ "requestId": "abcaa1" }'

```

## Run tests
```

make test_run

```


## notes
* Implemented via docker-compose with microservices
* Used a very simple custom Pub/Sub mechanism for async resource creation
* for easy code sharing fpm and queue (for the pub/sub) containers use same file system



